<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(in_array(app()->getLocale(), config('app.rtl_locales'))) dir="rtl" @endif>
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <title>
        @if(theme('seo_title'))
            {{ theme('seo_title') }}
            @isset($title)
                - {{ $title }}
            @endisset
        @else
            {{ config('app.name', 'Paymenter') }}
            @isset($title)
                - {{ $title }}
            @endisset
        @endif
    </title>
    
    @if(theme('seo_description'))
        <meta name="description" content="{{ theme('seo_description') }}">
        <meta property="og:description" content="{{ theme('seo_description') }}">
    @endif
    
    @if(theme('seo_keywords'))
        <meta name="keywords" content="{{ theme('seo_keywords') }}">
    @endif
    
    @if(theme('seo_author'))
        <meta name="author" content="{{ theme('seo_author') }}">
    @endif
    
    @vite(['themes/' . config('settings.theme') . '/js/app.js', 'themes/' . config('settings.theme') . '/css/app.css'], config('settings.theme'))
    @include('layouts.colors')

    @if (config('settings.logo'))
        <link rel="icon" href="{{ Storage::url(config('settings.logo')) }}" type="image/png">
    @endif
    
    <!-- Open Graph Meta Tags -->
    @isset($title)
        <meta content="{{ theme('seo_title') ? theme('seo_title') . ' - ' . $title : config('app.name', 'Paymenter') . ' - ' . $title }}" property="og:title">
        <meta content="{{ theme('seo_title') ? theme('seo_title') . ' - ' . $title : config('app.name', 'Paymenter') . ' - ' . $title }}" name="title">
    @else
        <meta content="{{ theme('seo_title') ?: config('app.name', 'Paymenter') }}" property="og:title">
        <meta content="{{ theme('seo_title') ?: config('app.name', 'Paymenter') }}" name="title">
    @endisset
    
    @isset($description)
        <meta content="{{ $description }}" property="og:description">
        <meta content="{{ $description }}" name="description">
    @endisset
    
    <!-- Open Graph Image -->
    @if(theme('og_image'))
        <meta content="{{ theme('og_image') }}" property="og:image">
        <meta content="{{ theme('og_image') }}" name="image">
        <meta content="{{ theme('og_image') }}" property="twitter:image">
    @elseif(isset($image))
        <meta content="{{ $image }}" property="og:image">
        <meta content="{{ $image }}" name="image">
        <meta content="{{ $image }}" property="twitter:image">
    @endif
    
    <!-- Additional Open Graph Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Paymenter') }}">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ theme('seo_title') ?: config('app.name', 'Paymenter') }}{{ isset($title) ? ' - ' . $title : '' }}">
    @if(theme('seo_description'))
        <meta name="twitter:description" content="{{ theme('seo_description') }}">
    @endif
   
    <meta name="theme-color" content="{{ theme('primary') }}">
    <meta name="locale" content="{{ app()->getLocale() }}">
    
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    {!! hook('head') !!}
    
    <!-- Custom CSS from theme settings -->
    @if(theme('custom_css'))
        <style>
            {!! theme('custom_css') !!}
        </style>
    @endif
    
    <!-- Custom HTML Head Section -->
    @if(theme('custom_head_html'))
        {!! theme('custom_head_html') !!}
    @endif
    
    <style>
        /* Critical animations that should load immediately */
        .animate-pulse-optimized {
            animation: pulse-optimized 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse-optimized {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.25; transform: scale(1.05); }
        }
        
        .low-performance * {
            animation-duration: 0.1s !important;
            transition-duration: 0.1s !important;
        }
        
        .low-performance .animate-pulse-optimized {
            animation: none !important;
            opacity: 0.1 !important;
        }
        
        .reveal-on-scroll {
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                       opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .reveal-on-scroll.revealed {
            transform: translateY(0);
            opacity: 1;
        }
        
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid currentColor;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .mobile-optimized * {
                -webkit-tap-highlight-color: transparent;
            }
            
            .mobile-optimized button,
            .mobile-optimized a {
                touch-action: manipulation;
            }
        }

        /* Background Image Styles */
        .background-image-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        @media (max-width: 768px) {
            .background-image-layer {
                background-attachment: scroll;
            }
        }
        
        /* Custom Integration Styles */
        .custom-chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .custom-notification-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9998;
            padding: 10px;
            background: var(--primary);
            color: white;
            text-align: center;
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }
        
        .custom-notification-bar.show {
            transform: translateY(0);
        }
    </style>
