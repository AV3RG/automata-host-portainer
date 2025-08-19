@extends('layouts.app')

@section('title', 'Product Tags')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 text-primary rounded-full mb-4">
            <x-ri-price-tag-3-line class="size-8" />
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-color-base mb-4">
            Product Tags
        </h1>
        <p class="text-lg text-color-muted max-w-2xl mx-auto">
            Discover products by browsing our comprehensive tag system.
        </p>
    </div>

    <!-- Search -->
    <div class="max-w-md mx-auto mb-8">
        <form method="GET" action="{{ route('tags.index') }}" class="relative">
            <input type="text" 
                   name="search" 
                   value="{{ $search }}"
                   placeholder="Search tags..." 
                   class="w-full px-4 py-3 pl-12 bg-background-secondary border border-neutral/50 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <x-ri-search-line class="size-5 text-color-muted" />
            </div>
            @if($search)
                <a href="{{ route('tags.index') }}" 
                   class="absolute inset-y-0 right-0 pr-4 flex items-center text-color-muted hover:text-color-base">
                    <x-ri-close-line class="size-5" />
                </a>
            @endif
        </form>
    </div>

    <!-- Stats -->
    @if($stats)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl p-6 text-center">
                <div class="text-2xl font-bold text-primary">{{ $stats['total_tags'] }}</div>
                <div class="text-sm text-color-muted">Total Tags</div>
            </div>
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl p-6 text-center">
                <div class="text-2xl font-bold text-primary">{{ $stats['active_tags'] }}</div>
                <div class="text-sm text-color-muted">Active Tags</div>
            </div>
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl p-6 text-center">
                <div class="text-2xl font-bold text-primary">{{ $stats['featured_tags'] }}</div>
                <div class="text-sm text-color-muted">Featured</div>
            </div>
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl p-6 text-center">
                <div class="text-2xl font-bold text-primary">{{ $stats['unused_tags'] }}</div>
                <div class="text-sm text-color-muted">Unused</div>
            </div>
        </div>
    @endif

    <!-- Featured Tags -->
    @if($featuredTags->count() > 0 && !$search)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-2">
                <x-ri-star-fill class="size-6 text-yellow-500" />
                Featured Tags
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($featuredTags as $tag)
                    <a href="{{ route('tags.show', $tag->slug) }}" 
                       class="group inline-flex items-center gap-2 px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                       style="background-color: {{ $tag->display_color }}; color: {{ $tag->text_color }};">
                        <x-ri-star-fill class="size-4" />
                        {{ $tag->name }}
                        <span class="ml-2 px-2 py-1 bg-black/20 rounded-full text-xs">
                            {{ $tag->usage_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Popular Tags -->
    @if($popularTags->count() > 0 && !$search)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-2">
                <x-ri-fire-fill class="size-6 text-red-500" />
                Popular Tags
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($popularTags as $tag)
                    <a href="{{ route('tags.show', $tag->slug) }}" 
                       class="group inline-flex items-center gap-2 px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                       style="background-color: {{ $tag->display_color }}; color: {{ $tag->text_color }};">
                        {{ $tag->name }}
                        <span class="ml-2 px-2 py-1 bg-black/20 rounded-full text-xs">
                            {{ $tag->usage_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- All Tags -->
    <div>
        <h2 class="text-2xl font-bold text-color-base mb-6">
            @if($search)
                Search Results for "{{ $search }}"
            @else
                All Tags
            @endif
        </h2>

        @if($tags->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($tags as $tag)
                    <a href="{{ route('tags.show', $tag->slug) }}" 
                       class="group bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl p-6 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium"
                                 style="background-color: {{ $tag->display_color }}; color: {{ $tag->text_color }};">
                                {{ $tag->name }}
                            </div>
                            @if($tag->is_featured)
                                <x-ri-star-fill class="size-4 text-yellow-500" />
                            @endif
                        </div>
                        
                        @if($tag->description)
                            <p class="text-color-muted text-sm mb-4 line-clamp-2">
                                {{ $tag->description }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm text-color-muted">
                            <span>{{ $tag->usage_count }} {{ Str::plural('product', $tag->usage_count) }}</span>
                            <x-ri-arrow-right-line class="size-4 transform transition-transform duration-300 group-hover:translate-x-1" />
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <x-ri-price-tag-3-line class="size-16 text-color-muted mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-color-base mb-2">
                    @if($search)
                        No tags found for "{{ $search }}"
                    @else
                        No tags available
                    @endif
                </h3>
                <p class="text-color-muted">
                    @if($search)
                        Try a different search term or browse all tags.
                    @else
                        Tags will appear here once they are created.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
