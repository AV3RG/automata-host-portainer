<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymenter Model Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the model class names for Paymenter's core models.
    | The FAQ extension uses these to establish relationships with existing
    | product categories.
    |
    */

    'models' => [
        'category' => env('PAYMENTER_CATEGORY_MODEL', 'App\Models\Category'),
        'product' => env('PAYMENTER_PRODUCT_MODEL', 'App\Models\Product'),
    ],
];
