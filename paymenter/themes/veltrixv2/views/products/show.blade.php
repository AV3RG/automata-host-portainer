<div class="flex flex-col @if ($product->image) md:grid grid-cols-2 gap-8 @endif bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)]/90 hover:bg-[var(--background-secondary)]/95 dark:hover:bg-[var(--dark-background-secondary)]/95 border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
    @if ($product->image)
        <div class="relative overflow-hidden rounded-lg bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] flex items-center justify-center">
            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                class="w-full h-96 object-contain object-center p-4 hover:scale-[1.02] transition-transform duration-300">
        </div>
    @endif

    <div class="flex flex-col h-full">
        <!-- Stock Status Badge -->
        @if ($product->stock === 0)
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 w-fit mb-4">
                {{ __('product.out_of_stock', ['product' => $product->name]) }}
            </span>
        @elseif($product->stock > 0)
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 w-fit mb-4">
                {{ __('product.in_stock') }}
            </span>
        @endif

        <!-- Product Header -->
        <div class="flex flex-row justify-between items-start gap-4">
            <div>
                <h2 class="text-3xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-1">{{ $product->name }}</h2>
                <h3 class="text-2xl font-semibold text-[var(--primary)] dark:text-[var(--dark-primary)]">
                    {{ $product->price() }}
                </h3>
            </div>

            @if ($product->stock !== 0 && $product->price()->available)
                <button class="p-2 rounded-full bg-[var(--background-secondary)] dark:bg-[var(--dark-neutral)] hover:bg-[var(--neutral)] dark:hover:bg-[var(--dark-muted)] text-[var(--muted)] dark:text-[var(--dark-inverted)] transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-[var(--primary)] dark:text-[var(--dark-primary)]">
                        <path d="M6 2a1 1 0 0 0-1 1v1H4a1 1 0 0 0-1 1v1H2v2h1v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8h1V6h-1V4a1 1 0 0 0-1-1h-1V3a1 1 0 0 0-1-1H6zm0 2h12v1h1v1h-1v9a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V6H5V5h1V4zm3 3a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zm5 0a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1z"/>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Product Description -->
        <article class="my-6 prose prose-gray dark:prose-invert max-w-none">
            {!! $product->description !!}
        </article>

        <!-- Add to Cart Button -->
        @if ($product->stock !== 0 && $product->price()->available)
            <div class="mt-auto">
                <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                    wire:navigate
                    class="block w-full">
                    <button class="gradient-button w-full bg-gradient-to-r from-[var(--primary)] to-[var(--accent)] hover:from-[var(--primary)]/90 hover:to-[var(--accent)]/90 text-[var(--inverted)] font-medium py-3 px-6 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                        {{ __('product.add_to_cart') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <path d="M3 6h18"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </button>
                </a>
            </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
    --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
    --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
    --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
    --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
    --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
    --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
    --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
    --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
    --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
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
    transition: background 0.3s ease;
}

.gradient-button:hover {
    background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
}
</style>
{!! theme('custom_layout_css', '') !!}