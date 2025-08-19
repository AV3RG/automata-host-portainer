# Product Tags Extension - Installation Guide

## Quick Start

The Product Tags extension works out of the box using helper functions and the TagService class. No modifications to your Product model are required for basic functionality.

## Basic Usage (No Model Changes Required)

You can start using tags immediately with the helper functions:

```php
// Get tags for a product
$tags = product_tags($productId);

// Check if product has a tag
$hasHostingTag = has_tag($productId, 'hosting');

// Add tags to a product
assign_tags_to_product($productId, [1, 2, 3]);

// Render tags in templates
echo render_product_tags($productId);
```

## Enhanced Integration (Optional)

For enhanced functionality and cleaner syntax, you can optionally add the `HasTags` trait to your Product model:

### Step 1: Modify Your Product Model

Add the trait to your `app/Models/Product.php` file:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Paymenter\Extensions\Others\ProductTags\Traits\HasTags;

class Product extends Model
{
    use HasTags;
    
    // ... rest of your model code
}
```

### Step 2: Enhanced Usage

With the trait added, you can use cleaner syntax:

```php
$product = Product::find(1);

// Get product tags
$tags = $product->tags;

// Check if product has a tag
$hasTag = $product->hasTag('hosting');

// Add a tag
$product->addTag($tagId);

// Remove a tag
$product->removeTag($tagId);

// Replace all tags
$product->syncTags([1, 2, 3]);
```

## Available Methods

### Helper Functions (Always Available)

- `product_tags($productId)` - Get all tags for a product
- `has_tag($productId, $tagIdentifier)` - Check if product has a tag
- `assign_tags_to_product($productId, $tagIds)` - Assign tags to product
- `add_tag_to_product($productId, $tagId)` - Add single tag to product
- `remove_tag_from_product($productId, $tagId)` - Remove tag from product
- `render_product_tags($productId)` - Render tags as HTML
- `get_tags()` - Get all active tags
- `popular_tags($limit)` - Get popular tags
- `featured_tags()` - Get featured tags
- `search_tags($query)` - Search tags

### TagService Methods

```php
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

// Create tags
$tag = TagService::createTag(['name' => 'Web Hosting', 'color' => '#ff6b6b']);

// Get statistics
$stats = TagService::getTagStats();

// Get related tags
$related = TagService::getRelatedTags($tagId, 5);

// Bulk import
$tags = TagService::bulkImportTags(['VPS', 'Dedicated', 'Cloud']);

// Cleanup unused tags
$result = TagService::cleanupUnusedTags();
```

### Model Methods (With HasTags Trait)

```php
// Relationship
$product->tags; // Collection of tags

// Helper methods
$product->hasTag($identifier);
$product->addTag($tagId);
$product->removeTag($tagId);
$product->syncTags($tagIds);
```

## Template Usage

### Blade Templates

```blade
{{-- Display all tags for a product --}}
<div class="product-tags">
    {!! render_product_tags($product->id) !!}
</div>

{{-- Custom tag display --}}
@php $productTags = product_tags($product->id) @endphp
@if($productTags->count() > 0)
    <div class="tags">
        @foreach($productTags as $tag)
            <span class="tag" style="background-color: {{ $tag->display_color }}; color: {{ $tag->text_color }};">
                {{ $tag->name }}
            </span>
        @endforeach
    </div>
@endif

{{-- Conditional content based on tags --}}
@if(has_tag($product->id, 'featured'))
    <span class="featured-badge">⭐ Featured Product!</span>
@endif
```

## Admin Panel

Once installed, you can manage tags through the admin panel at:
- **URL**: `/admin/product-tags`
- **Navigation**: Products → Product Tags

Features include:
- Create, edit, delete tags
- Bulk operations
- Usage statistics
- Cleanup unused tags
- Color customization

## API Endpoints

The extension provides API endpoints for AJAX operations:

- `GET /api/tags` - Get tags (with search)
- `GET /api/tags/popular` - Get popular tags
- `GET /api/tags/product?product_id=1` - Get product tags
- `POST /api/tags` - Create tag (auth required)
- `POST /api/tags/assign` - Assign tags (auth required)

## Configuration

Access extension settings in the admin panel under Extensions → Product Tags:

- Maximum tags per product
- Display preferences
- Auto-suggestion settings
- Popular tags limit

## Troubleshooting

### "Call to undefined method" Errors

If you get errors about undefined methods on the Product model, make sure you're using the helper functions instead:

```php
// Instead of: $product->tags
$tags = product_tags($product->id);

// Instead of: $product->hasTag('hosting')
$hasTag = has_tag($product->id, 'hosting');
```

### Database Issues

If you encounter migration issues, you can manually run:

```bash
php artisan migrate --path=extensions/Others/ProductTags/database/migrations --force
```

## Support

The extension is designed to work independently of your existing Product model structure. All functionality is available through helper functions and the TagService class, making it safe to use without modifying core application files.
