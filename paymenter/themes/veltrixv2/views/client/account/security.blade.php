<div>
    <div>
        <x-navigation.breadcrumb />
        <div class="px-2">
            <!-- Sessions -->
            <h5 class="text-lg font-bold pb-3 pt-4 text-gray-900 dark:text-white">{{ __('account.sessions') }}</h5>
            @foreach (Auth::user()->sessions->filter(fn ($session) => !$session->impersonating()) as $session)
            <div class="flex flex-row items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ $session->ip_address }} -
                        {{ $session->last_activity->diffForHumans() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $session->formatted_device }}</p>
                </div>
                <x-button.primary wire:click="logoutSession('{{ $session->id }}')" class="text-sm !w-fit !px-3 !py-1.5">
                    {{ __('account.logout_sessions') }}
                </x-button.primary>
            </div>
            @endforeach

            <!-- Change password -->
            <h5 class="text-lg font-bold pb-3 pt-10 text-gray-900 dark:text-white">{{ __('account.change_password') }}</h5>
            <form wire:submit="changePassword">
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input
                        divClass="col-span-2"
                        name="current_password"
                        type="password"
                        :label="__('account.input.current_password')"
                        :placeholder="__('account.input.current_password_placeholder')"
                        wire:model="current_password"
                        required
                        class="modern-input"
                    />
                    <x-form.input
                        name="password"
                        type="password"
                        :label="__('account.input.new_password')"
                        :placeholder="__('account.input.new_password_placeholder')"
                        wire:model="password"
                        required
                        class="modern-input"
                    />
                    <x-form.input
                        name="password_confirmation"
                        type="password"
                        :label="__('account.input.confirm_password')"
                        :placeholder="__('account.input.confirm_password_placeholder')"
                        wire:model="password_confirmation"
                        required
                        class="modern-input"
                    />
                </div>

                <x-button.primary type="submit" class="gradient-button w-full mt-4 modern-button">
                    {{ __('account.change_password') }}
                </x-button.primary>
            </form>

            <!-- Two factor authentication -->
            <h5 class="text-lg font-bold pb-3 pt-10 text-gray-900 dark:text-white">{{ __('account.two_factor_authentication') }}</h5>
            <div class="fmh-form-container rounded-xl p-6 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                @if ($twoFactorEnabled)
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('account.two_factor_authentication_enabled') }}</p>
                <x-button.primary wire:click="disableTwoFactor" class="gradient-button w-full mt-4 modern-button danger">
                    {{ __('Disable two factor authentication') }}
                </x-button.primary>
                @else
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('account.two_factor_authentication_description') }}</p>
                <x-button.primary wire:click="enableTwoFactor" class="gradient-button w-full mt-4 modern-button">
                    {{ __('account.two_factor_authentication_enable') }}
                </x-button.primary>
                @endif

                @if ($showEnableTwoFactor)
                <x-modal :title="__('account.two_factor_authentication_enable')" open="true">
                    <div class="fmh-form-container rounded-xl p-6 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <p class="text-gray-700 dark:text-gray-300">{{ __('account.two_factor_authentication_enable_description') }}</p>
                        <div class="flex flex-col items-center mt-4">
                            <img src="{{ $twoFactorData['image'] }}" alt="QR code" class="w-64 h-64" />
                            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm text-center">
                                {{ __('account.two_factor_authentication_secret') }}<br />{{ $twoFactorData['secret'] }}</p>
                        </div>
                        <form wire:submit.prevent="enableTwoFactor">
                            <x-form.input
                                divClass="mt-8"
                                name="two_factor_code"
                                type="text"
                                :label="__('account.input.two_factor_code')"
                                :placeholder="__('account.input.two_factor_code_placeholder')"
                                wire:model="twoFactorCode"
                                required
                                class="modern-input"
                            />
                            <x-button.primary class="gradient-button w-full mt-4 modern-button" type="submit">
                                {{ __('account.two_factor_authentication_enable') }}
                            </x-button.primary>
                        </form>
                        <x-slot name="closeTrigger">
                            <button @click="document.location.reload()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                <x-ri-close-fill class="size-6" />
                            </button>
                        </x-slot>
                    </div>
                </x-modal>
                @endif
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

    /* Modern Input Styles */
    .modern-input {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--neutral);
        background-color: var(--background-secondary);
        color: var(--base);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .modern-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(var(--primary), 0.2);
        outline: none;
    }

    .dark .modern-input {
        background-color: var(--dark-background-secondary);
        border-color: var(--dark-neutral);
    }

    .dark .modern-input:focus {
        border-color: var(--dark-primary);
    }

    /* Modern Button Styles */
    .modern-button {
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        background-color: var(--primary);
        color: var(--inverted);
        font-weight: 500;
        transition: background-color 0.2s ease;
    }

    .modern-button:hover {
        background-color: var(--dark-primary);
    }

    .modern-button.danger {
        background-color: #e3342f;
    }

    .modern-button.danger:hover {
        background-color: #cc1f1a;
    }

    /* Form Container Styles */
    .fmh-form-container {
        border-radius: 0.75rem;
        padding: 1.5rem;
        border: 1px solid var(--neutral);
        background-color: var(--background);
    }

    .dark .fmh-form-container {
        background-color: var(--dark-background);
        border-color: var(--dark-neutral);
    }
    </style>
    {!! theme('custom_layout_css', '') !!}
</div>
