# Product Tags Extension for Paymenter

A comprehensive product tagging system for Paymenter that allows you to create, manage, and use tags across your products for better organization and discoverability.

## Features

- **Comprehensive Tag Management**: Create, edit, and delete tags with colors, descriptions, and status controls
- **Product Integration**: Seamlessly assign tags to products and manage tag relationships
- **Admin Interface**: Full Filament admin panel integration with CRUD operations
- **Helper Functions**: Easy-to-use PHP helper functions for working with tags in your code
- **Search & Discovery**: Tag-based product browsing and search functionality
- **Popular & Featured Tags**: Highlight important tags and track usage statistics
- **API Endpoints**: RESTful API for AJAX operations and external integrations
- **Customizable Display**: Configurable tag colors, sizes, and display options

## Installation

1. Place the extension in the `extensions/Others/ProductTags/` directory
2. Enable the extension through the Paymenter admin panel
3. The extension will automatically run migrations to create necessary database tables

## Database Structure

### Product Tags Table (`ext_product_tags`)
- `id` - Primary key
- `name` - Tag name (max 100 characters)
- `slug` - URL-friendly slug (auto-generated)
- `color` - Hex color code for display
- `description` - Optional tag description
- `is_active` - Active status
- `is_featured` - Featured tag flag
- `usage_count` - Number of products using this tag
- `timestamps` - Created/updated timestamps

### Product Tag Assignments Table (`ext_product_tag_assignments`)
- `id` - Primary key
- `product_id` - Foreign key to products table
- `tag_id` - Foreign key to ext_product_tags table
- `timestamps` - Created/updated timestamps

## Usage

### Helper Functions

The extension provides numerous helper functions for easy integration:

```php
// Get all tags for a product
$tags = product_tags($productId);

// Get all active tags
$tags = get_tags();

// Get popular tags
$popularTags = popular_tags(10);

// Get featured tags
$featuredTags = featured_tags();

// Search tags
$searchResults = search_tags('hosting');

// Find or create a tag
$tag = find_or_create_tag('Web Hosting');

// Assign tags to a product
assign_tags_to_product($productId, [1, 2, 3]);

// Add a single tag to a product
add_tag_to_product($productId, $tagId);

// Remove a tag from a product
remove_tag_from_product($productId, $tagId);

// Check if product has a tag
$hasTag = has_tag($productId, 'hosting');

// Render tags as HTML
echo render_product_tags($productId);
echo render_tag($tag);
```

### Using the Tag Service

For more complex operations, use the `TagService` class:

```php
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

// Create a new tag
$tag = TagService::createTag([
    'name' => 'Premium Hosting',
    'color' => '#ff6b6b',
    'description' => 'High-performance hosting solutions',
    'is_featured' => true
]);

// Get tag statistics
$stats = TagService::getTagStats();

// Get related tags
$relatedTags = TagService::getRelatedTags($tagId, 5);

// Bulk import tags
$tags = TagService::bulkImportTags(['Web Hosting', 'VPS', 'Dedicated Servers']);

// Clean up unused tags
$result = TagService::cleanupUnusedTags();
```

### Product Model Integration

The extension automatically adds methods to the Product model:

```php
// Get product tags
$product = Product::find(1);
$tags = $product->tags;

// Check if product has a specific tag
$hasHostingTag = $product->hasTag('hosting');

// Add a tag to the product
$product->addTag($tagId);

// Remove a tag from the product
$product->removeTag($tagId);

// Sync tags (replace all existing tags)
$product->syncTags([1, 2, 3]);
```

### Displaying Tags in Templates

In your Blade templates, you can easily display tags:

```blade
{{-- Display all tags for a product --}}
@php $productTags = product_tags($product->id) @endphp
@if($productTags->count() > 0)
    <div class="product-tags">
        @foreach($productTags as $tag)
            {!! render_tag($tag) !!}
        @endforeach
    </div>
@endif

{{-- Or use the helper function --}}
<div class="product-tags">
    {!! render_product_tags($product->id) !!}
</div>

{{-- Check if product has specific tags --}}
@if(has_tag($product->id, 'featured'))
    <span class="featured-badge">Featured Product!</span>
@endif
```

## Admin Panel

The extension adds a comprehensive admin interface accessible at `/admin/product-tags` with:

- **Tag Management**: Create, edit, and delete tags
- **Bulk Operations**: Activate/deactivate, feature/unfeature multiple tags
- **Statistics**: View usage statistics and tag performance
- **Cleanup Tools**: Remove unused tags
- **Search & Filtering**: Find tags quickly

## API Endpoints

The extension provides RESTful API endpoints:

- `GET /api/tags` - Get all tags (with optional search)
- `GET /api/tags/popular` - Get popular tags
- `GET /api/tags/product?product_id=1` - Get tags for a specific product
- `POST /api/tags` - Create a new tag (requires authentication)
- `POST /api/tags/assign` - Assign tags to a product (requires authentication)

## Configuration

You can customize the extension behavior by publishing and modifying the configuration file:

```bash
php artisan vendor:publish --tag=product-tags-config
```

Configuration options include:
- Default tag colors and settings
- Display preferences
- API rate limiting
- Cache settings
- Admin panel customization

## Routes

The extension provides public routes for tag browsing:

- `/tags` - Browse all tags
- `/tags/{slug}` - View products for a specific tag

## Customization

### Custom Tag Colors

Tags support custom colors with automatic text color calculation for optimal contrast:

```php
$tag = TagService::createTag([
    'name' => 'Premium',
    'color' => '#ff6b6b', // Custom color
]);

// The extension automatically calculates whether to use light or dark text
echo $tag->text_color; // Returns '#ffffff' or '#000000'
```

### Extending Functionality

You can extend the extension by:

1. Creating custom tag types
2. Adding additional tag metadata
3. Implementing custom display components
4. Creating specialized search functionality

## Performance

The extension includes several performance optimizations:

- **Usage Count Tracking**: Automatic tracking of tag usage for efficient queries
- **Eager Loading**: Optimized database queries to prevent N+1 problems
- **Caching**: Optional caching for frequently accessed data
- **Indexes**: Database indexes on commonly queried fields

## Support

For support, feature requests, or bug reports, please refer to the Paymenter community forums or create an issue in the project repository.
