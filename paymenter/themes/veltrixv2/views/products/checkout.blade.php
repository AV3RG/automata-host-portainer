<!-- Product Details Full Layout -->
<div class="flex flex-col @if($product->image) md:grid md:grid-cols-4 gap-6 @endif bg-[var(--background)] dark:bg-[var(--dark-background)] p-6 border border-[var(--neutral)] dark:border-[var(--dark-neutral)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
    <!-- Left Column -->
    <div class="flex flex-col gap-4 w-full @if($product->image) md:col-span-3 @endif">
        <h1 class="text-3xl font-bold text-[var(--base)] dark:text-[var(--dark-base)]">{{ $product->name }}</h1>

        <!-- Product Image & Description -->
        <div class="flex flex-row w-full gap-4 items-start">
            @if ($product->image)
                <div class="relative overflow-hidden rounded-lg bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] flex-shrink-0">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="max-w-40 h-fit object-contain p-2">
                </div>
            @endif
            <div class="max-h-28 overflow-y-auto w-full">
                <article class="prose dark:prose-invert prose-sm max-w-none">
                    {!! $product->description !!}
                </article>
            </div>
        </div>

        <!-- Plan Selection -->
        @if ($product->availablePlans()->count() > 1)
            <x-form.select wire:model.live="plan_id"
                           class="text-[var(--inverted)] bg-[var(--primary)] hover:bg-[var(--primary)]/90 px-2.5 py-2.5 rounded-md w-full transition-colors"
                           name="plan_id"
                           label="Select a plan">
                @foreach ($product->availablePlans() as $availablePlan)
                    <option value="{{ $availablePlan->id }}">
                        {{ $availablePlan->name }} -
                        {{ $availablePlan->price() }}
                        @if ($availablePlan->price()->has_setup_fee)
                            + {{ $availablePlan->price()->formatted->setup_fee }} {{ __('product.setup_fee') }}
                        @endif
                    </option>
                @endforeach
            </x-form.select>
        @endif

        <!-- Configuration Options -->
        <div class="space-y-4 mb-4">
            @foreach ($product->configOptions as $configOption)
                @php
                    $showPriceTag = $configOption->children->filter(fn ($value) => !$value->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->is_free)->count() > 0;
                @endphp
                <x-form.configoption :config="$configOption" :name="'configOptions.' . $configOption->id" :showPriceTag="$showPriceTag" :plan="$plan">
                    @if ($configOption->type == 'select')
                        @foreach ($configOption->children as $configOptionValue)
                            <option value="{{ $configOptionValue->id }}">
                                {{ $configOptionValue->name }}
                                {{ $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available ? ' - ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                            </option>
                        @endforeach
                    @elseif ($configOption->type == 'radio')
                        @foreach ($configOption->children as $configOptionValue)
                            <div class="flex items-center gap-2">
                                <input type="radio" id="{{ $configOptionValue->id }}" name="{{ $configOption->id }}" wire:model.live="configOptions.{{ $configOption->id }}" value="{{ $configOptionValue->id }}" class="text-[var(--primary)] focus:ring-[var(--primary)] border-[var(--neutral)] dark:border-[var(--dark-neutral)]">
                                <label for="{{ $configOptionValue->id }}" class="text-[var(--muted)] dark:text-[var(--dark-inverted)]">
                                    {{ $configOptionValue->name }}
                                    {{ ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' - ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </x-form.configoption>
            @endforeach

            @foreach ($this->getCheckoutConfig() as $configOption)
                @php $configOption = (object) $configOption; @endphp
                <x-form.configoption :config="$configOption" :name="'checkoutConfig.' . $configOption->name">
                    @if ($configOption->type == 'select')
                        @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                            <option value="{{ $configOptionValue }}">{{ $configOptionValueName }}</option>
                        @endforeach
                    @elseif ($configOption->type == 'radio')
                        @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                            <div class="flex items-center gap-2">
                                <input type="radio" id="{{ $configOptionValue }}" name="{{ $configOption->name }}" wire:model.live="checkoutConfig.{{ $configOption->name }}" value="{{ $configOptionValue }}" class="text-[var(--primary)] focus:ring-[var(--primary)] border-[var(--neutral)] dark:border-[var(--dark-neutral)]">
                                <label for="{{ $configOptionValue }}" class="text-[var(--muted)] dark:text-[var(--dark-inverted)]">{{ $configOptionValueName }}</label>
                            </div>
                        @endforeach
                    @endif
                </x-form.configoption>
            @endforeach
        </div>
    </div>

    <!-- Right Column -->
    <div class="flex flex-col gap-3 w-full @if($product->image) md:col-span-1 @endif bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] p-4 rounded-md border border-[var(--neutral)] dark:border-[var(--dark-neutral)] h-fit">
        <h2 class="text-xl font-semibold text-[var(--base)] dark:text-[var(--dark-base)]">{{ __('product.order_summary') }}</h2>

        <div class="text-sm font-medium text-[var(--muted)] dark:text-[var(--dark-inverted)] flex justify-between">
            <span>{{ __('product.total_today') }}:</span>
            <span class="text-[var(--base)] dark:text-[var(--dark-base)]">{{ $total }}</span>
        </div>

        @if ($total->setup_fee && $plan->type == 'recurring')
            <div class="text-sm font-medium text-[var(--muted)] dark:text-[var(--dark-inverted)] flex justify-between">
                <span>{{ __('product.then_after_x', ['time' => $plan->billing_period . ' ' . trans_choice(__('services.billing_cycles.' . $plan->billing_unit), $plan->billing_period)]) }}:</span>
                <span class="text-[var(--base)] dark:text-[var(--dark-base)]">{{ $total->format($total->price - $total->setup_fee) }}</span>
            </div>
        @endif

        @if (($product->stock > 0 || !$product->stock) && $product->price()->available)
            <div class="mt-4">
                <button wire:click="checkout" wire:loading.attr="disabled" class="gradient-button w-full bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] hover:from-[var(--primary)]/90 hover:to-[var(--secondary)]/90 text-[var(--inverted)] font-medium py-3 px-6 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                    {{ __('product.checkout') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
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