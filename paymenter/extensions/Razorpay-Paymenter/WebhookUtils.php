<?php

namespace Paymenter\Extensions\Gateways\Razorpay;

use App\Helpers\ExtensionHelper;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;

class WebhookUtils {

    public static function onCharged($subscriptionEntity, $paymentEntity) {
        $invoice = Invoice::findOrFail($subscriptionEntity->notes->invoice_id);
        $user = $invoice->user;
        foreach ($invoice->items as $item) {
            if ($item->reference_type !== Service::class) {
                continue;
            }
            $service = $item->reference;
            if (!$service->subscription_id && !$service->properties->where('key', 'has_razorpay_subscription')->first()) {
                $service->update(['subscription_id' => $subscriptionEntity->id]);
                $service->properties()->updateOrCreate(['key' => 'has_razorpay_subscription'], ['value' => true]);
            }
        }
        ExtensionHelper::addPayment($invoice->id, 'Razorpay', $paymentEntity->amount / 100, $fee ?? null, $paymentEntity->id);
    }

    public static function onCancelled($subscriptionEntity) {
        $invoice = Invoice::findOrFail($subscriptionEntity->notes->invoice_id);
        foreach ($invoice->items as $item) {
            if ($item->reference_type !== Service::class) {
                continue;
            }
            $service = $item->reference;
            if ($service->subscription_id === $subscriptionEntity->id) {
                $service->update(['subscription_id' => null]);
                $service->properties()->updateOrCreate(['key' => 'has_razorpay_subscription'], ['value' => false]);
            }
        }
    }

}