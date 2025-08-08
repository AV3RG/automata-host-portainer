<div class="mx-auto container mt-8 mb-12 p-6 sm:px-6 lg:px-8">
    <!-- Title Section -->
    <div class="text-center sm:text-left mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-color-base leading-tight flex items-center gap-2">                           
            {{ __('services.upgrade_service', ['service' => $service->product->name]) }}
        </h1>
        <h2 class="text-lg font-semibold mt-2">
            @if($step == 1)
                {{ __('services.upgrade_choose_product') }}
            @else
                {{ __('services.upgrade_choose_config') }}
            @endif
        </h2>        
    </div>

    <div class="grid md:grid-cols-4 gap-8 lg:gap-12 items-start">
        <div class="flex flex-col gap-6 w-full col-span-3">

            @if($step == 1)
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                                <x-ri-price-tag-3-fill class="size-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-color-base">
                                    {{ __('services.upgrade_choose_product') }}
                                </h2>
                                <p class="text-color-muted text-sm">Select your new plan or keep your current one</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <input type="radio" name="upgrade" value="{{ $service->product->id }}" wire:model.live="upgrade"
                                    class="hidden peer" id="product-{{ $service->product->id }}">
                                <label for="product-{{ $service->product->id }}"
                                    class="flex flex-col cursor-pointer bg-background-tertiary/30 hover:bg-background-tertiary/50 border border-neutral/30 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-2 peer-checked:ring-primary p-6 rounded-xl transition-all duration-300 h-full">
                                    
                                    @if ($service->product->image)
                                        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-primary/10 to-primary/5 p-2 mb-4">
                                            <img src="{{ Storage::url($service->product->image) }}" alt="{{ $service->product->name }}" class="rounded-lg w-full object-cover object-center h-auto max-h-48">
                                        </div>
                                    @endif

                                    <div class="rounded-full border border-background inline-flex items-center justify-center gap-2 align-middle bg-primary/60 w-fit px-3 py-1 mb-4">
                                        <p class="text-sm font-medium text-color-base">{{ __('services.current_plan') }}</p>
                                    </div>
                                    
                                    <h3 class="text-lg font-bold text-color-base mb-2">{{ $service->product->name }}</h3>
                                    <div class="max-h-64 overflow-y-auto mb-4">
                                        <article class="prose dark:prose-invert text-color-muted text-sm">
                                            {!! $service->product->description !!}
                                        </article>
                                    </div>
                                    
                                    <div class="flex justify-between items-center p-3 bg-background-tertiary/30 rounded-lg mt-auto">
                                        <span class="text-sm text-color-muted">Current Price</span>
                                        <span class="text-lg font-semibold text-color-base">
                                            {{ $service->product->price(null, $service->plan->billing_period, $service->plan->billing_unit, $service->currency_code) }}
                                        </span>
                                    </div>
                                </label>
                            </div>

                            @foreach ($service->productUpgrades() as $product)
                                <div>
                                    <input type="radio" name="upgrade" value="{{ $product->id }}" wire:model.live="upgrade"
                                        class="hidden peer" id="product-{{ $product->id }}">
                                    <label for="product-{{ $product->id }}"
                                        class="flex flex-col cursor-pointer bg-background-tertiary/30 hover:bg-background-tertiary/50 border border-neutral/50 peer-checked:border-secondary peer-checked:bg-secondary/5 peer-checked:ring-2 peer-checked:ring-secondary p-6 rounded-xl transition-all duration-300 h-full">
                                                     
                                        @if ($product->image)
                                            <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-primary/10 to-primary/5 p-2 mb-4">
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="rounded-lg w-full object-cover object-center h-auto max-h-48">
                                            </div>
                                        @endif

                                        @if($upgrade == $product->id)
                                            <div class="rounded-full border border-background inline-flex items-center justify-center gap-2 align-middle bg-primary w-fit px-3 py-1 mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                                                <p class="text-sm font-medium text-color-base">{{ __('services.new_plan') }}</p>
                                            </div>
                                        @endif
                                        
                                        <h3 class="text-lg font-bold text-color-base mb-2">{{ $product->name }}</h3>
                                        <div class="max-h-64 overflow-y-auto mb-4">
                                            <article class="prose dark:prose-invert text-color-muted text-sm">
                                                {!! $product->description !!}
                                            </article>
                                        </div>
                                        
                                        <div class="flex justify-between items-center p-3 bg-background-tertiary/30 rounded-lg mt-auto">
                                            <span class="text-sm text-color-muted">New Price</span>
                                            <span class="text-lg font-semibold text-color-base">
                                                {{ $product->price(null, $service->plan->billing_period, $service->plan->billing_unit, $service->currency_code) }}
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                                <x-ri-settings-3-fill class="size-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-color-base">
                                    {{ __('services.upgrade_choose_config') }}
                                </h2>
                                <p class="text-color-muted text-sm">Customize your upgrade options</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @foreach ($upgradeProduct->upgradableConfigOptions as $configOption)
                                @php
                                    $showPriceTag = $configOption->children->filter(fn ($value) => !$value->price(billing_period: $service->plan->billing_period, billing_unit: $service->plan->billing_unit)->is_free)->count() > 0;
                                @endphp
                                
                                <x-form.configoption :config="$configOption" :name="'configOptions.' . $configOption->id" :showPriceTag="$showPriceTag" :plan="$service->plan">
                                    @if ($configOption->type == 'select')
                                        <select wire:model.live="configOptions.{{ $configOption->id }}"
                                            id="config-{{ $configOption->id }}"
                                            name="configOptions[{{ $configOption->id }}]"
                                            class="w-full text-color-base bg-background-tertiary/50 border border-neutral/50 focus:border-primary focus:ring-primary focus:ring-2 focus:ring-primary/20 rounded-xl px-4 py-3">
                                            @foreach ($configOption->children as $configOptionValue)
                                                <option value="{{ $configOptionValue->id }}">
                                                    {{ $configOptionValue->name }}
                                                    {{ ($showPriceTag && $configOptionValue->price(billing_period: $service->plan->billing_period, billing_unit: $service->plan->billing_unit)->available) ? ' - ' . $configOptionValue->price(billing_period: $service->billing_period, billing_unit: $service->billing_unit) : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif($configOption->type == 'radio')
                                        <div class="space-y-3">
                                            @foreach ($configOption->children as $configOptionValue)
                                                <label for="config-{{ $configOptionValue->id }}"
                                                    class="flex items-center gap-3 cursor-pointer text-color-base p-3 rounded-lg border border-transparent hover:bg-background-tertiary/20">
                                                    <input type="radio"
                                                        id="config-{{ $configOptionValue->id }}"
                                                        name="configOptions[{{ $configOption->id }}]"
                                                        wire:model.live="configOptions.{{ $configOption->id }}"
                                                        value="{{ $configOptionValue->id }}"
                                                        class="form-radio text-primary focus:ring-primary focus:ring-2 focus:ring-primary/20 size-4">
                                                    <span class="flex-grow">
                                                        {{ $configOptionValue->name }}
                                                        @if($showPriceTag && $configOptionValue->price(billing_period: $service->billing_period, billing_unit: $service->billing_unit)->available)
                                                            <span class="text-color-muted text-sm ml-2">
                                                                ({{ $configOptionValue->price(billing_period: $service->billing_period, billing_unit: $service->billing_unit) }})
                                                            </span>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </x-form.configoption>
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
                            <x-ri-arrow-up-circle-fill class="size-6" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-color-base">
                                {{ __('services.upgrade_summary') }}
                            </h2>
                            <p class="text-color-muted text-xs">Review your upgrade details</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center p-3 bg-background-tertiary/30 rounded-lg border border-neutral/30">
                            <div class="flex items-center gap-2">
                                <x-ri-archive-fill class="size-4 text-color-muted" />
                                <span class="text-sm text-color-muted">{{ __('services.current_plan') }}</span>
                            </div>
                            <span class="text-sm font-medium text-color-base">{{ $service->product->name }}</span>
                        </div>

                        @if($upgrade != $service->product->id)
                            <div class="flex justify-between items-center p-3 bg-primary/10 rounded-lg border border-primary/20">
                                <div class="flex items-center gap-2">
                                    <x-ri-star-fill class="size-4 text-primary" />
                                    <span class="text-sm text-primary font-medium">{{ __('services.new_plan') }}</span>
                                </div>
                                <span class="text-sm font-medium text-primary">{{ $upgradeProduct ? $upgradeProduct->name : __('general.select_plan') }}</span>
                            </div>
                        @endif

                        <div class="flex-col justify-between items-center p-4 bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl">
                            <div class="flex items-center gap-2">
                                <x-ri-money-dollar-circle-fill class="size-5 text-primary" />
                                <span class="text-lg font-semibold text-color-base">{{ __('services.total_today') }}</span>
                            </div>
                            <span class="text-2xl font-bold text-primary">{{ $this->totalToday() }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button wire:click="{{ ($upgradeProduct->upgradableConfigOptions()->count() > 0 && $step == 1) ? 'nextStep' : 'doUpgrade' }}" 
                                wire:loading.attr="disabled"
                                class="group/btn w-full inline-flex items-center justify-center gap-3 bg-primary hover:bg-primary/90 text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-lg hover:shadow-primary/30 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>
                                @if($upgradeProduct && $upgradeProduct->upgradableConfigOptions()->count() > 0 && $step == 1)
                                    {{ __('services.next_step') }}
                                @else
                                    {{ __('services.upgrade') }}
                                @endif
                            </span>
                            <span wire:loading>Processing...</span>
                            <x-ri-arrow-right-fill class="size-5 transform transition-transform duration-300 group-hover/btn:translate-x-1" wire:loading.remove />
                            <x-ri-loader-4-fill class="size-5 animate-spin" wire:loading />
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>