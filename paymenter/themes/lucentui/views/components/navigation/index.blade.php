<nav class="w-full px-4 lg:px-8 fixed top-4 z-20">
    <div class="bg-background-secondary/80 backdrop-blur-md border border-neutral/50 rounded-2xl shadow-xl">
        <div x-data="{ slideOverOpen: false }"
             x-init="$watch('slideOverOpen', value => { document.documentElement.style.overflow = value ? 'hidden' : '' })"
             class="relative z-50 w-full h-auto">

            <div class="flex flex-row items-center justify-between w-full h-16 px-6">
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center h-10 gap-2" wire:navigate>
                        <img src="{{ asset('assets/extended/automata_full.png') }}" alt="logo" class="h-10" />
                    </a>
                    <div class="hidden md:flex flex-row gap-2">
                        @foreach (\App\Classes\Navigation::getLinks() as $nav)
                            @if (isset($nav['children']) && count($nav['children']) > 0)
                                <div class="relative">
                                    <x-dropdown>
                                        <x-slot:trigger>
                                            <span class="flex items-center px-4 py-2 text-sm font-medium hover:text-base/80 transition-all">{{ $nav['name'] }}</span>
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
                                    class="flex items-center px-4 py-2">
                                    {{ $nav['name'] }}
                                </x-navigation.link>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2">
                        <x-dropdown>
                            <x-slot:trigger>
                                <span class="text-sm font-semibold">{{ strtoupper(app()->getLocale()) }} <span class="text-base/50">|</span> {{ session('currency', config('settings.default_currency')) }}</span>
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
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200 group">
                            <div class="relative">
                                <img src="{{ auth()->user()->avatar }}" 
                                     class="w-10 h-10 rounded-full border-2 border-background-secondary group-hover:border-blue-500 transition-all duration-300 shadow-lg" 
                                     alt="avatar" />
                                <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-background shadow-lg"
                                     x-show="isOnline"
                                     x-transition:enter="transition-all duration-300"
                                     x-transition:enter-start="scale-0 opacity-0"
                                     x-transition:enter-end="scale-100 opacity-100">
                                    <div class="w-full h-full bg-green-400 rounded-full animate-ping"></div>
                                </div>
                            </div>
                            <div class="hidden lg:block text-left">
                                <div class="text-sm font-bold">{{ Auth::user()->first_name }}</div>
                                <div class="text-xs">{{ auth()->user()->email }}</div>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" 
                                 :class="{ 'rotate-180': open }" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-72 bg-background-secondary border border-neutral rounded-2xl shadow-2xl z-50">
                            
                            <div class="p-4 border-b border-neutral">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img src="{{ auth()->user()->avatar }}" 
                                             class="rounded-full border-2 border-neutral" 
                                             alt="avatar" />
                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-neutral"></div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-base">{{ auth()->user()->name }}</div>
                                        <div class="text-sm text-base">{{ auth()->user()->email }}</div>
                                        <div class="text-xs text-green-400 font-medium">● Online</div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                                    <x-navigation.link 
                                        :href="route($nav['route'], $nav['params'] ?? null)" 
                                        :spa="isset($nav['spa']) ? $nav['spa'] : true"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-base hover:bg-background transition-all duration-200">
                                        {{ $nav['name'] }}
                                    </x-navigation.link>
                                @endforeach
                                <div class="px-1">
                                    <livewire:auth.logout />
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden lg:flex items-center gap-3">
                        <a href="{{ route('login') }}" wire:navigate>
                            <button class="px-4 py-2 text-sm font-medium text-base border border-neutral hover:text-white hover:bg-primary rounded-lg transition-all duration-200">
                                {{ __('navigation.login') }}
                            </button>
                        </a>
                        @if(!config('settings.registration_disabled', false))
                            <a href="{{ route('register') }}" wire:navigate>
                                <button class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary hover:from-primary/50 hover:to-secondary/50 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    {{ __('navigation.register') }}
                                </button>
                            </a>
                        @endif
                    </div>
                @endif
                    
                    <button
                        @click="slideOverOpen = !slideOverOpen"
                        class="relative w-12 h-12 flex lg:hidden items-center justify-center rounded-xl bg-background hover:bg-neutral/50 transition-all duration-300 group"
                        aria-label="Toggle Menu">
                        <span
                            x-show="!slideOverOpen"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="opacity-0 rotate-180 scale-50"
                            x-transition:enter-end="opacity-100 rotate-0 scale-100"
                            x-transition:leave="transition duration-200 ease-in"
                            x-transition:leave-start="opacity-100 rotate-0 scale-100"
                            x-transition:leave-end="opacity-0 -rotate-180 scale-50"
                            class="absolute inset-0 flex items-center justify-center"
                            aria-hidden="true">
                            <x-ri-menu-fill class="size-6 text-base/80 group-hover:text-foreground transition-colors duration-200" />
                        </span>
                        <span
                            x-show="slideOverOpen"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="opacity-0 rotate-180 scale-50"
                            x-transition:enter-end="opacity-100 rotate-0 scale-100"
                            x-transition:leave="transition duration-200 ease-in"
                            x-transition:leave-start="opacity-100 rotate-0 scale-100"
                            x-transition:leave-end="opacity-0 -rotate-180 scale-50"
                            class="absolute inset-0 flex items-center justify-center"
                            aria-hidden="true">
                            <x-ri-close-fill class="size-6 text-base/80 group-hover:text-foreground transition-colors duration-200" />
                        </span>
                    </button>
                </div>
            </div>
            
            <template x-teleport="body">
                <div
                    x-show="slideOverOpen"
                    @keydown.window.escape="slideOverOpen=false"
                    x-cloak
                    class="fixed left-0 right-0 top-20 w-full z-[99]"
                    style="height:calc(100dvh - 5rem);"
                    aria-modal="true"
                    tabindex="-1">
                    <div
                        x-show="slideOverOpen"
                        x-transition:enter="transition duration-300 ease-out"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition duration-200 ease-in"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-4"
                        @click.away="slideOverOpen = false"
                        class="absolute inset-0 bg-background-secondary/80 backdrop-blur-md border-t border-neutral/50 shadow-2xl overflow-y-auto flex flex-col rounded-t-2xl">

                        <div class="flex flex-col h-full p-6">
                            <div class="flex-1 min-h-0 overflow-y-auto">
                                <div class="space-y-2">
                                    <x-navigation.sidebar-links />
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-neutral/50">
                                @if(auth()->check())
                                    <div
                                        x-data="{ userPanelOpen: false }"
                                        @keydown.escape.window="userPanelOpen = false"
                                        x-cloak
                                        class="relative">

                                        <button @click="userPanelOpen = true" 
                                                aria-label="Open user menu" 
                                                class="flex gap-4 items-center justify-start w-full p-4 bg-background/80 backdrop-blur-sm hover:bg-neutral/50 rounded-2xl transition-all duration-300 group">
                                            <div class="relative">
                                                <img src="{{ auth()->user()->avatar }}" 
                                                     class="size-12 rounded-full border-2 border-neutral bg-background group-hover:border-primary transition-all duration-300" 
                                                     alt="avatar" />
                                                <!-- Pulsing Online Status for Mobile -->
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-background">
                                                    <div class="absolute inset-0 bg-green-400 rounded-full animate-ping"></div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="font-bold text-lg text-foreground">{{ auth()->user()->name }}</span>
                                                <span class="text-sm text-base/70">{{ auth()->user()->email }}</span>
                                                <span class="text-xs text-green-500 font-medium">● Online</span>
                                            </div>
                                        </button>

                                        <div
                                            x-show="userPanelOpen"
                                            x-transition:enter="transition-opacity ease-out duration-300"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition-opacity ease-in duration-200"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            @click="userPanelOpen=false"
                                            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
                                            style="pointer-events: auto"></div>

                                        <div
                                            x-show="userPanelOpen"
                                            x-transition:enter="transition transform ease-out duration-300"
                                            x-transition:enter-start="translate-y-full opacity-0"
                                            x-transition:enter-end="translate-y-0 opacity-100"
                                            x-transition:leave="transition transform ease-in duration-200"
                                            x-transition:leave-start="translate-y-0 opacity-100"
                                            x-transition:leave-end="translate-y-full opacity-0"
                                            class="fixed bottom-0 left-0 right-0 z-50 mx-auto w-full max-w-md"
                                            style="pointer-events: auto"
                                            @click.away="userPanelOpen = false"
                                            tabindex="-1"
                                            aria-modal="true">
                                            <div class="bg-background-secondary/90 backdrop-blur-md shadow-2xl rounded-t-2xl border-t border-neutral/50 p-6">
                                                <div class="flex gap-4 items-center justify-start mb-6">
                                                    <div class="relative">
                                                        <img src="{{ auth()->user()->avatar }}" 
                                                             class="size-14 rounded-full border-2 border-neutral bg-background" 
                                                             alt="avatar" />
                                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-background">
                                                            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping"></div>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold text-xl text-foreground">{{ auth()->user()->name }}</span>
                                                        <span class="text-sm text-base/60">{{ auth()->user()->email }}</span>
                                                        <span class="text-xs text-green-500 font-medium">● Online</span>
                                                    </div>
                                                </div>
                                                <div class="h-px w-full bg-neutral/50 my-6"></div>
                                                <div class="flex flex-col gap-2 w-full">
                                                    @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                                                        <x-navigation.link 
                                                            :href="route($nav['route'], $nav['params'] ?? null)" 
                                                            :spa="isset($nav['spa']) ? $nav['spa'] : true"
                                                            class="block px-4 py-3 text-base/80 hover:text-foreground hover:bg-neutral/50 rounded-lg transition-all duration-200 font-medium">
                                                            {{ $nav['name'] }}
                                                        </x-navigation.link>
                                                    @endforeach
                                                    <div class="h-px bg-neutral/50 my-2"></div>
                                                    <livewire:auth.logout />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-4">
                                        <a href="{{ route('register') }}" wire:navigate>
                                            <button class="w-full px-6 py-4 text-lg font-bold text-primary-foreground bg-primary hover:bg-primary/90 rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg">
                                                {{ __('navigation.register') }}
                                            </button>
                                        </a>
                                        <a href="{{ route('login') }}" wire:navigate>
                                            <button class="w-full px-6 py-4 text-lg border border-primary font-bold text-primary hover:text-foreground bg-background hover:bg-neutral/50 rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg">
                                                {{ __('navigation.login') }}
                                            </button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</nav>