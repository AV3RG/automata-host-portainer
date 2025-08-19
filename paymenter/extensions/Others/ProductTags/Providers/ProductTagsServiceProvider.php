<?php

namespace Paymenter\Extensions\Others\ProductTags\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProductTagsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/product-tags.php',
            'product-tags'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Eloquent model relationships
        $this->registerModelRelationships();
        
        // Register Blade components
        $this->registerBladeComponents();
        
        // Publish configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/product-tags.php' => config_path('product-tags.php'),
            ], 'product-tags-config');
        }
    }

    /**
     * Register Eloquent model relationships
     */
    protected function registerModelRelationships(): void
    {
        // Note: The Product model needs to manually use the HasTags trait
        // Add this to your Product model:
        // use Paymenter\Extensions\Others\ProductTags\Traits\HasTags;
        // class Product extends Model {
        //     use HasTags;
        // }
        
        // For now, we rely on the TagService static methods which work without model relationships
    }

    /**
     * Register Blade components
     */
    protected function registerBladeComponents(): void
    {
        // Register tag-related Blade components if needed
        // This can be expanded later for custom tag display components
    }
}
