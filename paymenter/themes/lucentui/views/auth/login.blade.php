<div class="bg-background-secondary/50 bg-background-secondary/50 backdrop-blur-md grid w-full max-w-6xl mx-auto overflow-hidden text-color-base rounded-2xl shadow-2xl md:grid-cols-2" style="margin: 50px auto;">
    <div class="hidden md:flex flex-col justify-between p-10 animated-gradient bg-gradient-to-br from-primary to-secondary">
        <div>
            <div class="mt-8 text-white">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight">
                    Your Journey Starts Here
                </h2>
                <p class="mt-4 text-2xl font-semibold text-white/90">
                    Access 24/7 expert support and intelligent performance optimization.
                </p>
                <p class="mt-3 text-white/80">
                    Sign up to unlock full access, personalized tools, and a smarter way to work
                </p>
            </div>
        </div>
        <p class="text-sm text-white/70">© {{ date('Y') }} {{ config('app.name') }} | All rights reserved.</p>
    </div>

    <div class="p-8 sm:p-12 form-fade-in">
        <form wire:submit="submit" id="login" class="w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">{{ __('auth.sign_in_title') }}</h1>
                <p class="mt-2 text-neutral-400">{{ __('auth.already_have_account') }}</p>
            </div>

            <x-form.input name="email" type="email" :label="__('general.input.email')"
                :placeholder="__('general.input.email_placeholder')" wire:model="email" hideRequiredIndicator required />
            <x-form.input name="password" type="password" :label="__('general.input.password')"
                :placeholder="__('general.input.password_placeholder')" required hideRequiredIndicator wire:model="password" />

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4 space-y-3 sm:space-y-0 sm:space-x-5">
                <x-form.checkbox name="remember" label="Remember me" wire:model="remember" />

                <a class="text-sm font-medium text-primary transition-colors hover:text-secondary" href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            </div>

            <div class="mt-4 mb-6">
                <x-captcha :form="'login'" />
            </div>

            <x-button.primary class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5 mt-5" type="submit">
                {{ __('auth.sign_in') }}
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

            @if(!config('settings.registration_disabled', false))
            <div class="mt-10 text-sm text-center text-neutral-400">
                {{ __("auth.dont_have_account") }}
                <a class="font-semibold text-primary transition-colors hover:text-secondary" href="{{ route('register') }}" wire:navigate>
                    {{ __('auth.sign_up') }}
                </a>
            </div>
            @endif
        </form>
    </div>
</div>