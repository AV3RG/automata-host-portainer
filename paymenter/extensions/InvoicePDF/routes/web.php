<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Others\InvoicePDF\InvoicePDFController;

// Invoice PDF generation route
Route::post('/invoices/download-pdf', [InvoicePDFController::class, 'generatePDF'])
    ->name('invoices.download-pdf')
    ->middleware(['web', 'auth']);

// Test route to check if extension is loaded
Route::get('/test-invoice-pdf', function() {
    return response()->json(['message' => 'InvoicePDF extension is working!']);
})->name('test.invoice.pdf');
