<div>
    <x-navigation.breadcrumb />
    <div class="px-2">
        <h4 class="text-2xl font-bold pb-3 text-fmh-text-primary">{{ __('account.credits') }}</h4>
        @if (Auth::user()->credits->count() > 0)
        <div class="flex flex-wrap gap-4 mb-8">
            @foreach (Auth::user()->credits as $credit)
            <div class="flex flex-col bg-fmh-form-bg w-fit rounded-lg px-5 p-3 items-center gap-1 shadow-fmh-form-shadow border border-fmh-border">
                <h5 class="text-lg font-bold text-fmh-text-primary">{{ $credit->currency->code }}</h5>
                <p class="@if($credit->amount == 0) text-red-500 @else text-green-500 @endif">{{ $credit->formattedAmount }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-fmh-text-secondary mb-8">{{ __('account.no_credit') }}</p>
        @endif

        <h4 class="text-xl font-bold pb-3 text-fmh-text-primary">{{ __('account.add_credit') }}</h4>

        <form wire:submit.prevent="addCredit">
            <div class="fmh-form-container rounded-3xl p-10 relative overflow-hidden transition-all duration-500">
                <!-- Form glow effect -->
                <div class="absolute -inset-1 bg-gradient-to-br from-indigo-500/10 via-transparent to-purple-500/10 rounded-3xl z-0"></div>
                <div class="relative z-10">
                    <!-- Currency and amount -->
                    <div class="grid grid-cols-2 gap-4">
                        <x-form.select name="currency" :label="__('account.input.currency')" wire:model="currency" required
                            class="fmh-neu-select w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300">
                            @foreach(\App\Models\Currency::all() as $currency)
                            <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                            @endforeach
                        </x-form.select>
                        <x-form.input x-mask:dynamic="$money($input, '.', '', 2)" name="amount" type="number"
                            :label="__('account.input.amount')" placeholder="Amount"
                            wire:model="amount" required
                            class="fmh-neu-input w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300" />
                    </div>

                    <div class="mt-4">
                        <x-form.select name="gateway" :label="__('product.payment_method')" wire:model="gateway" required
                            class="fmh-neu-select w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300">
                            @foreach(\App\Models\Gateway::all() as $gateway)
                            <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>

                    <x-button.primary type="submit" class="gradient-button w-full mt-4 fmh-signin-btn">
                        {{ __('account.add_credit') }}
                    </x-button.primary>
                </div>
            </div>
        </form>
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
    --border: {{ theme('border', 'hsl(220, 20%, 90%)') }};
    --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
    --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
    --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
    --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
    --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
    --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
    --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
    --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
    --dark-border: {{ theme('dark-border', 'hsl(220, 14%, 25%)') }};
}

/* Gradient Button */
.gradient-button {
    background: linear-gradient(to bottom right, var(--primary), var(--secondary));
    color: var(--inverted);
    transition: all 0.3s ease;
    border: none;
    font-weight: 600;
}

.gradient-button:hover {
    background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.dark .gradient-button:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
}

/* Form Input Styles */
.fmh-neu-input {
    background-color: var(--background);
    border: 1px solid var(--border);
    color: var(--base);
    transition: all 0.3s ease;
}

.dark .fmh-neu-input {
    background-color: var(--dark-background-secondary);
    border: 1px solid var(--dark-border);
    color: var(--dark-base);
}

.fmh-neu-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(var(--primary), 0.1);
    outline: none;
}

.dark .fmh-neu-input:focus {
    border-color: var(--dark-primary);
    box-shadow: 0 0 0 2px rgba(var(--dark-primary), 0.2);
}

/* Form Select Styles */
.fmh-neu-select {
    background-color: var(--background);
    border: 1px solid var(--border);
    color: var(--base);
    transition: all 0.3s ease;
}

.dark .fmh-neu-select {
    background-color: var(--dark-background-secondary);
    border: 1px solid var(--dark-border);
    color: var(--dark-base);
}

.fmh-neu-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(var(--primary), 0.1);
    outline: none;
}

.dark .fmh-neu-select:focus {
    border-color: var(--dark-primary);
    box-shadow: 0 0 0 2px rgba(var(--dark-primary), 0.2);
}

/* Placeholder text styles */
.placeholder-fmh-text-tertiary::placeholder {
    color: var(--muted);
    opacity: 0.7;
}

.dark .placeholder-fmh-text-tertiary::placeholder {
    color: var(--dark-muted);
    opacity: 0.7;
}

/* Form Container Styles */
.fmh-form-container {
    background-color: var(--background);
    border: 1px solid var(--border);
}

.dark .fmh-form-container {
    background-color: var(--dark-background-secondary);
    border: 1px solid var(--dark-border);
}

/* Button Styles */
.fmh-signin-btn {
    padding: 0.875rem 1.5rem;
    border-radius: 0.75rem;
}

/* Credit Box Styles */
.bg-fmh-form-bg {
    background-color: var(--background);
    border: 1px solid var(--border);
}

.dark .bg-fmh-form-bg {
    background-color: var(--dark-background-secondary);
    border: 1px solid var(--dark-border);
}

.border-fmh-border {
    border-color: var(--border);
}

.dark .border-fmh-border {
    border-color: var(--dark-border);
}

/* Text Color */
.text-fmh-text-primary {
    color: var(--base);
}

.dark .text-fmh-text-primary {
    color: var(--dark-base);
}

.text-fmh-text-secondary {
    color: var(--muted);
}

.dark .text-fmh-text-secondary {
    color: var(--dark-muted);
}

/* Shadow */
.shadow-fmh-form-shadow {
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
}

.dark .shadow-fmh-form-shadow {
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.2), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}

/* Transition effects */
.transition-all {
    transition-property: all;
}

.duration-300 {
    transition-duration: 300ms;
}
</style>
{!! theme('custom_layout_css', '') !!}