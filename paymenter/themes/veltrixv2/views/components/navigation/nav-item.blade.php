<div class="px-4 py-6 flex flex-col gap-2">
    <!-- Mobile Navigation Section -->
    <div class="block md:hidden">
        @foreach (\App\Classes\Navigation::getLinks() as $nav)
            @include('components.nav-item', ['nav' => $nav])
            @isset($nav['separator'])
                <div class="h-px w-full bg-neutral"></div>
            @endisset
        @endforeach
    </div>

    <!-- Dashboard Navigation Section -->
    <div class="flex flex-col gap-2">
        @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
            @include('components.nav-item', ['nav' => $nav])
            @isset($nav['separator'])
                <div class="h-px w-full bg-neutral"></div>
            @endisset
        @endforeach

        <!-- Language and Currency Switcher -->
        <div class="flex flex-row items-center mt-4 justify-between md:hidden">
            <x-dropdown>
                <x-slot:trigger>
                    <div class="flex flex-col">
                        <span class="text-sm text-base font-semibold text-nowrap">
                            {{ strtoupper(app()->getLocale()) }}
                            <span class="text-base/50 font-semibold">|</span>
                            {{ session('currency', config('settings.default_currency')) }}
                        </span>
                    </div>
                </x-slot:trigger>
                <x-slot:content>
                    <strong class="block p-2 text-xs font-semibold uppercase text-base/50">Language</strong>
                    <livewire:components.language-switch />
                    <livewire:components.currency-switch />
                </x-slot:content>
            </x-dropdown>

            <!-- Theme Toggle -->
            <x-theme-toggle />
        </div>
    </div>
</div>
{!! theme('custom_layout_css', '') !!}