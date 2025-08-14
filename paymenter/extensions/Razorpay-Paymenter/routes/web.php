<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Gateways\Razorpay\Razorpay;
use App\Models\Invoice;

Route::post(
    '/extensions/gateways/razorpay/webhook',
    [Razorpay::class, 'webhook']
)->name('extensions.gateways.razorpay.webhook');

Route::post('/extensions/gateways/razorpay/callback/{invoiceNumber}', function ($invoiceNumber) {
    Log::info('Razorpay: Callback received', ['invoiceId' => $invoiceNumber]);
    return redirect()->route('invoices.show', ['invoice' => $invoiceNumber, 'checkPayment' => 'true']);
})->name('extensions.gateways.razorpay.callback');

Route::get('/extensions/gateways/razorpay/cancel/{invoiceNumber}', function ($invoiceNumber) {
    return redirect()->route('invoices.show', ['invoice' => $invoiceNumber, 'checkPayment' => 'false']);
})->name('extensions.gateways.razorpay.cancel');
