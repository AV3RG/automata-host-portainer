<div class="bg-background-secondary/50 bg-background-secondary/50 backdrop-blur-md grid w-full max-w-6xl mx-auto overflow-hidden text-color-base rounded-2xl shadow-2xl md:grid-cols-2" style="margin: 200px auto;">
    <div class="hidden md:flex flex-col justify-between p-10 animated-gradient bg-gradient-to-br from-primary to-secondary">
        <div>
            <div class="mt-8 text-white">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight">
                    Reset Your Password
                </h2>
                <p class="mt-4 text-2xl font-semibold text-white/90">Secure Access Restoration</p>
                <p class="mt-3 text-white/80">
                    Enter your email address below and we'll send you a link to reset your password. Check your inbox for the reset instructions.
                </p>
            </div>
        </div>
        <p class="text-sm text-white/70">© {{ date('Y') }} {{ config('app.name') }} | All rights reserved.</p>
    </div>

    <div class="p-8 sm:p-12 form-fade-in">
        <form wire:submit="submit" id="reset" class="w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">{{ __('auth.reset_password') }}</h1>
            </div>

            <div class="space-y-8 text-color-base">
                <x-form.input name="email" type="text" :label="__('auth.input.email_label')" :placeholder="__('auth.input.email')" wire:model="email" required />
            </div>

            <div class="mt-8">
                <x-captcha :form="'reset'" />
            </div>

            <x-button.primary class="w-full py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5 mt-6" type="submit">
                {{ __('auth.reset_password') }}
            </x-button.primary>

            <div class="mt-10 text-sm text-center text-neutral-400">
                <a class="font-semibold text-primary transition-colors hover:text-secondary" href="{{ route('login') }}" wire:navigate>
                    {{ __('auth.already_have_account') }}
                </a>
            </div>
        </form>
    </div>
</div>