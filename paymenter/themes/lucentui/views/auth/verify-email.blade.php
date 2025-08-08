<div class="bg-background-secondary/50 bg-background-secondary/50 backdrop-blur-md grid w-full max-w-6xl mx-auto overflow-hidden text-color-base rounded-2xl shadow-2xl md:grid-cols-2" style="margin: 200px auto;">
    <div class="hidden md:flex flex-col justify-between p-10 animated-gradient bg-gradient-to-br from-primary to-secondary">
        <div>
            <div class="mt-8 text-white">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight">
                    Verify Your Email to Get Started!
                </h2>
                <p class="mt-4 text-2xl font-semibold text-white/90">Your Gateway to Best Service!</p>
                <p class="mt-3 text-white/80">
                    We've sent a verification link to your email address. Please check your inbox and click the link to activate your account. This ensures the security and integrity of your experience.
                </p>
            </div>
        </div>
        <p class="text-sm text-white/70">© {{ date('Y') }} {{ config('app.name') }} | All rights reserved.</p>
    </div>

    <div class="p-8 sm:p-12 form-fade-in">
        <form wire:submit.prevent="submit" id="verify-email" class="w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">{{ __('auth.verification.notice') }}</h1>
                <p class="mt-2 text-neutral-400">{{ __('auth.verification.check_your_email') }}</p>
            </div>

            <div class="space-y-8 text-color-base">
                </div>

            <p class="text-base text-left text-neutral-400 mb-4">{{ __('auth.verification.not_received') }}</p>

            <x-captcha :form="'verify-email'" />

            <x-button.primary class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5 mt-4" type="submit">
                {{ __('auth.verification.request_another') }}
            </x-button.primary>
        </form>
    </div>
</div>