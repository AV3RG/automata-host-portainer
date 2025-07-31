<div class="flex flex-col gap-4">
    {{ __('services.cancel_are_you_sure') }}

    <x-form.select name="type" label="{{ __('services.cancel_type') }}" required wire:model="type">
        <option value="end_of_period">{{ __('services.cancel_end_of_period') }}</option>
        <option value="immediate">{{ __('services.cancel_immediate') }}</option>
    </x-form.select>

    <x-form.textarea name="reason" label="{{ __('services.cancel_reason') }}" required wire:model="reason" />

    <!-- Show you'll lose data warning if immediate cancellation is selected -->
    <template x-if="$wire.type === 'immediate'">
        <div class="bg-orange-700 text-white p-4 rounded">
            {{ __('services.cancel_immediate_warning') }}
        </div>
    </template>

    <x-button.danger wire:confirm="Are you sure?" wire:click="cancelService" class="gradient-button">
        {{ __('services.cancel') }}
    </x-button.danger>
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