<?php

namespace Paymenter\Extensions\Others\ProductTags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $table = 'ext_product_tags';
    
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'is_active',
        'is_featured',
        'usage_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get the products that have this tag
     */
    public function products(): BelongsToMany
    {
        $productModel = config('paymenter.models.product', 'App\Models\Product');
        return $this->belongsToMany($productModel, 'ext_product_tag_assignments', 'tag_id', 'product_id')
                    ->withTimestamps();
    }

    /**
     * Get products for this tag using TagService (fallback method)
     */
    public function getProductsAttribute()
    {
        return \Paymenter\Extensions\Others\ProductTags\Services\TagService::getTagProducts($this->id);
    }

    /**
     * Scope to get active tags
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured tags
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by usage count
     */
    public function scopePopular($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    /**
     * Scope to search tags by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    /**
     * Get the tag's display color with fallback
     */
    public function getDisplayColorAttribute()
    {
        return $this->color ?: '#3b82f6';
    }

    /**
     * Get the tag's text color (light or dark) based on background color
     */
    public function getTextColorAttribute()
    {
        $color = str_replace('#', '', $this->display_color);
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement usage count
     */
    public function decrementUsage()
    {
        $this->decrement('usage_count');
    }

    /**
     * Get route key name for model binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the tag's URL
     */
    public function getUrlAttribute()
    {
        return route('tags.show', $this->slug);
    }
}
