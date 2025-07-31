<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ theme('seo_title') ?: config('app.name', 'Paymenter') }}
        @isset($title) - {{ $title }} @endisset
    </title>

    {{-- Styles & Scripts --}}
    @vite(['themes/' . config('settings.theme') . '/js/app.js', 'themes/' . config('settings.theme') . '/css/app.css'], config('settings.theme'))
    @include('layouts.colors')

    {{-- Favicon from theme --}}
    @if (theme('favicon_url'))
        <link rel="icon" href="{{ theme('favicon_url') }}" type="image/x-icon">
    @elseif(config('settings.logo'))
        <link rel="icon" href="{{ Storage::url(config('settings.logo')) }}" type="image/png">
    @endif

    {{-- Meta Title & Description --}}
    @if(theme('seo_description'))
        <meta name="description" content="{{ theme('seo_description') }}">
    @elseif(isset($description))
        <meta name="description" content="{{ $description }}">
    @endif

    @if(theme('seo_keywords'))
        <meta name="keywords" content="{{ theme('seo_keywords') }}">
    @endif

    {{-- Canonical --}}
    @if(theme('canonical_url'))
        <link rel="canonical" href="{{ theme('canonical_url') }}">
    @endif

    {{-- Robots --}}
    @if(theme('robots_content'))
        <meta name="robots" content="{{ theme('robots_content') }}">
    @endif

    {{-- Theme Color --}}
    <meta name="theme-color" content="{{ theme('primary') }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ theme('og_title') ?: $title ?? config('app.name', 'Paymenter') }}">
    @if(theme('og_description') || isset($description))
        <meta property="og:description" content="{{ theme('og_description') ?: $description }}">
    @endif
    @if(theme('og_image') || isset($image))
        <meta property="og:image" content="{{ theme('og_image') ?: $image }}">
    @endif

    {{-- Twitter Card --}}
    @if(theme('twitter_card')) <meta name="twitter:card" content="{{ theme('twitter_card') }}"> @endif
    @if(theme('twitter_site')) <meta name="twitter:site" content="{{ theme('twitter_site') }}"> @endif
    @if(theme('twitter_image') || isset($image))
        <meta name="twitter:image" content="{{ theme('twitter_image') ?: $image }}">
    @endif

    {{-- Hook for plugin or module extensions --}}
    {!! hook('head') !!}

    {{-- Custom code injection --}}
    {!! theme('inject', '') !!}
</head>


<body class="w-full bg-background text-base min-h-screen flex flex-col antialiased" x-cloak x-data="{darkMode: $persist(window.matchMedia('(prefers-color-scheme: dark)').matches)}" :class="{'dark': darkMode}">
    {!! hook('body') !!}
    <x-navigation />
    
    <div class="flex flex-col min-h-[calc(100vh-4rem)]"> <!-- Adjust 4rem to match your header height -->
        @if (isset($sidebar) && $sidebar)
            <x-navigation.sidebar title="$title" />
        @endif
        
        <main class="{{ (isset($sidebar) && $sidebar) ? 'md:ml-64' : '' }} flex-grow">
            <div class="container mt-24 mx-auto px-4 sm:px-6 md:px-8 lg:px-10">
                {{ $slot }}
            </div>
            <x-notification />
        </main>
        
        <!-- Remove the py-8 wrapper from around the footer -->
        <x-navigation.footer />
    </div>
        </div>
        <x-impersonating />
    </div>
    @if(theme('cookie', false))
