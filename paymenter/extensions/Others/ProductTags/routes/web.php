<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Others\ProductTags\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Product Tags Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Product Tags extension. These routes
| provide both web interface and API endpoints for tag management.
|
*/

// Public tag routes
Route::prefix('tags')->name('tags.')->group(function () {
    // Tag listing and search
    Route::get('/', [TagController::class, 'index'])->name('index');
    
    // Individual tag page
    Route::get('/{tag}', [TagController::class, 'show'])->name('show');
});

// API routes for AJAX requests
Route::prefix('api/tags')->name('api.tags.')->group(function () {
    // Get tags (with optional search)
    Route::get('/', [TagController::class, 'api'])->name('index');
    
    // Get popular tags
    Route::get('/popular', [TagController::class, 'popular'])->name('popular');
    
    // Get tags for a specific product
    Route::get('/product', [TagController::class, 'productTags'])->name('product');
    
    // Assign tags to product (requires authentication)
    Route::post('/assign', [TagController::class, 'assignTags'])
        ->name('assign')
        ->middleware(['auth']);
    
    // Create new tag (requires authentication)
    Route::post('/', [TagController::class, 'store'])
        ->name('store')
        ->middleware(['auth']);
});
