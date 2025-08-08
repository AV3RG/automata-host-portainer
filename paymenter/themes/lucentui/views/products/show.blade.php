<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="bg-background-secondary rounded-xl shadow-2xl p-6 lg:p-10 border border-neutral
                @if ($product->image) grid grid-cols-1 md:grid-cols-2 @endif gap-8 lg:gap-12 items-start">
        @if ($product->image)
            <div class="relative overflow-hidden rounded-2xl shadow-lg aspect-w-4 aspect-h-3 mb-6 md:mb-0">
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover object-center rounded-2xl transition-transform duration-300 hover:scale-105">
            </div>
        @endif
        <div class="flex flex-col gap-8">
            @if ($product->stock === 0)
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-800 text-red-100 w-fit">
                    {{ __('product.out_of_stock', ['product' => $product->name]) }}
                </span>
            @elseif($product->stock > 0)
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-800 text-green-100 w-fit">
                    {{ __('product.in_stock') }}
                </span>
            @endif
            <div>
                <h1 class="text-3xl font-extrabold text-color-base mb-2 leading-tight">{{ $product->name }}</h1> 
                <p class="text-xl font-bold text-primary">{{ $product->price() }}</p> 
            </div>
            @if ($product->description)
                <div class="border-t border-neutral pt-4">
                    <h2 class="text-lg font-bold text-color-base mb-2">{{ __('Description') }}</h2>
                    <article class="prose dark:prose-invert text-color-muted leading-relaxed">
                        {!! $product->description !!}
                    </article>
                </div>
            @endif
            @if ($product->stock !== 0 && $product->price()->available)
                <div class="mt-4">
                    <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                        wire:navigate>
                        <x-button.primary class="w-full sm:w-auto">{{ __('product.add_to_cart') }}</x-button.primary>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
