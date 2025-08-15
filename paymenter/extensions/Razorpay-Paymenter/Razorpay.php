<?php

namespace Paymenter\Extensions\Gateways\Razorpay;

use App\Classes\Extension\Gateway;
use App\Events\Service\Updated;
use App\Events\ServiceCancellation\Created;
use App\Helpers\ExtensionHelper;
use App\Models\Gateway as ModelsGateway;
use App\Models\Invoice;
use App\Models\Service;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class Razorpay extends Gateway
{
    public function boot()
    {
        require __DIR__ . '/routes/web.php';
        // Register webhook route
        View::addNamespace('extensions.gateways.razorpay', __DIR__ . '/views');

//        Event::listen(Updated::class, function (Updated $event) {
//            if ($event->service->properties->where('key', 'has_razorpay_subscription')->first()?->value !== '1' || !$event->service->subscription_id) {
//                // If the service is not a stripe subscription, skip
//                return;
//            }
//            if ($event->service->isDirty('price') || $event->service->isDirty('expires_at')) {
//                try {
//                    $this->updateSubscription($event->service);
//                } catch (Exception $e) {
//                }
//            }
//        });

        Event::listen(Created::class, function (Created $event) {
            \Log::info("Service cancellation event received", ['event' => $event]);
            $service = $event->cancellation->service;
            if ($service->properties->where('key', 'has_razorpay_subscription')->first()?->value !== '1' || !$service->subscription_id) {
                // If the service is not a stripe subscription, skip
                return;
            }
            try {
                $this->cancelSubscription($service, $event);
            } catch (Exception $e) {
                // Ignore exception
            }
        });
    }

    public function getApi() {
        $keyId = $this->config('razorpay_testing_mode') ? $this->config('razorpay_test_key_id') : $this->config('razorpay_key_id');
        $keySecret = $this->config('razorpay_testing_mode') ? $this->config('razorpay_test_key_secret') : $this->config('razorpay_key_secret');
        return new Api($keyId, $keySecret);
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'razorpay_key_id',
                'label' => 'Razorpay Key ID',
                'placeholder' => 'Enter your Razorpay Key ID',
                'type' => 'text',
                'description' => '',
                'required' => true,
            ],
            [
                'name' => 'razorpay_test_key_id',
                'label' => 'Razorpay Test Key ID',
                'placeholder' => 'Enter your Razorpay Test Key ID',
                'type' => 'text',
                'description' => '',
                'required' => true,
            ],
            [
                'name' => 'razorpay_webhook_secret',
                'label' => 'Razorpay webhook secret',
                'type' => 'text',
                'description' => 'Razorpay webhook secret',
                'required' => false,
            ],
            [
                'name' => 'razorpay_key_secret',
                'label' => 'Razorpay Key Secret',
                'placeholder' => 'Enter your Razorpay Key Secret',
                'type' => 'text',
                'description' => '',
                'required' => true,
            ],
            [
                'name' => 'razorpay_test_key_secret',
                'label' => 'Razorpay Test Key Secret',
                'placeholder' => 'Enter your Razorpay Test Key Secret',
                'type' => 'text',
                'description' => '',
                'required' => true,
            ],
            [
                'name' => 'razorpay_use_subscriptions',
                'label' => 'Use subscriptions',
                'type' => 'checkbox',
                'description' => 'Enable this option if you want to use subscriptions with Razorpay',
                'required' => false,
            ],
            [
                'name' => 'razorpay_testing_mode',
                'label' => 'Testing mode',
                'type' => 'checkbox',
                'description' => 'Enable this option to use Razorpay in testing mode. This will use the test key and secret.',
                'required' => false,
            ]
        ];
    }

    public function updated(ModelsGateway $gateway)
    {
//        if (!empty($gateway->settings()->where('key', 'razorpay_webhook_secret')->first()->value)) {
//            return;
//        }
//
//        // Check if webhook already exists
//        $webhooks = $this->request('get', '/webhook_endpoints');
//        foreach ($webhooks->data as $webhook) {
//            if ($webhook->url === route('extensions.gateways.stripe.webhook')) {
//                // Delete webhook
//                $this->request('delete', '/webhook_endpoints/' . $webhook->id);
//                break;
//            }
//        }
//
//        // Create webhook on stripe
//        $webhook = $this->request('post', '/webhook_endpoints', [
//            'url' => route('extensions.gateways.stripe.webhook'),
//            'description' => 'Paymenter Stripe Webhook',
//            'enabled_events' => [
//                'payment_intent.succeeded',
//                'setup_intent.succeeded',
//                'subscription_schedule.canceled',
//                'invoice.created',
//                'invoice.payment_succeeded',
//            ],
//            'api_version' => '2025-02-24.acacia', // Use the latest version
//        ]);
//
//        $gateway->settings()->updateOrCreate(['key' => 'stripe_webhook_secret'], ['value' => $webhook->secret]);
//
//        Notification::make()
//            ->success()
//            ->title('Webhook created')
//            ->body('We\'ve created a webhook for you on Stripe (refresh the page to see the secret)')
//            ->send();
    }

    public function pay($invoice, $total)
    {
        $eligibleForSubscription = collect($invoice->items)->filter(function ($item) {
                return $item->reference_type === Service::class && $item->reference->plan->type !== 'one-time';
        })->count() == count($invoice->items);
        if ($this->config('razorpay_use_subscriptions') && $eligibleForSubscription) {
            $razorpayCustomerId = $invoice->user->properties->where('key', 'razorpay_id')->first()->value;
            if (!isset($razorpayCustomerId)) {
                $createCustomerResponse = RazorpayUtils::createCustomer($this->getApi(), $invoice);
                $invoice->user->properties()->updateOrCreate(['key' => 'razorpay_id'], ['value' => $createCustomerResponse->id]);
                $razorpayCustomerId = $createCustomerResponse->id;
            }

            \Log::info("Razorpay customer ID: " . $razorpayCustomerId);

            // Find plan
            $fetchAllPlans = RazorpayUtils::fetchAllPlans($this->getApi());
            $filteredPlans = array_filter($fetchAllPlans, function ($plan) use ($invoice, $total) {
                return $plan['amount'] == $total * 100 &&
                       $plan['currency'] == $invoice->currency->code &&
                       $plan['period'] == RazorpayUtils::convertBillingUnitToRazorpay($invoice->items->first()->reference->plan->billing_period);
            });
            $filteredPlans = array_values($filteredPlans);
            $firstPlan = count($filteredPlans) > 0 ? $filteredPlans[0] : null;

            if (!$firstPlan) {
                // Create plan
                $firstPlan = RazorpayUtils::createPlan($this->getApi(), $invoice, $total);
            }

            \Log::info("Found plan: " . json_encode($firstPlan));
            \Log::info("Billing period: " . $invoice->items->first()->reference->plan->billing_unit);

            $subscription = RazorpayUtils::createSubscription($this->getApi(), $invoice, $firstPlan['id'], $razorpayCustomerId);
            $keyId = $this->config('razorpay_testing_mode') ? $this->config('razorpay_test_key_id') : $this->config('razorpay_key_id');
            return view(
                'extensions.gateways.razorpay::paysubscription',
                [
                    'keyId' => $keyId,
                    'subscriptionId' => $subscription->id,
                    'subscriptionDetails' => [
                        'description' => $firstPlan['description'],
                    ],
                    'invoiceId' => $invoice->id,
                    'invoiceNumber' => $invoice->number,
                ]
            );
        } else {

        }

    }

    public function webhook(Request $request)
    {

//        \Log::info('Razorpay webhook received', ['payload' => $request->getContent()]);

        try {
            $this->getApi()->utility->verifyWebhookSignature(
                $request->getContent(),
                $request->header('X-Razorpay-Signature'),
                $this->config('razorpay_webhook_secret')
            );
        } catch (SignatureVerificationError $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        \Log::info('Razorpay webhook signature verified successfully');

        $payload = json_decode($request->getContent());

        // Handle the event
        switch ($payload->event) {
            // Normal payment
            case 'subscription.charged':
                $subscriptionEntity = $payload->payload->subscription->entity; // contains a RazorpaySubscription
                $paymentEntity = $payload->payload->payment->entity; // contains a RazorpayPayment
                WebhookUtils::onCharged($subscriptionEntity, $paymentEntity);
                break;
            case 'subscription.cancelled':
                $subscriptionEntity = $payload->payload->subscription->entity;
                WebhookUtils::onCancelled($subscriptionEntity);
                break;
            default:

                // Not a event type we care about, just return 200
        }

        http_response_code(200);
    }

//    public function updateSubscription(Service $service)
//    {
//        $scheduleId = $this->request('get', '/subscriptions/' . $service->subscription_id);
//
//        if ($service->isDirty('price')) {
//            if ($scheduleId->schedule) {
//
//                $oldPhases = $this->request('get', '/subscription_schedules/' . $scheduleId->schedule)->phases;
//                // Overwrite phase 2 item 0 with the new price
//                $phases = [];
//                // Only keep items and end, start date
//                foreach ($oldPhases as $phase) {
//                    $phases[] = [
//                        'items' => $phase->items,
//                        'end_date' => $phase->end_date,
//                        'start_date' => $phase->start_date,
//                    ];
//                }
//                // Check if the service->product already exists in Stripe
//                $product = $service->product;
//                $stripeProduct = $this->request('get', '/products/search', ['query' => 'metadata[\'product_id\']:\'' . $product->id . '\'']);
//
//                if (empty($stripeProduct->data)) {
//                    // Create product
//                    $stripeProduct = $this->request('post', '/products', [
//                        'name' => $product->name,
//                        'metadata' => ['product_id' => $product->id],
//                    ]);
//                } else {
//                    $stripeProduct = $stripeProduct->data[0];
//                }
//                // Latest phase is the current one
//                $key = count($phases) - 1;
//                $phases[$key]['items'][0]->price_data = [
//                    'currency' => $service->currency->code,
//                    'unit_amount' => $service->price * 100,
//                    'product' => $stripeProduct->id,
//                    'recurring' => [
//                        'interval' => $service->plan->billing_unit,
//                        'interval_count' => $service->plan->billing_period,
//                    ],
//                ];
//                $phases[$key]['items'][0]->price = null;
//                $phases[$key]['items'][0]->plan = null;
//
//                // Update the schedule
//                $this->request('post', '/subscription_schedules/' . $scheduleId->schedule, [
//                    'phases' => $phases,
//                    'proration_behavior' => 'none',
//                ]);
//            } else {
//                // Get subscription
//                $subscription = $this->request('get', '/subscriptions/' . $service->subscription_id);
//                // Get first item
//                $item = $subscription->items->data[0];
//                // Update price
//                $this->request('post', '/subscription_items/' . $item->id, [
//                    'price_data' => [
//                        'currency' => $service->currency->code,
//                        'unit_amount' => $service->price * 100,
//                        'product' => $item->price->product,
//                        'recurring' => [
//                            'interval' => $service->plan->billing_unit,
//                            'interval_count' => $service->plan->billing_period,
//                        ],
//                    ],
//                    'proration_behavior' => 'none',
//                ]);
//            }
//        }
//
//        if ($service->isDirty('expires_at')) {
//            $subDate = Carbon::createFromTimestamp($scheduleId->current_period_end)->startOfDay();
//            // Check if current date is before the end date of the subscription
//            if ($subDate == $service->expires_at || $service->expires_at <= $subDate) {
//                return;
//            }
//
//            if ($scheduleId->schedule) {
//                // As phases are only used for the setup fee, we can remove the phases
//                $this->request('post', '/subscription_schedules/' . $scheduleId->schedule . '/release', []);
//
//                // Get subscription
//                $subscription = $this->request('get', '/subscriptions/' . $service->subscription_id);
//                // Get first item
//                $item = $subscription->items->data[0];
//                // Update price
//                $this->request('post', '/subscription_items/' . $item->id, [
//                    'price_data' => [
//                        'currency' => $service->currency->code,
//                        'unit_amount' => $service->price * 100,
//                        'product' => $item->price->product,
//                        'recurring' => [
//                            'interval' => $service->plan->billing_unit,
//                            'interval_count' => $service->plan->billing_period,
//                        ],
//                    ],
//                    'proration_behavior' => 'none',
//                ]);
//            }
//            // Update the subscription
//            $this->request('post', '/subscriptions/' . $service->subscription_id, [
//                'trial_end' => $service->expires_at->timestamp,
//                'proration_behavior' => 'none',
//            ]);
//        }
//    }

    public function cancelSubscription(Service $service, Created $event)
    {
        if (!$service->subscription_id && !$service->properties->where('key', 'has_razorpay_subscription')->first()) {
            \Log::info("Service does not have a Razorpay subscription, skipping cancellation.");
            return;
        }

        $cycle_end = $event->cancellation->type === 'end_of_period';

        try {
            $this->getApi()->subscription->fetch($service->subscription_id)->cancel([
                'cancel_at_cycle_end' => $cycle_end,
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to cancel Razorpay subscription: " . $e->getMessage());
            // Optionally, you can throw the exception or handle it as needed
            return false;
        }

        \Log::info('Razorpay subscription cancelled for service ID: ' . $service->id);
        $service->update(['subscription_id' => null]);
        // Remove has_stripe_subscription property
        $service->properties()->where('key', 'has_stripe_subscription')->delete();

        return true;
    }

}