<div class="relative min-h-[calc(100vh-200px)] w-full max-w-[1800px] mx-auto px-6 sm:px-8 lg:px-12">

    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="fmh-plasma-ball top-1/4 -left-20 opacity-80 mix-blend-overlay dark:opacity-60 dark:mix-blend-soft-light"></div>
        <div class="fmh-plasma-ball bottom-1/4 -right-20 animation-delay-3000 opacity-80 mix-blend-overlay dark:opacity-60 dark:mix-blend-soft-light"></div>
        <div class="fmh-particle bg-indigo-500/20 dark:bg-indigo-400/15" style="top: 25%; left: 25%; width: 10px; height: 10px;"></div>
        <div class="fmh-particle bg-purple-500/20 dark:bg-purple-400/15" style="top: 65%; left: 75%; width: 12px; height: 12px;"></div>
    </div>

    <div class="relative z-10 flex flex-col gap-12 py-12">

@if(theme('description') === 'default')
    <!-- Hero Section with border -->
    <div
        class="hero-section rounded-3xl shadow-2xl overflow-hidden hover:shadow-xl transition-all duration-300"
        style="
            background: linear-gradient(to bottom right, var(--primary), var(--secondary));
            border: 1px solid var(--neutral);
        "
    >
        <div class="hero-content p-6 md:p-8" style="
            background-color: var(--background);
            color: var(--base);
        ">
            <article class="prose prose-lg max-w-full" style="color: var(--base);">
                {!! Str::markdown(theme('home_page_text', 'Welcome to Paymenter'), [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]) !!}
            </article>
        </div>
    </div>
@elseif(theme('description') === 'no-border')
    <!-- Hero Section without border -->
    <div
        class="hero-section rounded-3xl shadow-2xl overflow-hidden hover:shadow-xl transition-all duration-300"
        style="
            background: linear-gradient(to bottom right, var(--primary), var(--secondary));
            /* No border */
        "
    >
        <div class="hero-content p-6 md:p-8" style="
            background-color: var(--background);
            color: var(--base);
        ">
            <article class="prose prose-lg max-w-full" style="color: var(--base);">
                {!! Str::markdown(theme('home_page_text', 'Welcome to Paymenter'), [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]) !!}
            </article>
        </div>
    </div>
@else
    <!-- Hero Section fallback -->
    <div
        class="hero-section rounded-3xl shadow-2xl overflow-hidden hover:shadow-xl transition-all duration-300"
        style="
            background: linear-gradient(to bottom right, var(--primary), var(--secondary));
            border: 1px solid var(--neutral);
        "
    >
        <div class="hero-content p-6 md:p-8" style="
            background-color: var(--background);
            color: var(--base);
        ">
            <article class="prose prose-lg max-w-full" style="color: var(--base);">
                {!! Str::markdown(theme('home_page_text', 'Welcome to Paymenter'), [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]) !!}
            </article>
        </div>
    </div>
@endif



<style>
    .dark .hero-section {
        border-color: var(--dark-muted) !important;
    }
    
    .dark .hero-content {
        background-color: var(--dark-background) !important;
        color: var(--dark-base) !important;
    }
    
    .dark .hero-content .prose {
        color: var(--dark-base) !important;
    }
</style>

