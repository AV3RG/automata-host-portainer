@if(theme('header_style', 'default') === 'default')
    <!-- Default Header -->
    <nav class="w-full md:px-4 bg-background-secondary border-b border-neutral md:h-14 flex md:flex-row flex-col justify-between fixed top-0 z-20">
        <div x-data="{ slideOverOpen: false, shortcutOpen: false }"
             x-init="$watch('slideOverOpen', value => { document.documentElement.style.overflow = value ? 'hidden' : '' })"
             class="relative z-50 w-full h-auto">
            <div class="flex flex-row items-center justify-between h-14 px-4">
                <div class="flex flex-row items-center">
                    <button @click="slideOverOpen=true" class="flex md:hidden w-10 h-10 items-center justify-center rounded-lg hover:bg-neutral transition">
                        <x-ri-menu-fill class="size-5 text-base" />
                    </button>
                    <a href="{{ route('home') }}" class="flex flex-row items-center" wire:navigate>
                        <x-logo class="h-10" />
                        @if (!theme('hidelogotext', false))
                            <span class="text-xl text-base ml-2 font-bold">{{ config('app.name') }}</span>
                        @endif
                    </a>

                    <div class="md:flex hidden flex-row ml-7">
                        @foreach (\App\Classes\Navigation::getLinks() as $nav)
                            @if (isset($nav['children']) && count($nav['children']) > 0)
                                <div class="relative">
                                    <x-dropdown>
                                        <x-slot:trigger>
                                            <div class="flex flex-col">
                                                <span class="flex flex-row items-center p-3 text-sm font-semibold text-base hover:text-base/80">
                                                    {{ $nav['name'] }}
                                                </span>
                                            </div>
                                        </x-slot:trigger>
                                        <x-slot:content>
                                            @foreach ($nav['children'] as $child)
                                                <x-navigation.link
                                                    :href="route($child['route'], $child['params'] ?? null)"
                                                    :spa="isset($child['spa']) ? $nav['spa'] : true">
                                                    {{ $child['name'] }}
                                                </x-navigation.link>
                                            @endforeach
                                        </x-slot:content>
                                    </x-dropdown>
                                </div>
                            @else
                                <x-navigation.link
                                    :href="route($nav['route'], $nav['params'] ?? null)"
                                    :spa="isset($nav['spa']) ? $nav['spa'] : true"
                                    class="flex items-center p-3 text-base hover:text-base/80">
                                    {{ $nav['name'] }}
                                </x-navigation.link>
                            @endif
                        @endforeach
                    </div>
                </div>
@php
    // Map language code to country code (ISO 3166-1 alpha-2)
    $localeToCountry = [
        'en' => 'us',
        'fr' => 'fr',
        'es' => 'es',
        'de' => 'de',
        'it' => 'it',
        'jp' => 'jp',
        'pt' => 'pt',
        'br' => 'br',
        'ru' => 'ru',
        'zh' => 'cn',
        'kr' => 'kr',
        'nl' => 'nl',
        'pl' => 'pl',
        'tr' => 'tr',
        'ar' => 'sa',
        'sv' => 'se',
        'no' => 'no',
        'da' => 'dk',
        'fi' => 'fi',
        'gr' => 'gr',
        'he' => 'il',
        'hi' => 'in',
        'th' => 'th',
        'vi' => 'vn',
        'id' => 'id',
        'ms' => 'my',
        'uk' => 'ua',
        'cs' => 'cz',
        'sk' => 'sk',
        'hu' => 'hu',
        'ro' => 'ro',
        'bg' => 'bg',
        'hr' => 'hr',
        'sl' => 'si',
        'et' => 'ee',
        'lv' => 'lv',
        'lt' => 'lt',
    ];

    $locale = app()->getLocale();
    $countryCode = $localeToCountry[$locale] ?? null;
    $flagUrl = $countryCode
        ? "https://flagcdn.com/w20/{$countryCode}.png"
        : 'https://flagcdn.com/w20/un.png'; // fallback (e.g. globe)
@endphp

