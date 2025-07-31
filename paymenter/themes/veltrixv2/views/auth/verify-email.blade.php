<div
    class="mx-auto flex flex-col gap-4 mt-6 shadow-lg px-8 sm:px-16 py-12 bg-gradient-to-br from-primary-800 to-primary-900 rounded-2xl xl:max-w-md w-full transition-all duration-300 ease-in-out">
    <h1 class="text-3xl font-bold text-center text-gray-100">{{ __('auth.verification.notice') }}</h1>
    <p class="mt-2 text-center text-gray-100">{{ __('auth.verification.check_your_email') }}</p>

    <form class="flex flex-col gap-4 mt-6" wire:submit.prevent="submit" id="verify-email">
        <x-captcha :form="'verify-email'" />

        <p class="text-base text-gray-100">{{ __('auth.verification.not_received') }}</p>
        <x-button.primary class="w-full mt-4 py-3 rounded-full text-lg font-semibold tracking-wide transition-transform duration-200 transform hover:scale-105"
            type="submit">{{ __('auth.verification.request_another') }}</x-button.primary>
    </form>
</div>
{!! theme('custom_layout_css', '') !!}