<div id="cookieConsentPopup" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-lg border-t border-gray-200 dark:border-gray-700 z-50 transform translate-y-full transition-transform duration-300 ease-in-out">
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-gray-700 dark:text-gray-300 text-sm">
                    {!! theme('cookie_text', 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.') !!}
                    @if(theme('privacy_link', false))
                        <a href="{{ theme('privacy_link') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ theme('privacy_link_text', 'Privacy Policy') }}</a>
                    @endif
                    @if(theme('tos_link', false))
                        <span class="mx-1">•</span>
                        <a href="{{ theme('tos_link') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ theme('tos_link_text', 'Terms of Service') }}</a>
                    @endif
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3">
                @if(theme('cookie_show_preferences', false))
                <div class="flex items-center">
                    <input type="checkbox" id="cookiePreferences" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                    <label for="cookiePreferences" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ theme('cookie_preferences_text', 'Remember my preferences') }}</label>
                </div>
                @endif
                <button id="acceptCookies" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition-colors duration-200">
                    {{ theme('cookie_accept_text', 'Accept') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('cookieConsentPopup');
        const acceptBtn = document.getElementById('acceptCookies');
        const preferencesCheckbox = document.getElementById('cookiePreferences');
        
        // Check if cookie consent was already given
        if (!document.cookie.includes('cookie_consent=true')) {
            setTimeout(() => {
                popup.classList.remove('translate-y-full');
            }, 1000);
        }
        
        acceptBtn.addEventListener('click', function() {
            const rememberPrefs = preferencesCheckbox ? preferencesCheckbox.checked : false;
            
            // Set cookie with expiration (30 days if remember prefs is checked)
            const expires = rememberPrefs ? '; max-age=' + (10 * 365 * 24 * 60 * 60) : '';
            document.cookie = `cookie_consent=true${expires}; path=/`;
            
            popup.classList.add('translate-y-full');
            
            // Optional: Trigger custom event
            document.dispatchEvent(new CustomEvent('cookieConsentAccepted', {
                detail: { rememberPreferences: rememberPrefs }
            }));
        });
    });
</script>

<style>
    #cookieConsentPopup {
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    }
    .dark #cookieConsentPopup {
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
    }
</style>
@endif

    {!! hook('footer') !!}

    <script>
    // Keyboard shortcuts for navigation
    document.addEventListener('keydown', function(e) {
        // Check if Alt key is pressed along with another key
        if (e.altKey) {
            const baseUrl = '{{ url("/") }}'; // Gets the application's base URL
            
            switch(e.key.toLowerCase()) {
                case 'd':
                    // Alt + D for Dashboard
                    window.location.href = baseUrl + '/dashboard';
                    break;
                case 't':
                    // Alt + T for Tickets
                    window.location.href = baseUrl + '/tickets';
                    break;
                case 'a':
                    // Alt + A for Account
                    window.location.href = baseUrl + '/account';
                    break;
                case 'h':
                    // Alt + H for Home
                    window.location.href = baseUrl + '/';
                    break;
                case 's':
                    // Alt + S for Services
                    window.location.href = baseUrl + '/services';
                    break;
                case 'i':
                    // Alt + I for Invoices
                    window.location.href = baseUrl + '/invoices';
                    break;
                }
            }
        });
    </script>

@if(theme('enable_google_analytics', false) && theme('google_analytics_id'))
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ theme('google_analytics_id') }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ theme('google_analytics_id') }}');
  </script>
@endif


@if(theme('enable_custom_script', false) && theme('custom_script_code', ''))
    {!! theme('custom_script_code') !!}
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if smooth scroll is enabled in the theme settings
        const enableSmoothScroll = {{ theme('enable_smooth_scroll', true) ? 'true' : 'false' }};

        if (enableSmoothScroll) {
            // Enable smooth scrolling
            document.documentElement.style.scrollBehavior = 'smooth';
        } else {
            // Disable smooth scrolling
            document.documentElement.style.scrollBehavior = 'auto';
        }
    });
</script>


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

    #scrollToTopBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 99;
        border: none;
        outline: none;
        background-color: var(--primary);
        color: var(--inverted);
        cursor: pointer;
        padding: 10px;
        border-radius: 50%;
        font-size: 18px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    #scrollToTopBtn:hover {
        background-color: var(--secondary);
    }

    .dark #scrollToTopBtn {
        background-color: var(--dark-primary);
        color: var(--dark-inverted);
    }

    .dark #scrollToTopBtn:hover {
        background-color: var(--dark-secondary);
    }
</style>
{!! theme('custom_layout_css', '') !!}

