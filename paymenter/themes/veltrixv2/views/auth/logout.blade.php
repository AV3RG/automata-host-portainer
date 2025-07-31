<div>
    <button wire:click="logout"
        class="flex flex-row items-center p-3 gap-2 text-sm font-semibold text-error/80 hover:text-error bg-gradient-to-br from-primary-800 to-primary-900 hover:bg-primary-700 rounded-full transition-all duration-300 ease-in-out">
        {{ __('auth.logout') }}
    </button>
</div>
{!! theme('custom_layout_css', '') !!}