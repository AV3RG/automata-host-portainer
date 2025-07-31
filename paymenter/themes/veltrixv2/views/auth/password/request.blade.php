<div class="w-full max-w-md mx-auto bg-white dark:bg-gray-950 rounded-3xl overflow-hidden shadow-2xl border-0 dark:border dark:border-gray-800/50">
    <div class="relative p-12 flex flex-col justify-center bg-white dark:bg-gray-950">
        <form class="w-full" wire:submit="submit" id="reset">
            <!-- Logo and Header -->
            <div class="flex flex-col items-center mb-12">
                <x-logo class="h-16 w-16 text-indigo-600 dark:text-indigo-400" />
                <h1 class="text-3xl font-bold text-center mt-6 text-gray-800 dark:text-white">
                    {{ __('auth.reset_password') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Enter your email to reset your password
                </p>
            </div>

            <!-- Email Input -->
            <div class="space-y-6">
                <x-form.input
                    name="email"
                    type="email"
                    :label="__('auth.input.email_label')"
                    :placeholder="__('auth.input.email')"
                    wire:model="email"
                    hideRequiredIndicator
                    required
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-5 py-4 rounded-xl text-lg"
                />
            </div>

            <!-- CAPTCHA -->
            <x-captcha :form="'reset'" class="mt-8" />

            <!-- Submit Button -->
            <x-button.primary class="w-full mt-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-500 dark:to-purple-500 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl transition-all duration-300 hover:shadow-xl text-lg shadow-md hover:shadow-indigo-500/30 dark:hover:shadow-indigo-500/20">
                <div class="flex items-center justify-center space-x-2">
                    <span>{{ __('auth.reset_password') }}</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </x-button.primary>

            <!-- Back to Login Link -->
            <div class="text-center mt-12 text-lg text-gray-600 dark:text-gray-400">
                Remember your password? 
                <a href="{{ route('login') }}" class="hover:underline ml-2 font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" wire:navigate>
                    Sign in
                </a>
            </div>
        </form>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="fmh-particle bg-indigo-500/10 dark:bg-indigo-400/10" style="top: 15%; left: 70%; width: 8px; height: 8px;"></div>
            <div class="fmh-particle bg-purple-500/10 dark:bg-purple-400/10" style="top: 75%; left: 30%; width: 6px; height: 6px;"></div>
        </div>
    </div>
</div>
{!! theme('custom_layout_css', '') !!}