<!-- Features Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Feature 1 -->
    <div class="bg-[var(--background-secondary)]/80 dark:bg-[var(--dark-background-secondary)]/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50 hover:shadow-xl transition-all duration-300">
        <div class="w-12 h-12 rounded-lg bg-[var(--iconbg1)] dark:bg-[var(--dark-iconbg1)] flex items-center justify-center mb-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--icon1)] dark:text-[var(--dark-icon1)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">
            {{ theme('title1', 'Free Credits') }}
        </h3>
        <div class="text-[var(--muted)] dark:text-[var(--dark-muted)] text-sm">
            {!! theme('content1', 'Earn credits through our rewards system and access all services without spending real money.') !!}
        </div>
    </div>

    <!-- Feature 2 -->
    <div class="bg-[var(--background-secondary)]/80 dark:bg-[var(--dark-background-secondary)]/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50 hover:shadow-xl transition-all duration-300">
        <div class="w-12 h-12 rounded-lg bg-[var(--iconbg2)] dark:bg-[var(--dark-iconbg2)] flex items-center justify-center mb-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--icon2)] dark:text-[var(--dark-icon2)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">
            {{ theme('title2', 'VIP Benefits') }}
        </h3>
        <div class="text-[var(--muted)] dark:text-[var(--dark-muted)] text-sm">
            {!! theme('content2', 'Upgrade to VIP for premium features, priority support, and exclusive resources.') !!}
        </div>
    </div>

    <!-- Feature 3 -->
    <div class="bg-[var(--background-secondary)]/80 dark:bg-[var(--dark-background-secondary)]/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50 hover:shadow-xl transition-all duration-300">
        <div class="w-12 h-12 rounded-lg bg-[var(--iconbg3)] dark:bg-[var(--dark-iconbg3)] flex items-center justify-center mb-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--icon3)] dark:text-[var(--dark-icon3)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">
            {{ theme('title3', 'Secure & Reliable') }}
        </h3>
        <div class="text-[var(--muted)] dark:text-[var(--dark-muted)] text-sm">
            {!! theme('content3', 'Enterprise-grade security and 99.9% uptime guarantee for all your hosting needs.') !!}
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach ($categories as $category)
        <a href="{{ route('category.show', ['category' => $category->slug]) }}" wire:navigate
           class="group relative bg-[var(--background-secondary)]/80 dark:bg-[var(--dark-background-secondary)]/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 border border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50 hover:border-[var(--primary)]/30 dark:hover:border-[var(--dark-primary)]/50">

            <div class="absolute inset-0 bg-gradient-to-br from-[var(--primary)]/0 to-[var(--secondary)]/0 group-hover:from-[var(--primary)]/5 group-hover:to-[var(--secondary)]/10 transition-all duration-500 z-0"></div>

            <div class="relative z-10 flex flex-col h-full">
                @if ($category->image)
                    <div class="{{ theme('small_images', false) ? 'p-6 flex items-start gap-5 rounded-xl' : 'rounded-xl' }}"
                         style="background-color: {{ theme('category_image_bg', 'var(--background-secondary)') }};">
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                             class="{{ theme('small_images', false) ? 'w-16 h-16 object-cover rounded-xl' : 'w-full h-56 object-cover rounded-xl' }}">
                        @if(theme('small_images', false))
                            <h2 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mt-1">{{ $category->name }}</h2>
                        @endif
                    </div>
                @else
                    @if(theme('level', false))
                        {{-- Transparent placeholder block to match image height if 'level' is true --}}
                        <div class="{{ theme('small_images', false) ? 'p-6 flex items-start gap-5 rounded-xl' : 'rounded-xl' }}"
                             style="background-color: transparent; height: {{ theme('small_images', false) ? '4rem' : '14rem' }};">
                            @if(theme('small_images', false))
                                <h2 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mt-1">{{ $category->name }}</h2>
                            @endif
                        </div>
                    @else
                        <div class="mt-6"></div>
                        <div></div>
                    @endif
                @endif

                <div class="p-6 pt-0 flex-grow">
                    @if(!theme('small_images', false) || !$category->image)
                        <h2 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-3">{{ $category->name }}</h2>
                    @endif

                    @if(theme('show_category_description', true))
                        <article class="prose-sm dark:prose-invert text-[var(--muted)] dark:text-[var(--dark-muted)] mb-5">
                            {!! $category->description !!}
                        </article>
                    @endif
                </div>

                <div class="px-6 pb-6">
                    <div class="w-full gradient-button text-[var(--inverted)] font-medium py-3 px-4 rounded-lg transition-all duration-300 group-hover:shadow-md group-hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        {{ __('general.view') }}
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>


        

        <div class="relative">
            {!! hook('pages.home') !!}
        </div>

        <!-- FAQ Section -->
        @php
            $featuredFaqs = collect();
            $faqServiceAvailable = false;
            
            try {
                if (class_exists('Paymenter\Extensions\Others\FAQ\FAQService')) {
                    $faqServiceAvailable = true;
                    $featuredFaqs = \Paymenter\Extensions\Others\FAQ\FAQService::getAllFeaturedQuestions();
                }
            } catch (Exception $e) {
                // Silently fail if FAQ service is not available
            }
        @endphp
        
        @if($faqServiceAvailable && $featuredFaqs->count() > 0)
                <div class="py-20">
                    <div class="container mx-auto px-4">
                        <div class="text-center mb-16">
                            <h2 class="text-4xl md:text-5xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-6">
                                {{ theme('faq_title', 'Frequently Asked Questions') }}
                            </h2>
                            <p class="text-xl text-[var(--muted)] dark:text-[var(--dark-muted)] max-w-2xl mx-auto">
                                {{ theme('faq_subtitle', 'Find answers to common questions about our services') }}
                            </p>
                        </div>

                        <div class="max-w-4xl mx-auto">
                            <div class="space-y-4">
                                @foreach($featuredFaqs as $faq)
                                    <div class="group bg-[var(--background-secondary)]/80 dark:bg-[var(--dark-background-secondary)]/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 border border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50">
                                        <button class="w-full text-left p-6 focus:outline-none focus:ring-2 focus:ring-primary/20" 
                                                onclick="toggleHomeFAQ({{ $faq->id }})"
                                                aria-expanded="false"
                                                aria-controls="home-faq-answer-{{ $faq->id }}">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-[var(--base)] dark:text-[var(--dark-base)] group-hover:text-primary transition-colors duration-300 pr-4">
                                                    {{ $faq->question }}
                                                </h3>
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="mr-1">
                                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                        </svg>
                                                        Featured
                                                    </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--muted)] dark:text-[var(--dark-muted)] transform transition-transform duration-300 home-faq-arrow-{{ $faq->id }}">
                                                        <path d="m6 9 6 6 6-6"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </button>
                                        
                                        <div id="home-faq-answer-{{ $faq->id }}" 
                                             class="home-faq-answer hidden px-6 pb-6 border-t border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50">
                                            <div class="pt-4">
                                                <article class="prose prose-sm prose-gray dark:prose-invert text-[var(--muted)] dark:text-[var(--dark-muted)] leading-relaxed max-w-none">
                                                    {!! $faq->answer !!}
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleHomeFAQ(faqId) {
                        const answer = document.getElementById(`home-faq-answer-${faqId}`);
                        const button = document.querySelector(`[onclick="toggleHomeFAQ(${faqId})"]`);
                        const arrow = document.querySelector(`.home-faq-arrow-${faqId}`);
                        const isExpanded = answer.classList.contains('hidden');
                        
                        // Toggle visibility
                        if (isExpanded) {
                            answer.classList.remove('hidden');
                            button.setAttribute('aria-expanded', 'true');
                            arrow.classList.add('rotate-180');
                        } else {
                            answer.classList.add('hidden');
                            button.setAttribute('aria-expanded', 'false');
                            arrow.classList.remove('rotate-180');
                        }
                    }

                    // Initialize FAQ accessibility
                    document.addEventListener('DOMContentLoaded', function() {
                        const faqButtons = document.querySelectorAll('[onclick^="toggleHomeFAQ"]');
                        faqButtons.forEach(button => {
                            button.addEventListener('keydown', function(e) {
                                if (e.key === 'Enter' || e.key === ' ') {
                                    e.preventDefault();
                                    const faqId = this.getAttribute('onclick').match(/\d+/)[0];
                                    toggleHomeFAQ(faqId);
                                }
                            });
                        });
                    });
                </script>
            @endif
        @endif
    </div>

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
        --dark-iconbg1: {{ theme('dark-iconbg1', 'hsl(217, 91%, 60%)') }};
        --dark-iconbg2: {{ theme('dark-iconbg2', 'hsl(142, 76%, 36%)') }};
        --dark-iconbg3: {{ theme('dark-iconbg3', 'hsl(346, 87%, 43%)') }};
        --dark-icon1: {{ theme('dark-icon1', 'hsl(0, 0%, 100%)') }};
        --dark-icon2: {{ theme('dark-icon2', 'hsl(0, 0%, 100%)') }};
        --dark-icon3: {{ theme('dark-icon3', 'hsl(0, 0%, 100%)') }};
        --iconbg1: {{ theme('iconbg1', 'hsl(217, 91%, 60%)') }};
        --iconbg2: {{ theme('iconbg2', 'hsl(142, 76%, 36%)') }};
        --iconbg3: {{ theme('iconbg3', 'hsl(346, 87%, 43%)') }};
        --icon1: {{ theme('icon1', 'hsl(0, 0%, 100%)') }};
        --icon2: {{ theme('icon2', 'hsl(0, 0%, 100%)') }};
        --icon3: {{ theme('icon3', 'hsl(0, 0%, 100%)') }};
    }

    .fmh-particle {
        position: absolute;
        border-radius: 50%;
        animation: float 25s infinite ease-in-out;
        z-index: 0;
        will-change: transform;
        filter: blur(3px);
    }

    .fmh-plasma-ball {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99,102,241,0.3) 0%, rgba(0,0,0,0) 70%);
        filter: blur(30px);
        z-index: 0;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        20% { transform: translate(15px, 15px) rotate(5deg); }
        40% { transform: translate(20px, -10px) rotate(-5deg); }
        60% { transform: translate(-15px, 20px) rotate(8deg); }
        80% { transform: translate(-10px, -5px) rotate(-3deg); }
    }

    .animation-delay-3000 {
        animation-delay: 3s;
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
</div>