@if(theme('show_scroll_to_top', true))
    <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" title="Go to top"
            class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-primary text-white text-xl shadow-lg hidden transition-all duration-300 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50">
        ↑
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');

            // Show button when user scrolls down
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    scrollToTopBtn.style.display = 'flex';
                    scrollToTopBtn.style.justifyContent = 'center';
                    scrollToTopBtn.style.alignItems = 'center';
                } else {
                    scrollToTopBtn.style.display = 'none';
                }
            });

            // Smooth scroll to top
            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
@endif
@if(theme('lazy_loading_enabled', true))
<script>
// Set up lazy loading configuration from theme settings
window.lazyConfig = {
    // Core Settings
    lazy_loading_enabled: {{ theme('lazy_loading_enabled', true) ? 'true' : 'false' }},
    lazy_root_margin: '{{ theme('lazy_root_margin', '500px 0px') }}',
    lazy_threshold: {{ theme('lazy_threshold', 0.001) }},
    lazy_delay: {{ theme('lazy_delay', 50) }},
    lazy_recheck_delay: {{ theme('lazy_recheck_delay', 500) }},
    lazy_timeout: {{ theme('lazy_timeout', 100) }},
    
    // CSS Classes
    lazy_class: '{{ theme('lazy_class', 'lazy-auto') }}',
    lazy_loaded_class: '{{ theme('lazy_loaded_class', 'lazy-loaded') }}',
    lazy_loading_class: '{{ theme('lazy_loading_class', 'lazy-loading') }}',
    lazy_error_class: '{{ theme('lazy_error_class', 'lazy-error') }}',
    
    // Element Selectors
    lazy_images_enabled: {{ theme('lazy_images_enabled', true) ? 'true' : 'false' }},
    lazy_backgrounds_enabled: {{ theme('lazy_backgrounds_enabled', true) ? 'true' : 'false' }},
    lazy_iframes_enabled: {{ theme('lazy_iframes_enabled', true) ? 'true' : 'false' }},
    lazy_custom_selector: '{{ theme('lazy_custom_selector', '') }}',
    
    // Performance Settings
    lazy_throttle_limit: {{ theme('lazy_throttle_limit', 100) }},
    lazy_viewport_multiplier: {{ theme('lazy_viewport_multiplier', 2) }},
    lazy_use_idle_callback: {{ theme('lazy_use_idle_callback', true) ? 'true' : 'false' }},
    
    // Dynamic Content Support
    lazy_observe_dom: {{ theme('lazy_observe_dom', true) ? 'true' : 'false' }},
    lazy_mutation_delay: {{ theme('lazy_mutation_delay', 100) }},
    
    // Advanced Options
    lazy_fallback_enabled: {{ theme('lazy_fallback_enabled', true) ? 'true' : 'false' }},
    lazy_events_enabled: {{ theme('lazy_events_enabled', true) ? 'true' : 'false' }},
    lazy_console_errors: {{ theme('lazy_console_errors', true) ? 'true' : 'false' }},
    lazy_ignore_attribute: '{{ theme('lazy_ignore_attribute', 'data-lazy-ignore') }}',
    
    // Critical Content Exclusions
    lazy_critical_selectors: '{{ theme('lazy_critical_selectors', 'header, .hero, .above-fold, .critical') }}',
    lazy_skip_small_images: {{ theme('lazy_skip_small_images', false) ? 'true' : 'false' }},
    lazy_small_image_threshold: {{ theme('lazy_small_image_threshold', 50) }}
};
</script>

