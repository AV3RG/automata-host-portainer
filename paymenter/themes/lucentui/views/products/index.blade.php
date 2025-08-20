<div class="mx-auto container mt-8 mb-12 p-6 sm:px-6 lg:px-8">
    @if ($category->image && theme('show_category_image_banner', true))
        <div class="mb-8 rounded-2xl overflow-hidden shadow-xl">
            <div class="relative h-48 md:h-60 lg:h-72">
                <img src="{{ Storage::url($category->image) }}" 
                     alt="{{ $category->name }}"
                     class="w-full h-full object-cover object-center">
                
                <div class="absolute inset-0 bg-gradient-to-t from-primary/30 via-primary/15 to-primary/10"></div>
                
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-primary/30 backdrop-blur-sm text-primary p-2.5 rounded-full shadow-lg">
                            <x-ri-folder-fill class="size-5" />
                        </div>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white text-shadow-lg mb-2 drop-shadow-2xl">
                        {{ $category->name }}
                    </h1>
                    @if ($category->description)
                        <div class="hidden md:block text-white md:text-lg text-shadow-lg max-w-3xl drop-shadow-lg ">
                            {!! Str::limit($category->description, 215, '...') !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid md:grid-cols-4 gap-8 lg:gap-12">
        
        <div class="flex flex-col gap-8 col-span-4">
            
            <!-- Child Categories -->
            @if (count($childCategories) >= 1)
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="hidden md:flex items-center gap-2 text-color-muted">
                            <x-ri-stack-fill class="size-4" />
                            <span class="text-sm">{{ count($childCategories) }} {{ Str::plural('category', count($childCategories)) }}</span>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($childCategories as $index => $childCategory)
                            @php
                                $hasImage = !empty($childCategory->image);
                            @endphp

                            <div class="group relative bg-gradient-to-br from-background-secondary via-background-secondary/90 to-background-secondary/70 border border-neutral/50 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 transition-all duration-500 overflow-hidden animate-fade-in-up" 
                                 style="animation-delay: {{ $index * 0.1 }}s;">
                                
                                @if ($hasImage)
                                    <div class="relative overflow-hidden">
                                        <img src="{{ Storage::url($childCategory->image) }}" alt="{{ $childCategory->name }}"
                                            class="w-full h-56 object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                @endif
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-color-base mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        {{ $childCategory->name }}
                                    </h3>
                                    
                                    @if(theme('show_category_description', true) && $childCategory->description)
                                        <article class="prose dark:prose-invert text-color-muted text-sm mb-4 leading-relaxed">
                                            {!! $childCategory->description !!}
                                        </article>
                                    @endif
                                    
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('category.show', ['category' => $childCategory->slug]) }}" wire:navigate 
                                           class="group/btn inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg hover:scale-105">
                                            {{ __('general.view') }}
                                            <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Products -->
            <div>
                @php
                    // Count most popular products (lightweight, without debug)
                    $mostPopularCount = 0;
                    foreach ($products as $p) {
                        try {
                            if (method_exists($p, 'hasTag') && $p->hasTag('most_popular')) {
                                $mostPopularCount++;
                            } elseif (method_exists($p, 'tags')) {
                                $mostPopularCount += $p->tags()->where('slug', 'most_popular')->exists() ? 1 : 0;
                            }
                        } catch (Exception $e) {}
                    }
                @endphp
                @if($mostPopularCount > 0)
                    <div class="mb-2 text-sm text-color-muted flex items-center gap-2">
                        <x-ri-star-fill class="size-4 text-yellow-500" />
                        <span>Highlighted: {{ $mostPopularCount }} {{ Str::plural('product', $mostPopularCount) }}</span>
                    </div>
                @endif
                
                <div class="flex items-center justify-between mb-6">
                    <div class="hidden md:flex items-center gap-2 text-color-muted">
                        <x-ri-price-tag-3-fill class="size-4" />
                        <span class="text-sm">{{ count($products) }} {{ Str::plural('product', count($products)) }}</span>
                    </div>
                    
                    <!-- Billing Period Chooser -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-color-muted">Billing Period:</span>
                        <div class="relative bg-background-tertiary border border-neutral/50 rounded-full p-1 shadow-sm">
                            <div class="flex items-center">
                                <button type="button" 
                                        onclick="setBillingPeriod('monthly')"
                                        id="monthly-btn"
                                        class="relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 text-color-muted hover:text-color-base">
                                    Monthly
                                </button>
                                <button type="button" 
                                        onclick="setBillingPeriod('yearly')"
                                        id="yearly-btn"
                                        class="relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-primary text-white shadow-sm">
                                    <span>Yearly</span>
                                    <span class="ml-1 bg-white/20 text-white px-1.5 py-0.5 rounded-full text-xs font-bold">-50%</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        let currentBillingPeriod = 'yearly';
                        
                        function setBillingPeriod(period) {
                            currentBillingPeriod = period;
                            
                            // Update button styles
                            const monthlyBtn = document.getElementById('monthly-btn');
                            const yearlyBtn = document.getElementById('yearly-btn');
                            
                            if (period === 'monthly') {
                                monthlyBtn.className = 'relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-primary text-white shadow-sm';
                                yearlyBtn.className = 'relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 text-color-muted hover:text-color-base';
                                yearlyBtn.querySelector('span:last-child').className = 'ml-1 bg-primary/10 text-primary px-1.5 py-0.5 rounded-full text-xs font-bold dark:bg-primary/20';
                            } else {
                                monthlyBtn.className = 'relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 text-color-muted hover:text-color-base';
                                yearlyBtn.className = 'relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-primary text-white shadow-sm';
                                yearlyBtn.querySelector('span:last-child').className = 'ml-1 bg-white/20 text-white px-1.5 py-0.5 rounded-full text-xs font-bold';
                            }
                            
                            // Update all product prices
                            updateProductPrices();
                        }
                        
                        // Initialize with yearly pricing on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            // Set initial state to yearly
                            currentBillingPeriod = 'yearly';
                            
                            // Ensure yearly button is properly styled initially
                            const yearlyBtn = document.getElementById('yearly-btn');
                            if (yearlyBtn) {
                                yearlyBtn.className = 'relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-primary text-white shadow-sm';
                            }
                            
                            // Add a small delay to ensure all elements are properly loaded
                            setTimeout(() => {
                                updateProductPrices();
                            }, 100);
                        });
                        
                        // Also try to initialize when the page is fully loaded
                        window.addEventListener('load', function() {
                            if (currentBillingPeriod === 'yearly') {
                                setTimeout(() => {
                                    updateProductPrices();
                                }, 50);
                            }
                        });
                        
                        // Additional fallback initialization
                        let initAttempts = 0;
                        const maxInitAttempts = 5;
                        
                        function attemptInitialization() {
                            if (initAttempts >= maxInitAttempts) return;
                            
                            const priceElements = document.querySelectorAll('.product-price');
                            if (priceElements.length > 0) {
                                updateProductPrices();
                            } else {
                                initAttempts++;
                                setTimeout(attemptInitialization, 200);
                            }
                        }
                        
                        // Try initialization after a longer delay as fallback
                        setTimeout(attemptInitialization, 500);
                        
                        // Listen for Livewire page updates
                        document.addEventListener('livewire:navigated', function() {
                            setTimeout(() => {
                                currentBillingPeriod = 'yearly';
                                updateProductPrices();
                            }, 100);
                        });
                        
                        // Also listen for any Livewire updates
                        document.addEventListener('livewire:updated', function() {
                            setTimeout(() => {
                                updateProductPrices();
                            }, 50);
                        });
                        
                        function updateProductPrices() {
                            try {
                                // Update all product prices based on selected billing period
                                const priceElements = document.querySelectorAll('.product-price');
                                
                                priceElements.forEach(element => {
                                    if (!element) return;
                                    
                                    const monthlyPrice = element.getAttribute('data-monthly');
                                    const yearlyPrice = element.getAttribute('data-yearly');
                                    const defaultPrice = element.getAttribute('data-default');
                                    const monthlyRaw = element.getAttribute('data-monthly-raw');
                                    const yearlyRaw = element.getAttribute('data-yearly-raw');
                                    
                                    let displayText;
                                    if (currentBillingPeriod === 'yearly') {
                                        // For yearly, show slashed pricing with strike-through
                                        const yearlyActual = yearlyRaw || defaultPrice;
                                        const monthlyActual = monthlyPrice || defaultPrice;
                                        // Extract numeric value from yearly price and divide by 12
                                        const yearlyNumeric = parseFloat(yearlyActual.replace(/[^0-9.]/g, ''));
                                        const monthlyEquivalent = (yearlyNumeric / 12).toFixed(2);
                                        
                                        // Extract currency symbol from the original price
                                        const currencyMatch = yearlyActual.match(/^[^\d]*/);
                                        const currencySymbol = currencyMatch ? currencyMatch[0] : '$';
                                        
                                        displayText = `<span class="line-through text-color-muted text-sm">${monthlyActual}</span><span class="ml-1 text-primary font-bold">${currencySymbol}${monthlyEquivalent} / month</span>`;
                                    } else {
                                        // For monthly, show monthly price
                                        const monthlyActual = monthlyPrice || defaultPrice;
                                        displayText = `${monthlyActual} / month`;
                                    }
                                    
                                    if (displayText) {
                                        element.innerHTML = displayText;
                                    }
                                });
                                
                                // Update checkout links with the correct plan ID
                                updateCheckoutLinks();
                            } catch (error) {
                                console.error('Error updating product prices:', error);
                            }
                        }
                        
                        function updateCheckoutLinks() {
                            try {
                                const checkoutLinks = document.querySelectorAll('.checkout-link');
                                
                                checkoutLinks.forEach(link => {
                                    if (!link) return;
                                    
                                    // Ensure button text is preserved
                                    const buttonText = link.querySelector('.button-text');
                                    if (!buttonText || !buttonText.textContent.trim()) {
                                        const textSpan = document.createElement('span');
                                        textSpan.className = 'button-text';
                                        textSpan.textContent = '{{ __("product.add_to_cart") }}';
                                        link.insertBefore(textSpan, link.firstChild);
                                    }
                                    
                                    const monthlyPlanId = link.getAttribute('data-monthly-plan');
                                    const yearlyPlanId = link.getAttribute('data-yearly-plan');
                                    const defaultPlanId = link.getAttribute('data-default-plan');
                                    
                                    let selectedPlanId;
                                    if (currentBillingPeriod === 'yearly' && yearlyPlanId) {
                                        selectedPlanId = yearlyPlanId;
                                    } else if (currentBillingPeriod === 'monthly' && monthlyPlanId) {
                                        selectedPlanId = monthlyPlanId;
                                    } else {
                                        selectedPlanId = defaultPlanId;
                                    }
                                    
                                    // Only update if we have a valid plan ID
                                    if (selectedPlanId) {
                                        const currentHref = link.getAttribute('href');
                                        if (currentHref) {
                                            const baseUrl = currentHref.split('?')[0];
                                            link.setAttribute('href', `${baseUrl}?plan=${selectedPlanId}`);
                                        }
                                    }
                                });
                            } catch (error) {
                                console.error('Error updating checkout links:', error);
                            }
                        }
                    </script>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch mt-10">
                    @php
                        // Reorder products so in-stock items come first
                        $productsCollection = collect($products);
                        [$inStock, $outOfStock] = $productsCollection->partition(function ($p) {
                            try {
                                return ($p->stock !== 0) && $p->price()->available;
                            } catch (Exception $e) {
                                return true; // fail open to avoid hiding items
                            }
                        });
                        $sortedProducts = $inStock->concat($outOfStock);
                    @endphp
                    @foreach ($sortedProducts as $index => $product)
                        @php
                            // Check if product is available
                            $isAvailable = ($product->stock !== 0) && $product->price()->available;
                            
                            // Get product tags for debugging
                            $productTags = collect();
                            try {
                                if (method_exists($product, 'tags')) {
                                    $productTags = $product->tags()->get();
                                }
                            } catch (Exception $e) {
                                // Silently handle any errors
                            }
                        @endphp

                        @php
                            // Check if product has the "most_popular" tag or "most-popular" slug
                            $isMostPopular = false;
                            $popularSlugs = ['most_popular', 'most-popular'];
                            try {
                                if (method_exists($product, 'hasTag')) {
                                    foreach ($popularSlugs as $slug) {
                                        if ($product->hasTag($slug)) { $isMostPopular = true; break; }
                                    }
                                } elseif (method_exists($product, 'tags')) {
                                    $isMostPopular = $product->tags()->whereIn('slug', $popularSlugs)->exists();
                                } else {
                                    $isMostPopular = \DB::table('ext_product_tag_assignments')
                                        ->join('ext_product_tags', 'ext_product_tag_assignments.tag_id', '=', 'ext_product_tags.id')
                                        ->where('ext_product_tag_assignments.product_id', $product->id)
                                        ->whereIn('ext_product_tags.slug', $popularSlugs)
                                        ->where('ext_product_tags.is_active', true)
                                        ->exists();
                                }
                            } catch (Exception $e) {}
                        @endphp
                        
                        <div class="relative animate-fade-in-up h-full" style="animation-delay: {{ $index * 0.1 }}s;">
                            @if($isMostPopular)
                                <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-20">
                                    <div class="px-3 py-1.5 rounded-full bg-yellow-400 text-white text-xs font-bold uppercase tracking-wide shadow-md">
                                        {{ __('Most Popular') }}
                                    </div>
                                </div>
                            @endif
                            @php
                                // Check if product has the "for-agency" tag or "for_agency" slug
                                $isForAgency = false;
                                $agencySlugs = ['for-agency', 'for_agency'];
                                try {
                                    if (method_exists($product, 'hasTag')) {
                                        foreach ($agencySlugs as $slug) {
                                            if ($product->hasTag($slug)) { $isForAgency = true; break; }
                                        }
                                    } elseif (method_exists($product, 'tags')) {
                                        $isForAgency = $product->tags()->whereIn('slug', $agencySlugs)->exists();
                                    } else {
                                        $isForAgency = \DB::table('ext_product_tag_assignments')
                                            ->join('ext_product_tags', 'ext_product_tag_assignments.tag_id', '=', 'ext_product_tags.id')
                                            ->where('ext_product_tag_assignments.product_id', $product->id)
                                            ->whereIn('ext_product_tags.slug', $agencySlugs)
                                            ->where('ext_product_tags.is_active', true)
                                            ->exists();
                                    }
                                } catch (Exception $e) {}
                            @endphp
                            @if($isForAgency)
                                <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-20">
                                    <div class="px-3 py-1.5 rounded-full bg-purple-500 text-white text-xs font-bold uppercase tracking-wide shadow-md">
                                        {{ __('For Agency') }}
                                    </div>
                                </div>
                            @endif
                            <div class="group relative h-full bg-gradient-to-br from-background-secondary via-background-secondary/90 to-background-secondary/70 rounded-3xl hover:shadow-2xl transform hover:-translate-y-3 transition-all duration-500 overflow-hidden {{ $isMostPopular ? 'border-2 border-yellow-400 shadow-lg' : ($isForAgency ? 'border-2 border-purple-500 shadow-lg' : 'border border-neutral/50 shadow-xl') }}">
                            
                            @if($isMostPopular)
                                
                            @endif
                            
                            
                            
                            @if(theme('small_images', false))
                                <div class="p-6">
                                    @if($isMostPopular)
                                        <div class="mb-3">
                                            <div class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 px-2.5 py-1 rounded-full shadow">
                                                <x-ri-star-fill class="size-4" />
                                                <span class="text-xs font-semibold uppercase">Most Popular</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($isForAgency)
                                        <div class="mb-3">
                                            <div class="inline-flex items-center gap-2 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 px-2.5 py-1 rounded-full shadow">
                                                <x-ri-star-fill class="size-4" />
                                                <span class="text-xs font-semibold uppercase">For Agency</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex items-start gap-4 mb-4">
                                        @if ($product->image)
                                            <div class="flex-shrink-0 relative">
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                    class="w-20 h-20 object-cover rounded-2xl shadow-lg {{ !$isAvailable ? 'filter grayscale opacity-60' : '' }}">
                                                @if (!$isAvailable)
                                                    <div class="absolute inset-0 bg-red-500/20 rounded-2xl"></div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold {{ $isMostPopular ? 'text-yellow-700 dark:text-yellow-300' : 'text-color-base' }} mb-2 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                                {{ $product->name }}
                                                @if($isMostPopular)
                                                    <span class="ml-2 text-yellow-500">⭐</span>
                                                @endif
                                                @if($isForAgency)
                                                    <span class="ml-2 text-purple-500">⭐</span>
                                                @endif
                                            </h3>
                                            <p class="text-2xl font-bold {{ $isAvailable ? 'text-primary' : 'text-color-muted line-through' }} mb-2">
                                                @php
                                                    $availablePlans = $product->availablePlans();
                                                    $monthlyPlan = $availablePlans->first(function($plan) {
                                                        return in_array(strtolower($plan->billing_unit), ['month', 'monthly']);
                                                    });
                                                    $yearlyPlan = $availablePlans->first(function($plan) {
                                                        return in_array(strtolower($plan->billing_unit), ['year', 'yearly', 'annual']);
                                                    });
                                                    
                                                    $defaultPrice = $product->price();
                                                    $monthlyPrice = $monthlyPlan ? $monthlyPlan->price() : $defaultPrice;
                                                    $yearlyPrice = $yearlyPlan ? $yearlyPlan->price() : $defaultPrice;
                                                @endphp
                                                <span class="product-price" 
                                                      data-monthly="{{ $monthlyPrice }}" 
                                                      data-yearly="{{ $yearlyPrice }}"
                                                      data-default="{{ $defaultPrice }}"
                                                      data-monthly-raw="{{ $monthlyPlan ? $monthlyPlan->price() : $product->price() }}"
                                                      data-yearly-raw="{{ $yearlyPlan ? $yearlyPlan->price() : $product->price() }}">
                                                    {{ $yearlyPrice }} / year
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        @if ($isAvailable && theme('direct_checkout', false))
                                            @php
                                                $availablePlans = $product->availablePlans();
                                                $monthlyPlan = $availablePlans->first(function($plan) {
                                                    return in_array(strtolower($plan->billing_unit), ['month', 'monthly']);
                                                });
                                                $yearlyPlan = $availablePlans->first(function($plan) {
                                                    return in_array(strtolower($plan->billing_unit), ['year', 'yearly', 'annual']);
                                                });
                                                
                                                // Default to yearly plan if available, otherwise monthly, otherwise first available plan
                                                $defaultPlan = $yearlyPlan ?: $monthlyPlan ?: $availablePlans->first();
                                                $defaultPlanId = $defaultPlan ? $defaultPlan->id : null;
                                            @endphp
                                            <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}?plan={{ $defaultPlanId }}" wire:navigate 
                                               class="group/btn w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg hover:scale-105 checkout-link"
                                               data-monthly-plan="{{ $monthlyPlan ? $monthlyPlan->id : '' }}"
                                               data-yearly-plan="{{ $yearlyPlan ? $yearlyPlan->id : '' }}"
                                               data-default-plan="{{ $defaultPlanId }}">
                                                <span class="button-text">{{ __('product.add_to_cart') }}</span>
                                                <x-ri-shopping-cart-fill class="size-4 transform transition-transform duration-300 group-hover/btn:scale-110" />
                                            </a>
                                        @elseif ($isAvailable)
                                            <a href="{{ route('products.show', ['category' => $product->category, 'product' => $product->slug]) }}" wire:navigate 
                                               class="group/btn w-full inline-flex items-center justify-center gap-2 bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 text-color-base px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg">
                                                {{ __('general.view') }}
                                                <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                            </a>
                                        @else
                                            <button disabled 
                                                    class="w-full inline-flex items-center justify-center gap-2 bg-red-100 dark:bg-red-900/30 border border-red-500 text-red-600 dark:text-red-400 px-4 py-3 rounded-2xl font-medium cursor-not-allowed opacity-75">
                                                {{ __('product.out_of_stock', ['product' => $product->name]) }}
                                                <x-ri-close-circle-fill class="size-4" />
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @else
                                @if ($product->image)
                                    <div class="relative overflow-hidden">
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-56 object-cover {{ !$isAvailable ? 'filter grayscale opacity-60' : '' }}">
                                        @if (!$isAvailable)
                                            <div class="absolute inset-0 bg-red-500/20"></div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                @endif
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold {{ $isMostPopular ? 'text-yellow-700 dark:text-yellow-300' : 'text-color-base' }} mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        {{ $product->name }}
                                        @if($isMostPopular)
                                            <span class="ml-2 text-yellow-500">⭐</span>
                                        @endif
                                        @if($isForAgency)
                                            <span class="ml-2 text-purple-500">⭐</span>
                                        @endif
                                    </h3>
                                    
                                    @if(theme('direct_checkout', false) && $product->description)
                                        <article class="prose dark:prose-invert text-sm mb-4 leading-relaxed">
                                            {!! html_entity_decode($product->description) !!}
                                        </article>
                                    @endif
                                    
                                    <div class="flex items-center justify-between mb-6">
                                        <p class="text-2xl font-bold text-primary">
                                            @php
                                                $availablePlans = $product->availablePlans();
                                                $monthlyPlan = $availablePlans->first(function($plan) {
                                                    return in_array(strtolower($plan->billing_unit), ['month', 'monthly']);
                                                });
                                                $yearlyPlan = $availablePlans->first(function($plan) {
                                                    return in_array(strtolower($plan->billing_unit), ['year', 'yearly', 'annual']);
                                                });
                                                
                                                $defaultPrice = $product->price();
                                                $monthlyPrice = $monthlyPlan ? $monthlyPlan->price() : $defaultPrice;
                                                $yearlyPrice = $yearlyPlan ? $yearlyPlan->price() : $defaultPrice;
                                            @endphp
                                            <span class="product-price" 
                                                  data-monthly="{{ $monthlyPrice }}" 
                                                  data-yearly="{{ $yearlyPrice }}"
                                                  data-default="{{ $defaultPrice }}"
                                                  data-monthly-raw="{{ $monthlyPlan ? $monthlyPlan->price() : $product->price() }}"
                                                  data-yearly-raw="{{ $yearlyPlan ? $yearlyPlan->price() : $product->price() }}">
                                                {{ $yearlyPrice }} / year
                                            </span>
                                        </p>
                                    </div>
                                    
                                    @if ($isAvailable && theme('direct_checkout', false))
                                        @php
                                            $availablePlans = $product->availablePlans();
                                            $monthlyPlan = $availablePlans->first(function($plan) {
                                                return in_array(strtolower($plan->billing_unit), ['month', 'monthly']);
                                            });
                                            $yearlyPlan = $availablePlans->first(function($plan) {
                                                return in_array(strtolower($plan->billing_unit), ['year', 'yearly', 'annual']);
                                            });
                                            
                                            // Default to yearly plan if available, otherwise monthly, otherwise first available plan
                                            $defaultPlan = $yearlyPlan ?: $monthlyPlan ?: $availablePlans->first();
                                            $defaultPlanId = $defaultPlan ? $defaultPlan->id : null;
                                        @endphp
                                        <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}?plan={{ $defaultPlanId }}" wire:navigate 
                                           class="group/btn w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg hover:scale-105 checkout-link"
                                           data-monthly-plan="{{ $monthlyPlan ? $monthlyPlan->id : '' }}"
                                           data-yearly-plan="{{ $yearlyPlan ? $yearlyPlan->id : '' }}"
                                           data-default-plan="{{ $defaultPlanId }}">
                                            <span class="button-text">{{ __('product.add_to_cart') }}</span>
                                            <x-ri-shopping-cart-fill class="size-4 transform transition-transform duration-300 group-hover/btn:scale-110" />
                                        </a>
                                    @elseif ($isAvailable)
                                        <a href="{{ route('products.show', ['category' => $product->category, 'product' => $product->slug]) }}" wire:navigate 
                                           class="group/btn w-full inline-flex items-center justify-center gap-2 bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 text-color-base px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg">
                                            {{ __('general.view') }}
                                            <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                        </a>
                                    @else
                                        <button disabled 
                                                class="w-full inline-flex items-center justify-center gap-2 bg-red-100 dark:bg-red-900/30 border border-red-500 text-red-600 dark:text-red-400 px-4 py-3 rounded-2xl font-medium cursor-not-allowed opacity-75">
                                            {{ __('product.out_of_stock', ['product' => $product->name]) }}
                                            <x-ri-close-circle-fill class="size-4" />
                                        </button>
                                    @endif
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-br {{ $isAvailable ? 'from-primary/5 to-primary/10' : 'from-red-500/5 to-red-500/10' }} opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                            </div>
                        </div>
                    @endforeach

                    <!--Custom Plan -->
                    <div class="group relative bg-gradient-to-br from-background-secondary via-background-secondary/90 to-background-secondary/70 border border-neutral/50 rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 transition-all duration-500 overflow-hidden animate-fade-in-up" 
                             style="animation-delay: {{ $index * 0.1 }}s;">
                            
                            @if(theme('small_images', false))
                                <div class="p-6">
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="flex-shrink-0 relative">
                                            <img src="{{ asset('assets/extended/n8n_enterprise_graphic.webp') }}" alt="Custom Plan"
                                                class="w-20 h-20 object-cover rounded-2xl shadow-lg">
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-color-base mb-2 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                                {{ 'Custom Enterprise Plan' }}
                                            </h3>
                                            <p class="text-sm text-color-muted mb-2">
                                                Contact us for a customized solution tailored to your needs
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="https://forms.automata.host/enterprise/form" target="_blank"
                                           class="group/btn w-full inline-flex items-center justify-center gap-2 bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 text-color-base px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg">
                                            {{ 'Get in Touch' }}
                                            <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="relative overflow-hidden">
                                    <img src="{{ asset('assets/extended/n8n_enterprise_graphic.webp') }}" alt="Custom Enterprise Plan"
                                        class="w-full h-56 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                </div>
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-color-base mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        {{ 'Custom Enterprise Plan' }}
                                    </h3>
                                    
                                    <article class="prose dark:prose-invert text-color-muted text-sm mb-4 leading-relaxed">
                                        Need a custom solution? Let's discuss your specific requirements and create a plan that perfectly matches your business needs.
                                    </article>
                                    
                                    <div class="flex items-center justify-between mb-6">
                                        <p class="text-xl font-bold text-primary">
                                            Custom Pricing
                                        </p>
                                    </div>
                                    
                                    <a href="https://forms.automata.host/enterprise/form" target="_blank" 
                                       class="group/btn w-full inline-flex items-center justify-center gap-2 bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 text-color-base px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg">
                                        {{ 'Get in Touch' }}
                                        <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                    </a>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    @php
        $faqQuestions = collect();
        $faqServiceAvailable = false;
        $debugInfo = [];
        
        try {
            // Test 1: Check if extension directory exists
            $extensionPath = base_path('extensions/Others/FAQ');
            $debugInfo[] = 'Extension path exists: ' . (is_dir($extensionPath) ? 'Yes' : 'No');
            
            // Test 2: Check if FAQService class exists
            if (class_exists('Paymenter\Extensions\Others\FAQ\FAQService')) {
                $faqServiceAvailable = true;
                $debugInfo[] = 'FAQService class found';
                
                // Test 3: Check if FAQQuestion model exists
                if (class_exists('Paymenter\Extensions\Others\FAQ\Models\FAQQuestion')) {
                    $debugInfo[] = 'FAQQuestion model found';
                    
                    // Test 4: Check if table exists
                    try {
                        $tableExists = \Schema::hasTable('ext_faq_questions');
                        $debugInfo[] = 'Table ext_faq_questions exists: ' . ($tableExists ? 'Yes' : 'No');
                        
                        if ($tableExists) {
                            // Test 5: Try to get questions
                            try {
                                $faqQuestions = \Paymenter\Extensions\Others\FAQ\FAQService::getQuestionsForCategory($category->id, true);
                                $debugInfo[] = 'Questions retrieved: ' . $faqQuestions->count();
                            } catch (Exception $e) {
                                $debugInfo[] = 'Error getting questions: ' . $e->getMessage();
                            }
                        }
                    } catch (Exception $e) {
                        $debugInfo[] = 'Error checking table: ' . $e->getMessage();
                    }
                } else {
                    $debugInfo[] = 'FAQQuestion model not found';
                }
            } else {
                $debugInfo[] = 'FAQService class not found';
            }
            
            // Test 6: Check if extension is enabled in Paymenter
            try {
                $extensions = app('extensions') ?? collect();
                $faqExtension = $extensions->first(function($ext) {
                    return str_contains(get_class($ext), 'FAQ');
                });
                $debugInfo[] = 'FAQ Extension enabled: ' . ($faqExtension ? 'Yes' : 'No');
            } catch (Exception $e) {
                $debugInfo[] = 'Error checking extensions: ' . $e->getMessage();
            }
            
        } catch (Exception $e) {
            $debugInfo[] = 'Exception: ' . $e->getMessage();
        }
    @endphp
    
    <!-- FAQ Section -->
    @if($faqServiceAvailable)
        @if($faqQuestions->count() > 0)
            <div class="mt-16">
                <!-- Debug Info (remove after testing) -->
                @if(config('app.debug'))
                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 rounded text-sm">
                        <strong>Debug Info:</strong><br>
                        FAQ Service Available: {{ $faqServiceAvailable ? 'Yes' : 'No' }}<br>
                        FAQ Questions Count: {{ $faqQuestions->count() }}<br>
                        Category ID: {{ $category->id }}<br>
                        <strong>Debug Steps:</strong><br>
                        @foreach($debugInfo as $info)
                            • {{ $info }}<br>
                        @endforeach
                        <strong>FAQ Questions:</strong><br>
                        <pre>{{ $faqQuestions->toJson(JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
                
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 text-primary rounded-full mb-4">
                        <x-ri-question-line class="size-8" />
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-color-base mb-4">
                        Frequently Asked Questions
                    </h2>
                    <p class="text-lg text-color-muted max-w-2xl mx-auto">
                        Find answers to common questions about {{ $category->name }} and our services.
                    </p>
                </div>

                <div class="max-w-4xl mx-auto">
                    <div class="space-y-4">
                        @foreach($faqQuestions as $faq)
                            <div class="group bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <button class="w-full text-left p-6 focus:outline-none focus:ring-2 focus:ring-primary/20" 
                                        onclick="toggleFAQ({{ $faq->id }})"
                                        aria-expanded="false"
                                        aria-controls="faq-answer-{{ $faq->id }}">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-color-base group-hover:text-primary transition-colors duration-300 pr-4">
                                            {{ $faq->question }}
                                        </h3>
                                        <div class="flex items-center gap-3">
                                            @if($faq->is_featured)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                    <x-ri-star-fill class="size-3 mr-1" />
                                                    Featured
                                                </span>
                                            @endif
                                            <x-ri-arrow-down-s-line class="size-5 text-color-muted transform transition-transform duration-300 faq-arrow-{{ $faq->id }}" />
                                        </div>
                                    </div>
                                </button>
                                
                                <div id="faq-answer-{{ $faq->id }}" 
                                     class="faq-answer overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0 px-0 pb-0 border-t-0 transform origin-top border-neutral/50">
                                    <div class="pt-4 px-6 pb-6 transform transition-transform duration-300">
                                        <article class="prose dark:prose-invert text-color-muted leading-relaxed max-w-none">
                                            {!! $faq->answer !!}
                                        </article>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <script>
                    function toggleFAQ(faqId) {
                        const answer = document.getElementById(`faq-answer-${faqId}`);
                        const button = document.querySelector(`[onclick="toggleFAQ(${faqId})"]`);
                        const arrow = document.querySelector(`.faq-arrow-${faqId}`);
                        const content = answer.querySelector('.pt-4');
                        const isExpanded = answer.style.maxHeight && answer.style.maxHeight !== '0px';

                        // Toggle visibility with smooth animation
                        if (!isExpanded) {
                            // Expand
                            answer.style.maxHeight = answer.scrollHeight + 'px';
                            answer.style.opacity = '1';
                            answer.style.paddingLeft = '1.5rem';
                            answer.style.paddingRight = '1.5rem';
                            answer.style.paddingBottom = '1.5rem';
                            answer.style.borderTopWidth = '1px';
                            content.style.transform = 'scaleY(1)';
                            button.setAttribute('aria-expanded', 'true');
                            arrow.classList.add('rotate-180');
                        } else {
                            // Collapse
                            answer.style.maxHeight = '0px';
                            answer.style.opacity = '0';
                            answer.style.paddingLeft = '0';
                            answer.style.paddingRight = '0';
                            answer.style.paddingBottom = '0';
                            answer.style.borderTopWidth = '0px';
                            content.style.transform = 'scaleY(0.95)';
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
            </div>
        @else
            <!-- No FAQs available -->
            @if(config('app.debug'))
                <div class="mt-16">
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 text-gray-400 rounded-full mb-4">
                            <x-ri-question-line class="size-8" />
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-400 mb-4">
                            No FAQ Questions Available
                        </h2>
                        <p class="text-lg text-gray-500 max-w-2xl mx-auto">
                            No FAQ questions have been added for {{ $category->name }} yet.
                        </p>
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                            <strong>Debug Info:</strong><br>
                            FAQ Service Available: {{ $faqServiceAvailable ? 'Yes' : 'No' }}<br>
                            FAQ Questions Count: {{ $faqQuestions->count() }}<br>
                            Category ID: {{ $category->id }}<br>
                            <strong>Debug Steps:</strong><br>
                            @foreach($debugInfo as $info)
                                • {{ $info }}<br>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @else
        <!-- FAQ Service not available -->
        @if(config('app.debug'))
            <div class="mt-16">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-400 rounded-full mb-4">
                        <x-ri-error-warning-line class="size-8" />
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-red-400 mb-4">
                        FAQ Service Not Available
                    </h2>
                    <p class="text-lg text-red-500 max-w-2xl mx-auto">
                        The FAQ extension is not properly loaded or configured.
                    </p>
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                        <strong>Debug Info:</strong><br>
                        FAQ Service Available: {{ $faqServiceAvailable ? 'Yes' : 'No' }}<br>
                        <strong>Debug Steps:</strong><br>
                        @foreach($debugInfo as $info)
                            • {{ $info }}<br>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
