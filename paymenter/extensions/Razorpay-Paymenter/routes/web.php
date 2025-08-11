<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Gateways\Razorpay\Razorpay;

// Helper function to format invoice ID
function formatInvoiceId($invoiceId) {
    $date = now()->format('Y-m-d');
    $paddedId = str_pad($invoiceId, 6, '0', STR_PAD_LEFT);
    return "INV-AH-{$date}-{$paddedId}";
}

Route::post(
    '/extensions/gateways/razorpay/webhook',
    [Razorpay::class, 'webhook']
)->name('extensions.gateways.razorpay.webhook');

Route::post('/extensions/gateways/razorpay/callback/{invoiceId}', function ($invoiceId) {
    Log::info('Razorpay: Callback received', ['invoiceId' => $invoiceId]);
    $formattedInvoiceId = formatInvoiceId($invoiceId);
    return redirect()->route('invoices.show', ['invoice' => $formattedInvoiceId]);
})->name('extensions.gateways.razorpay.callback');

Route::get('/extensions/gateways/razorpay/cancel/{invoiceId}', function ($invoiceId) {
    $formattedInvoiceId = formatInvoiceId($invoiceId);
    return redirect()->route('invoices.show', ['invoice' => $formattedInvoiceId]);
})->name('extensions.gateways.razorpay.cancel');

// Subscription management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/extensions/gateways/razorpay/subscriptions', function () {
        return view('extensions.gateways.razorpay::subscriptions');
    })->name('extensions.gateways.razorpay.subscriptions');
    
    Route::get('/extensions/gateways/razorpay/subscription/{subscriptionId}', function ($subscriptionId) {
        return view('extensions.gateways.razorpay::subscription-details', compact('subscriptionId'));
    })->name('extensions.gateways.razorpay.subscription.details');
    
    Route::post('/extensions/gateways/razorpay/subscription/{subscriptionId}/cancel', function ($subscriptionId) {
        // Handle subscription cancellation
        return redirect()->back()->with('success', 'Subscription cancellation request submitted');
    })->name('extensions.gateways.razorpay.subscription.cancel');
    
    Route::post('/extensions/gateways/razorpay/subscription/{subscriptionId}/pause', function ($subscriptionId) {
        // Handle subscription pause
        return redirect()->back()->with('success', 'Subscription pause request submitted');
    })->name('extensions.gateways.razorpay.subscription.pause');
    
    Route::post('/extensions/gateways/razorpay/subscription/{subscriptionId}/resume', function ($subscriptionId) {
        // Handle subscription resume
        return redirect()->back()->with('success', 'Subscription resume request submitted');
    })->name('extensions.gateways.razorpay.subscription.resume');
});
