<div class="grid md:grid-cols-4 gap-4">
    <!-- Left Sidebar -->
    <div class="flex flex-col gap-4">
<style>
:root {
    --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
    --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
    --accent: {{ theme('accent', 'hsl(280, 100%, 70%)') }};
    --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
    --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
    --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
    --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
    --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
    --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
    --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
    --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
    --dark-accent: {{ theme('dark-accent', 'hsl(280, 100%, 70%)') }};
    --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
    --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
    --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
    --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
    --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
    --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
}
</style>

<!-- Category Header -->
<div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] rounded-lg border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-4 shadow-sm">
    <h1 class="text-3xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">{{ $category->name }}</h1>
    <article class="prose prose-gray dark:prose-invert max-w-none">
        {!! $category->description !!}
    </article>
</div>

<!-- Category List -->
<div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] rounded-lg border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-4 shadow-sm">
    <div class="flex flex-col gap-2">
        @foreach ($categories as $ccategory)
            <a href="{{ route('category.show', ['category' => $ccategory->slug]) }}" wire:navigate
               class="px-3 py-2 rounded-md transition-colors duration-200
                      {{ $category->id == $ccategory->id
                         ? 'bg-[var(--primary)]/10 text-[var(--primary)] dark:bg-[var(--dark-primary)]/20 font-semibold'
                         : 'hover:bg-[var(--background-secondary)] dark:hover:bg-[var(--dark-neutral)] text-[var(--muted)] dark:text-[var(--dark-inverted)]' }}">
                {{ $ccategory->name }}
            </a>
        @endforeach
    </div>
</div>
</div>

<!-- Main Content -->
<div class="flex flex-col gap-4 col-span-3">
    @if(count($childCategories) >= 1)
        <!-- Child Categories -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 auto-rows-fr">
            @foreach ($childCategories as $childCategory)
                <div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] rounded-lg border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-4 shadow-sm hover:shadow-md transition-all flex flex-col h-full">
                    <div class="flex-1">
                        @if($childCategory->image)
                            <img src="{{ Storage::url($childCategory->image) }}" alt="{{ $childCategory->name }}"
                                 class="w-full h-32 object-cover rounded-md mb-3">
                        @endif

                        <h2 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">{{ $childCategory->name }}</h2>

                        @if(theme('show_category_description', true) && $childCategory->description)
                            <article class="prose prose-sm prose-gray dark:prose-invert mb-3">
                                {!! $childCategory->description !!}
                            </article>
                        @endif
                    </div>

                    <a href="{{ route('category.show', ['category' => $childCategory->slug]) }}" wire:navigate>
                        <button class="gradient-button w-full bg-[var(--primary)] hover:bg-[var(--primary)]/90 text-[var(--inverted)] py-2 px-4 rounded-md text-sm font-medium transition-colors">
                            {{ __('general.view') }}
                        </button>
                    </a>
                </div>
            @endforeach
        </div>
    @endif


<!-- Products Grid -->
<div class="grid sm:grid-cols-1 md:grid-cols-2 gap-6 auto-rows-fr">
    @foreach ($products as $product)
        <div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] border border-[var(--neutral)] dark:border-[var(--dark-neutral)] hover:bg-[var(--background-secondary)]/95 dark:hover:bg-[var(--dark-background-secondary)]/95 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full">
            <div class="flex-1">
            <!-- Product Image -->
            @if($product->image)
                <div class="relative overflow-hidden rounded-lg bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] flex items-center justify-center mb-4">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-contain object-center p-3 hover:scale-[1.02] transition-transform duration-300">
                </div>
            @elseif(theme('level', false))
                <div class="relative overflow-hidden rounded-lg bg-transparent flex items-center justify-center mb-4" style="height: 12rem;">
                    {{-- Transparent placeholder to keep height consistent --}}
                </div>
            @endif


                <!-- Stock Status Badge -->
                @if ($product->stock === 0)
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 w-fit mb-3 inline-block">
                        {{ __('product.out_of_stock', ['product' => $product->name]) }}
                    </span>
                @elseif($product->stock > 0)
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 w-fit mb-3 inline-block">
                        {{ __('product.in_stock') }}
                    </span>
                @endif

                <!-- Product Header -->
                <div class="flex flex-row justify-between items-start gap-4 mb-3">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-1">{{ $product->name }}</h2>
                        <h3 class="text-xl font-semibold text-primary dark:text-primary-300">
                            {{ $product->price() }}
                        </h3>
                    </div>

                    @if ($product->stock !== 0 && $product->price()->available)
                        <button class="p-2 rounded-full bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] hover:bg-[var(--background-secondary)]/90 dark:hover:bg-[var(--dark-background-secondary)]/90 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-primary dark:text-primary-400">
                                <path d="M6 2a1 1 0 0 0-1 1v1H4a1 1 0 0 0-1 1v1H2v2h1v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8h1V6h-1V4a1 1 0 0 0-1-1h-1V3a1 1 0 0 0-1-1H6zm0 2h12v1h1v1h-1v9a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V6H5V5h1V4zm3 3a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zm5 0a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1z"/>
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Product Description -->
                @if(theme('descript', false) && $product->description)
                    <article class="mb-4 prose prose-sm prose-gray dark:prose-invert max-w-none text-[var(--muted)] dark:text-[var(--dark-muted)]">
                        {!! $product->description !!}
                    </article>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 mt-4">
                @if(($product->stock > 0 || !$product->stock) && $product->price()->available && theme('direct_checkout', false))
                    <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                       wire:navigate class="flex-1">
                        <button class="gradient-button w-full bg-gradient-to-r from-primary to-accent hover:from-primary/90 hover:to-accent/90 text-white font-medium py-2.5 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                            {{ __('product.add_to_cart') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </button>
                    </a>
                @else
                    <a href="{{ route('products.show', ['category' => $product->category, 'product' => $product->slug]) }}"
                       wire:navigate class="flex-1">
                        <button class="gradient-button w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white font-medium py-2.5 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                            {{ __('general.view') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </a>
                @endif

                @if(($product->stock > 0 || !$product->stock) && $product->price()->available && !theme('direct_checkout', false))
                    <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                       wire:navigate class="flex-1">
                        <button class="gradient-button w-full bg-gradient-to-r from-accent to-primary hover:from-accent/90 hover:to-primary/90 text-white font-medium py-2.5 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                            {{ __('product.add_to_cart') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </button>
                    </a>
                @endif
            </div>
        </div>
    @endforeach
</div>



<style>
:root {
    --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
    --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
    --accent: {{ theme('accent', 'hsl(280, 100%, 70%)') }};
    --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
    --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
    --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
    --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
    --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
    --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
    --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
    --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
    --dark-accent: {{ theme('dark-accent', 'hsl(280, 100%, 70%)') }};
    --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
    --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
    --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
    --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
    --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
    --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
}

/* Gradient Button */
.gradient-button {
    background: linear-gradient(to bottom right, var(--primary), var(--secondary));
    transition: all 0.3s ease;
}

.gradient-button:hover {
    background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
    transform: translateY(-1px);
}

/* Enhanced card hover effects */
.hover\:shadow-md:hover {
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}
</style>
{!! theme('custom_layout_css', '') !!}