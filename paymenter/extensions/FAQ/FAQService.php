<?php

namespace Paymenter\Extensions\Others\FAQ;

use Paymenter\Extensions\Others\FAQ\Models\FAQQuestion;

class FAQService
{
    /**
     * Get all FAQ questions for a specific category
     */
    public static function getQuestionsForCategory($categoryId, $activeOnly = true)
    {
        $query = FAQQuestion::byCategory($categoryId);
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->orderBy('sort_order')->get();
    }

    /**
     * Get featured FAQ questions for a specific category
     */
    public static function getFeaturedQuestionsForCategory($categoryId, $activeOnly = true)
    {
        $query = FAQQuestion::byCategory($categoryId)->featured();
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->orderBy('sort_order')->get();
    }

    /**
     * Get all active FAQ questions across all categories
     */
    public static function getAllActiveQuestions()
    {
        return FAQQuestion::active()->orderBy('product_category_id')->orderBy('sort_order')->get();
    }

    /**
     * Get all featured FAQ questions across all categories
     */
    public static function getAllFeaturedQuestions()
    {
        return FAQQuestion::active()->featured()->orderBy('product_category_id')->orderBy('sort_order')->get();
    }

    /**
     * Get FAQ questions grouped by category
     */
    public static function getQuestionsGroupedByCategory($activeOnly = true)
    {
        $query = FAQQuestion::query();
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->with('productCategory')
                    ->orderBy('product_category_id')
                    ->orderBy('sort_order')
                    ->get()
                    ->groupBy('product_category_id');
    }
}
