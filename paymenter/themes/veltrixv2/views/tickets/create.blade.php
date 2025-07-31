<div class="bg-primary-800 p-6 rounded-xl shadow-lg">
    <h1 class="text-2xl font-semibold text-white mb-4">{{ __('ticket.create_ticket') }}</h1>

    <form wire:submit.prevent="create">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Subject field -->
            <x-form.input
                wire:model="subject"
                label="{{ __('ticket.subject') }}"
                name="subject"
                required
            />

            <!-- Department selection -->
            @if (count($departments) > 0)
                <x-form.select
                    wire:model="department"
                    label="{{ __('ticket.department') }}"
                    name="department"
                    required
                >
                    <option value="">{{ __('ticket.select_department') }}</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </x-form.select>
            @endif

            <!-- Priority selection -->
            <x-form.select
                wire:model="priority"
                label="{{ __('ticket.priority') }}"
                name="priority"
                required
            >
                <option value="">{{ __('ticket.select_priority') }}</option>
                <option value="low" selected>{{ __('ticket.low') }}</option>
                <option value="medium">{{ __('ticket.medium') }}</option>
                <option value="high">{{ __('ticket.high') }}</option>
            </x-form.select>

            <!-- Service selection -->
            <x-form.select
                wire:model="service"
                label="{{ __('ticket.service') }}"
                name="service"
            >
                <option value="">{{ __('ticket.select_service') }}</option>
                @foreach ($services as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->product->name }} ({{ ucfirst($product->status) }})
                        @if ($product->expires_at)
                            - {{ $product->expires_at->format('Y-m-d') }}
                        @endif
                    </option>
                @endforeach
            </x-form.select>

            <!-- Message editor - full width -->
            <div class="col-span-1 md:col-span-2 mt-2">
                <div wire:ignore class="mb-2">
                    <textarea id="editor" placeholder="Initial message" class="w-full"></textarea>
                    <x-easymde-editor />
                </div>

                <!-- Submit button -->
                <div class="flex justify-end mt-4">
                    <x-button.primary class="gradient-button">
                        {{ __('ticket.create') }}
                    </x-button.primary>
                </div>
            </div>
        </div>
    </form>
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