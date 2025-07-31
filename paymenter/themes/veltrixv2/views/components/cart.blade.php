<div class="relative w-10 h-10 flex items-center justify-center rounded-lg hover:bg-neutral transition">
    <x-navigation.link :href="route('cart')" aria-label="View cart">
        <!-- Better cart icon (Heroicons Shopping Cart SVG) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" >
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 2.293A1 1 0 0 0 7 17h10m-10 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm10 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
        </svg>

        @if($cartCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs font-semibold w-5 h-5 flex items-center justify-center select-none pointer-events-none">
                {{ $cartCount > 99 ? '99+' : $cartCount }}
            </span>
        @endif
    </x-navigation.link>
</div>

{!! theme('custom_layout_css', '') !!}
