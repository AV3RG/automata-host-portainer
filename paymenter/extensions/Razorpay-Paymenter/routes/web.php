<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Gateways\Razorpay\Razorpay;
use App\Models\Invoice;

Route::post(
    '/extensions/gateways/razorpay/webhook',
    [Razorpay::class, 'webhook']
)->name('extensions.gateways.razorpay.webhook');

// pass the invoice number to the callback
Route::post('/extensions/gateways/razorpay/callback/{invoiceId}',
    [Razorpay::class, 'callback']
)->name('extensions.gateways.razorpay.callback');

Route::get('/extensions/gateways/razorpay/cancel/{invoiceNumber}', function ($invoiceNumber) {
    return redirect()->route('invoices.show', ['invoice' => $invoiceNumber, 'checkPayment' => 'false']);
})->name('extensions.gateways.razorpay.cancel');