<script>
// =============================================
// LAZY LOADER IMPLEMENTATION
// =============================================
document.addEventListener('DOMContentLoaded', function() {
    // Default configuration (fallbacks in case window.lazyConfig is missing)
    var defaultConfig = {
        lazy_loading_enabled: true,
        lazy_root_margin: '500px 0px',
        lazy_threshold: 0.001,
        lazy_delay: 50,
        lazy_recheck_delay: 500,
        lazy_timeout: 100,
        lazy_class: 'lazy-auto',
        lazy_loaded_class: 'lazy-loaded',
        lazy_loading_class: 'lazy-loading',
        lazy_error_class: 'lazy-error',
        lazy_images_enabled: true,
        lazy_backgrounds_enabled: true,
        lazy_iframes_enabled: true,
        lazy_custom_selector: '',
        lazy_throttle_limit: 100,
        lazy_viewport_multiplier: 2,
        lazy_use_idle_callback: true,
        lazy_observe_dom: true,
        lazy_mutation_delay: 100,
        lazy_fallback_enabled: true,
        lazy_events_enabled: true,
        lazy_console_errors: true,
        lazy_ignore_attribute: 'data-lazy-ignore',
        lazy_critical_selectors: 'header, .hero, .above-fold, .critical',
        lazy_skip_small_images: false,
        lazy_small_image_threshold: 50
    };

    // Merge configurations (theme settings override defaults)
    var config = Object.assign({}, defaultConfig, window.lazyConfig || {});

    // Build selector string based on configuration
    function buildSelectors() {
        var selectors = [];
        if (config.lazy_images_enabled) selectors.push('img[src]:not([src=""]):not([loading="lazy"])');
        if (config.lazy_backgrounds_enabled) selectors.push('[style*="background-image"]:not([' + config.lazy_ignore_attribute + '])');
        if (config.lazy_iframes_enabled) selectors.push('iframe[src]:not([src=""]):not([loading="lazy"])');
        if (config.lazy_custom_selector) selectors.push(config.lazy_custom_selector);
        return selectors.join(', ');
    }

    // Auto-convert elements to lazy load
    function autoLazify() {
        if (!config.lazy_loading_enabled) return false;

        var selectorString = buildSelectors();
        if (!selectorString) return false;

        var candidates = Array.prototype.slice.call(document.querySelectorAll(selectorString));
        var criticalElements = config.lazy_critical_selectors ? 
            document.querySelectorAll(config.lazy_critical_selectors) : [];

        candidates.forEach(function(el) {
            // Skip if in critical areas or already processed
            if (el.closest('[' + config.lazy_ignore_attribute + ']') || 
                Array.prototype.slice.call(criticalElements).some(function(critical) { 
                    return critical.contains(el); 
                }) ||
                el.classList.contains(config.lazy_loaded_class) || 
                el.classList.contains(config.lazy_class)) {
                return;
            }

            // Skip small images if enabled
            if (config.lazy_skip_small_images && el.tagName === 'IMG') {
                var rect = el.getBoundingClientRect();
                if (rect.width <= config.lazy_small_image_threshold && 
                    rect.height <= config.lazy_small_image_threshold) {
                    return;
                }
            }

            // Handle images
            if (el.tagName === 'IMG') {
                el.dataset.src = el.src;
                el.removeAttribute('src');
                el.classList.add(config.lazy_class);
            }
            // Handle background images
            else if (el.style.backgroundImage) {
                var bgUrl = el.style.backgroundImage.match(/url\(["']?(.*?)["']?\)/i);
                if (bgUrl && bgUrl[1]) {
                    el.dataset.bgSrc = bgUrl[1];
                    el.style.backgroundImage = 'none';
                    el.classList.add(config.lazy_class);
                }
            }
            // Handle iframes
            else if (el.tagName === 'IFRAME') {
                el.dataset.src = el.src;
                el.removeAttribute('src');
                el.classList.add(config.lazy_class);
            }
            // Handle custom elements
            else if (config.lazy_custom_selector && el.matches(config.lazy_custom_selector)) {
                if (el.hasAttribute('src')) {
                    el.dataset.src = el.src;
                    el.removeAttribute('src');
                }
                el.classList.add(config.lazy_class);
            }
        });

        return candidates.length > 0;
    }

    // Throttle function for scroll events
    function throttle(func, limit) {
        var inThrottle;
        return function() {
            var args = arguments;
            var context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(function() { inThrottle = false; }, limit);
            }
        };
    }

    // Check if element is in viewport (fallback)
    function isInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top <= window.innerHeight * config.lazy_viewport_multiplier &&
            rect.bottom >= 0 &&
            rect.left <= window.innerWidth * config.lazy_viewport_multiplier &&
            rect.right >= 0
        );
    }

    // Load individual item
    function loadItem(element) {
        if (!element.classList.contains(config.lazy_class)) return;

        element.classList.add(config.lazy_loading_class);

        function performLoad() {
            try {
                if (element.dataset.src) {
                    if (element.tagName === 'IMG') {
                        element.src = element.dataset.src;
                    } else if (element.tagName === 'IFRAME') {
                        element.src = element.dataset.src;
                    } else {
                        element.src = element.dataset.src;
                    }
                }

                if (element.dataset.bgSrc) {
                    element.style.backgroundImage = `url(${element.dataset.bgSrc})`;
                }

                element.classList.remove(config.lazy_class, config.lazy_loading_class);
                element.classList.add(config.lazy_loaded_class);

                // Dispatch event if enabled
                if (config.lazy_events_enabled) {
                    element.dispatchEvent(new CustomEvent('lazy-loaded', {
                        bubbles: true
                    }));
                }
            } catch (error) {
                element.classList.remove(config.lazy_loading_class);
                element.classList.add(config.lazy_error_class);
                if (config.lazy_console_errors) {
                    console.error('Lazy load error:', error);
                }
            }
        }

        if (config.lazy_use_idle_callback && 'requestIdleCallback' in window) {
            requestIdleCallback(performLoad, { timeout: config.lazy_timeout });
        } else {
            setTimeout(performLoad, config.lazy_timeout);
        }
    }

    // Core lazy loading functionality
    function turboLazyLoad() {
        if (!config.lazy_loading_enabled) return;

        // First auto-convert elements
        var hasNewItems = autoLazify();
        var lazyItems = document.getElementsByClassName(config.lazy_class);
        var totalItems = lazyItems.length;
        
        if (!totalItems) return;

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var lazyItem = entry.target;
                        loadItem(lazyItem);
                        observer.unobserve(lazyItem);
                    }
                });
            }, {
                rootMargin: config.lazy_root_margin,
                threshold: config.lazy_threshold
            });

            Array.prototype.slice.call(lazyItems).forEach(function(item) {
                observer.observe(item);
            });
        } else if (config.lazy_fallback_enabled) {
            // Fallback for older browsers
            var loadVisible = throttle(function() {
                Array.prototype.slice.call(lazyItems).forEach(function(item) {
                    if (isInViewport(item)) {
                        loadItem(item);
                    }
                });
            }, config.lazy_throttle_limit);

            window.addEventListener('scroll', loadVisible);
            window.addEventListener('resize', loadVisible);
            loadVisible();
        }

        // If new items were added, check again after a short delay
        if (hasNewItems) {
            setTimeout(turboLazyLoad, config.lazy_recheck_delay);
        }
    }

    // DOM Mutation Observer for dynamic content
    function setupMutationObserver() {
        if (!config.lazy_observe_dom) return;

        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function() {
                if (autoLazify()) {
                    setTimeout(turboLazyLoad, config.lazy_mutation_delay);
                }
            });
        });

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true,
            attributes: false,
            characterData: false
        });
    }

    // Initialize
    if (config.lazy_loading_enabled) {
        setTimeout(turboLazyLoad, config.lazy_delay);
        setupMutationObserver();
    }

    // Expose for manual control
    window.turboLazyLoad = turboLazyLoad;
});
</script>
@endif

