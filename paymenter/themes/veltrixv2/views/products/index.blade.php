<div class="grid md:grid-cols-4 gap-4">
    <!-- Left Sidebar -->
    <div class="flex flex-col gap-4">
<style>
:root {
    --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
    --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
    --accent: {{ theme('accent', 'hsl(280, 100%, 70%)') }};
    --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
    --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
    --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
    --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
    --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
    --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
    --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
    --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
    --dark-accent: {{ theme('dark-accent', 'hsl(280, 100%, 70%)') }};
    --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
    --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
    --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
    --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
    --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
    --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
}
</style>

<!-- Category Header -->
<div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] rounded-lg border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-4 shadow-sm">
    <h1 class="text-3xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">{{ $category->name }}</h1>
    <article class="prose prose-gray dark:prose-invert max-w-none">
        {!! $category->description !!}
    </article>
</div>

<!-- Category List -->
<div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] rounded-lg border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-4 shadow-sm">
    <div class="flex flex-col gap-2">
        @foreach ($categories as $ccategory)
            <a href="{{ route('category.show', ['category' => $ccategory->slug]) }}" wire:navigate
               class="px-3 py-2 rounded-md transition-colors duration-200
                      {{ $category->id == $ccategory->id
                         ? 'bg-[var(--primary)]/10 text-[var(--primary)] dark:bg-[var(--dark-primary)]/20 font-semibold'
                         : 'hover:bg-[var(--background-secondary)] dark:hover:bg-[var(--dark-neutral)] text-[var(--muted)] dark:text-[var(--dark-inverted)]' }}">
                {{ $ccategory->name }}
            </a>
        @endforeach
    </div>
</div>
</div>

<!-- Main Content -->
<div class="flex flex-col gap-4 col-span-3">
    @if(count($childCategories) >= 1)
        <!-- Child Categories -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 auto-rows-fr">
            @foreach ($childCategories as $childCategory)
                <div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] rounded-lg border border-[var(--neutral)] dark:border-[var(--dark-neutral)] p-4 shadow-sm hover:shadow-md transition-all flex flex-col h-full">
                    <div class="flex-1">
                        @if($childCategory->image)
                            <img src="{{ Storage::url($childCategory->image) }}" alt="{{ $childCategory->name }}"
                                 class="w-full h-32 object-cover rounded-md mb-3">
                        @endif

                        <h2 class="text-xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-2">{{ $childCategory->name }}</h2>

                        @if(theme('show_category_description', true) && $childCategory->description)
                            <article class="prose prose-sm prose-gray dark:prose-invert mb-3">
                                {!! $childCategory->description !!}
                            </article>
                        @endif
                    </div>

                    <a href="{{ route('category.show', ['category' => $childCategory->slug]) }}" wire:navigate>
                        <button class="gradient-button w-full bg-[var(--primary)] hover:bg-[var(--primary)]/90 text-[var(--inverted)] py-2 px-4 rounded-md text-sm font-medium transition-colors">
                            {{ __('general.view') }}
                        </button>
                    </a>
                </div>
            @endforeach
        </div>
    @endif


<!-- Products Grid -->
<div class="grid sm:grid-cols-1 md:grid-cols-2 gap-6 auto-rows-fr">
    @foreach ($products as $product)
        <div class="bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] border border-[var(--neutral)] dark:border-[var(--dark-neutral)] hover:bg-[var(--background-secondary)]/95 dark:hover:bg-[var(--dark-background-secondary)]/95 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full">
            <div class="flex-1">
            <!-- Product Image -->
            @if($product->image)
                <div class="relative overflow-hidden rounded-lg bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] flex items-center justify-center mb-4">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-contain object-center p-3 hover:scale-[1.02] transition-transform duration-300">
                </div>
            @elseif(theme('level', false))
                <div class="relative overflow-hidden rounded-lg bg-transparent flex items-center justify-center mb-4" style="height: 12rem;">
                    {{-- Transparent placeholder to keep height consistent --}}
                </div>
            @endif


                <!-- Stock Status Badge -->
                @if ($product->stock === 0)
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 w-fit mb-3 inline-block">
                        {{ __('product.out_of_stock', ['product' => $product->name]) }}
                    </span>
                @elseif($product->stock > 0)
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 w-fit mb-3 inline-block">
                        {{ __('product.in_stock') }}
                    </span>
                @endif

                <!-- Product Header -->
                <div class="flex flex-row justify-between items-start gap-4 mb-3">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-1">{{ $product->name }}</h2>
                        <h3 class="text-xl font-semibold text-primary dark:text-primary-300">
                            {{ $product->price() }}
                        </h3>
                    </div>

                    @if ($product->stock !== 0 && $product->price()->available)
                        <button class="p-2 rounded-full bg-[var(--background-secondary)] dark:bg-[var(--dark-background-secondary)] hover:bg-[var(--background-secondary)]/90 dark:hover:bg-[var(--dark-background-secondary)]/90 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-primary dark:text-primary-400">
                                <path d="M6 2a1 1 0 0 0-1 1v1H4a1 1 0 0 0-1 1v1H2v2h1v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8h1V6h-1V4a1 1 0 0 0-1-1h-1V3a1 1 0 0 0-1-1H6zm0 2h12v1h1v1h-1v9a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V6H5V5h1V4zm3 3a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zm5 0a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1z"/>
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Product Description -->
                @if(theme('descript', false) && $product->description)
                    <article class="mb-4 prose prose-sm prose-gray dark:prose-invert max-w-none text-[var(--muted)] dark:text-[var(--dark-muted)]">
                        {!! $product->description !!}
                    </article>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 mt-4">
                @if(($product->stock > 0 || !$product->stock) && $product->price()->available && theme('direct_checkout', false))
                    <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                       wire:navigate class="flex-1">
                        <button class="gradient-button w-full bg-gradient-to-r from-primary to-accent hover:from-primary/90 hover:to-accent/90 text-white font-medium py-2.5 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                            {{ __('product.add_to_cart') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </button>
                    </a>
                @else
                    <a href="{{ route('products.show', ['category' => $product->category, 'product' => $product->slug]) }}"
                       wire:navigate class="flex-1">
                        <button class="gradient-button w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white font-medium py-2.5 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                            {{ __('general.view') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </a>
                @endif

                @if(($product->stock > 0 || !$product->stock) && $product->price()->available && !theme('direct_checkout', false))
                    <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                       wire:navigate class="flex-1">
                        <button class="gradient-button w-full bg-gradient-to-r from-accent to-primary hover:from-accent/90 hover:to-primary/90 text-white font-medium py-2.5 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                            {{ __('product.add_to_cart') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </button>
                    </a>
                @endif
            </div>
        </div>
    @endforeach
