<?php

namespace Paymenter\Extensions\Others\ProductTags\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of tags
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        if ($search) {
            $tags = TagService::searchTags($search);
        } else {
            $tags = TagService::getActiveTags();
        }
        
        $featuredTags = TagService::getFeaturedTags();
        $popularTags = TagService::getPopularTags(10);
        $stats = TagService::getTagStats();
        
        return view('product-tags::tags.index', compact('tags', 'featuredTags', 'popularTags', 'stats', 'search'));
    }

    /**
     * Display the specified tag and its products
     */
    public function show(Tag $tag): View
    {
        $products = TagService::getTagProducts($tag->id);
        $relatedTags = TagService::getRelatedTags($tag->id);
        
        return view('product-tags::tags.show', compact('tag', 'products', 'relatedTags'));
    }

    /**
     * Get tags as JSON (for AJAX requests)
     */
    public function api(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $limit = $request->get('limit', 10);
        
        if ($search) {
            $tags = TagService::searchTags($search)->take($limit);
        } else {
            $tags = TagService::getActiveTags()->take($limit);
        }
        
        return response()->json([
            'tags' => $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->display_color,
                    'usage_count' => $tag->usage_count,
                ];
            }),
        ]);
    }

    /**
     * Get popular tags as JSON
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $tags = TagService::getPopularTags($limit);
        
        return response()->json([
            'tags' => $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->display_color,
                    'usage_count' => $tag->usage_count,
                ];
            }),
        ]);
    }

    /**
     * Get tags for a specific product
     */
    public function productTags(Request $request): JsonResponse
    {
        $productId = $request->get('product_id');
        
        if (!$productId) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }
        
        $tags = TagService::getProductTags($productId);
        
        return response()->json([
            'tags' => $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->display_color,
                    'usage_count' => $tag->usage_count,
                ];
            }),
        ]);
    }

    /**
     * Assign tags to a product (AJAX)
     */
    public function assignTags(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'integer|exists:ext_product_tags,id',
        ]);

        TagService::assignTagsToProduct($request->product_id, $request->tag_ids);

        return response()->json([
            'success' => true,
            'message' => 'Tags assigned successfully',
        ]);
    }

    /**
     * Create a new tag via AJAX
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:ext_product_tags,name',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:500',
        ]);

        $tag = TagService::createTag($request->only(['name', 'color', 'description']));

        return response()->json([
            'success' => true,
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'color' => $tag->display_color,
                'usage_count' => $tag->usage_count,
            ],
            'message' => 'Tag created successfully',
        ]);
    }
}
