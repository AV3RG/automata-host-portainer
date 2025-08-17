<div class="mx-auto container mt-8 p-4 sm:p-6 lg:p-8 rounded-2xl">
    <div class="grid md:grid-cols-4 gap-8 lg:gap-12 items-start">

        <div class="flex flex-col gap-6 w-full col-span-3">

            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 lg:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <div class="size-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-green-600 uppercase tracking-wide">Available</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        @if ($product->image)
                            <div class="flex-shrink-0 relative overflow-hidden rounded-xl w-32 h-32 sm:w-36 sm:h-36 bg-gradient-to-br from-primary/10 to-primary/5 p-4">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain object-center">
                            </div>
                        @endif
                        <div class="flex-grow text-center sm:text-left">
                            <h1 class="text-3xl lg:text-4xl font-bold text-color-base mb-3 leading-tight">
                                {{ $product->name }}
                            </h1>
                            <div>
                                <article class="prose dark:prose-invert text-color-muted text-sm leading-relaxed">
                                    {!! html_entity_decode($product->description) !!}
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($product->availablePlans()->count() > 1)
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                                <x-ri-price-tag-3-fill class="size-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-color-base">
                                    {{ __('Select Your Plan') }}
                                </h2>
                                <p class="text-color-muted text-sm">Choose the perfect plan for your needs</p>
                            </div>
                        </div>

                        <div class="w-full">
                            <label for="plan_id" class="block text-sm font-medium text-color-base mb-2">
                                {{ __('Choose a plan') }}
                            </label>
                            <select wire:model.live="plan_id"
                                id="plan_id"
                                name="plan_id"
                                class="w-full text-color-base bg-background-secondary border border-neutral/50 focus:border-primary focus:ring-primary focus:ring-2 focus:ring-primary/20 rounded-xl px-4 py-3 appearance-none [&>option]:bg-background-secondary [&>option]:text-color-base dark:[&>option]:bg-gray-800 dark:[&>option]:text-gray-100">
                                @foreach ($product->availablePlans() as $availablePlan)
                                    <option value="{{ $availablePlan->id }}">
                                        {{ $availablePlan->name }} - {{ $availablePlan->price() }}
                                        @if ($availablePlan->price()->has_setup_fee)
                                            + {{ $availablePlan->price()->formatted->setup_fee }} {{ __('product.setup_fee') }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            @if($product->configOptions->count() > 0 || count($this->getCheckoutConfig()) > 0)
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                                <x-ri-settings-3-fill class="size-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-color-base">
                                    {{ __('Configure Your Product') }}
                                </h2>
                                <p class="text-color-muted text-sm">Customize options to fit your requirements</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            {{-- Product Config Options --}}
                            @foreach ($product->configOptions as $configOption)
                                @php
                                    $showPriceTag = $configOption->children->filter(fn ($value) => !$value->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->is_free)->count() > 0;
                                @endphp
                                <div class="bg-background-tertiary/30 rounded-xl p-4 border border-neutral/30">
                                    <x-form.configoption :config="$configOption" :name="'configOptions.' . $configOption->id" :showPriceTag="$showPriceTag" :plan="$plan">
                                        @if ($configOption->type == 'select')
                                            @foreach ($configOption->children as $configOptionValue)
                                                <option value="{{ $configOptionValue->id }}" class="bg-background-secondary text-color-base dark:bg-gray-800 dark:text-gray-100">
                                                    {{ $configOptionValue->name }}
                                                    {{ ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' - ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                                                </option>
                                            @endforeach
                                        @elseif($configOption->type == 'radio')
                                            <div class="space-y-3">
                                                @foreach ($configOption->children as $configOptionValue)
                                                    <div class="flex items-center gap-2">
                                                        <input type="radio" id="{{ $configOptionValue->id }}" name="{{ $configOption->id }}"
                                                            wire:model.live="configOptions.{{ $configOption->id }}"
                                                            value="{{ $configOptionValue->id }}"
                                                            class="form-radio text-primary focus:ring-primary focus:ring-2 focus:ring-primary/20 size-4" />
                                                        <label for="{{ $configOptionValue->id }}" class="text-color-base">
                                                            {{ $configOptionValue->name }}
                                                            {{ ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' - ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </x-form.configoption>
                                </div>
                            @endforeach

                            @foreach ($this->getCheckoutConfig() as $configOption)
                                @php $configOption = (object) $configOption; @endphp
                                <div class="bg-background-tertiary/30 rounded-xl p-4 border border-neutral/30">
                                    <x-form.configoption :config="$configOption" :name="'checkoutConfig.' . $configOption->name">
                                        @if ($configOption->type == 'select')
                                            @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                                                <option value="{{ $configOptionValue }}" class="bg-background-secondary text-color-base dark:bg-gray-800 dark:text-gray-100">
                                                    {{ $configOptionValueName }}
                                                </option>
                                            @endforeach
                                        @elseif($configOption->type == 'radio')
                                            <div class="space-y-3">
                                                @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                                                    <div class="flex items-center gap-2">
                                                        <input type="radio" id="{{ $configOptionValue }}" name="{{ $configOption->name }}"
                                                            wire:model.live="checkoutConfig.{{ $configOption->name }}"
                                                            value="{{ $configOptionValue }}"
                                                            class="form-radio text-primary focus:ring-primary focus:ring-2 focus:ring-primary/20 size-4" />
                                                        <label for="{{ $configOptionValue }}" class="text-color-base">
                                                            {{ $configOptionValueName }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </x-form.configoption>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="flex flex-col gap-6 w-full col-span-3 md:col-span-1 sticky top-8 md:top-20">
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-neutral/50">
                        <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                            <x-ri-shopping-cart-2-fill class="size-6" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-color-base">
                                {{ __('product.order_summary') }}
                            </h2>
                            <p class="text-color-muted text-xs">Review your order details</p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex-col items-start items-center p-4 bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl">
                            <div class="flex items-center gap-2">
                                <x-ri-money-dollar-circle-fill class="size-5 text-primary" />
                                <h4 class="text-lg font-semibold text-color-base">{{ __('product.total_today') }}</h4>
                            </div>
                            <span class="text-2xl font-bold text-primary gtag-item-price-tag">{{ $total }}</span>
                        </div>

                        @if ($total->setup_fee && $plan->type == 'recurring')
                            <div class="flex justify-between items-center p-3 bg-background-tertiary/30 rounded-2xl border border-neutral/30">
                                <div class="flex items-center gap-2">
                                    <x-ri-time-fill class="size-4 text-color-muted" />
                                    <h4 class="text-sm text-color-muted">
                                        {{ __('product.then_after_x', ['time' => $plan->billing_period . ' ' . trans_choice(__('services.billing_cycles.' . $plan->billing_unit), $plan->billing_period)]) }}
                                    </h4>
                                </div>
                                <span class="text-base font-medium text-color-base">
                                    {{ $total->format($total->price - $total->setup_fee) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    @if (($product->stock > 0 || !$product->stock) && $product->price()->available)
                        <div class="space-y-3">
                            <x-button.primary wire:click="checkout" wire:loading.attr="disabled" 
                                    class="group/btn w-full inline-flex items-center justify-center gap-3 bg-primary hover:bg-primary/90 text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-lg hover:shadow-primary/30 disabled:opacity-50 disabled:cursor-not-allowed gtag-checkout-button">
                                <span wire:loading.remove>{{ __('product.checkout') }}</span>
                                <span wire:loading>Processing...</span>
                                <x-ri-arrow-right-fill class="size-5 transform transition-transform duration-300 group-hover/btn:translate-x-1 gtag-checkout-button" wire:loading.remove />
                                <x-ri-loader-4-fill class="size-5 animate-spin gtag-checkout-button" wire:loading />
                            </x-button.primary>
                            
                            <div class="flex items-center justify-center gap-2 text-xs text-color-muted">
                                <x-ri-shield-check-fill class="size-4 text-green-500" />
                                <span>Secure checkout powered by encryption</span>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                <x-ri-error-warning-fill class="size-5" />
                                <span class="font-medium">Currently Unavailable</span>
                            </div>
                            <p class="text-sm text-red-500 dark:text-red-300 mt-1">
                                This product is currently out of stock or not available for purchase.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>