<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product Tags Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Product Tags
    | extension. You can modify these settings to customize the behavior
    | of the tagging system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Tag Settings
    |--------------------------------------------------------------------------
    |
    | These settings define the default values for new tags.
    |
    */
    'defaults' => [
        'color' => '#3b82f6',
        'is_active' => true,
        'is_featured' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tag Limits
    |--------------------------------------------------------------------------
    |
    | Configure limits for the tagging system.
    |
    */
    'limits' => [
        'max_tags_per_product' => 0, // 0 = unlimited
        'max_tag_name_length' => 100,
        'max_tag_description_length' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Display Settings
    |--------------------------------------------------------------------------
    |
    | Control how tags are displayed throughout the application.
    |
    */
    'display' => [
        'show_usage_count' => true,
        'show_on_product_list' => true,
        'show_on_product_detail' => true,
        'auto_suggest_tags' => true,
        'popular_tags_limit' => 10,
        'related_tags_limit' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Settings
    |--------------------------------------------------------------------------
    |
    | Configure SEO-related settings for tag pages.
    |
    */
    'seo' => [
        'generate_meta_descriptions' => true,
        'meta_description_template' => 'Browse products tagged with :tag_name. Find the perfect :tag_name products for your needs.',
        'canonical_urls' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching for improved performance.
    |
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour in seconds
        'prefix' => 'product_tags',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Settings
    |--------------------------------------------------------------------------
    |
    | Configure admin panel settings.
    |
    */
    'admin' => [
        'show_in_navigation' => true,
        'navigation_group' => 'Products',
        'navigation_sort' => 3,
        'enable_bulk_actions' => true,
        'enable_cleanup_tools' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    |
    | Configure API endpoints and behavior.
    |
    */
    'api' => [
        'enabled' => true,
        'rate_limit' => '60,1', // 60 requests per minute
        'require_auth_for_create' => true,
        'require_auth_for_assign' => true,
    ],
];
