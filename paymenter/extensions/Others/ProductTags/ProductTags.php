<?php

namespace Paymenter\Extensions\Others\ProductTags;

use App\Classes\Extension\Extension;

class ProductTags extends Extension
{
    /**
     * Get extension metadata
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'Product Tags',
            'version' => '1.0.0',
            'description' => 'Comprehensive product tagging system for Paymenter that allows you to create, manage, and use tags across your products.',
            'author' => 'Paymenter Community',
            'website' => '',
        ];
    }

    /**
     * Get extension configuration
     */
    public function getConfig($values = [])
    {
        try {
            return [
                [
                    'name' => 'Notice',
                    'type' => 'placeholder',
                    'label' => new \Illuminate\Support\HtmlString('You can use this extension to create and manage product tags. Go to <a class="text-primary-600" href="' . \Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource::getUrl() . '">Product Tags</a> to get started.'),
                ],
                [
                    'name' => 'max_tags_per_product',
                    'type' => 'number',
                    'label' => 'Maximum Tags Per Product',
                    'description' => 'Maximum number of tags that can be assigned to a single product (0 = unlimited)',
                    'default' => 0,
                ],
                [
                    'name' => 'show_tags_on_product_list',
                    'type' => 'checkbox',
                    'label' => 'Show Tags on Product List',
                    'description' => 'Display tags on product listing pages',
                    'default' => true,
                ],
                [
                    'name' => 'show_tags_on_product_detail',
                    'type' => 'checkbox',
                    'label' => 'Show Tags on Product Detail',
                    'description' => 'Display tags on individual product pages',
                    'default' => true,
                ],
                [
                    'name' => 'auto_suggest_tags',
                    'type' => 'checkbox',
                    'label' => 'Auto-suggest Tags',
                    'description' => 'Enable auto-suggestion of existing tags when creating/editing products',
                    'default' => true,
                ],
                [
                    'name' => 'popular_tags_limit',
                    'type' => 'number',
                    'label' => 'Popular Tags Limit',
                    'description' => 'Number of popular tags to display on the frontend',
                    'default' => 10,
                ],
            ];
        } catch (\Exception $e) {
            return [
                [
                    'name' => 'Notice',
                    'type' => 'placeholder',
                    'label' => new \Illuminate\Support\HtmlString('You can use this extension to create and manage product tags. You\'ll need to enable this extension above to get started.'),
                ],
            ];
        }
    }

    /**
     * Get extension admin resources
     */
    public function getAdminResources(): array
    {
        return [
            \Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource::class,
            \Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource::class,
        ];
    }

    /**
     * Called when extension is enabled
     */
    public function enabled()
    {
        // Run migrations
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--path' => 'extensions/Others/ProductTags/database/migrations', '--force' => true]);
    }

    /**
     * Boot extension
     */
    public function boot()
    {
        // Register service provider
        if (class_exists(\Paymenter\Extensions\Others\ProductTags\Providers\ProductTagsServiceProvider::class)) {
            app()->register(\Paymenter\Extensions\Others\ProductTags\Providers\ProductTagsServiceProvider::class);
        }
        
        // Load views
        \Illuminate\Support\Facades\View::addNamespace('product-tags', __DIR__ . '/resources/views');
        
        // Load routes
        require __DIR__ . '/routes/web.php';
        
        // Register helper functions
        if (!function_exists('product_tags')) {
            require_once __DIR__ . '/helpers/functions.php';
        }
    }
}
