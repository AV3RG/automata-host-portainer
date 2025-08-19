<?php

namespace Paymenter\Extensions\Others\ProductTags\Services;

use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Models\ProductTagAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TagService
{
    /**
     * Get all active tags
     */
    public static function getActiveTags(): Collection
    {
        return Tag::active()->orderBy('name')->get();
    }

    /**
     * Get featured tags
     */
    public static function getFeaturedTags(): Collection
    {
        return Tag::featured()->active()->orderBy('name')->get();
    }

    /**
     * Get popular tags (ordered by usage count)
     */
    public static function getPopularTags(int $limit = 10): Collection
    {
        return Tag::active()->popular()->limit($limit)->get();
    }

    /**
     * Get tags for a specific product
     */
    public static function getProductTags($productId): Collection
    {
        return Tag::whereIn('id', function ($query) use ($productId) {
            $query->select('tag_id')
                  ->from('ext_product_tag_assignments')
                  ->where('product_id', $productId);
        })->active()->orderBy('name')->get();
    }

    /**
     * Get products for a specific tag
     */
    public static function getTagProducts($tagId): Collection
    {
        $productModel = config('paymenter.models.product', 'App\Models\Product');
        $productIds = \DB::table('ext_product_tag_assignments')
            ->where('tag_id', $tagId)
            ->pluck('product_id');
            
        return $productModel::whereIn('id', $productIds)->get();
    }

    /**
     * Search tags by name or description
     */
    public static function searchTags(string $search, bool $activeOnly = true): Collection
    {
        $query = Tag::search($search);
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->orderBy('name')->get();
    }

    /**
     * Create a new tag
     */
    public static function createTag(array $data): Tag
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Tag::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        return Tag::create($data);
    }

    /**
     * Update an existing tag
     */
    public static function updateTag(Tag $tag, array $data): Tag
    {
        // Generate slug if name changed and slug is empty
        if (isset($data['name']) && $data['name'] !== $tag->name && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure slug is unique (excluding current tag)
        if (isset($data['slug'])) {
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Tag::where('slug', $data['slug'])->where('id', '!=', $tag->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $tag->update($data);
        return $tag->fresh();
    }

    /**
     * Delete a tag and remove all assignments
     */
    public static function deleteTag(Tag $tag): bool
    {
        // Remove all product assignments
        ProductTagAssignment::where('tag_id', $tag->id)->delete();
        
        return $tag->delete();
    }

    /**
     * Assign tags to a product
     */
    public static function assignTagsToProduct($productId, array $tagIds): void
    {
        // Remove existing assignments
        ProductTagAssignment::where('product_id', $productId)->delete();

        // Create new assignments
        foreach ($tagIds as $tagId) {
            ProductTagAssignment::create([
                'product_id' => $productId,
                'tag_id' => $tagId,
            ]);
        }
    }

    /**
     * Add a single tag to a product
     */
    public static function addTagToProduct($productId, $tagId): bool
    {
        // Check if assignment already exists
        if (ProductTagAssignment::where('product_id', $productId)
            ->where('tag_id', $tagId)->exists()) {
            return false;
        }

        ProductTagAssignment::create([
            'product_id' => $productId,
            'tag_id' => $tagId,
        ]);

        return true;
    }

    /**
     * Remove a tag from a product
     */
    public static function removeTagFromProduct($productId, $tagId): bool
    {
        return ProductTagAssignment::where('product_id', $productId)
            ->where('tag_id', $tagId)
            ->delete() > 0;
    }

    /**
     * Find or create tag by name
     */
    public static function findOrCreateTag(string $name, array $additionalData = []): Tag
    {
        $slug = Str::slug($name);
        
        $tag = Tag::where('slug', $slug)->first();
        
        if (!$tag) {
            $data = array_merge([
                'name' => $name,
                'slug' => $slug,
            ], $additionalData);
            
            $tag = self::createTag($data);
        }
        
        return $tag;
    }

    /**
     * Get tag statistics
     */
    public static function getTagStats(): array
    {
        return [
            'total_tags' => Tag::count(),
            'active_tags' => Tag::active()->count(),
            'featured_tags' => Tag::featured()->count(),
            'most_used_tag' => Tag::orderBy('usage_count', 'desc')->first(),
            'unused_tags' => Tag::where('usage_count', 0)->count(),
        ];
    }

    /**
     * Get related tags (tags used with the same products)
     */
    public static function getRelatedTags($tagId, int $limit = 5): Collection
    {
        // Get products that have this tag
        $productIds = \DB::table('ext_product_tag_assignments')
            ->where('tag_id', $tagId)
            ->pluck('product_id');
            
        // Get other tags used by these products
        $relatedTagIds = \DB::table('ext_product_tag_assignments')
            ->whereIn('product_id', $productIds)
            ->where('tag_id', '!=', $tagId)
            ->pluck('tag_id')
            ->unique();
            
        return Tag::whereIn('id', $relatedTagIds)
            ->active()
            ->popular()
            ->limit($limit)
            ->get();
    }

    /**
     * Bulk import tags from array
     */
    public static function bulkImportTags(array $tagNames, array $defaultData = []): Collection
    {
        $tags = collect();
        
        foreach ($tagNames as $name) {
            $tag = self::findOrCreateTag(trim($name), $defaultData);
            $tags->push($tag);
        }
        
        return $tags;
    }

    /**
     * Clean up unused tags
     */
    public static function cleanupUnusedTags(bool $dryRun = false): array
    {
        $unusedTags = Tag::where('usage_count', 0)->get();
        
        if (!$dryRun) {
            foreach ($unusedTags as $tag) {
                $tag->delete();
            }
        }
        
        return [
            'count' => $unusedTags->count(),
            'tags' => $unusedTags->pluck('name')->toArray(),
        ];
    }
}
