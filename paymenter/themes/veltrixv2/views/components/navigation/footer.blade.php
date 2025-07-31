@if(theme('footer_style', 'default') === 'default')
    <!-- Default Footer -->
    <footer class="mt-12 w-full bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] relative overflow-hidden border-t border-gray-200 dark:border-gray-700 pt-8">

        <!-- Floating particles background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="fmh-particle bg-[var(--primary)]/20 dark:bg-[var(--dark-primary)]/15" style="top: 20%; left: 15%; width: 8px; height: 8px;"></div>
            <div class="fmh-particle bg-[var(--secondary)]/20 dark:bg-[var(--dark-secondary)]/15" style="top: 70%; left: 80%; width: 12px; height: 12px;"></div>
            <div class="fmh-particle bg-[var(--primary)]/15 dark:bg-[var(--dark-primary)]/10" style="top: 40%; left: 65%; width: 6px; height: 6px;"></div>
            <div class="fmh-plasma-ball -bottom-20 -left-20 opacity-40 dark:opacity-15"></div>
        </div>

        <!-- Reduced vertical padding from py-12 to py-8 -->
        <div class="container mx-auto px-4 py-8 max-w-7xl relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand/Logo section -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col items-start">
                        <x-logo class="h-8 transition-all duration-300 hover:scale-105 hover:drop-shadow-lg" />
                        <p class="mt-2 text-sm text-[var(--muted)] dark:text-[var(--dark-muted)] leading-relaxed">
                            {{ theme('footer_tagline', 'Modern solutions for modern businesses.') }}
                        </p>
                    </div>

                    <!-- Social icon -->
                    <div class="flex items-center gap-3">
                        <a href="{{ theme('Discord-link', 'https://discord.com/invite/x3s5juS7EH') }}"
                           target="_blank"
                           class="p-2 rounded-lg bg-white/80 dark:bg-[var(--dark-background)] shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]"
                           aria-label="Discord">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 127 96" xmlns="http://www.w3.org/2000/svg">
                                <path d="M107.7 8.1A105.2 105.2 0 0 0 81.5 0a72.1 72.1 0 0 0-3.4 6.8 97.6 97.6 0 0 0-29.1 0A72.1 72.1 0 0 0 45.5 0a105.2 105.2 0 0 0-26.2 8.1C2.8 32.4-2.7 56.4 1.5 80a105.4 105.4 0 0 0 32.2 16.9 77.7 77.7 0 0 0 6.7-10.8 68.3 68.3 0 0 1-10.6-5.1c.9-.6 1.7-1.3 2.5-2a75.2 75.2 0 0 0 64.3 0c.8.7 1.6 1.4 2.5 2a68.3 68.3 0 0 1-10.6 5.1 77.7 77.7 0 0 0 6.7 10.8 105.4 105.4 0 0 0 32.2-16.9c4.4-23.6-1.3-47.6-18.8-71.9ZM42.4 58.3c-6.1 0-11.2-5.6-11.2-12.5s5-12.5 11.2-12.5c6.2 0 11.2 5.6 11.2 12.5.1 6.9-5 12.5-11.2 12.5Zm42.5 0c-6.1 0-11.2-5.6-11.2-12.5s5-12.5 11.2-12.5c6.2 0 11.2 5.6 11.2 12.5.1 6.9-5 12.5-11.2 12.5Z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-[var(--base)] dark:text-[var(--dark-base)] uppercase tracking-wider mb-4 relative pl-3">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] rounded-full"></span>
                        <span class="relative inline-block px-2 py-1 bg-white/50 dark:bg-[var(--dark-background)]/50 rounded-lg backdrop-blur-sm">
                            {{ theme('quick_links_title', 'Quick Links') }}
                        </span>
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ theme('Home-link', 'https://freemchosting.com') }}"
                               class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 mr-2 group-hover:bg-[var(--primary)] transition-colors"></span>
                                {{ theme('home_link_text', 'Home') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ theme('Feature-link', 'https://freemchosting.com/#features') }}"
                               class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 mr-2 group-hover:bg-[var(--primary)] transition-colors"></span>
                                {{ theme('features_link_text', 'Features') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ theme('Pricing-link', 'https://freemchosting.com/#compare') }}"
                               class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 mr-2 group-hover:bg-[var(--primary)] transition-colors"></span>
                                {{ theme('pricing_link_text', 'Pricing') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-sm font-semibold text-[var(--base)] dark:text-[var(--dark-base)] uppercase tracking-wider mb-4 relative pl-3">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-[var(--secondary)] to-pink-500 rounded-full"></span>
                        <span class="relative inline-block px-2 py-1 bg-white/50 dark:bg-[var(--dark-background)]/50 rounded-lg backdrop-blur-sm">
                            {{ theme('support_title', 'Support') }}
                        </span>
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ theme('Contact-link', 'https://freemchosting.com/contact') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary)]/70 dark:bg-[var(--dark-secondary)]/70 mr-2"></span>
                                {{ theme('contact_link_text', 'Contact') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tickets') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary)]/30 dark:bg-[var(--dark-secondary)]/30 mr-2"></span>
                                {{ theme('tickets_link_text', 'Tickets') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ theme('Status-link', 'https://status.freemchosting.com/status/freemchosting') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary)]/50 dark:bg-[var(--dark-secondary)]/50 mr-2"></span>
                                {{ theme('status_link_text', 'Status') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-sm font-semibold text-[var(--base)] dark:text-[var(--dark-base)] uppercase tracking-wider mb-4 relative pl-3">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-[var(--primary)] to-blue-500 rounded-full"></span>
                        <span class="relative inline-block px-2 py-1 bg-white/50 dark:bg-[var(--dark-background)]/50 rounded-lg backdrop-blur-sm">
                            {{ theme('company_title', 'Company') }}
                        </span>
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ theme('About-link', 'https://freemchosting.com/aboutus') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 mr-2 group-hover:bg-[var(--primary)] transition-colors"></span>
                                {{ theme('about_link_text', 'About Us') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ theme('Privacy-policy-link', 'https://freemchosting.com/privacy-policy') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 mr-2 group-hover:bg-[var(--primary)] transition-colors"></span>
                                {{ theme('privacy_link_text', 'Privacy Policy') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ theme('Tos-link', 'https://freemchosting.com/terms-of-service') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors flex items-center group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 mr-2 group-hover:bg-[var(--primary)] transition-colors"></span>
                                {{ theme('tos_link_text', 'Terms of Service') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom section -->
            <div class="mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 border-t border-gray-200 dark:border-gray-700 relative">
                <!-- Animated top border -->
                <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[var(--primary)] to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>

                <div class="text-sm text-[var(--muted)] dark:text-[var(--dark-muted)]">
                    {{ config('app.name') }} © {{ date('Y') }}. {{ theme('copyright_text', 'All rights reserved.') }}
                </div>

                <a href="https://paymenter.org" target="_blank" class="group flex items-center gap-1 text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)] transition-colors">
                    <svg class="size-3 text-current group-hover:text-[#4667FF] transition-colors duration-200" viewBox="0 0 150 205" fill="currentColor">
                        <g clip-path="url(#clip0_1_17)">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 107V205H42.8571V139.638H100C133.333 139.638 150 123 150 89.7246V69.5L75 107V69.5L148.227 32.8863C143.133 10.9621 127.057 0 100 0H0V107ZM0 107V69.5L75 32V69.5L0 107Z"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_1_17">
                                <rect width="150" height="205"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                    <p class="text-xs">{{ theme('powered_by_text', 'Powered by Paymenter') }}</p>
                </a>
            </div>
        </div>
    </footer>

@elseif(theme('footer_style', 'default') === 'simple')
    <!-- Simple Footer -->
    <footer class="w-full bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] mt-auto pt-6 pb-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center justify-center">
                <x-logo class="h-8 mb-4" />
                <p class="text-sm text-[var(--muted)] dark:text-[var(--dark-muted)]">
                    {{ theme('footer_tagline', 'Modern solutions for modern businesses.') }}
                </p>
                <div class="mt-4 text-sm text-[var(--muted)] dark:text-[var(--dark-muted)]">
                    {{ config('app.name') }} © {{ date('Y') }}. {{ theme('copyright_text', 'All rights reserved.') }}
                </div>
            </div>
        </div>
    </footer>

@elseif(theme('footer_style', 'default') === 'expanded')
    <!-- Expanded Footer -->
    <footer class="w-full bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] mt-auto pt-12 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <!-- Brand/Logo section -->
                <div class="md:col-span-2">
                    <x-logo class="h-8 mb-4" />
                    <p class="text-sm text-[var(--muted)] dark:text-[var(--dark-muted)]">
                        {{ theme('footer_tagline', 'Modern solutions for modern businesses.') }}
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-[var(--base)] dark:text-[var(--dark-base)] uppercase tracking-wider mb-4">
                        {{ theme('quick_links_title', 'Quick Links') }}
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ theme('Home-link', 'https://freemchosting.com') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('home_link_text', 'Home') }}</a></li>
                        <li><a href="{{ theme('Feature-link', 'https://freemchosting.com/#features') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('features_link_text', 'Features') }}</a></li>
                        <li><a href="{{ theme('Pricing-link', 'https://freemchosting.com/#compare') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('pricing_link_text', 'Pricing') }}</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-sm font-semibold text-[var(--base)] dark:text-[var(--dark-base)] uppercase tracking-wider mb-4">
                        {{ theme('support_title', 'Support') }}
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ theme('Contact-link', 'https://freemchosting.com/contact') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('contact_link_text', 'Contact') }}</a></li>
                        <li><a href="{{ route('tickets') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('tickets_link_text', 'Tickets') }}</a></li>
                        <li><a href="{{ theme('Status-link', 'https://status.freemchosting.com/status/freemchosting') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('status_link_text', 'Status') }}</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-sm font-semibold text-[var(--base)] dark:text-[var(--dark-base)] uppercase tracking-wider mb-4">
                        {{ theme('company_title', 'Company') }}
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ theme('About-link', 'https://freemchosting.com/aboutus') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('about_link_text', 'About Us') }}</a></li>
                        <li><a href="{{ theme('Privacy-policy-link', 'https://freemchosting.com/privacy-policy') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('privacy_link_text', 'Privacy Policy') }}</a></li>
                        <li><a href="{{ theme('Tos-link', 'https://freemchosting.com/terms-of-service') }}" class="text-sm text-[var(--muted)] hover:text-[var(--primary)] dark:text-[var(--dark-muted)] dark:hover:text-[var(--dark-primary)]">{{ theme('tos_link_text', 'Terms of Service') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-[var(--muted)] dark:text-[var(--dark-muted)]">
                {{ config('app.name') }} © {{ date('Y') }}. {{ theme('copyright_text', 'All rights reserved.') }}
            </div>
        </div>
    </footer>

@elseif(theme('footer_style', 'default') === 'custom')
<footer>
    {!! theme('custom_footer', 'Custom footer content goes here.') !!}
</footer>
@endif

<style>
    :root {
        /* Light mode colors */
        --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
        --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
        --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
        --base: {{ theme('base', 'hsl(0, 0%, 10%)') }};
        --muted: {{ theme('muted', 'hsl(220, 10%, 40%)') }};
        --inverted: {{ theme('inverted', 'hsl(0, 0%, 100%)') }};
        --background: {{ theme('background', 'hsl(0, 0%, 100%)') }};
        --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};

        /* Dark mode colors - improved contrast */
        --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 70%)') }};
        --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 50%, 65%)') }};
        --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 15%, 35%)') }};
        --dark-base: {{ theme('dark-base', 'hsl(0, 0%, 95%)') }};
        --dark-muted: {{ theme('dark-muted', 'hsl(220, 10%, 75%)') }};
        --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 20%)') }};
        --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
        --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};

        /* Additional contrast variables */
        --text-primary: {{ theme('text-primary', 'var(--base)') }};
        --text-secondary: {{ theme('text-secondary', 'var(--muted)') }};
        --dark-text-primary: {{ theme('dark-text-primary', 'var(--dark-base)') }};
        --dark-text-secondary: {{ theme('dark-text-secondary', 'var(--dark-muted)') }};
    }

    /* Improved dark mode link contrast */
    .dark a {
        color: var(--dark-text-secondary);
    }

    .dark a:hover {
        color: var(--dark-primary);
    }

    .fmh-particle {
        position: absolute;
        border-radius: 50%;
        animation: float 15s infinite ease-in-out;
        z-index: 0;
        filter: blur(1px);
        opacity: 0.8;
    }

    .fmh-plasma-ball {
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, rgba(0,0,0,0) 70%);
        filter: blur(30px);
        z-index: 0;
        animation: pulse 8s infinite ease-in-out;
    }

    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        50% {
            transform: translate(15px, 15px) rotate(5deg);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 0.2;
            transform: scale(1);
        }
        50% {
            opacity: 0.4;
            transform: scale(1.1);
        }
    }
</style>
{!! theme('custom_layout_css', '') !!}