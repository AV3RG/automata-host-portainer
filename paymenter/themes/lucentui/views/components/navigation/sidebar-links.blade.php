<div class="lg:px-4 lg:py-6 flex flex-col gap-3 h-full">
    <div class="flex flex-col gap-2 md:hidden">
        @foreach (\App\Classes\Navigation::getLinks() as $nav)
            @if (!empty($nav['children']))
                <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
                    class="relative w-full overflow-hidden text-sm font-medium">
                    <div class="cursor-pointer">
                        <button @click="activeAccordion = !activeAccordion"
                            class="flex items-center justify-between w-full p-3 text-sm font-semibold whitespace-nowrap rounded-lg
                                hover:bg-primary/10 transition-colors duration-200 {{ $nav['active'] ? 'text-primary bg-primary/5' : 'text-color-base' }}">
                            <div class="flex items-center gap-3">
                                @isset($nav['icon'])
                                    <x-dynamic-component :component="$nav['icon']"
                                        class="size-5 {{ $nav['active'] ? 'text-primary' : 'text-color-muted' }}" />
                                @endisset
                                <span>{{ $nav['name'] }}</span>
                            </div>
                            <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                                class="size-5 text-color-muted transition-transform duration-300" />
                        </button>
                        <div x-show="activeAccordion" x-collapse x-cloak>
                            <div class="py-2 pl-8 pr-4 border-l-2 border-primary/50 ml-4 opacity-90">
                                @foreach ($nav['children'] as $child)
                                    <div class="flex items-center py-1">
                                        <x-navigation.link :href="route($child['route'], $child['params'] ?? [])"
                                            :spa="$child['spa'] ?? true"
                                            class="block w-full p-2 rounded-md transition-colors duration-200
                                                        {{ $child['active'] ? 'text-primary font-bold bg-primary/10' : 'text-color-muted hover:bg-primary/5' }}">
                                            {{ $child['name'] }}
                                        </x-navigation.link>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <x-navigation.link :href="route($nav['route'], $nav['params'] ?? [])"
                    :spa="$nav['spa'] ?? true"
                    class="flex items-center gap-3 p-3 rounded-lg transition-colors duration-200 w-full
                                {{ $nav['active'] ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-primary/5 text-color-base' }}">
                    @isset($nav['icon'])
                        <x-dynamic-component :component="$nav['icon']"
                            class="size-5 {{ $nav['active'] ? 'text-primary' : 'text-color-muted' }}" />
                    @endisset
                    <span>{{ $nav['name'] }}</span>
                </x-navigation.link>
            @endif
            @isset($nav['separator'])
                <div class="h-px w-full bg-neutral/50 my-2"></div>
            @endisset
        @endforeach
    </div>

    {{-- Bagian Navigasi Dashboard (Umumnya terlihat di Desktop, tapi juga di Mobile) --}}
    <div class="flex flex-col gap-2 flex-grow">
        @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
            @if (!empty($nav['children']))
                <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
                    class="relative w-full overflow-hidden text-sm font-medium">
                    <div class="cursor-pointer">
                        <button @click="activeAccordion = !activeAccordion"
                            class="flex items-center justify-between w-full p-3 text-sm font-semibold whitespace-nowrap rounded-lg
                                hover:bg-primary/10 transition-colors duration-200 {{ $nav['active'] ? 'text-primary bg-primary/5' : 'text-color-base' }}">
                            <div class="flex items-center gap-3">
                                @isset($nav['icon'])
                                    <x-dynamic-component :component="$nav['icon']"
                                        class="size-5 {{ $nav['active'] ? 'text-primary' : 'text-color-muted' }}" />
                                @endisset
                                <span>{{ $nav['name'] }}</span>
                            </div>
                            <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                                class="size-5 text-color-muted transition-transform duration-300" />
                        </button>
                        <div x-show="activeAccordion" x-collapse x-cloak>
                            <div class="py-2 pl-8 pr-4 border-l-2 border-primary/50 ml-4 opacity-90">
                                @foreach ($nav['children'] as $child)
                                    @if ($child['condition'] ?? true)
                                        <div class="flex items-center py-1">
                                            <x-navigation.link :href="route($child['route'], $child['params'] ?? [])"
                                                :spa="$child['spa'] ?? true"
                                                class="block w-full p-2 rounded-md transition-colors duration-200
                                                            {{ $child['active'] ? 'text-primary font-bold bg-primary/10' : 'text-color-muted hover:bg-primary/5' }}">
                                                {{ $child['name'] }}
                                            </x-navigation.link>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <x-navigation.link :href="route($nav['route'], $nav['params'] ?? [])"
                    :spa="$nav['spa'] ?? true"
                    class="flex items-center gap-3 p-3 rounded-lg transition-colors duration-200 w-full
                                {{ $nav['active'] ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-primary/5 text-color-base' }}">
                    @isset($nav['icon'])
                        <x-dynamic-component :component="$nav['icon']"
                            class="size-5 {{ $nav['active'] ? 'text-primary' : 'text-color-muted' }}" />
                    @endisset
                    <span>{{ $nav['name'] }}</span>
                </x-navigation.link>
            @endif
            @isset($nav['separator'])
                <div class="h-px w-full bg-neutral/50 my-2"></div>
            @endisset
        @endforeach

        <div class="flex flex-row items-center mt-6 justify-between md:hidden border-t border-neutral/50 pt-4">
            <x-dropdown>
                <x-slot:trigger>
                    <div class="flex flex-col items-start cursor-pointer hover:opacity-80 transition-opacity duration-200">
                        <div class="flex items-center gap-2">
                            <x-ri-global-line class="size-5 text-color-muted" />
                            <span class="text-sm text-color-base font-semibold">
                                {{ strtoupper(app()->getLocale()) }}
                                <span class="text-color-muted/50 font-semibold mx-1">|</span>
                                <span class="text-color-base">{{ session('currency', config('settings.default_currency')) }}</span>
                            </span>
                        </div>
                        <span class="text-xs text-color-muted mt-1">Language & Currency</span>
                    </div>
                </x-slot:trigger>
                <x-slot:content>
                    <strong class="block p-2 text-xs font-semibold uppercase text-color-muted">Language</strong>
                    <livewire:components.language-switch />
                    <strong class="block p-2 text-xs font-semibold uppercase text-color-muted mt-2">Currency</strong>
                    <livewire:components.currency-switch />
                </x-slot:content>
            </x-dropdown>

            <x-theme-toggle class="p-2 rounded-full hover:bg-primary/5 transition-colors duration-200" />
        </div>
    </div>

    @if(auth()->check())
        <div class="border-t border-neutral/50 pt-4 mt-auto hidden md:block" style="height: 180px;"> 
            <div class="flex items-center gap-3 p-3">
                <img src="{{ auth()->user()->avatar }}" class="size-8 rounded-full border border-neutral bg-background" alt="User Avatar" />
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-color-base">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-color-muted">{{ auth()->user()->email }}</span>
                </div>
            </div>
            <livewire:auth.logout
                class="flex items-center gap-3 p-3 rounded-lg transition-colors duration-200 w-full text-color-base hover:bg-primary/5" />
        </div>
    @endif
</div>
