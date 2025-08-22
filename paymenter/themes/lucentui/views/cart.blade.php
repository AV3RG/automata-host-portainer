<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-4 gap-8 lg:gap-12 items-start">

        {{-- Cart Items Section --}}
        <div class="flex flex-col col-span-3 gap-6">

            @if (Cart::get()->isEmpty())
                <div class="bg-background-secondary/70 backdrop-blur-md rounded-2xl shadow-lg p-8 text-center border border-neutral/50">
                    <h1 class="text-3xl font-bold text-color-base mb-4">
                        {{ __('product.empty_cart') }}
                    </h1>
                </div>
            @endif

            @foreach (Cart::get() as $key => $item)
                <div class="bg-background-secondary/70 backdrop-blur-md rounded-2xl shadow-lg p-5 border border-neutral/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

                    <div class="flex-grow flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        @if ($item->product->image)
                            <div class="flex-shrink-0 relative overflow-hidden rounded-lg w-20 h-20">
                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}"
                                    class="w-full h-full object-contain object-center p-1">
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-bold text-color-base mb-1">{{ $item->product->name }}</h2>
                            <p class="text-sm text-color-muted leading-tight">
                                {{ $item->plan->name }} Plan
                            </p>
                            @if ($item->configOptions->isNotEmpty())
                                <p class="text-sm text-color-muted leading-tight">
                                    @foreach ($item->configOptions as $option)
                                        <span class="block">{{ $option->option_name }}: <span class="font-medium">{{ $option->value_name }}</span></span>
                                    @endforeach
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-center md:items-end gap-4 mt-4 md:mt-0">
                        <h3 class="text-xl lg:text-2xl font-bold text-primary mb-1">
                            {{ $item->price->format($item->price->price * $item->quantity) }}
                            @if ($item->quantity > 1)
                                <span class="text-sm text-color-muted font-normal block md:inline-block">({{ $item->price }} {{ __('each') }})</span>
                            @endif
                        </h3>

                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            @if ($item->product->allow_quantity == 'combined')
                                <div class="flex items-center space-x-2">
                                    <x-button.secondary
                                        wire:click="updateQuantity({{ $key }}, {{ $item->quantity - 1 }})"
                                        class="p-2.5 rounded-full flex items-center justify-center size-8 !min-w-0 !min-h-0">
                                        <x-ri-subtract-line class="size-4" />
                                    </x-button.secondary>
                                    <x-form.input class="text-center w-14 py-2.5" disabled value="{{ $item->quantity }}" name="quantity-{{ $key }}" divClass="!mt-0 !w-auto" />
                                    <x-button.secondary
                                        wire:click="updateQuantity({{ $key }}, {{ $item->quantity + 1 }});"
                                        class="p-2.5 rounded-full flex items-center justify-center size-8 !min-w-0 !min-h-0">
                                        <x-ri-add-line class="size-4" />
                                    </x-button.secondary>
                                </div>
                            @endif

                            <a href="{{ route('products.checkout', [$item->product->category, $item->product, 'edit' => $key]) }}"
                                wire:navigate
                                class="inline-flex justify-center items-center px-4 py-2 border border-neutral/50 rounded-lg text-sm font-medium text-color-base hover:bg-neutral/30 transition-colors duration-200">
                                {{ __('product.edit') }}
                            </a>
                            <x-button.danger wire:click="removeProduct({{ $key }})" class="inline-flex justify-center items-center px-4 py-2 rounded-lg text-sm">
                                {{ __('product.remove') }}
                            </x-button.danger>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Order Summary & Checkout Section --}}
        <div class="flex flex-col gap-6 w-full col-span-3 md:col-span-1 sticky top-8 md:top-20">
            @if (!Cart::get()->isEmpty())
                <div class="bg-background-secondary/70 backdrop-blur-md rounded-2xl shadow-lg p-6 lg:p-8 border border-neutral/50">
                    <h2 class="text-2xl font-bold text-color-base mb-5 pb-3 border-b border-neutral/50">
                        {{ __('product.order_summary') }}
                    </h2>

                    <div class="space-y-4">
                        {{-- Coupon Input / Display --}}
                        @if(!$coupon)
                            <div class="flex items-end gap-3 mb-4">
                                <x-form.input wire:model="coupon" name="coupon" :label="__('Coupon Code')" class="h-10 px- py-2.5 text-base font-semibold flex-shrink-0" />
                                <x-button.primary wire:click="applyCoupon" class="h-fit !w-fit mb-0.5" wire:loading.attr="disabled">
                                    <x-loading target="applyCoupon" />
                                    <div wire:loading.remove wire:target="applyCoupon">
                                        {{ __('product.apply') }}
                                    </div>
                                </x-button.primary>
                            </div>
                        @else
                            <div class="flex justify-between items-center bg-neutral/20 rounded-md p-3">
                                <h4 class="font-medium text-color-base">{{ $coupon->code }} <span class="text-sm text-color-muted">({{ __('Applied') }})</span></h4>
                                <x-button.secondary wire:click="removeCoupon" class="p-2 rounded-full size-8 !min-w-0 !min-h-0">
                                    <x-ri-close-line class="size-4" />
                                </x-button.secondary>
                            </div>
                        @endif

                        {{-- Price Details --}}
                        <div class="flex justify-between items-center text-color-base">
                            <h4 class="font-semibold">{{ __('invoices.subtotal') }}:</h4>
                            <span>{{ $total->format($total->price - $total->tax) }}</span>
                        </div>
                        @if ($total->tax > 0)
                            <div class="flex justify-between items-center text-color-base">
                                <h4 class="font-semibold">{{ \App\Classes\Settings::tax()->name }}:</h4>
                                <span>{{ $total->formatted->tax }}</span>
                            </div>
                        @endif

                        <div class="border-t border-dashed border-neutral/50 pt-4 mt-4">
                            <div class="flex justify-between items-center text-xl font-bold text-color-base">
                                <h4>{{ __('invoices.total') }}:</h4>
                                <span class="text-primary">{{ $total }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-background-secondary/70 backdrop-blur-md rounded-2xl shadow-lg p-6 lg:p-8 border border-neutral/50">
                    @if($total->price > 0)
                        @if(count($gateways) > 1)
                            <div class="mb-5">
                                <x-form.select wire:model.live="gateway" name="gateway" :label="__('product.payment_method')"
                                    class="w-full text-color-base bg-neutral/20 border-neutral/50 focus:border-primary focus:ring-primary rounded-lg px-4 py-2.5 transition-colors duration-200">
                                    @foreach ($gateways as $gateway)
                                        <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                                    @endforeach
                                </x-form.select>
                            </div>
                        @endif
                        @if(Auth::check() && Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->exists() && Auth::user()->credits()->where('currency_code', Cart::get()->first()->price->currency->code)->first()->amount > 0)
                            <div class="mb-5">
                                <x-form.checkbox wire:model="use_credits" name="use_credits" label="{{ __('product.use_credits') }}" />
                            </div>
                        @endif
                    @endif

                    @if(config('settings.tos'))
                        <div class="mb-6">
                            <x-form.checkbox wire:model="tos" name="tos">
                                {{ __('product.tos') }}
                                <a href="{{ config('settings.tos') }}" target="_blank" class="text-primary hover:text-primary/80 font-medium ml-1">
                                    {{ __('product.tos_link') }}
                                </a>
                            </x-form.checkbox>
                        </div>
                    @endif

                    <x-button.primary wire:click="checkout" class="w-full py-3 text-lg font-semibold rounded-lg shadow-lg hover:shadow-primary/30 transition-all duration-300" wire:loading.attr="disabled">
                        <x-loading target="checkout" class="mr-2" />
                        <span wire:loading.remove wire:target="checkout">
                            {{ __('product.checkout') }}
                        </span>
                    </x-button.primary>
                    <div class="flex items-center justify-center gap-2 text-xs text-color-muted mt-4">
                        <x-ri-shield-check-fill class="size-4 text-green-500" />
                        <span>Secure checkout powered by encryption</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>