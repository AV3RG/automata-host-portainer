<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 rounded-2xl">
    <x-navigation.breadcrumb class="mb-6" />

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-9">
        {{ __('account.security') }}
    </h1>

    <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg mb-8">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('account.sessions') }}</h2>

        <div class="space-y-4"> 
            @foreach (Auth::user()->sessions->filter(fn ($session) => !$session->impersonating()) as $session)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4 border-b border-neutral-700 last:border-b-0">
                    <div>
                        <p class="text-lg font-semibold text-color-base mb-1">{{ $session->ip_address }}</p>
                        <p class="text-sm text-color-muted">{{ $session->formatted_device }} - {{ $session->last_activity->diffForHumans() }}</p>
                    </div>
                    <x-button.primary wire:click="logoutSession('{{ $session->id }}')" class="mt-3 sm:mt-0 px-5 py-2 text-sm">
                        {{ __('account.logout_sessions') }}
                    </x-button.primary>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg mb-8">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('account.change_password') }}</h2>
        <form wire:submit="changePassword">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
                <x-form.input divClass="md:col-span-2" name="current_password" type="password"
                    :label="__('account.input.current_password')"
                    :placeholder="__('account.input.current_password_placeholder')" wire:model="current_password"
                    required />
                <x-form.password-strength name="password" :label="__('general.input.password')" :placeholder="__('general.input.password_placeholder')"
                    wire:model="password" required />
                <x-form.input name="password_confirmation" type="password" :label="__('account.input.confirm_password')"
                    :placeholder="__('account.input.confirm_password_placeholder')" wire:model="password_confirmation"
                    required />
            </div>

            <div class="flex justify-end mt-6">
                <x-button.primary type="submit" class="py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5">
                    {{ __('account.change_password') }}
                </x-button.primary>
            </div>
        </form>
    </div>

    <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('account.two_factor_authentication') }}</h2>

        @if ($twoFactorEnabled)
            <p class="text-base text-color-muted mb-4">{{ __('account.two_factor_authentication_enabled') }}</p>
            <x-button.primary wire:click="disableTwoFactor" class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5">
                {{ __('Disable two factor authentication') }}
            </x-button.primary>
        @else
            <p class="text-base text-color-muted mb-4">{{ __('account.two_factor_authentication_description') }}</p>
            <x-button.primary wire:click="enableTwoFactor" class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5">
                {{ __('account.two_factor_authentication_enable') }}
            </x-button.primary>

            @if ($showEnableTwoFactor)
                <x-modal :title="__('account.two_factor_authentication_enable')" open="true">
                    <p class="text-color-base mb-4">{{ __('account.two_factor_authentication_enable_description') }}</p>
                    <div class="flex flex-col items-center mt-4 mb-6">
                        <img src="{{ $twoFactorData['image'] }}" alt="QR code" class="w-64 h-64 border border-neutral-700 rounded-lg p-2 bg-background-tertiary" />
                        <p class="text-color-muted mt-4 text-sm text-center font-mono break-all"> 
                            {{ __('account.two_factor_authentication_secret') }}<br />
                            <span class="text-color-base text-base font-bold">{{ $twoFactorData['secret'] }}</span>
                        </p>
                    </div>
                    <form wire:submit.prevent="enableTwoFactor">
                        <x-form.input name="two_factor_code" type="text"
                            :label="__('account.input.two_factor_code')"
                            :placeholder="__('account.input.two_factor_code_placeholder')" wire:model="twoFactorCode"
                            required />
                        <x-button.primary class="w-full mt-4 py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5" type="submit">
                            {{ __('account.two_factor_authentication_enable') }}
                        </x-button.primary>
                    </form>
                    <x-slot name="closeTrigger">
                        <button @click="document.location.reload()" class="text-color-muted hover:text-color-base transition-colors duration-200">
                            <x-ri-close-fill class="size-6" />
                        </button>
                    </x-slot>
                </x-modal>
            @endif
        @endif
    </div>
</div>