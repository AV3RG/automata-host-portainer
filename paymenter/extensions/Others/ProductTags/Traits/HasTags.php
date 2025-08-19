<?php

namespace Paymenter\Extensions\Others\ProductTags\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

trait HasTags
{
    /**
     * Get the tags for the product
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'ext_product_tag_assignments',
            'product_id',
            'tag_id'
        )->withTimestamps();
    }

    /**
     * Check if the product has a specific tag
     */
    public function hasTag($tagIdentifier): bool
    {
        if (is_numeric($tagIdentifier)) {
            return TagService::getProductTags($this->id)->contains('id', $tagIdentifier);
        }
        
        return TagService::getProductTags($this->id)->contains('slug', $tagIdentifier);
    }

    /**
     * Add a tag to the product
     */
    public function addTag($tagId): bool
    {
        return TagService::addTagToProduct($this->id, $tagId);
    }

    /**
     * Remove a tag from the product
     */
    public function removeTag($tagId): bool
    {
        return TagService::removeTagFromProduct($this->id, $tagId);
    }

    /**
     * Sync tags for the product (replace all existing tags)
     */
    public function syncTags(array $tagIds): void
    {
        TagService::assignTagsToProduct($this->id, $tagIds);
    }

    /**
     * Get all tags for the product
     */
    public function getTagsAttribute()
    {
        return TagService::getProductTags($this->id);
    }
}
