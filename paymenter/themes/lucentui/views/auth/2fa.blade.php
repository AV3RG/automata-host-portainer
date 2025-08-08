<div class="bg-background-secondary/50 bg-background-secondary/50 backdrop-blur-md grid w-full max-w-6xl mx-auto overflow-hidden text-color-base rounded-2xl shadow-2xl md:grid-cols-2" style="margin: 200px auto;">
    <div class="hidden md:flex flex-col justify-between p-10 animated-gradient bg-gradient-to-br from-primary to-secondary">
        <div>
            <div class="mt-8 text-white">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight">
                    Secure Your Account with Next-Level Protection!
                </h2>
                <p class="mt-4 text-2xl font-semibold text-white/90">Your Safety, Our Priority!</p>
                <p class="mt-3 text-white/80">
                    We're committed to keeping your account safe. Two-Factor Authentication adds an extra layer of security, ensuring only you can access your data. Verify and proceed with confidence!
                </p>
            </div>
        </div>
        <p class="text-sm text-white/70">© {{ date('Y') }} {{ config('app.name') }} | All rights reserved.</p>
    </div>

    <div class="p-8 sm:p-12 form-fade-in">
        <form wire:submit="verify" class="w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">{{ __('auth.verify_2fa') }}</h1>
                <p class="mt-2 text-neutral-400">{{ __('Enter the code from your authenticator app to proceed.') }}</p>
            </div>

            <div class="space-y-8 text-color-base">
                <div class="form-input-container">
                    <input
                        name="code"
                        type="text"
                        placeholder=" "
                        wire:model="code"
                        required
                        class="text-color-base form-input" />
                    <label for="code" class="form-input-label">{{ __('account.input.two_factor_code') }}</label>
                </div>
            </div>

            <x-button.primary class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5 mt-8" type="submit">
                {{ __('auth.verify') }}
            </x-button.primary>

            <div class="mt-10 text-sm text-center text-neutral-400">
                <a class="font-semibold text-primary transition-colors hover:text-secondary" href="{{ route('register') }}" wire:navigate>
                    {{ __('auth.dont_have_account') }}
                </a>
            </div>
        </form>
    </div>
</div>