<div class="flex flex-row items-center space-x-4">
    <div class="items-center hidden md:flex space-x-2">
        <x-dropdown>
            <x-slot:trigger>
                <div class="flex flex-row items-center space-x-2 cursor-pointer select-none">
                    {{-- Flag image with language code and currency --}}
                    <span class="text-sm font-semibold text-base flex items-center">
                        <img src="{{ $flagUrl }}" alt="flag" class="w-5 h-auto mr-2 rounded-sm border border-base/20" />
                        {{ strtoupper($locale) }} <span class="text-base/50 mx-1">|</span> {{ session('currency', config('settings.default_currency')) }}
                    </span>
                </div>
            </x-slot:trigger>
            <x-slot:content>
                <strong class="block p-2 text-xs font-semibold uppercase text-base/50">Language</strong>
                <livewire:components.language-switch />
                <livewire:components.currency-switch />
            </x-slot:content>
        </x-dropdown>
        <x-theme-toggle />
    </div>

    <livewire:components.cart />

    @if(auth()->check())
        <x-dropdown>
            <x-slot:trigger>
                <img src="{{ auth()->user()->avatar }}" class="size-8 rounded-full border border-neutral bg-background" alt="avatar" />
            </x-slot:trigger>
            <x-slot:content>
                <div class="flex flex-col p-2">
                    <span class="text-sm text-base">{{ auth()->user()->name }}</span>
                    <span class="text-sm text-base">{{ auth()->user()->email }}</span>
                </div>
                @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                    <x-navigation.link :href="route($nav['route'], $nav['params'] ?? null)" :spa="isset($nav['spa']) ? $nav['spa'] : true">
                        {{ $nav['name'] }}
                    </x-navigation.link>
                @endforeach
                <livewire:auth.logout />
            </x-slot:content>
        </x-dropdown>
    @else
        <div class="flex flex-row space-x-4">
            <x-navigation.link :href="route('login')" class="text-base hover:text-base/80">
                {{ __('navigation.login') }}
            </x-navigation.link>
            @if(!config('settings.registration_disabled', false))
                <x-navigation.link :href="route('register')">
                    <x-button.primary class="bg-gray-600 hover:bg-gray-700 text-white">
                        {{ __('navigation.register') }}
                    </x-button.primary>
                </x-navigation.link>
            @endif
        </div>
    @endif
</div>

            <!-- Shortcut Help Menu -->
            <div x-show="shortcutOpen" x-transition class="fixed top-0 right-0 mt-16 mr-4 bg-background-secondary text-base shadow-lg p-4 rounded-md z-50 border border-neutral">
                <h3 class="text-lg font-bold">Keyboard Shortcuts</h3>
                <ul class="space-y-2 mt-2">
                    <li><span class="font-semibold">Account:</span> <kbd class="bg-neutral px-2 py-1 rounded">Alt + A</kbd></li>
                    <li><span class="font-semibold">Tickets:</span> <kbd class="bg-neutral px-2 py-1 rounded">Alt + T</kbd></li>
                    <li><span class="font-semibold">Dashboard:</span> <kbd class="bg-neutral px-2 py-1 rounded">Alt + D</kbd></li>
                    <li><span class="font-semibold">Logout:</span> <kbd class="bg-neutral px-2 py-1 rounded">Alt + L</kbd></li>
                    <li><span class="font-semibold">Home:</span> <kbd class="bg-neutral px-2 py-1 rounded">Alt + H</kbd></li>
                </ul>
                <button @click="shortcutOpen = false" class="mt-4 bg-red-500/90 text-white rounded-md px-4 py-2">Close</button>
            </div>
            <template x-teleport="body">
                <div x-show="slideOverOpen" @keydown.window.escape="slideOverOpen=false" class="relative z-[99]">
                    <div x-show="slideOverOpen" x-transition.opacity.duration.600ms @click="slideOverOpen = false" class="fixed inset-0 bg-primary/20"></div>
                    <div class="fixed inset-0 overflow-hidden">
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="fixed inset-y-0 left-0 flex max-w-full pr-44">
                                <div x-show="slideOverOpen"
                                     @click.away="slideOverOpen = false"
                                     x-transition:enter="transform transition ease-in-out duration-500"
                                     x-transition:enter-start="-translate-x-full"
                                     x-transition:enter-end="translate-x-0"
                                     x-transition:leave="transform transition ease-in-out duration-500"
                                     x-transition:leave-start="translate-x-0"
                                     x-transition:leave-end="-translate-x-full"
                                     class="w-screen max-w-full">
                                    <div class="flex flex-col h-full bg-background-secondary border-r border-neutral shadow-lg">
                                        <div class="px-4 sm:px-5">
                                            <div class="flex items-center justify-between pb-1">
                                                <div class="flex flex-row items-center justify-between h-14 px-4">
                                                    <a href="{{ route('home') }}" class="flex flex-row items-center" wire:navigate>
                                                        <x-logo class="h-10" />
                                                        <span class="text-xl text-base ml-2 font-bold">{{ config('app.name') }}</span>
                                                    </a>
                                                </div>
                                                <div class="flex items-center ml-3">
                                                    <button @click="slideOverOpen=false" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-neutral transition">
                                                        <x-ri-close-fill class="size-5 text-base" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="relative flex-1 px-4 mt-5 sm:px-5">
                                            <div class="absolute inset-0 px-4 sm:px-5">
                                                <div class="relative h-full overflow-hidden">
                                                    <x-navigation.sidebar-links />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </nav>

