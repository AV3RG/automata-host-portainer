<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 rounded-2xl">

    <!-- Dashboard-specific custom HTML -->
    @if(theme('custom_dashboard_html'))
        <div class="custom-dashboard-content">
            {!! theme('custom_dashboard_html') !!}
        </div>
    @endif

    @if(theme('banner_enabled', false))
        <div x-data="{ 
            showBanner: !localStorage.getItem('banner_dismissed_{{ Auth::user()->id }}_{{ md5(theme('banner_message', '')) }}'),
            dismissBanner() {
                this.showBanner = false;
                @if(theme('banner_dismissible', true))
                    localStorage.setItem('banner_dismissed_{{ Auth::user()->id }}_{{ md5(theme('banner_message', '')) }}', 'true');
                @endif
            }
        }" 
        x-show="showBanner" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="w-full mb-4 p-4 rounded-2xl shadow-md relative overflow-hidden
            @switch(theme('banner_type', 'info'))
                @case('critical')
                    bg-red-600/20 border-l-4 border-red-500 text-red-500
                    @break
                @case('warning')
                    bg-yellow-600/20 border-l-4 border-yellow-500 text-yellow-500
                    @break
                @case('success')
                    bg-green-600/20 border-l-4 border-green-500 text-green-500
                    @break
                @default
                    bg-primary/20 border-l-4 border-primary text-primary
            @endswitch
        ">
            <div class="relative flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="flex-shrink-0">
                        @switch(theme('banner_type', 'info'))
                            @case('critical')
                                <x-ri-error-warning-line class="size-5 text-red-500" />
                                @break
                            @case('warning')
                                <x-ri-alert-line class="size-5 text-yellow-500" />
                                @break
                            @case('success')
                                <x-ri-checkbox-circle-line class="size-5 text-green-500" />
                                @break
                            @default
                                <x-ri-information-line class="size-5 text-primary" />
                        @endswitch
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm leading-relaxed">
                            {!! Str::markdown(theme('banner_message', '**This is an important announcement for all users.** You can use **bold**, *italic*, or even [links](https://example.com).'), ['html_input' => 'strip']) !!}
                        </p>
                    </div>
                </div>
                @if(theme('banner_dismissible', true))
                    <button @click="dismissBanner()" 
                            class="flex-shrink-0 bg-white/20 hover:bg-white/30 p-1.5 rounded-full transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <x-ri-close-line class="size-4 text-white" />
                    </button>
                @endif
            </div>
        </div>
    @endif

    <div class="rounded-2xl p-6 lg:p-8 mb-8 shadow-xl backdrop-blur-md border border-neutral/10 bg-primary/50 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <div class="mb-4">
                    <div class="flex items-center gap-4">
                        <x-ri-time-line class="size-4 text-secondary" />
                        <div class="text-white text-sm font-medium" id="current-time">
                            <span class="animate-pulse">Loading Time...</span>
                        </div>
                        <x-ri-calendar-line class="size-4 text-secondary" />
                        <div class="text-white text-sm font-medium" id="current-date">
                            <span class="animate-pulse">Loading Date...</span>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-2">
                        {{ __('dashboard.welcome_back', ['name' => Auth::user()->first_name]) }}
                    </h1>
                    <p class="text-xl text-white/80 font-medium max-w-2xl">
                        {{ __('dashboard.dashboard_description') }}
                    </p>
                </div>

                <div x-data="{ showBalance: false }" class="mt-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-white/90 text-l font-medium">{{ __('account.credits') }}:</h3>
                        @if (Auth::user()->credits->count() > 0)
                            <button @click="showBalance = !showBalance" class="text-white/70 hover:text-white transition-colors duration-300 focus:outline-none">
                                <template x-if="showBalance">
                                    <x-ri-eye-off-line class="size-4" />
                                </template>
                                <template x-if="!showBalance">
                                    <x-ri-eye-line class="size-4" />
                                </template>
                            </button>
                        @endif
                    </div>

                    @if (Auth::user()->credits->count() > 0)
                        <div class="flex flex-wrap gap-x-6 gap-y-2 mt-2">
                            @foreach (Auth::user()->credits as $credit)
                                <div class="flex items-center text-white/90 font-bold text-l">
                                    <span x-bind:class="showBalance ? '' : 'blur-sm'" class="transition-all duration-300">{{ $credit->formattedAmount }}</span>
                                    <span class="ml-2 text-white/70 text-base font-semibold">{{ $credit->currency->code }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-white/70 text-lg mt-2">{{ __('account.no_credit') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards - Conditional --}}
    @if(config('settings.tickets_disabled', false))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mb-6">
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
    @endif

        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <div class="bg-primary/10 text-primary p-2 rounded-full inline-flex mb-3">
                        <x-ri-archive-stack-fill class="size-5" />
                    </div>
                    <h3 class="text-sm font-medium text-color-muted mb-1">{{ __('dashboard.active_services') }}</h3>
                    <p class="text-2xl font-bold text-color-base">{{ Auth::user()->services()->where('status', 'active')->count() }}</p>
                </div>
                <a href="{{ route('services') }}" class="text-primary hover:text-blue-700 transition-colors duration-300">
                    <x-ri-arrow-right-line class="size-5" />
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <div class="bg-orange-500/10 text-orange-500 p-2 rounded-full inline-flex mb-3">
                        <x-ri-receipt-fill class="size-5" />
                    </div>
                    <h3 class="text-sm font-medium text-color-muted mb-1">{{ __('dashboard.unpaid_invoices') }}</h3>
                    <p class="text-2xl font-bold text-color-base">{{ Auth::user()->invoices()->where('status', 'pending')->count() }}</p>
                </div>
                <a href="{{ route('invoices') }}" class="text-orange-500 hover:text-orange-700 transition-colors duration-300">
                    <x-ri-arrow-right-line class="size-5" />
                </a>
            </div>
        </div>

        @if(!config('settings.tickets_disabled', false))
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="bg-green-500/10 text-green-500 p-2 rounded-full inline-flex mb-3">
                            <x-ri-customer-service-fill class="size-5" />
                        </div>
                        <h3 class="text-sm font-medium text-color-muted mb-1">{{ __('dashboard.open_tickets') }}</h3>
                        <p class="text-2xl font-bold text-color-base">{{ Auth::user()->tickets()->where('status', '!=', 'closed')->count() }}</p>
                    </div>
                    <a href="{{ route('tickets') }}" class="text-green-500 hover:text-green-700 transition-colors duration-300">
                        <x-ri-arrow-right-line class="size-5" />
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl p-6 lg:p-8 mb-8 shadow-xl transform transition-all duration-300 hover:scale-[1.005] animate-fade-in-up" style="animation-delay: 0.4s;">
        <h2 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-3">
            <div class="bg-primary/10 text-primary p-2 rounded-full">
                <x-ri-flashlight-fill class="size-6" />
            </div>
            Quick Actions
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Buy Server --}}
            <a href="{{ theme('buy_server_link', 'https://yourserver.com') }}" class="group bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary text-white p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-full">
                        <x-ri-server-fill class="size-5" />
                    </div>
                    <div>
                        <h3 class="font-semibold">Buy Service</h3>
                        <p class="text-sm text-white/80">Hosting</p>
                    </div>
                </div>
            </a>

            {{-- Help/Discord --}}
            <a href="{{ theme('help_link', 'https://help.yourserver.com') }}" target="_blank" class="group bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-full">
                        <x-ri-question-answer-fill class="size-5" />
                    </div>
                    <div>
                        <h3 class="font-semibold">Get Help</h3>
                        <p class="text-sm text-white/80">Chat Support</p>
                    </div>
                </div>
            </a>

            {{-- Profile --}}
            <a href="account" class="group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-full">
                        <x-ri-user-settings-fill class="size-5" />
                    </div>
                    <div>
                        <h3 class="font-semibold">{{ __('navigation.account') }}</h3>
                        <p class="text-sm text-white/80">Manage Profile</p>
                    </div>
                </div>
            </a>

            {{-- Documentation --}}
            <a href="{{ theme('docs_link', 'https://docs.yourserver.com') }}" class="group bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-full">
                        <x-ri-book-open-fill class="size-5" />
                    </div>
                    <div>
                        <h3 class="font-semibold">Knowledge Base</h3>
                        <p class="text-sm text-white/80">Get Started</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Bottom Widgets - Conditional --}}
    @if(config('settings.tickets_disabled', false))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mt-8 mb-8 items-start">
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mt-8 mb-8 items-start">
    @endif
        
        {{-- Active Services --}}
        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-xl p-6 lg:p-8 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="flex items-center justify-between mb-6 border-b border-dashed border-neutral/50 pb-4">
                <div class="flex items-center gap-4">
                    <div class="bg-primary/10 text-primary p-3 rounded-full flex-shrink-0 shadow-md">
                        <x-ri-archive-stack-fill class="size-6" />
                    </div>
                    <h2 class="text-2xl font-bold text-color-base">{{ __('dashboard.active_services') }}</h2>
                </div>
                <span class="bg-primary text-white text-lg font-bold rounded-full size-10 flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110">
                    {{ Auth::user()->services()->where('status', 'active')->count() }}
                </span>
            </div>
            <div class="space-y-4 mb-6">
                <livewire:services.widget status="active" />
            </div>
            <x-navigation.link class="w-full bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 flex items-center justify-center px-4 py-3 rounded-xl font-medium text-color-base transition-all duration-300 group"
                :href="route('services')">
                {{ __('dashboard.view_all') }}
                <x-ri-arrow-right-fill class="size-5 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" />
            </x-navigation.link>
        </div>

        {{-- Unpaid Invoices --}}
        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-xl p-6 lg:p-8 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="flex items-center justify-between mb-6 border-b border-dashed border-neutral/50 pb-4">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-500/10 text-orange-500 p-3 rounded-full flex-shrink-0 shadow-md">
                        <x-ri-receipt-fill class="size-6" />
                    </div>
                    <h2 class="text-2xl font-bold text-color-base">{{ __('dashboard.unpaid_invoices') }}</h2>
                </div>
                <span class="bg-orange-500 text-white text-lg font-bold rounded-full size-10 flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110">
                    {{ Auth::user()->invoices()->where('status', 'pending')->count() }}
                </span>
            </div>
            <div class="space-y-4 mb-6">
                <livewire:invoices.widget :limit="3" />
            </div>
            <x-navigation.link class="w-full bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 flex items-center justify-center px-4 py-3 rounded-xl font-medium text-color-base transition-all duration-300 group"
                :href="route('invoices')">
                {{ __('dashboard.unpaid_invoices') }}
                <x-ri-arrow-right-fill class="size-5 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" />
            </x-navigation.link>
        </div>

        {{-- Open Tickets --}}
        @if(!config('settings.tickets_disabled', false))
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-xl p-6 lg:p-8 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.7s;">
                <div class="flex items-center justify-between mb-6 border-b border-dashed border-neutral/50 pb-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-500/10 text-green-500 p-3 rounded-full flex-shrink-0 shadow-md">
                            <x-ri-customer-service-fill class="size-6" />
                        </div>
                        <h2 class="text-2xl font-bold text-color-base">{{ __('dashboard.open_tickets') }}</h2>
                        <a href="{{ route('tickets.create') }}" wire:navigate class="text-color-muted hover:text-primary ml-2 group">
                            <x-ri-add-circle-fill class="size-7 transform transition-transform duration-300 group-hover:rotate-90" />
                        </a>
                    </div>
                    <span class="bg-green-500 text-white text-lg font-bold rounded-full size-10 flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110">
                        {{ Auth::user()->tickets()->where('status', '!=', 'closed')->count() }}
                    </span>
                </div>
                <div class="space-y-4 mb-6">
                    <livewire:tickets.widget />
                </div>
                <x-navigation.link class="w-full bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 flex items-center justify-center px-4 py-3 rounded-xl font-medium text-color-base transition-all duration-300 group"
                    :href="route('tickets')">
                    {{ __('dashboard.open_tickets') }}
                    <x-ri-arrow-right-fill class="size-5 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" />
                </x-navigation.link>
            </div>
        @endif

    </div>

    {{-- Widget --}}

    {!! hook('pages.dashboard') !!}

</div>