</div>

    <!-- FAQ Section -->
    @php
        $faqQuestions = collect();
        $faqServiceAvailable = false;
        
        try {
            if (class_exists('Paymenter\Extensions\Others\FAQ\FAQService')) {
                $faqServiceAvailable = true;
                $faqQuestions = \Paymenter\Extensions\Others\FAQ\FAQService::getQuestionsForCategory($category->id, true);
            }
        } catch (Exception $e) {
            // Silently fail if FAQ service is not available
        }
    @endphp
    
    @if($faqServiceAvailable && $faqQuestions->count() > 0)
        <div class="mt-16">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <path d="M12 17h.01"/>
                    </svg>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-[var(--base)] dark:text-[var(--dark-base)] mb-4">
                    Frequently Asked Questions
                </h2>
                <p class="text-lg text-[var(--muted)] dark:text-[var(--dark-muted)] max-w-2xl mx-auto">
                    Find answers to common questions about {{ $category->name }} and our services.
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="space-y-4">
                    @foreach($faqQuestions as $faq)
                        <div class="group bg-[var(--background-secondary)]/80 dark:bg-[var(--dark-background-secondary)]/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 border border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50">
                            <button class="w-full text-left p-6 focus:outline-none focus:ring-2 focus:ring-primary/20" 
                                    onclick="toggleFAQ({{ $faq->id }})"
                                    aria-expanded="false"
                                    aria-controls="faq-answer-{{ $faq->id }}">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-[var(--base)] dark:text-[var(--dark-base)] group-hover:text-primary transition-colors duration-300 pr-4">
                                        {{ $faq->question }}
                                    </h3>
                                    <div class="flex items-center gap-3">
                                        @if($faq->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="mr-1">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                </svg>
                                                Featured
                                            </span>
                                        @endif
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--muted)] dark:text-[var(--dark-muted)] transform transition-transform duration-300 faq-arrow-{{ $faq->id }}">
                                            <path d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>
                            
                            <div id="faq-answer-{{ $faq->id }}" 
                                 class="faq-answer hidden px-6 pb-6 border-t border-[var(--neutral)]/50 dark:border-[var(--dark-neutral)]/50">
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

        <script>
            function toggleFAQ(faqId) {
                const answer = document.getElementById(`faq-answer-${faqId}`);
                const button = document.querySelector(`[onclick="toggleFAQ(${faqId})"]`);
                const arrow = document.querySelector(`.faq-arrow-${faqId}`);
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
                const faqButtons = document.querySelectorAll('[onclick^="toggleFAQ"]');
                faqButtons.forEach(button => {
                    button.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            const faqId = this.getAttribute('onclick').match(/\d+/)[0];
                            toggleFAQ(faqId);
                        }
                    });
                });
            });
        </script>
    @endif
@endif

<style>
:root {
    --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
    --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
    --accent: {{ theme('accent', 'hsl(280, 100%, 70%)') }};
    --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
    --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
    --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
    --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
    --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
    --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
    --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
    --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
    --dark-accent: {{ theme('dark-accent', 'hsl(280, 100%, 70%)') }};
    --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
    --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
    --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
    --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
    --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
    --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
}

/* Gradient Button */
.gradient-button {
    background: linear-gradient(to bottom right, var(--primary), var(--secondary));
    transition: all 0.3s ease;
}

.gradient-button:hover {
    background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
    transform: translateY(-1px);
}

/* Enhanced card hover effects */
.hover\:shadow-md:hover {
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}
</style>
{!! theme('custom_layout_css', '') !!}