<?php

use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

if (!function_exists('product_tags')) {
    /**
     * Get tags for a specific product
     * 
     * @param int $productId
     * @return \Illuminate\Support\Collection
     */
    function product_tags($productId)
    {
        return TagService::getProductTags($productId);
    }
}

if (!function_exists('get_tag')) {
    /**
     * Get a tag by ID or slug
     * 
     * @param mixed $identifier
     * @return \Paymenter\Extensions\Others\ProductTags\Models\Tag|null
     */
    function get_tag($identifier)
    {
        if (is_numeric($identifier)) {
            return Tag::find($identifier);
        }
        
        return Tag::where('slug', $identifier)->first();
    }
}

if (!function_exists('get_tags')) {
    /**
     * Get all active tags
     * 
     * @param bool $activeOnly
     * @return \Illuminate\Support\Collection
     */
    function get_tags($activeOnly = true)
    {
        return $activeOnly ? TagService::getActiveTags() : Tag::all();
    }
}

if (!function_exists('popular_tags')) {
    /**
     * Get popular tags ordered by usage count
     * 
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    function popular_tags($limit = 10)
    {
        return TagService::getPopularTags($limit);
    }
}

if (!function_exists('featured_tags')) {
    /**
     * Get featured tags
     * 
     * @return \Illuminate\Support\Collection
     */
    function featured_tags()
    {
        return TagService::getFeaturedTags();
    }
}

if (!function_exists('search_tags')) {
    /**
     * Search tags by name or description
     * 
     * @param string $search
     * @param bool $activeOnly
     * @return \Illuminate\Support\Collection
     */
    function search_tags($search, $activeOnly = true)
    {
        return TagService::searchTags($search, $activeOnly);
    }
}

if (!function_exists('tag_products')) {
    /**
     * Get products for a specific tag
     * 
     * @param mixed $tagId
     * @return \Illuminate\Support\Collection
     */
    function tag_products($tagId)
    {
        return TagService::getTagProducts($tagId);
    }
}

if (!function_exists('find_or_create_tag')) {
    /**
     * Find or create a tag by name
     * 
     * @param string $name
     * @param array $additionalData
     * @return \Paymenter\Extensions\Others\ProductTags\Models\Tag
     */
    function find_or_create_tag($name, $additionalData = [])
    {
        return TagService::findOrCreateTag($name, $additionalData);
    }
}

if (!function_exists('assign_tags_to_product')) {
    /**
     * Assign tags to a product
     * 
     * @param int $productId
     * @param array $tagIds
     * @return void
     */
    function assign_tags_to_product($productId, $tagIds)
    {
        TagService::assignTagsToProduct($productId, $tagIds);
    }
}

if (!function_exists('add_tag_to_product')) {
    /**
     * Add a single tag to a product
     * 
     * @param int $productId
     * @param int $tagId
     * @return bool
     */
    function add_tag_to_product($productId, $tagId)
    {
        return TagService::addTagToProduct($productId, $tagId);
    }
}

if (!function_exists('remove_tag_from_product')) {
    /**
     * Remove a tag from a product
     * 
     * @param int $productId
     * @param int $tagId
     * @return bool
     */
    function remove_tag_from_product($productId, $tagId)
    {
        return TagService::removeTagFromProduct($productId, $tagId);
    }
}

if (!function_exists('render_tag')) {
    /**
     * Render a tag as HTML
     * 
     * @param \Paymenter\Extensions\Others\ProductTags\Models\Tag $tag
     * @param bool $linkable
     * @param string $size
     * @return string
     */
    function render_tag($tag, $linkable = true, $size = 'sm')
    {
        $sizeClasses = [
            'xs' => 'px-2 py-1 text-xs',
            'sm' => 'px-2.5 py-1.5 text-sm',
            'md' => 'px-3 py-2 text-base',
            'lg' => 'px-4 py-2.5 text-lg',
        ];
        
        $classes = $sizeClasses[$size] ?? $sizeClasses['sm'];
        $classes .= ' inline-flex items-center font-medium rounded-full';
        
        $style = sprintf(
            'background-color: %s; color: %s;',
            $tag->display_color,
            $tag->text_color
        );
        
        $content = '<span class="' . $classes . '" style="' . $style . '">' . e($tag->name) . '</span>';
        
        if ($linkable && function_exists('route')) {
            try {
                $url = route('tags.show', $tag->slug);
                $content = '<a href="' . $url . '" class="hover:opacity-80 transition-opacity">' . $content . '</a>';
            } catch (Exception $e) {
                // Route doesn't exist, return without link
            }
        }
        
        return $content;
    }
}

if (!function_exists('render_product_tags')) {
    /**
     * Render all tags for a product as HTML
     * 
     * @param int $productId
     * @param bool $linkable
     * @param string $size
     * @param string $separator
     * @return string
     */
    function render_product_tags($productId, $linkable = true, $size = 'sm', $separator = ' ')
    {
        $tags = product_tags($productId);
        
        if ($tags->isEmpty()) {
            return '';
        }
        
        $renderedTags = $tags->map(function ($tag) use ($linkable, $size) {
            return render_tag($tag, $linkable, $size);
        });
        
        return $renderedTags->join($separator);
    }
}

if (!function_exists('has_tag')) {
    /**
     * Check if a product has a specific tag
     * 
     * @param int $productId
     * @param mixed $tagIdentifier (ID or slug)
     * @return bool
     */
    function has_tag($productId, $tagIdentifier)
    {
        $tags = product_tags($productId);
        
        if (is_numeric($tagIdentifier)) {
            return $tags->contains('id', $tagIdentifier);
        }
        
        return $tags->contains('slug', $tagIdentifier);
    }
}

if (!function_exists('get_related_tags')) {
    /**
     * Get related tags for a specific tag
     * 
     * @param int $tagId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    function get_related_tags($tagId, $limit = 5)
    {
        return TagService::getRelatedTags($tagId, $limit);
    }
}

if (!function_exists('tag_stats')) {
    /**
     * Get tag statistics
     * 
     * @return array
     */
    function tag_stats()
    {
        return TagService::getTagStats();
    }
}
