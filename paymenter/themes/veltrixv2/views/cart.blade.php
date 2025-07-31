<div class="flex flex-col md:grid md:grid-cols-4 gap-4">
    <!-- Red Warning Banner - Shows when total exceeds credits -->
    @if(Auth::check() &&
        Cart::get()->isNotEmpty() &&
        $total->price > 0 &&
        Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->exists() &&
        $total->price > Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->first()->amount)
        <div class="col-span-full bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-200 p-4 rounded-md mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>
                    Warning: Your order total exceeds your available credits by
                    {{ $total->format($total->price - Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->first()->amount) }}
                </span>
            </div>
            <a href="/rewards" class="gradient-button text-white px-3 py-1 rounded-md text-sm font-medium transition-colors flex items-center gap-1">
                Earn More Credits
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    @endif

    <div class="flex flex-col col-span-3 gap-4">
        @if (Cart::get()->isEmpty())
            <h1 class="text-2xl font-semibold">
                {{ __('product.empty_cart') }}
            </h1>
        @endif
        @foreach (Cart::get() as $key => $item)
            <div class="flex flex-row justify-between w-full bg-background-secondary p-3 rounded-md">
                <div class="flex flex-col gap-1">
                    <h2 class="text-2xl font-semibold">
                        {{ $item->product->name }}
                    </h2>
                    <p class="text-sm">
                        @foreach ($item->configOptions as $option)
                            {{ $option->option_name }}: {{ $option->value_name }}<br>
                        @endforeach
                    </p>
                </div>
                <div class="flex flex-col justify-between items-end gap-4">
                    <h3 class="text-xl font-semibold p-1">
                        {{ $item->price->format($item->price->price * $item->quantity) }} @if ($item->quantity > 1)
                            ({{ $item->price }} each)
                        @endif
                    </h3>
                    <div class="flex flex-row gap-2">
                        @if ($item->product->allow_quantity == 'combined')
                            <div class="flex flex-row gap-1 items-center mr-4">
                                <x-button.secondary
                                    wire:click="updateQuantity({{ $key }}, {{ $item->quantity - 1 }})"
                                    class="h-full !w-fit">
                                    -
                                </x-button.secondary>
                                <x-form.input class="h-10 text-center" disabled
                                    wire:model="items.{{ $key }}.quantity" divClass="!mt-0 !w-14"
                                    name="quantity" />
                                <x-button.secondary
                                    wire:click="updateQuantity({{ $key }}, {{ $item->quantity + 1 }});"
                                    class="h-full !w-fit">
                                    +
                                </x-button.secondary>
                            </div>
                        @endif
                        <a href="{{ route('products.checkout', [$item->product->category, $item->product, 'edit' => $key]) }}"
                            wire:navigate>
                            <x-button.primary class="gradient-button h-fit w-fit">
                                {{ __('product.edit') }}
                            </x-button.primary>
                        </a>
                        <x-button.danger wire:click="removeProduct({{ $key }})" class="h-fit !w-fit">
                            {{ __('product.remove') }}
                        </x-button.danger>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col gap-4">
        @if (!Cart::get()->isEmpty())
            <div class="flex flex-col gap-2 w-full col-span-1 bg-background-secondary p-3 rounded-md">
                <h2 class="text-2xl font-semibold mb-3">
                    {{ __('product.order_summary') }}
                </h2>


<!-- Credit Balance Display -->
@if(Auth::check() && Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->exists())
    <div class="font-semibold flex justify-between mb-2">
        <h4>{{ __('Credit Balance') }}:</h4>
        {{ Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->first()->formattedAmount }}
    </div>
@endif

<div class="font-semibold flex items-end gap-2">
    @if(!$coupon)
        <x-form.input wire:model="coupon" name="coupon" label="Coupon" />
        <x-button.primary wire:click="applyCoupon" class="gradient-button h-fit !w-fit mb-0.5">
            {{ __('product.apply') }}
        </x-button.primary>
    @else
        <div class="flex justify-between items-center w-full">
            <h4 class="text-center w-full">{{ $coupon->code }}</h4>
            <x-button.secondary wire:click="removeCoupon" class="h-fit !w-fit">
                {{ __('product.remove') }}
            </x-button.secondary>
        </div>
    @endif
</div>
<div class="font-semibold flex justify-between">
    <h4>{{ __('invoices.subtotal') }}:</h4> {{ $total->format($total->price - $total->tax) }}
</div>
@if ($total->tax > 0)
    <div class="font-semibold flex justify-between">
        <h4>{{ \App\Classes\Settings::tax()->name }}:</h4> {{ $total->formatted->tax }}
    </div>
@endif
<div class="text-lg font-semibold flex justify-between mt-1">
    <h4>{{ __('invoices.total') }}:</h4> {{ $total }}
</div>
</div>
            <div class="flex flex-col gap-2 w-full col-span-1 bg-background-secondary p-3 rounded-md">
                @if($total->price > 0)
                @if(count($gateways) > 1)
                <x-form.select wire:model.live="gateway" name="gateway" :label="__('product.payment_method')">
                    @foreach ($gateways as $gateway)
                    <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                    @endforeach
                </x-form.select>
                @endif
                @if(Auth::check() && Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->exists() && Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->first()->amount > 0)
                    <x-form.checkbox wire:model="use_credits" name="use_credits" label="Use Credits" />
                @endif
                @endif
                @if(config('settings.tos'))
                    <x-form.checkbox wire:model="tos" name="tos">
                        {{ __('product.tos') }}
                        <a href="{{ config('settings.tos') }}" target="_blank" class="text-primary hover:text-primary/80">
                            {{ __('product.tos_link') }}
                        </a>
                    </x-form.checkbox>
                @endif

                <div class="flex flex-row justify-end gap-2">
                    <x-button.primary wire:click="checkout" class="gradient-button h-fit" wire:loading.attr="disabled">
                        <x-loading target="checkout" />
                        <div wire:loading.remove wire:target="checkout">
                            {{ __('product.checkout') }}
                        </div>
                    </x-button.primary>
                </div>
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