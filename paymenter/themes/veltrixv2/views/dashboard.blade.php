<div>
<div class="min-h-screen w-full bg-gradient-to-br from-background to-background/70">

    <x-navigation.breadcrumb class="mb-6" />

    <div class="mb-12">
        <h1 class="text-4xl md:text-5xl font-bold" style="color: var(--base);">
            Welcome Back, {{ explode(' ', Auth::user()->first_name)[0] }}!
        </h1>
        <p class="text-lg text-foreground/80 font-medium max-w-3xl leading-relaxed tracking-tight">
            {{ __('dashboard.dashboard_description') }}
        </p>
    </div>
    <!-- Credit Balance - Fixed -->
    @if(Auth::user()->credits->count() > 0)
    <div class="bg-background-secondary/90 backdrop-blur-xl border border-white/5 rounded-2xl p-7 shadow-sm hover:shadow-lg transition-all duration-300 group/card mb-8">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3.5">
                <div class="bg-gradient-to-br from-green-500/10 to-cyan-500/10 p-3 rounded-xl shadow-inner">
                    <x-ri-wallet-3-line class="size-5 text-yellow-500" />
                </div>
                <h3 class="text-xl font-bold text-foreground tracking-tight">
                    Available Credit
                </h3>
            </div>
            <span class="text-xs px-2 py-1 rounded-full backdrop-blur-sm bg-white/10 dark:bg-gray-600/20">
                {{ Auth::user()->credits->count() }} currencies
            </span>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach (Auth::user()->credits as $credit)
            <div class="bg-background/50 backdrop-blur-sm border border-white/10 rounded-lg px-3 py-2 flex items-center gap-2">
                <span class="font-medium text-sm" style="color: var(--primary);">{{ $credit->currency->code }}</span>
                <div class="h-4 w-px bg-white/20 dark:bg-gray-400/20"></div>
                <span class="text-sm font-semibold" style="color: var(--base);">{{ $credit->formattedAmount }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
<BR></BR>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        <div class="grid gap-8 items-start">

            <div class="bg-background-secondary/90 backdrop-blur-xl border border-white/5 rounded-2xl p-7 shadow-sm hover:shadow-lg transition-all duration-300 group/card">
                <div class="flex items-center justify-between mb-7">
                    <div class="flex items-center gap-3.5">
                        <div class="bg-gradient-to-br from-primary/10 to-accent/10 p-3 rounded-xl shadow-inner">
                            <x-ri-archive-stack-fill class="size-5 text-primary" />
                        </div>
                        <h2 class="text-xl font-bold text-foreground tracking-tight">
                            {{ __('dashboard.active_services') }}
                        </h2>
                    </div>
                    <span class="bg-gradient-to-br from-primary to-accent flex items-center justify-center font-semibold rounded-full size-9 text-sm text-white shadow-md shadow-primary/30">
                        {{ Auth::user()->services()->where('status', 'active')->count() }}
                    </span>
                </div>

                <div class="space-y-5 mb-7">
                    <livewire:services.widget status="active" />
                </div>

                <a href="{{ route('services') }}" wire:navigate class="group">
                    <button class="gradient-button w-full flex items-center justify-center gap-2 hover:bg-background-secondary/90 border border-white/5 rounded-xl px-5 py-3.5 text-sm font-medium tracking-tight transition-all duration-250 group-hover:gap-3 shadow-sm hover:shadow-md">
                        {{ __('dashboard.view_all') }}
                        <x-ri-arrow-right-fill class="size-4 group-hover:translate-x-1 transition-transform duration-250" />
                    </button>
                </a>
            </div>

            <div class="bg-background-secondary/90 backdrop-blur-xl border border-white/5 rounded-2xl p-7 shadow-sm hover:shadow-lg transition-all duration-300 group/card">
                <div class="flex items-center justify-between mb-7">
                    @if(!config('settings.tickets_disabled', false))
                    <div class="flex items-center gap-3.5">
                        <div class="bg-gradient-to-br from-blue-500/10 to-cyan-400/10 p-3 rounded-xl shadow-inner">
                            <x-ri-customer-service-fill class="size-5 text-blue-500" />
                        </div>
                        <h2 class="text-xl font-bold text-foreground tracking-tight">
                            {{ __('dashboard.open_tickets') }}
                        </h2>
                        <a href="{{ route('tickets.create') }}" wire:navigate class="text-blue-500 hover:text-blue-400 transition-colors duration-200">
                            <x-ri-add-fill class="size-5 hover:scale-110 transition-transform" />
                        </a>
                    </div>
                    <span class="bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center font-semibold rounded-full size-9 text-sm text-white shadow-md shadow-blue-500/30">
                        {{ Auth::user()->tickets()->where('status', '!=', 'closed')->count() }}
                    </span>
                    @else
                    <div class="flex flex-col items-center justify-center text-center w-full py-12">
                        <div class="bg-gradient-to-br from-gray-500/10 to-gray-400/10 p-4 rounded-xl shadow-inner inline-block mb-4">
                            <x-ri-close-circle-fill class="size-8 text-gray-500" />
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">
                            Tickets Unavailable
                        </h3>
                        <p class="text-sm text-foreground/60">
                            The tickets are currently disabled.
                            If you think this is a mistake, contact an administrator.
                        </p>
                    </div>
                    @endif
                </div>
                
                @if(!config('settings.tickets_disabled', false))
                <div class="space-y-5 mb-7">
                    <livewire:tickets.widget />
                </div>
                @endif

                @if(!config('settings.tickets_disabled', false))
                <a href="{{ route('tickets') }}" wire:navigate class="group">
                    <button class="gradient-button w-full flex items-center justify-center gap-2 hover:bg-background-secondary/90 border border-white/5 rounded-xl px-5 py-3.5 text-sm font-medium tracking-tight transition-all duration-250 group-hover:gap-3 shadow-sm hover:shadow-md">
                        {{ __('dashboard.view_all') }}
                        <x-ri-arrow-right-fill class="size-4 group-hover:translate-x-1 transition-transform duration-250" />
                    </button>
                </a>
                @endif
            </div>
        </div>

        <div class="grid gap-8 items-start">

            <div class="bg-background-secondary/90 backdrop-blur-xl border border-white/5 rounded-2xl p-7 shadow-sm hover:shadow-lg transition-all duration-300 group/card">
                <div class="flex items-center justify-between mb-7">
                    <div class="flex items-center gap-3.5">
                        <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 p-3 rounded-xl shadow-inner">
                            <x-ri-receipt-fill class="size-5 text-purple-500" />
                        </div>
                        <h2 class="text-xl font-bold text-foreground tracking-tight">
                            {{ __('dashboard.unpaid_invoices') }}
                        </h2>
                    </div>
                    <span class="bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center font-semibold rounded-full size-9 text-sm text-white shadow-md shadow-purple-500/30">
                        {{ Auth::user()->invoices()->where('status', 'pending')->count() }}
                    </span>
                </div>

                <div class="space-y-5 mb-7">
                    <livewire:invoices.widget :limit="3" />
                </div>

                <a href="{{ route('invoices') }}" wire:navigate class="group">
                    <button class="gradient-button w-full flex items-center justify-center gap-2 hover:bg-background-secondary/90 border border-white/5 rounded-xl px-5 py-3.5 text-sm font-medium tracking-tight transition-all duration-250 group-hover:gap-3 shadow-sm hover:shadow-md">
                        {{ __('dashboard.view_all') }}
                        <x-ri-arrow-right-fill class="size-4 group-hover:translate-x-1 transition-transform duration-250" />
                    </button>
                </a>
            </div>

<div class="bg-background-secondary/90 backdrop-blur-xl border border-white/5 rounded-2xl p-7 shadow-sm hover:shadow-lg transition-all duration-300 group/card">
    <div class="flex items-center justify-between mb-7">
        <div class="flex items-center gap-3.5">
            <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 p-3 rounded-xl shadow-inner">
                @switch(theme('icon', 'document'))
                    @case('document')
                        <x-ri-file-text-fill class="size-5 text-purple-500" />
                        @break
                    @case('money')
                        <x-ri-money-dollar-circle-fill class="size-5 text-green-500" />
                        @break
                    @case('chart')
                        <x-ri-bar-chart-fill class="size-5 text-indigo-500" />
                        @break
                    @case('info')
                        <x-ri-information-fill class="size-5 text-blue-500" />
                        @break
                    @case('settings')
                        <x-ri-settings-3-fill class="size-5 text-gray-500" />
                        @break
                    @case('user')
                        <x-ri-user-fill class="size-5 text-yellow-500" />
                        @break
                    @case('calendar')
                        <x-ri-calendar-fill class="size-5 text-red-500" />
                        @break
                    @case('chat')
                        <x-ri-chat-3-fill class="size-5 text-teal-500" />
                        @break
                    @case('star')
                        <x-ri-star-fill class="size-5 text-pink-500" /> 
                        @break
                    @case('home')
                        <x-ri-home-fill class="size-5 text-cyan-500" />
                        @break
                    @case('download')
                        <x-ri-download-2-fill class="size-5 text-green-600" />
                        @break
                    @case('upload')
                        <x-ri-upload-cloud-fill class="size-5 text-indigo-600" />
                        @break
                    @case('notification')
                        <x-ri-notification-3-fill class="size-5 text-orange-500" />
                        @break
                    @case('camera')
                        <x-ri-camera-3-fill class="size-5 text-pink-600" />
                        @break
                    @case('heart')
                        <x-ri-heart-fill class="size-5 text-red-600" />
                        @break
                    @case('bookmark')
                        <x-ri-bookmark-fill class="size-5 text-yellow-600" />
                        @break
                    @case('thumb-up')
                        <x-ri-thumb-up-fill class="size-5 text-green-500" />
                        @break
                    @case('settings-gear')
                        <x-ri-settings-5-fill class="size-5 text-gray-700" />
                        @break
                    @case('mail')
                        <x-ri-mail-send-fill class="size-5 text-blue-600" />
                        @break
                    @case('phone')
                        <x-ri-phone-fill class="size-5 text-teal-600" />
                        @break
                    @case('map')
                        <x-ri-map-pin-fill class="size-5 text-purple-600" />
                        @break
                    @case('time')
                        <x-ri-time-fill class="size-5 text-indigo-500" />
                        @break
                    @case('alarm')
                        <x-ri-alarm-warning-fill class="size-5 text-red-500" />
                        @break
                    @case('dashboard')
                        <x-ri-dashboard-fill class="size-5 text-blue-500" />
                        @break
                    @case('folder')
                        <x-ri-folder-3-fill class="size-5 text-yellow-500" />
                        @break
                    @case('pie-chart')
                        <x-ri-pie-chart-2-fill class="size-5 text-indigo-500" />
                        @break
                    @case('bar-chart')
                        <x-ri-bar-chart-2-fill class="size-5 text-green-500" />
                        @break
                    @case('line-chart')
                        <x-ri-line-chart-fill class="size-5 text-blue-500" />
                        @break
                    @case('donut-chart')
                        <x-ri-donut-chart-fill class="size-5 text-pink-500" />
                        @break
                    @case('team')
                        <x-ri-team-fill class="size-5 text-purple-500" />
                        @break
                    @case('user-add')
                        <x-ri-user-add-fill class="size-5 text-green-500" />
                        @break
                    @case('group')
                        <x-ri-group-fill class="size-5 text-yellow-500" />
                        @break
                    @case('admin')
                        <x-ri-admin-fill class="size-5 text-red-500" />
                        @break
                    @case('award')
                        <x-ri-award-fill class="size-5 text-yellow-500" />
                        @break
                    @case('medal')
                        <x-ri-medal-fill class="size-5 text-orange-500" />
                        @break
                    @case('trophy')
                        <x-ri-trophy-fill class="size-5 text-pink-500" />
                        @break
                    @case('tools')
                        <x-ri-tools-fill class="size-5 text-blue-500" />
                        @break
                    @case('folder-add')
                        <x-ri-folder-add-fill class="size-5 text-yellow-500" />
                        @break
                    @case('folder-lock')
                        <x-ri-folder-lock-fill class="size-5 text-red-500" />
                        @break
                    @case('file-add')
                        <x-ri-file-add-fill class="size-5 text-blue-500" />
                        @break
                    @case('file-edit')
                        <x-ri-file-edit-fill class="size-5 text-green-500" />
                        @break
                    @case('file-download')
                        <x-ri-file-download-fill class="size-5 text-indigo-500" />
                        @break
                    @case('file-upload')
                        <x-ri-file-upload-fill class="size-5 text-teal-500" />
                        @break
                    @case('file-lock')
                        <x-ri-file-lock-fill class="size-5 text-gray-500" />
                        @break
                    @case('file-warning')
                        <x-ri-file-warning-fill class="size-5 text-orange-500" />
                        @break
                    @case('file-search')
                        <x-ri-file-search-fill class="size-5 text-purple-500" />
                        @break
                    @case('file-list')
                        <x-ri-file-list-fill class="size-5 text-pink-500" />
                        @break
                    @case('file-copy')
                        <x-ri-file-copy-fill class="size-5 text-yellow-500" />
                        @break
                    @case('file-chart')
                        <x-ri-file-chart-fill class="size-5 text-blue-500" />
                        @break
                    @case('file-code')
                        <x-ri-file-code-fill class="size-5 text-green-500" />
                        @break
                    @case('file-excel')
                        <x-ri-file-excel-fill class="size-5 text-green-700" />
                        @break
                    @case('file-pdf')
                        <x-ri-file-pdf-fill class="size-5 text-red-700" />
                        @break
                    @case('file-word')
                        <x-ri-file-word-fill class="size-5 text-blue-700" />
                        @break
                    @case('file-zip')
                        <x-ri-file-zip-fill class="size-5 text-yellow-700" />
                        @break
                    @case('file-music')
                        <x-ri-file-music-fill class="size-5 text-pink-700" />
                        @break
                    @case('file-video')
                        <x-ri-file-video-fill class="size-5 text-indigo-700" />
                        @break
                    @case('file-image')
                        <x-ri-file-image-fill class="size-5 text-purple-700" />
                        @break
                    @case('file-shield')
                        <x-ri-file-shield-fill class="size-5 text-teal-700" />
                        @break
                    @case('file-settings')
                        <x-ri-file-settings-fill class="size-5 text-blue-700" />
                        @break
                    @case('file-user')
                        <x-ri-file-user-fill class="size-5 text-green-700" />
                    @case('rocket')
                        <x-ri-rocket-fill class="size-5 text-orange-500" />
                        @break
                    { -- Fallback icon if none matches -- }
                    @case('search')
                        <x-ri-search-fill class="size-5 text-gray-500" />
                        @break
                    @case('arrow-up')
                        <x-ri-arrow-up-fill class="size-5 text-green-500" />
                        @break
                    @case('arrow-down')
                        <x-ri-arrow-down-fill class="size-5 text-red-500" />
                        @break
                    @case('arrow-left')
                        <x-ri-arrow-left-fill class="size-5 text-blue-500" />
                        @break
                    @case('arrow-right')
                        <x-ri-arrow-right-fill class="size-5 text-blue-500" />
                        @break
                    @case('check')
                        <x-ri-check-fill class="size-5 text-green-500" />
                        @break
                    @case('close')
                        <x-ri-close-fill class="size-5 text-red-500" />
                        @break
                    @case('menu')
                        <x-ri-menu-fill class="size-5 text-gray-500" />
                        @break
                    @case('filter')
                        <x-ri-filter-fill class="size-5 text-indigo-500" />
                        @break
                    @case('refresh')
                        <x-ri-refresh-fill class="size-5 text-blue-500" />
                        @break
                    @case('delete')
                        <x-ri-delete-bin-fill class="size-5 text-red-500" />
                        @break
                    @case('edit')
                        <x-ri-edit-fill class="size-5 text-yellow-500" />
                        @break
                    @case('save')
                        <x-ri-save-fill class="size-5 text-green-500" />
                        @break
                    @case('share')
                        <x-ri-share-fill class="size-5 text-blue-500" />
                        @break
                    @case('lock')
                        <x-ri-lock-fill class="size-5 text-red-500" />
                        @break
                    @case('unlock')
                        <x-ri-lock-unlock-fill class="size-5 text-green-500" />
                        @break
                    @case('eye')
                        <x-ri-eye-fill class="size-5 text-blue-500" />
                        @break
                    @case('eye-off')
                        <x-ri-eye-off-fill class="size-5 text-gray-500" />
                        @break
                    @case('bell')
                        <x-ri-notification-fill class="size-5 text-yellow-500" />
                        @break
                    @case('database')
                        <x-ri-database-fill class="size-5 text-purple-500" />
                        @break
                    @case('cloud')
                        <x-ri-cloud-fill class="size-5 text-blue-500" />
                        @break
                    @case('wifi')
                        <x-ri-wifi-fill class="size-5 text-green-500" />
                        @break
                    @case('battery')
                        <x-ri-battery-fill class="size-5 text-green-500" />
                        @break
                    @case('bluetooth')
                        <x-ri-bluetooth-fill class="size-5 text-blue-500" />
                        @break
                    @case('gift')
                        <x-ri-gift-fill class="size-5 text-pink-500" />
                        @break
                    @case('shopping-cart')
                        <x-ri-shopping-cart-fill class="size-5 text-blue-500" />
                        @break
                    @case('credit-card')
                        <x-ri-bank-card-fill class="size-5 text-purple-500" />
                        @break
                    @case('price-tag')
                        <x-ri-price-tag-3-fill class="size-5 text-green-500" />
                        @break
                    @case('store')
                        <x-ri-store-fill class="size-5 text-orange-500" />
                        @break
                    @case('truck')
                        <x-ri-truck-fill class="size-5 text-indigo-500" />
                        @break
                    @case('package')
                        <x-ri-archive-fill class="size-5 text-yellow-500" />
                        @break
                    @case('headphone')
                        <x-ri-headphone-fill class="size-5 text-pink-500" />
                        @break
                    @case('music')
                        <x-ri-music-fill class="size-5 text-purple-500" />
                        @break
                    @case('movie')
                        <x-ri-movie-fill class="size-5 text-blue-500" />
                        @break
                    @case('game')
                        <x-ri-gamepad-fill class="size-5 text-green-500" />
                        @break
                    @case('book')
                        <x-ri-book-fill class="size-5 text-indigo-500" />
                        @break
                    @case('newspaper')
                        <x-ri-newspaper-fill class="size-5 text-gray-500" />
                        @break
                    @case('printer')
                        <x-ri-printer-fill class="size-5 text-gray-700" />
                        @break
                    @case('keyboard')
                        <x-ri-keyboard-fill class="size-5 text-gray-700" />
                        @break
                    @case('mouse')
                        <x-ri-mouse-fill class="size-5 text-gray-700" />
                        @break
                    @case('cpu')
                        <x-ri-cpu-fill class="size-5 text-blue-500" />
                        @break
                    @case('hard-drive')
                        <x-ri-hard-drive-fill class="size-5 text-purple-500" />
                        @break
                    @case('smartphone')
                        <x-ri-smartphone-fill class="size-5 text-blue-500" />
                        @break
                    @case('tablet')
                        <x-ri-tablet-fill class="size-5 text-indigo-500" />
                        @break
                    @case('tv')
                        <x-ri-tv-fill class="size-5 text-blue-500" />
                        @break
                    @case('camera-off')
                        <x-ri-camera-off-fill class="size-5 text-red-500" />
                        @break
                    @case('microphone')
                        <x-ri-mic-fill class="size-5 text-blue-500" />
                        @break
                    @case('microphone-off')
                        <x-ri-mic-off-fill class="size-5 text-red-500" />
                        @break
                    @case('volume-up')
                        <x-ri-volume-up-fill class="size-5 text-blue-500" />
                        @break
                    @case('volume-down')
                        <x-ri-volume-down-fill class="size-5 text-blue-500" />
                        @break
                    @case('volume-off')
                        <x-ri-volume-mute-fill class="size-5 text-red-500" />
                        @break
                    @case('sun')
                        <x-ri-sun-fill class="size-5 text-yellow-500" />
                        @break
                    @case('moon')
                        <x-ri-moon-fill class="size-5 text-indigo-500" />
                        @break
                    @case('flashlight')
                        <x-ri-flashlight-fill class="size-5 text-yellow-500" />
                        @break
                    @case('umbrella')
                        <x-ri-umbrella-fill class="size-5 text-blue-500" />
                        @break
                    @case('flag')
                        <x-ri-flag-fill class="size-5 text-red-500" />
                        @break
                    @case('link')
                        <x-ri-link class="size-5 text-blue-500" />
                        @break
                    @case('external-link')
                        <x-ri-external-link-fill class="size-5 text-purple-500" />
                        @break
                    @case('attachment')
                        <x-ri-attachment-fill class="size-5 text-gray-500" />
                        @break
                    @case('fingerprint')
                        <x-ri-fingerprint-fill class="size-5 text-green-500" />
                        @break
                    @case('shield')
                        <x-ri-shield-fill class="size-5 text-blue-500" />
                        @break
                    @case('bug')
                        <x-ri-bug-fill class="size-5 text-red-500" />
                        @break
                    @case('code')
                        <x-ri-code-fill class="size-5 text-gray-700" />
                        @break
                    @case('cloud-upload')
                        <x-ri-upload-cloud-fill class="size-5 text-blue-500" />
                        @break
                    @case('cloud-download')
                        <x-ri-download-cloud-fill class="size-5 text-green-500" />
                        @break
                    @case('compass')
                        <x-ri-compass-fill class="size-5 text-blue-500" />
                        @break
                    @case('map-pin')
                        <x-ri-map-pin-fill class="size-5 text-red-500" />
                        @break
                    @case('navigation')
                        <x-ri-navigation-fill class="size-5 text-blue-500" />
                        @break
                    @case('global')
                        <x-ri-global-fill class="size-5 text-green-500" />
                        @break
                    @case('translate')
                        <x-ri-translate class="size-5 text-indigo-500" />
                        @break
                    @case('key')
                        <x-ri-key-fill class="size-5 text-yellow-500" />
                        @break
                    @case('login')
                        <x-ri-login-box-fill class="size-5 text-green-500" />
                        @break
                    @case('logout')
                        <x-ri-logout-box-fill class="size-5 text-red-500" />
                        @break
                    @case('question')
                        <x-ri-question-fill class="size-5 text-blue-500" />
                        @break
                    @case('alert')
                        <x-ri-alert-fill class="size-5 text-yellow-500" />
                        @break
                    @case('error')
                        <x-ri-error-warning-fill class="size-5 text-red-500" />
                        @break
                    @case('forbid')
                        <x-ri-forbid-fill class="size-5 text-red-500" />
                        @break
                    @case('lightbulb')
                        <x-ri-lightbulb-fill class="size-5 text-yellow-500" />
                        @break
                    @case('magic')
                        <x-ri-magic-fill class="size-5 text-purple-500" />
                        @break
                    @case('ruler')
                        <x-ri-ruler-fill class="size-5 text-blue-500" />
                        @break
                    @case('paint')
                        <x-ri-paint-brush-fill class="size-5 text-pink-500" />
                        @break
                    @case('palette')
                        <x-ri-palette-fill class="size-5 text-indigo-500" />
                        @break
                    @case('brush')
                        <x-ri-brush-fill class="size-5 text-blue-500" />
                        @break
                    @case('contrast')
                        <x-ri-contrast-fill class="size-5 text-purple-500" />
                        @break
                    @case('drop')
                        <x-ri-drop-fill class="size-5 text-blue-500" />
                        @break
                    @case('fire')
                        <x-ri-fire-fill class="size-5 text-orange-500" />
                        @break
                    @case('flask')
                        <x-ri-flask-fill class="size-5 text-purple-500" />
                        @break
                    @case('apps')
                        <x-ri-apps-fill class="size-5 text-gray-500" />
                        @break
                    @case('grid')
                        <x-ri-grid-fill class="size-5 text-gray-500" />
                        @break
                    @case('layout')
                        <x-ri-layout-fill class="size-5 text-blue-500" />
                        @break
                    @case('sidebar')
                        <x-ri-sidebar-unfold-fill class="size-5 text-indigo-500" />
                        @break
                    @case('fullscreen')
                        <x-ri-fullscreen-fill class="size-5 text-gray-500" />
                        @break
                    @case('zoom-in')
                        <x-ri-zoom-in-fill class="size-5 text-blue-500" />
                        @break
                    @case('zoom-out')
                        <x-ri-zoom-out-fill class="size-5 text-blue-500" />
                        @break
                    @case('scan')
                        <x-ri-scan-fill class="size-5 text-green-500" />
                        @break
                    @case('qrcode')
                        <x-ri-qr-code-fill class="size-5 text-black" />
                        @break
                    @case('barcode')
                        <x-ri-barcode-fill class="size-5 text-black" />
                        @break
                    @case('history')
                        <x-ri-history-fill class="size-5 text-gray-500" />
                        @break
                    @case('timer')
                        <x-ri-timer-fill class="size-5 text-blue-500" />
                        @break
                    @case('hourglass')
                        <x-ri-hourglass-fill class="size-5 text-yellow-500" />
                        @break
                    @case('calendar-check')
                        <x-ri-calendar-check-fill class="size-5 text-green-500" />
                        @break
                    @case('calendar-event')
                        <x-ri-calendar-event-fill class="size-5 text-blue-500" />
                        @break
                    @case('mail-open')
                        <x-ri-mail-open-fill class="size-5 text-blue-500" />
                        @break
                    @case('mail-unread')
                        <x-ri-mail-unread-fill class="size-5 text-blue-500" />
                        @break
                    @case('inbox')
                        <x-ri-inbox-fill class="size-5 text-blue-500" />
                        @break
                    @case('send')
                        <x-ri-send-plane-fill class="size-5 text-green-500" />
                        @break
                    @case('reply')
                        <x-ri-reply-fill class="size-5 text-blue-500" />
                        @break
                    @case('user-star')
                        <x-ri-user-star-fill class="size-5 text-yellow-500" />
                        @break
                    @case('user-heart')
                        <x-ri-user-heart-fill class="size-5 text-pink-500" />
                        @break
                    @case('user-settings')
                        <x-ri-user-settings-fill class="size-5 text-gray-500" />
                        @break
                    @case('user-follow')
                        <x-ri-user-follow-fill class="size-5 text-blue-500" />
                        @break
                    @case('user-unfollow')
                        <x-ri-user-unfollow-fill class="size-5 text-red-500" />
                        @break
                    @case('user-shared')
                        <x-ri-user-shared-fill class="size-5 text-indigo-500" />
                        @break
                    @case('user-received')
                        <x-ri-user-received-fill class="size-5 text-green-500" />
                        @break
                    @case('user-location')
                        <x-ri-user-location-fill class="size-5 text-purple-500" />
                        @break
                    @case('user-search')
                        <x-ri-user-search-fill class="size-5 text-blue-500" />
                        @break
                    @case('git-branch')
                        <x-ri-git-branch-fill class="size-5 text-purple-500" />
                        @break
                    @case('git-commit')
                        <x-ri-git-commit-fill class="size-5 text-blue-500" />
                        @break
                    @case('git-merge')
                        <x-ri-git-merge-fill class="size-5 text-indigo-500" />
                        @break
                    @case('git-pull-request')
                        <x-ri-git-pull-request-fill class="size-5 text-green-500" />
                        @break
                    @case('git-repository')
                        <x-ri-git-repository-fill class="size-5 text-gray-500" />
                        @break
                    @case('terminal')
                        <x-ri-terminal-fill class="size-5 text-gray-700" />
                        @break
                    @case('command')
                        <x-ri-command-fill class="size-5 text-gray-700" />
                        @break
                    @case('shut-down')
                        <x-ri-shut-down-fill class="size-5 text-red-500" />
                        @break
                    @case('restart')
                        <x-ri-restart-fill class="size-5 text-blue-500" />
                        @break
                    @case('battery-charge')
                        <x-ri-battery-charge-fill class="size-5 text-green-500" />
                        @break
                    @case('battery-low')
                        <x-ri-battery-low-fill class="size-5 text-yellow-500" />
                        @break
                    @case('airplay')
                        <x-ri-airplay-fill class="size-5 text-blue-500" />
                        @break
                    @case('cast')
                        <x-ri-cast-fill class="size-5 text-blue-500" />
                        @break
                    @case('router')
                        <x-ri-router-fill class="size-5 text-indigo-500" />
                        @break
                    @case('wifi-off')
                        <x-ri-wifi-off-fill class="size-5 text-red-500" />
                        @break
                    @case('bluetooth-connect')
                        <x-ri-bluetooth-connect-fill class="size-5 text-blue-500" />
                        @break
                    @case('dashboard')
                        <x-ri-dashboard-fill class="size-5 text-blue-500" />
                        @break
                    @case('function')
                        <x-ri-function-fill class="size-5 text-purple-500" />
                        @break
                    @case('hashtag')
                        <x-ri-hashtag class="size-5 text-blue-500" />
                        @break
                    @case('sort-asc')
                        <x-ri-sort-asc class="size-5 text-blue-500" />
                        @break
                    @case('sort-desc')
                        <x-ri-sort-desc class="size-5 text-blue-500" />
                        @break
                    @case('stack')
                        <x-ri-stack-fill class="size-5 text-purple-500" />
                        @break
                    @case('sticky-note')
                        <x-ri-sticky-note-fill class="size-5 text-yellow-500" />
                        @break
                    @case('table')
                        <x-ri-table-fill class="size-5 text-blue-500" />
                        @break
                    @case('text')
                        <x-ri-text class="size-5 text-gray-500" />
                        @break
                    @case('underline')
                        <x-ri-underline class="size-5 text-gray-500" />
                        @break
                    @case('window')
                        <x-ri-window-fill class="size-5 text-blue-500" />
                        @break
                    @case('add-circle')
                        <x-ri-add-circle-fill class="size-5 text-green-500" />
                        @break
                    @case('alert-circle')
                        <x-ri-error-warning-fill class="size-5 text-yellow-500" />
                        @break
                    @case('check-circle')
                        <x-ri-check-fill class="size-5 text-green-500" />
                        @break
                    @case('close-circle')
                        <x-ri-close-circle-fill class="size-5 text-red-500" />
                        @break
                    @case('download-cloud')
                        <x-ri-download-cloud-fill class="size-5 text-blue-500" />
                        @break
                    @case('equalizer')
                        <x-ri-equalizer-fill class="size-5 text-purple-500" />
                        @break
                    @case('forbid-2')
                        <x-ri-forbid-2-fill class="size-5 text-red-500" />
                        @break
                    @case('information')
                        <x-ri-information-fill class="size-5 text-blue-500" />
                        @break
                    @case('loader')
                        <x-ri-loader-fill class="size-5 text-gray-500" />
                        @break
                    @case('more')
                        <x-ri-more-fill class="size-5 text-gray-500" />
                        @break
                    @case('pause-circle')
                        <x-ri-pause-circle-fill class="size-5 text-yellow-500" />
                        @break
                    @case('play-circle')
                        <x-ri-play-circle-fill class="size-5 text-green-500" />
                        @break
                    @case('record-circle')
                        <x-ri-record-circle-fill class="size-5 text-red-500" />
                        @break
                    @case('stop-circle')
                        <x-ri-stop-circle-fill class="size-5 text-red-500" />
                        @break
                    @case('subtract')
                        <x-ri-subtract-fill class="size-5 text-red-500" />
                        @break
                    @case('thumb-down')
                        <x-ri-thumb-down-fill class="size-5 text-red-500" />
                        @break
                    @case('time-circle')
                        <x-ri-time-fill class="size-5 text-blue-500" />
                        @break
                    @case('upload-cloud')
                        <x-ri-upload-cloud-fill class="size-5 text-blue-500" />
                        @break
                    @case('wallet')
                        <x-ri-wallet-fill class="size-5 text-green-500" />
                        @break
                    @case('wind')
                        <x-ri-windy-fill class="size-5 text-blue-500" />
                        @break
                    @case('zoom-in-circle')
                        <x-ri-search-fill class="size-5 text-blue-500" />
                        @break
                    @case('zoom-out-circle')
                        <x-ri-search-fill class="size-5 text-blue-500" />
                        @break
    { -- Default icon if none matches -- }    
                    @default
                        <x-ri-file-text-fill class="size-5 text-purple-500" />
                @endswitch
            </div>
            <h2 class="text-xl font-bold text-foreground tracking-tight">
                {{ theme('htmltitle', 'Custom Content') }}
            </h2>
        </div>
    </div>

    <div class="space-y-5 mb-7">
        {!! theme('html', '<div class="bg-blue-500/10 p-4 rounded-lg"><p class="text-blue-500">Default HTML Content</p></div>') !!}
    </div>

<a href="{{ theme('footerlink', '#') }}" class="group">
    <button class="gradient-button w-full flex items-center justify-center gap-2 hover:bg-background-secondary/90 border border-white/5 rounded-xl px-5 py-3.5 text-sm font-medium tracking-tight transition-all duration-250 group-hover:gap-3 shadow-sm hover:shadow-md">
        {{ theme('footerbutton', 'View More') }}
        <x-ri-arrow-right-fill class="size-4 group-hover:translate-x-1 transition-transform duration-250" />
    </button>
</a>



            <div class="animate-fade-in [animation-delay:200ms] opacity-0">
                {!! hook('pages.dashboard') !!}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
        --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
        --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
        --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
        --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
        --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
        --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
        --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
        --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
        --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
        --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
        --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
        --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
        --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
        --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
        --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
    }

    .animate-fade-in {
        animation: fadeIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .group\/card:hover {
        transform: translateY(-2px);
    }

    /* Gradient Button */
    .gradient-button {
        background: linear-gradient(to bottom right, var(--primary), var(--secondary));
        transition: background 0.3s ease;
    }


    .gradient-button:hover {
        background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
    }
</style>
{!! theme('custom_layout_css', '') !!}
@endpush
</div>