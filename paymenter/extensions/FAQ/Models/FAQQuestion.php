<?php

namespace Paymenter\Extensions\Others\FAQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQQuestion extends Model
{
    protected $table = 'ext_faq_questions';
    protected $fillable = [
        'product_category_id',
        'question',
        'answer',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product category that owns this question
     */
    public function productCategory(): BelongsTo
    {
        $categoryModel = config('paymenter.models.category', 'App\Models\Category');
        return $this->belongsTo($categoryModel, 'product_category_id');
    }

    /**
     * Get the category name for display purposes
     */
    public function getCategoryNameAttribute()
    {
        return $this->productCategory->name ?? 'Unknown Category';
    }

    /**
     * Scope to get questions by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('product_category_id', $categoryId);
    }

    /**
     * Scope to get active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured questions
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}

