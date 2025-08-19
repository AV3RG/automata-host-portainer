<?php

namespace Paymenter\Extensions\Others\ProductTags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTagAssignment extends Model
{
    protected $table = 'ext_product_tag_assignments';
    
    protected $fillable = [
        'product_id',
        'tag_id',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'tag_id' => 'integer',
    ];

    /**
     * Get the product that owns this assignment
     */
    public function product(): BelongsTo
    {
        $productModel = config('paymenter.models.product', 'App\Models\Product');
        return $this->belongsTo($productModel);
    }

    /**
     * Get the tag that owns this assignment
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($assignment) {
            // Increment tag usage count
            $assignment->tag()->increment('usage_count');
        });

        static::deleted(function ($assignment) {
            // Decrement tag usage count
            $assignment->tag()->decrement('usage_count');
        });
    }
}
