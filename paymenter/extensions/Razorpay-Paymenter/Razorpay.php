<?php

namespace Paymenter\Extensions\Gateways\Razorpay;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Helpers\ExtensionHelper;
use App\Classes\Extension\Gateway;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

class Razorpay extends Gateway
{
    public function boot()
    {
        require __DIR__ . '/routes/web.php';
        View::addNamespace('extensions.gateways.razorpay', __DIR__ . '/views');
        
        // Add navigation for subscription management
        $this->registerNavigation();
    }

    /**
     * Register navigation links for subscription management
     */
    protected function registerNavigation()
    {
        Event::listen('navigation.dashboard', function () {
            return [
                [
                    'name' => 'Subscriptions',
                    'route' => 'extensions.gateways.razorpay.subscriptions',
                ]
            ];
        });
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'key_id',
                'label' => 'Key ID',
                'description' => 'Generate your Key ID at https://razorpay.com/docs/payments/dashboard/account-settings/api-keys/#live-mode-api-keys',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'key_secret',
                'label' => 'Key Secret',
                'description' => 'Generate your Key Secret at https://razorpay.com/docs/payments/dashboard/account-settings/api-keys/#live-mode-api-keys',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'webhook_secret',
                'label' => 'Webhook Secret',
                'description' => 'Generate your Webhook Secret at https://razorpay.com/docs/payments/dashboard/account-settings/webhooks/#set-up-webhooks and make sure your Webhook URL format is "https://<your_paymenter_url>/extensions/gateways/razorpay/webhook"',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'test_mode',
                'label' => 'Test Mode',
                'type' => 'checkbox',
                'required' => false,
            ],
            [
                'name' => 'test_key_id',
                'label' => 'Test Key ID',
                'description' => 'Generate your Test Key ID at https://razorpay.com/docs/payments/dashboard/account-settings/api-keys/#test-mode-api-keys',
                'type' => 'text',
                'required' => false,
            ],
            [
                'name' => 'test_key_secret',
                'label' => 'Test Key Secret',
                'description' => 'Generate your Test Key Secret at https://razorpay.com/docs/payments/dashboard/account-settings/api-keys/#test-mode-api-keys',
                'type' => 'text',
                'required' => false,
            ],
            [
                'name' => 'subscription_support',
                'label' => 'Enable Subscription Support',
                'description' => 'Enable support for recurring subscriptions and automatic billing',
                'type' => 'checkbox',
                'required' => false,
            ],
            [
                'name' => 'subscription_webhook_secret',
                'label' => 'Subscription Webhook Secret',
                'description' => 'Separate webhook secret for subscription events (optional, uses main webhook secret if not set)',
                'type' => 'text',
                'required' => false,
            ],
        ];
    }

    public function pay($invoice, $total)
    {
        Log::info('Razorpay: Starting payment process', [
            'invoice_id' => $invoice->id,
            'total' => $total,
            'currency' => $invoice->currency_code,
            'user_id' => $invoice->user_id ?? 'unknown'
        ]);

        if ($invoice->currency_code !== "INR") {
            Log::warning('Razorpay: Currency not supported', [
                'invoice_id' => $invoice->id,
                'currency' => $invoice->currency_code,
                'required_currency' => 'INR'
            ]);
            
            return view('extensions.gateways.razorpay::error', [
                'error' => 'The product currency code must be "INR" to make payments with Razorpay!',
            ]);
        }

        $keyId = $this->config('test_mode') ? $this->config('test_key_id') : $this->config('key_id');
        $keySecret = $this->config('test_mode') ? $this->config('test_key_secret') : $this->config('key_secret');
        $orderAmount = $total * 100;

        Log::info('Razorpay: Configuration loaded', [
            'invoice_id' => $invoice->id,
            'test_mode' => $this->config('test_mode'),
            'key_id' => $keyId ? '***' . substr($keyId, -4) : 'not_set',
            'order_amount' => $orderAmount,
            'order_amount_paise' => $orderAmount
        ]);

        // Check if this is a subscription
        $isSubscription = $this->isSubscriptionInvoice($invoice);
        
        Log::info('Razorpay: Subscription check completed', [
            'invoice_id' => $invoice->id,
            'is_subscription' => $isSubscription,
            'subscription_support_enabled' => $this->config('subscription_support')
        ]);
        
        if ($isSubscription && $this->config('subscription_support')) {
            Log::info('Razorpay: Creating subscription payment', [
                'invoice_id' => $invoice->id,
                'total' => $total
            ]);
            
            return $this->createSubscription($invoice, $total, $keyId, $keySecret);
        }

        // Regular one-time payment
        Log::info('Razorpay: Creating one-time payment order', [
            'invoice_id' => $invoice->id,
            'amount' => $total,
            'amount_paise' => $orderAmount
        ]);

        $url = "https://api.razorpay.com/v1/orders";

        $client = new Client();

        $payload = [
            "amount" => $orderAmount,
            "currency" => $invoice->currency_code,
            "notes" => [
                'invoice_id' => $invoice->id,
                'type' => 'one_time',
            ],
        ];

        Log::info('Razorpay: API request payload prepared', [
            'invoice_id' => $invoice->id,
            'url' => $url,
            'payload' => $payload
        ]);

        try {
            Log::info('Razorpay: Making API call to create order', [
                'invoice_id' => $invoice->id,
                'url' => $url
            ]);

            $response = $client->post($url, [
                'auth' => [$keyId, $keySecret],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            Log::info('Razorpay: Order created successfully', [
                'invoice_id' => $invoice->id,
                'razorpay_order_id' => $data['id'] ?? 'unknown',
                'response_status' => $response->getStatusCode(),
                'response_data' => $data
            ]);

            return view('extensions.gateways.razorpay::pay', [
                'keyId' => $keyId,
                'id' => $data['id'],
                'orderAmount' => $orderAmount,
                'invoiceId' => $invoice->id,
                'isSubscription' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to create order', [
                'invoice_id' => $invoice->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'payload' => $payload
            ]);
            
            throw new \Exception('Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Create a subscription for recurring billing
     */
    protected function createSubscription($invoice, $total, $keyId, $keySecret)
    {
        Log::info('Razorpay: Starting subscription creation process', [
            'invoice_id' => $invoice->id,
            'total' => $total,
            'key_id' => $keyId ? '***' . substr($keyId, -4) : 'not_set'
        ]);

        try {
            $client = new Client();
            
            // Get subscription details from the invoice/service
            Log::info('Razorpay: Getting subscription details', [
                'invoice_id' => $invoice->id
            ]);
            
            $subscriptionDetails = $this->getSubscriptionDetails($invoice);
            
            Log::info('Razorpay: Subscription details retrieved', [
                'invoice_id' => $invoice->id,
                'subscription_details' => $subscriptionDetails
            ]);
            
            // Create subscription plan if it doesn't exist
            Log::info('Razorpay: Creating or getting subscription plan', [
                'invoice_id' => $invoice->id,
                'plan_details' => $subscriptionDetails
            ]);
            
            $planId = $this->createOrGetPlan($subscriptionDetails, $keyId, $keySecret);
            
            Log::info('Razorpay: Plan ID obtained', [
                'invoice_id' => $invoice->id,
                'plan_id' => $planId
            ]);
            
            // Create subscription
            $subscriptionPayload = [
                "plan_id" => $planId,
                "customer_notify" => 1,
                "total_count" => $this->calculateTotalCount($subscriptionDetails['billing_unit'], $subscriptionDetails['billing_period']),
                "notes" => [
                    'invoice_id' => $invoice->id,
                    'type' => 'subscription',
                    'service_id' => $subscriptionDetails['service_id'] ?? null,
                ],
            ];

            // Note: Razorpay API requires total_count >= 1
            // - total_count = 60 (monthly): Subscription will be charged 60 times (5 years)
            // - total_count = 5 (yearly): Subscription will be charged 5 times (5 years)
            // - For other periods: Calculated based on billing unit and period

            Log::info('Razorpay: Subscription payload prepared', [
                'invoice_id' => $invoice->id,
                'plan_id' => $planId,
                'payload' => $subscriptionPayload
            ]);

            // Validate subscription payload
            Log::info('Razorpay: Validating subscription payload', [
                'invoice_id' => $invoice->id,
                'validation' => [
                    'plan_id_valid' => !empty($planId),
                    'plan_id_length' => strlen($planId),
                    'customer_notify_valid' => $subscriptionPayload['customer_notify'] === 1,
                    'total_count_valid' => is_numeric($subscriptionPayload['total_count']) && $subscriptionPayload['total_count'] >= 1,
                    'total_count_value' => $subscriptionPayload['total_count'],
                    'notes_valid' => !empty($subscriptionPayload['notes']['invoice_id'])
                ]
            ]);

            // Additional validation for subscription payload
            if ($subscriptionPayload['total_count'] < 1) {
                $error = "Invalid total_count '{$subscriptionPayload['total_count']}' for Razorpay subscription. Must be at least 1.";
                Log::error('Razorpay: Subscription validation failed - invalid total_count', [
                    'invoice_id' => $invoice->id,
                    'total_count' => $subscriptionPayload['total_count']
                ]);
                throw new \Exception($error);
            }

            Log::info('Razorpay: Subscription payload validation passed', [
                'invoice_id' => $invoice->id,
                'total_count' => $subscriptionPayload['total_count'],
                'billing_unit' => $subscriptionDetails['billing_unit'],
                'billing_period' => $subscriptionDetails['billing_period']
            ]);

            Log::info('Razorpay: Making API call to create subscription', [
                'invoice_id' => $invoice->id,
                'url' => 'https://api.razorpay.com/v1/subscriptions',
                'plan_id' => $planId
            ]);

            $response = $client->post("https://api.razorpay.com/v1/subscriptions", [
                'auth' => [$keyId, $keySecret],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $subscriptionPayload,
            ]);

            $subscriptionData = json_decode($response->getBody(), true);

            Log::info('Razorpay: Subscription created successfully', [
                'invoice_id' => $invoice->id,
                'razorpay_subscription_id' => $subscriptionData['id'] ?? 'unknown',
                'response_status' => $response->getStatusCode(),
                'response_data' => $subscriptionData
            ]);

            return view('extensions.gateways.razorpay::subscription', [
                'keyId' => $keyId,
                'subscriptionId' => $subscriptionData['id'],
                'invoiceId' => $invoice->id,
                'subscriptionDetails' => $subscriptionDetails,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Subscription creation failed', [
                'invoice_id' => $invoice->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'subscription_details' => $subscriptionDetails ?? 'not_available'
            ]);
            
            throw new \Exception('Failed to create subscription: ' . $e->getMessage());
        }
    }

    /**
     * Create or get a subscription plan
     */
    protected function createOrGetPlan($subscriptionDetails, $keyId, $keySecret)
    {
        Log::info('Razorpay: Starting plan creation/retrieval process', [
            'subscription_details' => $subscriptionDetails
        ]);

        $client = new Client();
        
        // Check if plan already exists
        $planName = $this->generatePlanName($subscriptionDetails);
        
        Log::info('Razorpay: Generated plan name', [
            'plan_name' => $planName,
            'subscription_details' => $subscriptionDetails
        ]);
        
        $planId = $this->findExistingPlan($planName, $keyId, $keySecret);
        
        if ($planId) {
            Log::info('Razorpay: Existing plan found', [
                'plan_name' => $planName,
                'plan_id' => $planId
            ]);
            return $planId;
        }

        Log::info('Razorpay: No existing plan found, creating new plan', [
            'plan_name' => $planName
        ]);

        // Create new plan
        $planPayload = [
            "period" => $this->mapBillingUnitToRazorpayPeriod($subscriptionDetails['billing_unit'], $subscriptionDetails['billing_period']),
            "interval" => $subscriptionDetails['billing_period'],
            "item" => [
                "name" => $planName,
                "amount" => $subscriptionDetails['amount'] * 100, // Convert to paise
                "currency" => "INR",
                "description" => $subscriptionDetails['description'] ?? 'Subscription plan',
            ],
        ];

        // Validate plan payload
        Log::info('Razorpay: Validating plan creation payload', [
            'plan_name' => $planName,
            'payload' => $planPayload,
            'validation' => [
                'period_valid' => in_array($planPayload['period'], ['daily', 'weekly', 'monthly', 'yearly']),
                'interval_valid' => is_numeric($planPayload['interval']) && $planPayload['interval'] > 0,
                'amount_valid' => is_numeric($planPayload['item']['amount']) && $planPayload['item']['amount'] > 0,
                'currency_valid' => $planPayload['item']['currency'] === 'INR'
            ]
        ]);

        // Additional validation checks
        if (!in_array($planPayload['period'], ['daily', 'weekly', 'monthly', 'yearly'])) {
            $error = "Invalid period '{$planPayload['period']}' for Razorpay plan. Expected: daily, weekly, monthly, yearly";
            Log::error('Razorpay: Plan validation failed - invalid period', [
                'plan_name' => $planName,
                'period' => $planPayload['period'],
                'billing_unit' => $subscriptionDetails['billing_unit'],
                'billing_period' => $subscriptionDetails['billing_period']
            ]);
            throw new \Exception($error);
        }

        if (!is_numeric($planPayload['interval']) || $planPayload['interval'] <= 0) {
            $error = "Invalid interval '{$planPayload['interval']}' for Razorpay plan. Must be a positive number.";
            Log::error('Razorpay: Plan validation failed - invalid interval', [
                'plan_name' => $planName,
                'interval' => $planPayload['interval'],
                'billing_period' => $subscriptionDetails['billing_period']
            ]);
            throw new \Exception($error);
        }

        if (!is_numeric($planPayload['item']['amount']) || $planPayload['item']['amount'] <= 0) {
            $error = "Invalid amount '{$planPayload['item']['amount']}' for Razorpay plan. Must be a positive number in paise.";
            Log::error('Razorpay: Plan validation failed - invalid amount', [
                'plan_name' => $planName,
                'amount' => $planPayload['item']['amount'],
                'original_amount' => $subscriptionDetails['amount']
            ]);
            throw new \Exception($error);
        }

        Log::info('Razorpay: Plan creation payload prepared and validated', [
            'plan_name' => $planName,
            'payload' => $planPayload
        ]);

        try {
            $response = $client->post("https://api.razorpay.com/v1/plans", [
                'auth' => [$keyId, $keySecret],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $planPayload,
            ]);

            $planData = json_decode($response->getBody(), true);
            
            Log::info('Razorpay: Plan created successfully', [
                'plan_name' => $planName,
                'plan_id' => $planData['id'] ?? 'unknown',
                'response_status' => $response->getStatusCode(),
                'response_data' => $planData
            ]);
            
            return $planData['id'];
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to create plan', [
                'plan_name' => $planName,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'payload' => $planPayload
            ]);
            
            throw $e;
        }
    }

    /**
     * Find existing plan by name
     */
    protected function findExistingPlan($planName, $keyId, $keySecret)
    {
        Log::info('Razorpay: Searching for existing plan', [
            'plan_name' => $planName
        ]);

        try {
            $client = new Client();
            $response = $client->get("https://api.razorpay.com/v1/plans", [
                'auth' => [$keyId, $keySecret],
                'query' => ['count' => 100],
            ]);

            $plans = json_decode($response->getBody(), true);
            
            Log::info('Razorpay: Retrieved plans from API', [
                'plan_name' => $planName,
                'total_plans' => count($plans['items'] ?? []),
                'response_status' => $response->getStatusCode()
            ]);
            
            foreach ($plans['items'] as $plan) {
                if ($plan['item']['name'] === $planName) {
                    Log::info('Razorpay: Existing plan found', [
                        'plan_name' => $planName,
                        'plan_id' => $plan['id']
                    ]);
                    return $plan['id'];
                }
            }
            
            Log::info('Razorpay: No existing plan found', [
                'plan_name' => $planName
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Razorpay: Could not fetch existing plans', [
                'plan_name' => $planName,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode()
            ]);
            return null;
        }
    }

    /**
     * Generate plan name for Razorpay
     */
    protected function generatePlanName($subscriptionDetails)
    {
        $period = $subscriptionDetails['billing_period'] > 1 ? $subscriptionDetails['billing_period'] . ' ' : '';
        $unit = $subscriptionDetails['billing_unit'];
        
        $planName = "Plan - {$period}{$unit} - " . ($subscriptionDetails['amount'] ?? '0');
        
        Log::debug('Razorpay: Generated plan name', [
            'plan_name' => $planName,
            'billing_period' => $subscriptionDetails['billing_period'],
            'billing_unit' => $subscriptionDetails['billing_unit'],
            'amount' => $subscriptionDetails['amount']
        ]);
        
        return $planName;
    }

    /**
     * Map billing unit to Razorpay period format
     */
    protected function mapBillingUnitToRazorpayPeriod($billingUnit, $billingPeriod)
    {
        Log::debug('Razorpay: Mapping billing unit to Razorpay period', [
            'billing_unit' => $billingUnit,
            'billing_period' => $billingPeriod
        ]);

        // Razorpay expects: daily, weekly, monthly, yearly
        $periodMap = [
            'day' => 'daily',
            'days' => 'daily',
            'week' => 'weekly', 
            'weeks' => 'weekly',
            'month' => 'monthly',
            'months' => 'monthly',
            'year' => 'yearly',
            'years' => 'yearly'
        ];

        $normalizedUnit = strtolower($billingUnit);
        $razorpayPeriod = $periodMap[$normalizedUnit] ?? 'monthly';

        Log::debug('Razorpay: Period mapping result', [
            'original_unit' => $billingUnit,
            'normalized_unit' => $normalizedUnit,
            'razorpay_period' => $razorpayPeriod,
            'period_map' => $periodMap
        ]);

        return $razorpayPeriod;
    }

    /**
     * Check if invoice is for a subscription
     */
    protected function isSubscriptionInvoice($invoice)
    {
        Log::info('Razorpay: Checking if invoice is for subscription', [
            'invoice_id' => $invoice->id,
            'total_items' => $invoice->items->count(),
            'items' => $invoice->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'reference_type' => $item->reference_type,
                    'reference_id' => $item->reference_id
                ];
            })->toArray()
        ]);

        // Check if all invoice items are services
        $eligibleForSubscription = collect($invoice->items)->filter(function ($item) {
            return $item->reference_type === \App\Models\Service::class;
        })->count() == $invoice->items->count();

        Log::info('Razorpay: Subscription eligibility check result', [
            'invoice_id' => $invoice->id,
            'total_items' => $invoice->items->count(),
            'service_items_count' => collect($invoice->items)->filter(function ($item) {
                return $item->reference_type === \App\Models\Service::class;
            })->count(),
            'eligible_for_subscription' => $eligibleForSubscription
        ]);

        if (!$eligibleForSubscription) {
            Log::info('Razorpay: Invoice not eligible for subscription - not all items are services', [
                'invoice_id' => $invoice->id
            ]);
            return false;
        }

        // Check if any of the services have recurring plans
        $hasRecurringService = collect($invoice->items)->filter(function ($item) {
            if ($item->reference_type === \App\Models\Service::class && $item->reference) {
                $service = $item->reference;
                return isset($service->plan) && $service->plan->type === 'recurring';
            }
            return false;
        })->count() > 0;

        Log::info('Razorpay: Recurring service check result', [
            'invoice_id' => $invoice->id,
            'has_recurring_service' => $hasRecurringService,
            'services_with_plans' => collect($invoice->items)->filter(function ($item) {
                if ($item->reference_type === \App\Models\Service::class && $item->reference) {
                    $service = $item->reference;
                    return [
                        'service_id' => $service->id,
                        'has_plan' => isset($service->plan),
                        'plan_type' => isset($service->plan) ? $service->plan->type : 'no_plan'
                    ];
                }
                return null;
            })->filter()->toArray()
        ]);

        return $hasRecurringService;
    }

    /**
     * Get subscription details from invoice
     */
    protected function getSubscriptionDetails($invoice)
    {
        Log::debug('Razorpay: Getting subscription details from invoice', [
            'invoice_id' => $invoice->id,
            'total_items' => $invoice->items->count()
        ]);

        // Get the first service item from the invoice
        $serviceItem = collect($invoice->items)->filter(function ($item) {
            return $item->reference_type === \App\Models\Service::class;
        })->first();

        if (!$serviceItem || !$serviceItem->reference) {
            Log::error('Razorpay: No service item found for subscription', [
                'invoice_id' => $invoice->id,
                'service_item' => $serviceItem ? $serviceItem->id : 'not_found',
                'has_reference' => $serviceItem ? isset($serviceItem->reference) : false
            ]);
            
            throw new \Exception('No service found for subscription');
        }

        $service = $serviceItem->reference;
        
        if (!$service->plan) {
            Log::error('Razorpay: No plan found for service', [
                'invoice_id' => $invoice->id,
                'service_id' => $service->id,
                'has_plan' => isset($service->plan)
            ]);
            
            throw new \Exception('No plan found for subscription');
        }

        $plan = $service->plan;

        $details = [
            'amount' => $invoice->total,
            'billing_period' => $plan->billing_period,
            'billing_unit' => $plan->billing_unit,
            'service_id' => $service->id,
            'description' => $service->product->name ?? 'Subscription Service',
        ];

        Log::debug('Razorpay: Subscription details extracted', [
            'invoice_id' => $invoice->id,
            'service_id' => $service->id,
            'plan_id' => $plan->id,
            'details' => $details
        ]);

        return $details;
    }

    /**
     * Calculate total count for Razorpay subscription based on billing unit and period
     */
    protected function calculateTotalCount($billingUnit, $billingPeriod)
    {
        Log::debug('Razorpay: Calculating total count for subscription', [
            'billing_unit' => $billingUnit,
            'billing_period' => $billingPeriod
        ]);

        $normalizedUnit = strtolower($billingUnit);
        
        // Calculate total count based on billing unit
        // For 5-year subscription duration:
        switch ($normalizedUnit) {
            case 'month':
            case 'months':
                $totalCount = 60; // 5 years × 12 months = 60 charges
                break;
                
            case 'year':
            case 'years':
                $totalCount = 5; // 5 years × 1 year = 5 charges
                break;
                
            case 'week':
            case 'weeks':
                $totalCount = 260; // 5 years × 52 weeks = 260 charges
                break;
                
            case 'day':
            case 'days':
                $totalCount = 1825; // 5 years × 365 days = 1825 charges
                break;
                
            default:
                $totalCount = 60; // Default to monthly equivalent
                Log::warning('Razorpay: Unknown billing unit, defaulting to monthly equivalent', [
                    'billing_unit' => $billingUnit,
                    'default_total_count' => $totalCount
                ]);
        }

        Log::info('Razorpay: Total count calculated for subscription', [
            'billing_unit' => $billingUnit,
            'normalized_unit' => $normalizedUnit,
            'billing_period' => $billingPeriod,
            'total_count' => $totalCount,
            'subscription_duration' => '5 years'
        ]);

        return $totalCount;
    }

    public function webhook(Request $request)
    {
        Log::info('Razorpay: Webhook received', [
            'method' => $request->method(),
            'url' => $request->url(),
            'content_length' => strlen($request->getContent())
        ]);

        $content = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');
        
        // Use subscription webhook secret if available, otherwise fall back to main secret
        $secret = $this->config('subscription_webhook_secret') ?: $this->config('webhook_secret');

        Log::info('Razorpay: Webhook signature verification', [
            'signature_received' => $signature ? '***' . substr($signature, -8) : 'not_present',
            'secret_used' => $this->config('subscription_webhook_secret') ? 'subscription_secret' : 'main_secret',
            'secret_length' => strlen($secret)
        ]);

        $expected_signature = hash_hmac('sha256', $content, $secret);

        if ($signature !== $expected_signature) {
            Log::error('Razorpay: Webhook signature verification failed', [
                'signature_received' => $signature,
                'expected_signature' => $expected_signature,
                'content_length' => strlen($content)
            ]);
            
            return response('Signature verification failed', 401);
        }

        Log::info('Razorpay: Webhook signature verified successfully');

        $data = json_decode($content, true);
        $event = $data['event'] ?? '';

        Log::info('Razorpay: Processing webhook event', [
            'event' => $event,
            'payload_keys' => array_keys($data),
            'has_payload' => isset($data['payload']),
            'payment_type' => $this->detectPaymentType($data)
        ]);

        // Log webhook structure for debugging
        $this->logWebhookStructure($data);

        try {
            switch ($event) {
                case 'order.paid':
                    Log::info('Razorpay: Processing order.paid event');
                    $this->handleOrderPaid($data);
                    break;
                    
                case 'invoice.paid':
                    Log::info('Razorpay: Processing invoice.paid event');
                    $this->handleInvoicePaid($data);
                    break;
                    
                case 'subscription.activated':
                    Log::info('Razorpay: Processing subscription.activated event');
                    $this->handleSubscriptionActivated($data);
                    break;
                    
                case 'subscription.charged':
                    Log::info('Razorpay: Processing subscription.charged event');
                    $this->handleSubscriptionCharged($data);
                    break;
                    
                case 'subscription.halted':
                    Log::info('Razorpay: Processing subscription.halted event');
                    $this->handleSubscriptionHalted($data);
                    break;
                    
                case 'subscription.cancelled':
                    Log::info('Razorpay: Processing subscription.cancelled event');
                    $this->handleSubscriptionCancelled($data);
                    break;
                    
                case 'subscription.completed':
                    Log::info('Razorpay: Processing subscription.completed event');
                    $this->handleSubscriptionCompleted($data);
                    break;
                    
                default:
                    Log::info('Razorpay: Unhandled webhook event', [
                        'event' => $event,
                        'payload' => $data
                    ]);
            }

            Log::info('Razorpay: Webhook processed successfully', [
                'event' => $event,
                'payment_type' => $this->detectPaymentType($data)
            ]);

            return response('Webhook received and processed successfully');
        } catch (\Exception $e) {
            Log::error('Razorpay: Error processing webhook', [
                'event' => $event,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'payload' => $data
            ]);
            
            return response('Webhook processing failed', 500);
        }
    }

    /**
     * Handle one-time payment success
     */
    protected function handleOrderPaid($data)
    {
        Log::info('Razorpay: Processing order.paid webhook', [
            'payload' => $data
        ]);

        try {
            $orderId = $data['payload']['payment']['entity']['id'];
            $orderAmount = $data['payload']['order']['entity']['amount'] / 100;
            
            // Try to get invoice_id from different possible locations
            $invoiceId = null;
            
            // Check payment notes first (for subscription payments)
            if (isset($data['payload']['payment']['entity']['notes']['invoice_id'])) {
                $invoiceId = $data['payload']['payment']['entity']['notes']['invoice_id'];
                Log::info('Razorpay: Invoice ID found in payment notes', [
                    'invoice_id' => $invoiceId,
                    'source' => 'payment_notes'
                ]);
            }
            // Check order notes (for one-time payments)
            elseif (isset($data['payload']['order']['entity']['notes']['invoice_id'])) {
                $invoiceId = $data['payload']['order']['entity']['notes']['invoice_id'];
                Log::info('Razorpay: Invoice ID found in order notes', [
                    'invoice_id' => $invoiceId,
                    'source' => 'order_notes'
                ]);
            }
            // Check if this is a subscription payment
            elseif (isset($data['payload']['payment']['entity']['notes']['subscription_id'])) {
                $subscriptionId = $data['payload']['payment']['entity']['notes']['subscription_id'];
                Log::info('Razorpay: This is a subscription payment, processing as subscription.charged', [
                    'subscription_id' => $subscriptionId,
                    'payment_id' => $orderId
                ]);
                
                // This should be handled by subscription.charged webhook instead
                // For now, we'll skip processing this as order.paid
                Log::info('Razorpay: Skipping order.paid processing for subscription payment', [
                    'subscription_id' => $subscriptionId,
                    'payment_id' => $orderId
                ]);
                return;
            }
            
            if (!$invoiceId) {
                Log::warning('Razorpay: No invoice ID found in webhook payload', [
                    'payment_notes' => $data['payload']['payment']['entity']['notes'] ?? 'not_found',
                    'order_notes' => $data['payload']['order']['entity']['notes'] ?? 'not_found',
                    'payment_id' => $orderId
                ]);
                return;
            }

            Log::info('Razorpay: Order payment details extracted', [
                'order_id' => $orderId,
                'order_amount' => $orderAmount,
                'invoice_id' => $invoiceId
            ]);

            ExtensionHelper::addPayment($invoiceId, 'Razorpay', $orderAmount, null, $orderId);

            Log::info('Razorpay: Payment added successfully', [
                'invoice_id' => $invoiceId,
                'amount' => $orderAmount,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process order.paid webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Handle invoice paid (for recurring payments)
     */
    protected function handleInvoicePaid($data)
    {
        Log::info('Razorpay: Processing invoice.paid webhook', [
            'payload' => $data
        ]);

        try {
            $invoiceId = $data['payload']['invoice']['entity']['id'];
            $invoiceAmount = $data['payload']['invoice']['entity']['amount'] / 100;
            $paymentId = $data['payload']['payment']['entity']['id'];
            $subscriptionId = $data['payload']['invoice']['entity']['subscription_id'] ?? null;
            
            Log::info('Razorpay: Invoice paid details extracted', [
                'invoice_id' => $invoiceId,
                'amount' => $invoiceAmount,
                'payment_id' => $paymentId,
                'subscription_id' => $subscriptionId
            ]);

            // Create new invoice for recurring payment
            $this->createRecurringInvoice($invoiceId, $invoiceAmount, $paymentId, $subscriptionId);

            Log::info('Razorpay: Invoice paid processed successfully', [
                'invoice_id' => $invoiceId,
                'amount' => $invoiceAmount,
                'payment_id' => $paymentId,
                'subscription_id' => $subscriptionId
            ]);

            // Log subscription lifecycle information
            if ($subscriptionId) {
                Log::info('Razorpay: Subscription payment processed', [
                    'subscription_id' => $subscriptionId,
                    'invoice_id' => $invoiceId,
                    'amount' => $invoiceAmount,
                    'event_type' => 'invoice.paid',
                    'next_billing' => 'Will be processed by Razorpay automatically'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process invoice.paid webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription activation
     */
    protected function handleSubscriptionActivated($data)
    {
        Log::info('Razorpay: Processing subscription.activated webhook', [
            'payload' => $data
        ]);

        try {
            $subscriptionId = $data['payload']['subscription']['entity']['id'];
            $invoiceId = $data['payload']['subscription']['entity']['notes']['invoice_id'];
            
            Log::info('Razorpay: Subscription activation details extracted', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
            
            // Update service status if needed
            $this->updateServiceStatus($invoiceId, 'active');

            Log::info('Razorpay: Subscription activated successfully', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process subscription.activated webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription charge
     */
    protected function handleSubscriptionCharged($data)
    {
        Log::info('Razorpay: Processing subscription.charged webhook', [
            'payload' => $data
        ]);

        try {
            $subscriptionId = $data['payload']['subscription']['entity']['id'];
            $paymentId = $data['payload']['payment']['entity']['id'];
            $amount = $data['payload']['payment']['entity']['amount'] / 100;
            $invoiceId = $data['payload']['subscription']['entity']['notes']['invoice_id'];
            
            Log::info('Razorpay: Subscription charge details extracted', [
                'subscription_id' => $subscriptionId,
                'payment_id' => $paymentId,
                'amount' => $amount,
                'invoice_id' => $invoiceId
            ]);
            
            // Create new invoice for recurring payment
            $this->createRecurringInvoice($invoiceId, $amount, $paymentId, $subscriptionId);

            Log::info('Razorpay: Subscription charge processed successfully', [
                'subscription_id' => $subscriptionId,
                'payment_id' => $paymentId,
                'amount' => $amount,
                'invoice_id' => $invoiceId
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process subscription.charged webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription halted
     */
    protected function handleSubscriptionHalted($data)
    {
        Log::info('Razorpay: Processing subscription.halted webhook', [
            'payload' => $data
        ]);

        try {
            $subscriptionId = $data['payload']['subscription']['entity']['id'];
            $invoiceId = $data['payload']['subscription']['entity']['notes']['invoice_id'];
            
            Log::info('Razorpay: Subscription halted details extracted', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
            
            // Update service status
            $this->updateServiceStatus($invoiceId, 'suspended');

            Log::info('Razorpay: Subscription halted successfully', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process subscription.halted webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription cancellation
     */
    protected function handleSubscriptionCancelled($data)
    {
        Log::info('Razorpay: Processing subscription.cancelled webhook', [
            'payload' => $data
        ]);

        try {
            $subscriptionId = $data['payload']['subscription']['entity']['id'];
            $invoiceId = $data['payload']['subscription']['entity']['notes']['invoice_id'];
            
            Log::info('Razorpay: Subscription cancellation details extracted', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
            
            // Update service status
            $this->updateServiceStatus($invoiceId, 'cancelled');

            Log::info('Razorpay: Subscription cancelled successfully', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process subscription.cancelled webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription completion
     */
    protected function handleSubscriptionCompleted($data)
    {
        Log::info('Razorpay: Processing subscription.completed webhook', [
            'payload' => $data
        ]);

        try {
            $subscriptionId = $data['payload']['subscription']['entity']['id'];
            $invoiceId = $data['payload']['subscription']['entity']['notes']['invoice_id'];
            
            Log::info('Razorpay: Subscription completion details extracted', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
            
            // Update service status
            $this->updateServiceStatus($invoiceId, 'completed');

            Log::info('Razorpay: Subscription completed successfully', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $invoiceId
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to process subscription.completed webhook', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'payload' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update service status
     */
    protected function updateServiceStatus($invoiceId, $status)
    {
        Log::info('Razorpay: Updating service status', [
            'invoice_id' => $invoiceId,
            'new_status' => $status
        ]);

        try {
            $invoice = \App\Models\Invoice::find($invoiceId);
            
            if (!$invoice) {
                Log::warning('Razorpay: Invoice not found for status update', [
                    'invoice_id' => $invoiceId,
                    'status' => $status
                ]);
                return;
            }

            if (!$invoice->service) {
                Log::warning('Razorpay: Service not found for invoice', [
                    'invoice_id' => $invoiceId,
                    'status' => $status
                ]);
                return;
            }

            $oldStatus = $invoice->service->status;
            $invoice->service->update(['status' => $status]);

            Log::info('Razorpay: Service status updated successfully', [
                'invoice_id' => $invoiceId,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'service_id' => $invoice->service->id
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to update service status', [
                'invoice_id' => $invoiceId,
                'status' => $status,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode()
            ]);
        }
    }

    /**
     * Create recurring invoice for subscription
     */
    protected function createRecurringInvoice($originalInvoiceId, $amount, $paymentId, $subscriptionId = null)
    {
        Log::info('Razorpay: Creating recurring invoice', [
            'original_invoice_id' => $originalInvoiceId,
            'amount' => $amount,
            'payment_id' => $paymentId,
            'subscription_id' => $subscriptionId
        ]);

        try {
            $originalInvoice = \App\Models\Invoice::find($originalInvoiceId);
            
            if (!$originalInvoice) {
                Log::warning('Razorpay: Original invoice not found for recurring invoice creation', [
                    'original_invoice_id' => $originalInvoiceId
                ]);
                return;
            }

            if (!$originalInvoice->service) {
                Log::warning('Razorpay: Service not found for original invoice', [
                    'original_invoice_id' => $originalInvoiceId
                ]);
                return;
            }

            // Create new invoice for recurring payment
            $newInvoice = \App\Models\Invoice::create([
                'user_id' => $originalInvoice->user_id,
                'service_id' => $originalInvoice->service_id,
                'total' => $amount,
                'currency_code' => $originalInvoice->currency_code,
                'status' => 'paid',
                'due_date' => now()->addDays(30), // Adjust based on billing cycle
            ]);

            Log::info('Razorpay: New recurring invoice created', [
                'new_invoice_id' => $newInvoice->id,
                'original_invoice_id' => $originalInvoiceId,
                'amount' => $amount,
                'user_id' => $newInvoice->user_id,
                'service_id' => $newInvoice->service_id
            ]);

            // Add payment record
            ExtensionHelper::addPayment($newInvoice->id, 'Razorpay Subscription', $amount, null, $paymentId);
            
            Log::info('Razorpay: Payment record added for recurring invoice', [
                'new_invoice_id' => $newInvoice->id,
                'payment_id' => $paymentId,
                'amount' => $amount
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to create recurring invoice', [
                'original_invoice_id' => $originalInvoiceId,
                'amount' => $amount,
                'payment_id' => $paymentId,
                'subscription_id' => $subscriptionId,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);
        }
    }

    /**
     * Detect payment type from webhook payload
     */
    protected function detectPaymentType($data)
    {
        // Check if this is an invoice.paid event (subscription payment)
        if (isset($data['payload']['invoice']['entity']['subscription_id'])) {
            return 'subscription_payment';
        }
        
        // Check if this is a subscription payment in order.paid
        if (isset($data['payload']['payment']['entity']['notes']['subscription_id'])) {
            return 'subscription_payment';
        }
        
        return 'one_time_payment';
    }

    /**
     * Log webhook structure for debugging
     */
    protected function logWebhookStructure($data)
    {
        Log::debug('Razorpay: Webhook structure analysis', [
            'event' => $data['event'] ?? 'unknown',
            'has_payment' => isset($data['payload']['payment']),
            'has_order' => isset($data['payload']['order']),
            'has_invoice' => isset($data['payload']['invoice']),
            'payment_notes' => $data['payload']['payment']['entity']['notes'] ?? 'not_found',
            'order_notes' => $data['payload']['order']['entity']['notes'] ?? 'not_found',
            'invoice_id' => isset($data['payload']['invoice']['entity']['id']) ? $data['payload']['invoice']['entity']['id'] : 'not_found',
            'subscription_id' => isset($data['payload']['invoice']['entity']['subscription_id']) ? $data['payload']['invoice']['entity']['subscription_id'] : 'not_found',
            'payment_amount' => isset($data['payload']['payment']['entity']['amount']) ? $data['payload']['payment']['entity']['amount'] / 100 : 'not_found',
            'order_amount' => isset($data['payload']['order']['entity']['amount']) ? $data['payload']['order']['entity']['amount'] / 100 : 'not_found',
            'invoice_amount' => isset($data['payload']['invoice']['entity']['amount']) ? $data['payload']['invoice']['entity']['amount'] / 100 : 'not_found'
        ]);
    }
}
