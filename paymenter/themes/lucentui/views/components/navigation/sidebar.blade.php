<aside class="mt-24 ml-8 w-64 h-[calc(100vh-2rem)] md:flex hidden flex-col fixed top-0 left-0 rtl:right-0 z-10">
    <div class="h-9/10 bg-background-secondary/80 backdrop-blur-md border border-neutral/50 rounded-2xl shadow-xl overflow-hidden">
        <div class="flex flex-col h-full">
            
            <div class="p-6 border-b border-neutral/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center shadow-lg">
                        <x-ri-dashboard-line class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-foreground">{{ __('navigation.home') }}</h2>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <div class="space-y-2">
                    
                    <div class="mb-6 md:hidden">
                        <h3 class="text-xs font-semibold text-base/50 uppercase tracking-wider mb-3 px-3"></h3>
                        <div class="space-y-1">
                            @foreach (\App\Classes\Navigation::getLinks() as $nav)
                                @if (!empty($nav['children']))
                                    <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
                                        class="relative w-full overflow-hidden">
                                        <button @click="activeAccordion = !activeAccordion"
                                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                                                {{ $nav['active'] ? 'text-primary bg-primary/10 border border-primary/20 shadow-sm' : 'text-base/80 hover:text-foreground hover:bg-background' }}">
                                            <div class="flex items-center gap-3">
                                                @isset($nav['icon'])
                                                    <x-dynamic-component :component="$nav['icon']"
                                                        class="w-5 h-5 {{ $nav['active'] ? 'text-primary' : 'text-base/60 group-hover:text-primary' }} transition-colors" />
                                                @endisset
                                                <span>{{ $nav['name'] }}</span>
                                            </div>
                                            <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                                                class="w-5 h-5 text-base/60 transition-transform duration-300" />
                                        </button>
                                        <div x-show="activeAccordion" x-collapse x-cloak>
                                            <div class="py-2 pl-8 pr-4 mt-2 border-l-2 border-primary/30 ml-4">
                                                @foreach ($nav['children'] as $child)
                                                    <x-navigation.link :href="route($child['route'], $child['params'] ?? [])"
                                                        :spa="$child['spa'] ?? true"
                                                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-1 transition-all duration-200 group
                                                            {{ $child['active'] ? 'text-primary bg-primary/10 border border-primary/20' : 'text-base/70 hover:text-foreground hover:bg-background/50' }}">
                                                        {{ $child['name'] }}
                                                        @if($child['active'])
                                                            <div class="ml-auto w-2 h-2 bg-primary rounded-full"></div>
                                                        @endif
                                                    </x-navigation.link>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <x-navigation.link :href="route($nav['route'], $nav['params'] ?? [])"
                                        :spa="$nav['spa'] ?? true"
                                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                                            {{ $nav['active'] ? 'text-primary bg-primary/10 border border-primary/20 shadow-sm' : 'text-base/80 hover:text-foreground hover:bg-background' }}">
                                        @isset($nav['icon'])
                                            <x-dynamic-component :component="$nav['icon']"
                                                class="w-5 h-5 {{ $nav['active'] ? 'text-primary' : 'text-base/60 group-hover:text-primary' }} transition-colors" />
                                        @endisset
                                        <span>{{ $nav['name'] }}</span>
                                        @if($nav['active'])
                                            <div class="ml-auto w-2 h-2 bg-primary rounded-full"></div>
                                        @endif
                                    </x-navigation.link>
                                @endif
                                @isset($nav['separator'])
                                    <div class="h-px w-full bg-neutral/30 my-3 mx-3"></div>
                                @endisset
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-base/50 uppercase tracking-wider mb-3 px-3">{{ __('navigation.dashboard') }}</h3>
                        <div class="space-y-1">
                            @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
                                @if (!empty($nav['children']))
                                    <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
                                        class="relative w-full overflow-hidden">
                                        <button @click="activeAccordion = !activeAccordion"
                                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                                                {{ $nav['active'] ? 'text-primary bg-primary/10 border border-primary/20 shadow-sm' : 'text-base/80 hover:text-foreground hover:bg-background' }}">
                                            <div class="flex items-center gap-3">
                                                @isset($nav['icon'])
                                                    <x-dynamic-component :component="$nav['icon']"
                                                        class="w-5 h-5 {{ $nav['active'] ? 'text-primary' : 'text-base/60 group-hover:text-primary' }} transition-colors" />
                                                @endisset
                                                <span>{{ $nav['name'] }}</span>
                                            </div>
                                            <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                                                class="w-5 h-5 text-base/60 transition-transform duration-300" />
                                        </button>
                                        <div x-show="activeAccordion" x-collapse x-cloak>
                                            <div class="py-2 pl-8 pr-4 mt-2 border-l-2 border-primary/30 ml-4">
                                                @foreach ($nav['children'] as $child)
                                                    @if ($child['condition'] ?? true)
                                                        <x-navigation.link :href="route($child['route'], $child['params'] ?? [])"
                                                            :spa="$child['spa'] ?? true"
                                                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-1 transition-all duration-200 group
                                                                {{ $child['active'] ? 'text-primary bg-primary/10 border border-primary/20' : 'text-base/70 hover:text-foreground hover:bg-background/50' }}">
                                                            {{ $child['name'] }}
                                                            @if($child['active'])
                                                                <div class="ml-auto w-2 h-2 bg-primary rounded-full"></div>
                                                            @endif
                                                        </x-navigation.link>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <x-navigation.link :href="route($nav['route'], $nav['params'] ?? [])"
                                        :spa="$nav['spa'] ?? true"
                                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:scale-105 group
                                            {{ $nav['active'] ? 'text-primary bg-primary/10 border border-primary/20 shadow-sm' : 'text-base/80 hover:text-foreground hover:bg-background' }}">
                                        @isset($nav['icon'])
                                            <x-dynamic-component :component="$nav['icon']"
                                                class="w-5 h-5 {{ $nav['active'] ? 'text-primary' : 'text-base/60 group-hover:text-primary' }} transition-colors" />
                                        @endisset
                                        <span>{{ $nav['name'] }}</span>
                                        @if($nav['active'])
                                            <div class="ml-auto w-2 h-2 bg-primary rounded-full"></div>
                                        @endif
                                    </x-navigation.link>
                                @endif
                                @isset($nav['separator'])
                                    <div class="h-px w-full bg-neutral/30 my-3 mx-3"></div>
                                @endisset
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

                @if(auth()->check())
                    <div class="flex items-center gap-3 p-3 bg-background/80 backdrop-blur-sm rounded-xl border border-neutral/50">
                        <div class="relative">
                            <img src="{{ auth()->user()->avatar }}" 
                                 class="w-10 h-10 rounded-full border-2 border-neutral/50 bg-background" 
                                 alt="User Avatar" />
                            <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-background">
                                <div class="w-full h-full bg-green-400 rounded-full animate-ping"></div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-foreground truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-base/60 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <livewire:auth.logout class="w-8 h-8 hover:bg-neutral/50 rounded-lg flex items-center justify-center transition-all duration-200" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</aside>