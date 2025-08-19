<?php

namespace Paymenter\Extensions\Others\FacebookPixel;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Event;
use App\Events\Order\Finalized as OrderFinalizedEvent;
use App\Events\Invoice\Paid as InvoicePaidEvent;
use App\Models\User;


class FacebookPixel extends Extension
{
    private const events = [
        'User Created' => 'user.registration_complete',
        'Order Finalized' => OrderFinalizedEvent::class,
        'Invoice Paid' => InvoicePaidEvent::class,
    ];

    /**
     * Get all the configuration for the extension
     *
     * @param  array  $values
     * @return array
     */
    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'capi_access_token',
                'type' => 'text',
                'label' => 'CAPI Access Token',
                'required' => true,
            ],
            [
                'name' => 'pixel_id',
                'type' => 'text',
                'label' => 'Pixel ID',
                'required' => true,
            ]
        ];
    }

    public function boot()
    {
        if (empty($this->config('capi_access_token')) || empty($this->config('pixel_id'))) {
            \Log::error('Facebook Pixel Extension: CAPI Access Token or Pixel ID is not set. Please set them in the extension configuration.');
            return;
        }

        // Check if Facebook SDK is available before proceeding
        if (!CapiHelper::isSdkAvailable()) {
            \Log::error('Facebook Pixel Extension: Facebook SDK is not available. Please ensure facebook/php-business-sdk is properly installed.');
            return;
        }

        try {
            CapiHelper::initWithFallback($this->config('capi_access_token'));
        } catch (\Exception $e) {
            \Log::error('Facebook Pixel Extension: Failed to initialize Facebook API: ' . $e->getMessage());
            return;
        }

        \Log::info('Facebook Pixel Extension: Facebook SDK initialized successfully.');
        foreach (self::events as $eventType => $eventClass) {
            Event::listen(
                $eventClass,
                function ($event) use ($eventType) {
                    try {
                        $this->handleEvent($event, $eventType);
                    } catch (\Exception $e) {
                        // Log the error
                        \Log::error('Facebook Pixel Extension: Event handling failed: ' . $e->getMessage());
                        if (config('settings.debug')) {
                            throw $e;
                        }
                    }
                }
            );
        }

    }

    private function userCreatedEvent($event)
    {
        \Log::info('Facebook Pixel Extension: Creating event for user: ' . $event->id);
        $user = $event;
        $userData = CapiHelper::buildUserData($user);
        $capiEvent = CapiHelper::buildWebsiteEvent('CompleteRegistration', $userData, null);
        CapiHelper::sendEvent($capiEvent, $this->config('pixel_id'));
    }

    private function checkoutInitiatedEvent($event)
    {
        $order = $event->order;
        $user = $order->user;
        $userData = CapiHelper::buildUserData($user);
        $customData = CapiHelper::buildPurchaseDataForOrder($order);
        $capiEvent = CapiHelper::buildWebsiteEvent('InitiateCheckout', $userData, $customData);
        CapiHelper::sendEvent($capiEvent, $this->config('pixel_id'));
        \Log::info("Checkout Initiated Event sent successfully: " . json_encode($capiEvent));
    }

    private function invoicePaidEvent($event)
    {
        $invoice = $event->invoice;
        $user = $invoice->user;
        $userData = CapiHelper::buildUserData($user);
        $customData = CapiHelper::buildPurchaseDataForInvoice($invoice);
        $capiEvent = CapiHelper::buildWebsiteEvent('Purchase', $userData, $customData);
        \Log::info("Invoice Paid Event: " . json_encode($capiEvent));
        CapiHelper::sendEvent($capiEvent, $this->config('pixel_id'));
        \Log::info("Invoice Paid Event sent successfully: " . json_encode($capiEvent));
    }

    private function handleEvent($event, $eventType)
    {
        $functions = [
            'User Created' => fn ($event) => $this->userCreatedEvent($event),
            'Order Finalized' => fn ($event) => $this->checkoutInitiatedEvent($event),
            'Invoice Paid' => fn ($event) => $this->invoicePaidEvent($event),
        ];

        // Check if the event type is valid
        if (!array_key_exists($eventType, $functions)) {
            return;
        }

        $functions[$eventType]($event);

    }
}