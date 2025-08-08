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
        
        <div class="flex flex-col gap-6 col-span-3 md:col-span-1">
            
            @if (!$category->image || !theme('show_category_image_banner', true))
                <div class="group bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg hover:shadow-xl transform transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                                <x-ri-folder-fill class="size-6" />
                            </div>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-color-base mb-3 group-hover:text-primary transition-colors duration-300">
                            {{ $category->name }}
                        </h1>
                        
                        @if ($category->description)
                            <article class="prose dark:prose-invert text-color-muted leading-relaxed">
                                {!! $category->description !!}
                            </article>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 pb-4 border-b border-neutral/50">
                    <div class="flex items-center justify-center">
                        <div class="bg-primary/10 text-primary p-3 rounded-full">
                            <x-ri-list-check-2 class="size-8" />
                        </div>
                    </div>
                </div>
                
                <nav class="flex flex-col py-2">
                    @foreach ($categories as $ccategory)
                        <a href="{{ route('category.show', ['category' => $ccategory->slug]) }}" wire:navigate
                            class="group/nav px-6 py-3 text-color-base hover:bg-primary/10 hover:text-primary transition-all duration-300 border-l-4 border-transparent hover:border-primary {{ $category->id == $ccategory->id ? 'font-bold text-primary bg-primary/10 border-primary' : '' }}"
                            aria-current="{{ $category->id == $ccategory->id ? 'page' : 'false' }}">
                            <div class="flex items-center justify-between">
                                <span>{{ $ccategory->name }}</span>
                                <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/nav:translate-x-1 opacity-0 group-hover/nav:opacity-100" />
                            </div>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="flex flex-col gap-8 col-span-3">
            
            <!-- Child Categories -->
            @if (count($childCategories) >= 1)
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="hidden md:flex items-center gap-2 text-color-muted">
                            <x-ri-stack-fill class="size-4" />
                            <span class="text-sm">{{ count($childCategories) }} {{ Str::plural('category', count($childCategories)) }}</span>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                                        class="relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-primary text-white shadow-sm">
                                    Monthly
                                </button>
                                <button type="button" 
                                        onclick="setBillingPeriod('yearly')"
                                        id="yearly-btn"
                                        class="relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 text-color-muted hover:text-color-base">
                                    <span>Yearly</span>
                                    <span class="ml-1 bg-primary/10 text-primary px-1.5 py-0.5 rounded-full text-xs font-bold dark:bg-primary/20 group-[.bg-primary]:text-white group-[.bg-primary]:bg-white/20">-50%</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        let currentBillingPeriod = 'monthly';
                        
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
                        
                        function updateProductPrices() {
                            // Update all product prices based on selected billing period
                            const priceElements = document.querySelectorAll('.product-price');
                            
                            priceElements.forEach(element => {
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
                                    displayText = `<span class="line-through text-color-muted">${monthlyActual}</span><span class="ml-1 text-primary font-bold">${yearlyActual.charAt(0)}${monthlyEquivalent} / month</span><br><span class="text-sm text-color-muted">(billed yearly as ${yearlyActual})</span>`;
                                } else {
                                    // For monthly, show monthly price
                                    const monthlyActual = monthlyPrice || defaultPrice;
                                    displayText = `${monthlyActual} / month`;
                                }
                                
                                element.innerHTML = displayText;
                            });
                        }
                    </script>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($products as $index => $product)
                        @php
                            // Check if product is available
                            $isAvailable = ($product->stock !== 0) && $product->price()->available;
                        @endphp

                        <div class="group relative bg-gradient-to-br from-background-secondary via-background-secondary/90 to-background-secondary/70 border border-neutral/50 rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 transition-all duration-500 overflow-hidden animate-fade-in-up" 
                             style="animation-delay: {{ $index * 0.1 }}s;">
                            
                            <div class="absolute top-4 right-4 z-10">
                                <div class="flex items-center gap-2 bg-background-secondary/70 backdrop-blur-sm rounded-full px-3 py-1.5 shadow-lg">
                                    <div class="w-2 h-2 {{ $isAvailable ? 'bg-green-500' : 'bg-red-500' }} rounded-full animate-pulse"></div>
                                    <span class="text-xs font-bold {{ $isAvailable ? 'text-green-600' : 'text-red-600' }} uppercase">
                                        {{ $isAvailable ? $product->stock . ' in stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if(theme('small_images', false))
                                <div class="p-6">
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
                                            <h3 class="text-xl font-bold text-color-base mb-2 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                                {{ $product->name }}
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
                                                    {{ $monthlyPrice }} / month
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        @if ($isAvailable && theme('direct_checkout', false))
                                            <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}" wire:navigate 
                                               class="group/btn w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg hover:scale-105">
                                                {{ __('product.add_to_cart') }}
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
                                    <h3 class="text-xl font-bold text-color-base mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                    
                                    @if(theme('direct_checkout', false) && $product->description)
                                        <article class="prose dark:prose-invert text-color-muted text-sm mb-4 leading-relaxed">
                                            {!! html_entity_decode($product->description) !!}
                                        </article>
                                    @endif
                                    
                                    <div class="flex items-center justify-between mb-6">
                                        <p class="text-2xl font-bold {{ $isAvailable ? 'text-primary' : 'text-color-muted line-through' }}">
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
                                                {{ $monthlyPrice }} / month
                                            </span>
                                        </p>
                                    </div>
                                    
                                    @if ($isAvailable && theme('direct_checkout', false))
                                        <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}" wire:navigate 
                                           class="group/btn w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg hover:scale-105">
                                            {{ __('product.add_to_cart') }}
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
                    @endforeach

                    <!--Custom Plan -->
                    <div class="group relative bg-gradient-to-br from-background-secondary via-background-secondary/90 to-background-secondary/70 border border-neutral/50 rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 transition-all duration-500 overflow-hidden animate-fade-in-up" 
                             style="animation-delay: {{ $index * 0.1 }}s;">
                            
                            <div class="absolute top-4 right-4 z-10">
                                <div class="flex items-center gap-2 bg-background-secondary/70 backdrop-blur-sm rounded-full px-3 py-1.5 shadow-lg">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-bold text-green-600 uppercase">
                                        {{ 'Contact Us' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if(theme('small_images', false))
                                <div class="p-6">
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="flex-shrink-0 relative">
                                            <img src="{{ asset('images/custom-plan.png') }}" alt="Custom Plan"
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
                                        <a href="https://contactus.com" wire:navigate 
                                           class="group/btn w-full inline-flex items-center justify-center gap-2 bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 text-color-base px-4 py-3 rounded-2xl font-medium transition-all duration-300 hover:shadow-lg">
                                            {{ 'Get in Touch' }}
                                            <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="relative overflow-hidden">
                                    <img src="{{ asset('images/custom-plan.png') }}" alt="Custom Enterprise Plan"
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
                                    
                                    <a href="https://contactus.com" wire:navigate 
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
</div>
