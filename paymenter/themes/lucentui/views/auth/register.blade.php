<div class="bg-background-secondary/50 bg-background-secondary/50 backdrop-blur-md grid w-full max-w-6xl mx-auto overflow-hidden text-color-base rounded-2xl shadow-2xl md:grid-cols-2">
    <div class="hidden md:flex flex-col justify-between p-10 animated-gradient bg-gradient-to-br from-primary to-secondary">
        <div>
            <div class="mt-8 text-white">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight">
                    Manage Your Billing with Ease
                </h2>
                <p class="mt-4 text-2xl font-semibold text-white/90">Stay on Top of Your Payments</p>
                <p class="mt-3 text-white/80">
                    Access your invoices, update payment methods, and keep track of your subscriptions all in one place.
                </p>
            </div>
        </div>
        <p class="text-sm text-white/70">© {{ date('Y') }} {{ config('app.name') }} | All rights reserved.</p>
    </div>

    <div class="p-8 sm:p-12 form-fade-in">
        <form wire:submit.prevent="submit" id="register" class="w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">{{ __('auth.sign_up_title') }}</h1>
            </div>

            <div class="space-y-2 text-color-base">
                <x-form.input name="first_name" type="text" :label="__('general.input.first_name')"
                    :placeholder="__('general.input.first_name_placeholder')" wire:model="first_name" required />
                <x-form.input name="last_name" type="text" :label="__('general.input.last_name')"
                    :placeholder="__('general.input.last_name_placeholder')" wire:model="last_name" required />
                <x-form.input name="email" type="email" :label="__('general.input.email')"
                    :placeholder="__('general.input.email_placeholder')" required wire:model="email" divClass="col-span-2" />
                <x-form.password-strength name="password" :label="__('general.input.password')" :placeholder="__('general.input.password_placeholder')"
                    wire:model="password" required />
                <x-form.input name="password_confirm" type="password" :label="__('Confirm Password')"
                    :placeholder="__('Confirm your password')" wire:model="password_confirmation" required />

                {{-- Custom Properties Section --}}
                @if ($custom_properties->isNotEmpty())
                    <div class="relative flex items-center w-full my-8">
                        <div class="flex-grow border-t border-base"></div>
                        <span class="flex-shrink mx-4 text-xs font-medium uppercase text-base">{{ __('Additional Information') }}</span>
                        <div class="flex-grow border-t border-base"></div>
                    </div>
                    <div class="space-y-2">
                        <x-form.properties :custom_properties="$custom_properties" :properties="$properties" class="text-color-base form-input" />
                    </div>
                @endif
            </div>

            <div class="mt-4 mb-6">
                <x-captcha :form="'register'" />
            </div>

            <x-button.primary class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5" type="submit">
                {{ __('auth.sign_up') }}
            </x-button.primary>

            @if (config('settings.oauth_github') || config('settings.oauth_google') || config('settings.oauth_discord'))
            <div class="relative flex items-center w-full my-8">
                <div class="flex-grow border-t border-base-600"></div>
                <span class="flex-shrink mx-4 text-xs font-medium uppercase text-base">{{ __('auth.or_sign_in_with') }}</span>
                <div class="flex-grow border-t border-base-600"></div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 w-full">
                @foreach (['github', 'google', 'discord'] as $provider)
                    @if (config('settings.oauth_' . $provider))
                    <a href="{{ route('oauth.redirect', $provider) }}" class="flex items-center justify-center w-full py-2.5 border border-background bg-primary dark:bg-background rounded-lg text-white hover:bg-secondary hover:border-secondary transition-all space-x-2">
                        <img src="/assets/images/{{ $provider }}-light.svg" alt="{{ ucfirst($provider) }}" class="size-5 block dark:hidden">
                        <img src="/assets/images/{{ $provider }}-dark.svg" alt="{{ ucfirst($provider) }}" class="size-5 hidden dark:block">
                        <span class="text-sm font-medium">{{ ucfirst($provider) }}</span>
                    </a>
                    @endif
                @endforeach
            </div>
            @endif

            <div class="mt-10 text-sm text-center text-neutral-400">
                {{ __("auth.already_have_account") }}
                <a class="font-semibold text-primary transition-colors hover:text-secondary" href="{{ route('login') }}" wire:navigate>
                    {{ __('auth.sign_in') }}
                </a>
            </div>
        </form>
    </div>
</div>