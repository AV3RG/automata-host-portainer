@extends('layouts.app')

@section('title', $tag->name . ' - Product Tags')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center px-6 py-3 rounded-2xl font-bold text-lg mb-4"
             style="background-color: {{ $tag->display_color }}; color: {{ $tag->text_color }};">
            <x-ri-price-tag-3-fill class="size-6 mr-2" />
            {{ $tag->name }}
        </div>
        
        @if($tag->description)
            <p class="text-lg text-color-muted max-w-2xl mx-auto mb-4">
                {{ $tag->description }}
            </p>
        @endif
        
        <div class="flex items-center justify-center gap-6 text-sm text-color-muted">
            <span>{{ $products->count() }} {{ Str::plural('product', $products->count()) }}</span>
            @if($tag->is_featured)
                <span class="flex items-center gap-1 text-yellow-600">
                    <x-ri-star-fill class="size-4" />
                    Featured
                </span>
            @endif
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('tags.index') }}" class="text-color-muted hover:text-primary transition-colors">
                    <x-ri-price-tag-3-line class="size-4 mr-1" />
                    All Tags
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <x-ri-arrow-right-s-line class="size-4 text-color-muted" />
                    <span class="ml-1 text-color-base font-medium">{{ $tag->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Related Tags -->
    @if($relatedTags->count() > 0)
        <div class="mb-12">
            <h2 class="text-xl font-bold text-color-base mb-4 flex items-center gap-2">
                <x-ri-links-line class="size-5" />
                Related Tags
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($relatedTags as $relatedTag)
                    <a href="{{ route('tags.show', $relatedTag->slug) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105"
                       style="background-color: {{ $relatedTag->display_color }}; color: {{ $relatedTag->text_color }};">
                        {{ $relatedTag->name }}
                        <span class="px-2 py-0.5 bg-black/20 rounded-full text-xs">
                            {{ $relatedTag->usage_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Products -->
    <div>
        <h2 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-2">
            <x-ri-cube-line class="size-6" />
            Products with {{ $tag->name }}
        </h2>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="group bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                        @if($product->image)
                            <div class="relative overflow-hidden">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-color-base mb-2 group-hover:text-primary transition-colors duration-300">
                                {{ $product->name }}
                            </h3>
                            
                            @if($product->description)
                                <p class="text-color-muted text-sm mb-4 line-clamp-2">
                                    {!! Str::limit(strip_tags($product->description), 100) !!}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg font-bold text-primary">
                                    {{ $product->price() }}
                                </span>
                                
                                @if($product->stock === 0)
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                        Out of Stock
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                        In Stock
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Product Tags -->
                            @php
                                $productTags = product_tags($product->id);
                            @endphp
                            @if($productTags->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($productTags->take(3) as $productTag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium"
                                              style="background-color: {{ $productTag->display_color }}20; color: {{ $productTag->display_color }};">
                                            {{ $productTag->name }}
                                        </span>
                                    @endforeach
                                    @if($productTags->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                            +{{ $productTags->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            <a href="{{ route('products.show', ['category' => $product->category, 'product' => $product->slug]) }}" 
                               class="group/btn w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-xl font-medium transition-all duration-300 hover:shadow-lg">
                                View Product
                                <x-ri-arrow-right-line class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <x-ri-cube-line class="size-16 text-color-muted mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-color-base mb-2">
                    No products found
                </h3>
                <p class="text-color-muted">
                    There are currently no products with the {{ $tag->name }} tag.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