<script>
/*
Veltrix Theme CLI v2.4 BETA
Theme Version: 2.4 BETA
CLI Version: 0.9.0 BETA
Created by QKing and Melvin

Access in browser console:
theme.help()
*/

const theme = (() => {
    const root = document.documentElement;
    const THEME_VERSION = "2.4";
    const CLI_VERSION = "0.9.1 BETA";

    const isHSL = (val) => /^hsl\(/i.test(val.trim());

    function get(variable) {
        const val = getComputedStyle(root).getPropertyValue(variable).trim();
        if (!val) console.warn(`"${variable}" not found.`);
        return val;
    }

    function set(variable, value) {
        if (!/^--/.test(variable)) {
            console.error('Invalid variable name. Must start with "--"');
            return;
        }
        if (!isHSL(value)) {
            console.warn(`"${value}" does not appear to be an HSL color. Proceeding anyway.`);
        }
        root.style.setProperty(variable, value);
        console.log(`Set ${variable} → ${value}`);
    }

    function list() {
        const styles = getComputedStyle(root);
        const vars = {};
        for (let i = 0; i < styles.length; i++) {
            const name = styles[i];
            if (name.startsWith("--")) {
                vars[name] = styles.getPropertyValue(name).trim();
            }
        }
        console.table(vars);
    }

    function exportTheme(filename = 'veltrix-theme.css') {
        const styles = getComputedStyle(root);
        let css = ':root {\n';
        for (let i = 0; i < styles.length; i++) {
            const name = styles[i];
            if (name.startsWith("--")) {
                const value = styles.getPropertyValue(name).trim();
                css += `  ${name}: ${value};\n`;
            }
        }
        css += '}';
        const blob = new Blob([css], { type: "text/css" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
        console.log(`Theme exported as "${filename}"`);
    }

    function importTheme(cssText) {
        const matches = cssText.match(/--[\w-]+:\s*[^;]+;/g);
        if (!matches) {
            console.error("No valid CSS variables found.");
            return;
        }
        matches.forEach(line => {
            const [key, val] = line.split(":").map(s => s.trim().replace(";", ""));
            set(key, val);
        });
        console.log("Theme variables imported.");
    }

    function save(name = "veltrix") {
        const styles = getComputedStyle(root);
        const vars = {};
        for (let i = 0; i < styles.length; i++) {
            const prop = styles[i];
            if (prop.startsWith("--")) {
                vars[prop] = styles.getPropertyValue(prop).trim();
            }
        }
        localStorage.setItem(`veltrix-theme-${name}`, JSON.stringify(vars));
        console.log(`Theme saved as "${name}"`);
    }

    function load(name = "veltrix") {
        const data = localStorage.getItem(`veltrix-theme-${name}`);
        if (!data) {
            console.warn(`No theme saved under "${name}"`);
            return;
        }
        const vars = JSON.parse(data);
        Object.entries(vars).forEach(([k, v]) => set(k, v));
        console.log(`Loaded theme "${name}"`);
    }

    function clearSaved(name = "veltrix") {
        localStorage.removeItem(`veltrix-theme-${name}`);
        console.log(`Cleared saved theme "${name}"`);
    }

    function reset() {
        if (!confirm("Reset all theme variables to browser default? This cannot be undone.")) return;
        const styles = getComputedStyle(root);
        for (let i = 0; i < styles.length; i++) {
            const prop = styles[i];
            if (prop.startsWith("--")) {
                root.style.removeProperty(prop);
            }
        }
        console.log("Theme variables reset to default.");
    }

    function stats() {
        const styles = getComputedStyle(root);
        let count = 0, dark = 0, icons = 0;
        for (let i = 0; i < styles.length; i++) {
            const prop = styles[i];
            if (prop.startsWith("--")) {
                count++;
                if (prop.includes("dark")) dark++;
                if (prop.includes("icon")) icons++;
            }
        }
        console.log(`Total variables: ${count}`);
        console.log(`Dark mode variables: ${dark}`);
        console.log(`Icon variables: ${icons}`);
    }

    function help() {
        console.log(`
Veltrix Theme CLI ${CLI_VERSION}
----------------------------
theme.version()             → Show theme version
theme.cliVersion()          → Show CLI version (BETA)
theme.authors()             → Show theme creators
theme.list()                → List all CSS variables
theme.get('--primary')      → Get a variable
theme.set('--primary', val) → Set a variable
theme.export()              → Download current theme as .css
theme.import(cssText)       → Import theme from CSS string
theme.save('name')          → Save theme to localStorage
theme.load('name')          → Load theme from localStorage
theme.clearSaved('name')    → Delete saved theme
theme.stats()               → Show theme variable stats
theme.reset()               → Reset theme variables
        `);
    }

    function version() {
        console.log(`Veltrix Theme Version: ${THEME_VERSION}`);
    }

    function cliVersion() {
        console.log(`CLI Tool Version: ${CLI_VERSION}`);
    }

    function authors() {
        console.log("Theme created by QKing and Melvin");
    }

    return {
        get, set, list, version, cliVersion, authors,
        export: exportTheme,
        import: importTheme,
        save, load, clearSaved,
        reset, stats, help
    };
})();

window.theme = theme;
</script>




</body>

</html>