<div>
    <div>
        <x-navigation.breadcrumb />
        <div class="px-2">
            <div class="fmh-form-container rounded-3xl p-10 relative overflow-hidden transition-all duration-500">
                <!-- Form glow effect -->
                <div class="absolute -inset-1 bg-gradient-to-br from-indigo-500/10 via-transparent to-purple-500/10 rounded-3xl z-0"></div>
                <div class="relative z-10">
                    <div class="grid grid-cols-2 gap-4">
                        <x-form.input
                            name="first_name"
                            type="text"
                            :label="__('general.input.first_name')"
                            :placeholder="__('general.input.first_name_placeholder')"
                            wire:model="first_name"
                            required
                            dirty
                            class="fmh-neu-input w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300"
                        />
                        <x-form.input
                            name="last_name"
                            type="text"
                            :label="__('general.input.last_name')"
                            :placeholder="__('general.input.last_name_placeholder')"
                            wire:model="last_name"
                            required
                            dirty
                            class="fmh-neu-input w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300"
                        />
                        <x-form.input
                            name="email"
                            type="email"
                            :label="__('general.input.email')"
                            :placeholder="__('general.input.email_placeholder')"
                            required
                            wire:model="email"
                            dirty
                            class="fmh-neu-input w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300"
                        />
                        <x-form.properties
                            :custom_properties="$custom_properties"
                            :properties="$properties"
                            dirty
                            class="fmh-neu-input w-full px-5 py-3.5 rounded-xl text-fmh-text-primary placeholder-fmh-text-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-300"
                        />
                    </div>
                    <x-button.primary wire:click="submit" class="gradient-button w-full mt-4 fmh-signin-btn">
                        {{ __('general.update') }}
                    </x-button.primary>
                </div>
            </div>
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

        /* Form Input Styles */
        .fmh-neu-input {
            background-color: var(--background-secondary);
            border: 1px solid var(--neutral);
            color: var(--base);
        }

        .fmh-neu-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(var(--primary), 0.2);
        }

        /* Form Container Styles */
        .fmh-form-container {
            background-color: var(--background);
            border: 1px solid var(--neutral);
        }

        .dark .fmh-form-container {
            background-color: var(--dark-background);
        }

        /* Button Styles */
        .fmh-signin-btn {
            background-color: var(--primary);
            color: var(--inverted);
        }

        .fmh-signin-btn:hover {
            background-color: var(--dark-primary);
        }
    </style>

    {!! theme('custom_layout_css', '') !!}
</div>