@elseif(theme('header_style', 'default') === 'centered')
    <!-- Centered Header -->
    <nav class="w-full md:px-4 bg-background-secondary border-b border-neutral md:h-14 flex flex-row items-center fixed top-0 z-20">
        <div class="flex flex-row items-center flex-1">
            <!-- Left spacer -->
        </div>
        <div class="flex flex-row items-center justify-center">
            <a href="{{ route('home') }}" class="flex flex-row items-center mr-8" wire:navigate>
                <x-logo class="h-10" />
                <span class="text-xl text-base ml-2 font-bold">{{ config('app.name') }}</span>
            </a>
            <div class="flex flex-row items-center">
                @foreach (\App\Classes\Navigation::getLinks() as $nav)
                    @if (isset($nav['children']) && count($nav['children']) > 0)
                        <div class="relative px-2">
                            <x-dropdown>
                                <x-slot:trigger>
                                    <div class="flex flex-col">
                                        <span class="flex flex-row items-center py-3 px-2 text-sm font-semibold text-base hover:text-base/80">
                                            {{ $nav['name'] }}
                                        </span>
                                    </div>
                                </x-slot:trigger>
                                <x-slot:content>
                                    @foreach ($nav['children'] as $child)
                                        <x-navigation.link
                                            :href="route($child['route'], $child['params'] ?? null)"
                                            :spa="isset($child['spa']) ? $nav['spa'] : true">
                                            {{ $child['name'] }}
                                        </x-navigation.link>
                                    @endforeach
                                </x-slot:content>
                            </x-dropdown>
                        </div>
                    @else
                        <x-navigation.link
                            :href="route($nav['route'], $nav['params'] ?? null)"
                            :spa="isset($nav['spa']) ? $nav['spa'] : true"
                            class="px-2 flex items-center py-3 text-base hover:text-base/80">
                            {{ $nav['name'] }}
                        </x-navigation.link>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="flex flex-row items-center justify-end flex-1">
            <livewire:components.cart />
            @if(auth()->check())
                <x-dropdown>
                    <x-slot:trigger>
                        <img src="{{ auth()->user()->avatar }}" class="size-8 rounded-full border border-neutral bg-background" alt="avatar" />
                    </x-slot:trigger>
                    <x-slot:content>
                        <div class="flex flex-col p-2">
                            <span class="text-sm text-base">{{ auth()->user()->name }}</span>
                            <span class="text-sm text-base">{{ auth()->user()->email }}</span>
                        </div>
                        @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                            <x-navigation.link :href="route($nav['route'], $nav['params'] ?? null)" :spa="isset($nav['spa']) ? $nav['spa'] : true">
                                {{ $nav['name'] }}
                            </x-navigation.link>
                        @endforeach
                        <livewire:auth.logout />
                    </x-slot:content>
                </x-dropdown>
            @else
                <div class="flex flex-row">
                    <x-navigation.link :href="route('login')" class="text-base hover:text-base/80">
                        {{ __('navigation.login') }}
                    </x-navigation.link>
                    @if(!config('settings.registration_disabled', false))
                    <x-navigation.link :href="route('register')">
                        <x-button.primary class="bg-gray-600 hover:bg-gray-700 text-white">
                            {{ __('navigation.register') }}
                        </x-button.primary>
                    </x-navigation.link>
                    @endif
                </div>
            @endif
        </div>
    </nav>

@elseif(theme('header_style', 'default') === 'minimal')
    <!-- Minimal Header -->
    <nav class="w-full md:px-4 bg-background-secondary border-b border-neutral md:h-14 flex flex-row justify-between fixed top-0 z-20">
        <div class="flex flex-row items-center h-14 px-4">
            <a href="{{ route('home') }}" class="flex flex-row items-center" wire:navigate>
                <x-logo class="h-10" />
            </a>
        </div>
        <div class="flex flex-row items-center">
            <livewire:components.cart />
            @if(auth()->check())
                <x-dropdown>
                    <x-slot:trigger>
                        <img src="{{ auth()->user()->avatar }}" class="size-8 rounded-full border border-neutral bg-background" alt="avatar" />
                    </x-slot:trigger>
                    <x-slot:content>
                        <div class="flex flex-col p-2">
                            <span class="text-sm text-base">{{ auth()->user()->name }}</span>
                            <span class="text-sm text-base">{{ auth()->user()->email }}</span>
                        </div>
                        @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                            <x-navigation.link :href="route($nav['route'], $nav['params'] ?? null)" :spa="isset($nav['spa']) ? $nav['spa'] : true">
                                {{ $nav['name'] }}
                            </x-navigation.link>
                        @endforeach
                        <livewire:auth.logout />
                    </x-slot:content>
                </x-dropdown>
            @else
                <div class="flex flex-row">
                    <x-navigation.link :href="route('login')" class="text-base hover:text-base/80">
                        {{ __('navigation.login') }}
                    </x-navigation.link>
                </div>
            @endif
        </div>
    </nav>

@elseif(theme('header_style', 'default') === 'custom')
<nav>
    {!! theme('custom_header', 'Custom Header') !!}
</nav>
@endif
{!! theme('custom_layout_css', '') !!}