</head>

<body class="w-full bg-background text-base min-h-screen flex flex-col antialiased" 
      x-cloak 
      x-data="{darkMode: $persist(window.matchMedia('(prefers-color-scheme: dark)').matches)}" 
      :class="{'dark': darkMode}">
    
    {!! hook('body') !!}
    
    <!-- Custom HTML Body Top -->
    @if(theme('custom_body_top_html'))
        {!! theme('custom_body_top_html') !!}
    @endif

    @if(theme('background_image_url'))
        <div class="background-image-layer"
             style="background-image: url('{{ theme('background_image_url') }}');
                    opacity: {{ theme('background_image_opacity', 30) / 100 }};
                    filter: blur({{ theme('background_image_blur', 5) }}px);">
        </div>
        
        <div class="background-overlay"
             style="opacity: {{ theme('background_overlay_opacity', 70) / 100 }};">
        </div>
    @endif

    <x-navigation />

    @if(!theme('background_image_url'))
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[50%] left-[50%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-primary rounded-full mix-blend-normal sm:mix-blend-screen filter blur-[40px] sm:blur-[120px] opacity-15 animate-pulse-optimized transform -translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="hidden sm:block absolute top-[30%] right-[30%] w-32 h-32 sm:w-48 sm:h-48 bg-primary rounded-full mix-blend-screen filter blur-[80px] sm:blur-[120px] opacity-10 animate-pulse-optimized" 
                 style="animation-delay: -1s;"></div>
            
            <div class="hidden md:block absolute bottom-[15%] right-[15%] w-40 h-40 sm:w-60 sm:h-60 bg-primary rounded-full mix-blend-screen filter blur-[70px] sm:blur-[100px] opacity-15 animate-pulse-optimized" 
                 style="animation-delay: -2s;"></div>
            
            <div class="hidden sm:block absolute top-[25%] left-[25%] w-[150px] h-[150px] sm:w-[200px] sm:h-[200px] bg-primary rounded-full mix-blend-screen filter blur-[60px] sm:blur-[90px] opacity-10 animate-pulse-optimized" 
                 style="animation-delay: -3s;"></div>
        </div>
    @endif

    <div class="relative z-10 w-full flex flex-grow">
        @if (isset($sidebar) && $sidebar)
            <x-navigation.sidebar title="$title" />
        @endif
        
        <div class="{{ (isset($sidebar) && $sidebar) ? 'md:ml-64 rtl:ml-0 rtl:md:mr-64' : '' }} flex flex-col flex-grow overflow-auto">
            
            <main class="container mt-24 mx-auto px-4 sm:px-6 md:px-8 lg:px-10 relative z-10">
                {{ $slot }}
            </main>
            
            <x-notification />
            
            <div class="mb-3 px-4 lg:px-16 py-4 relative z-0">
                <x-navigation.footer />
            </div>
        </div>
        <x-impersonating />
    </div>
    
    {!! hook('footer') !!}
    
    <!-- Custom JavaScript from theme settings -->
    @if(theme('custom_js'))
        <script>
            {!! theme('custom_js') !!}
        </script>
    @endif
    
    <!-- Custom HTML Body Bottom -->
    @if(theme('custom_body_bottom_html'))
        {!! theme('custom_body_bottom_html') !!}
    @endif
    
    <script>
        (function() {
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            const isLowEnd = connection && (connection.saveData || connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g');
            const isSlowDevice = navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2;
            
            if (isLowEnd || isSlowDevice) {
                document.body.classList.add('low-performance');
            }
            
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });
                
                document.querySelectorAll('.reveal-on-scroll').forEach(el => {
                    observer.observe(el);
                });
            }
            
            document.addEventListener('click', function(e) {
                if (e.target.matches('button[type="submit"], .btn-submit')) {
                    e.target.classList.add('btn-loading');
                    
                    setTimeout(() => {
                        e.target.classList.remove('btn-loading');
                    }, 3000);
                }
            });
            
            if (window.innerWidth <= 768) {
                document.body.classList.add('mobile-optimized');
            }
        })();
        
        console.log('%c🚀 Powered with LucentUI', 'color: #006effff; font-size: 16px; font-weight: bold;');
        console.log('%cCustom integrations loaded successfully!', 'color: #4ecdc4; font-size: 12px;');
    </script>
</body>
</html>