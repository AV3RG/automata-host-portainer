<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Others\Pages\Livewire\Page;
use Paymenter\Extensions\Others\Pages\Models\Page as ModelsPage;


// Register custom routes for pages
Route::middleware('web')->group(function () {
    // Get all pages with custom routes
    $pages = ModelsPage::where('visible', true)
        ->whereNotNull('custom_route')
        ->where('custom_route', '!=', '')
        ->get();
    
    foreach ($pages as $page) {
        Route::get($page->custom_route, Page::class)
            ->name('extensions.others.pages.custom.' . $page->id)
            ->defaults('fallbackPlaceholder', $page->slug);
    }
});

// Fallback route for pages without custom routes (using slug)
Route::fallback(Page::class)->middleware('web')->name('extensions.others.pages');


