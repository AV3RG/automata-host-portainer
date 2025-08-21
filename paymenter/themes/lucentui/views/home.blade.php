
<div class="min-h-screen">

    <!-- Homepage-specific custom HTML -->
    @if(theme('custom_homepage_html'))
        <div class="custom-homepage-content">
            {!! theme('custom_homepage_html') !!}
        </div>
    @endif

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="container mx-auto px-4 py-24 relative z-10">
            <div class="text-center max-w-5xl mx-auto">
                <!-- Hero Badge -->
                <div class="animate-fade-in-down">
                    <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-primary/10 to-secondary/10 border-2 border-primary/20 mb-8 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-primary">{{ theme('homepage_hero_badge', '🚀 Welcome to Paymenter - Now with 50% faster deployment!') }}</span>
                        </div>
                        <span style="display:none;">05dc38bc14ae6ecd1610d556b1bfe890</span>
                    </div>
                </div>

                <!-- Hero Title -->
                <div class="mb-10 animate-fade-in-up">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-color-base mb-6 leading-tight">
                        {{ theme('homepage_hero_title1', 'Premium') }} 
                        <span class="bg-primary bg-clip-text text-transparent">
                            {{ theme('homepage_hero_title2', 'Game Hosting') }}
                        </span>
                    </h1>
                </div>

                <!-- Hero Description -->
                <div class="mb-12 animate-fade-in-up animation-delay-200">
                    <p class="text-l md:text-xl text-color-muted max-w-4xl mx-auto leading-relaxed">
                        {{ theme('homepage_hero_desc', 'Experience next-generation game hosting with blazing-fast SSD performance, enterprise-grade security, and 24/7 expert support. Deploy your servers instantly across our global network of premium datacenters and enjoy zero-lag gaming with 99.9% uptime guarantee. Join thousands of satisfied gamers who trust our platform for their competitive edge.') }}
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in-up animation-delay-400 mb-12">
                    <a href="#services" class="group bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary text-white px-10 py-5 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-primary/30">
                        <div class="flex items-center gap-4">
                            <x-ri-rocket-line class="size-6 group-hover:scale-110 transition-transform" />
                            <span>{{ theme('hero_cta_primary_text', 'Start Gaming Now') }}</span>
                            <x-ri-arrow-right-line class="size-6 group-hover:translate-x-1 transition-transform" />
                        </div>
                    </a>
                    <a href="#features" class="group bg-gradient-to-r from-background-secondary to-background-secondary/70 border-2 border-primary/30 hover:border-primary/50 text-color-base px-10 py-5 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-secondary/30">
                        <div class="flex items-center gap-4">
                            <x-ri-settings-3-line class="size-6 group-hover:scale-110 transition-transform" />
                            <span>{{ theme('hero_cta_secondary_text', 'Explore Features') }}</span>
                        </div>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="flex flex-wrap justify-center items-center gap-8 text-color-muted animate-fade-in-up animation-delay-500">
                    <div class="flex items-center gap-2 bg-background-secondary/50 px-4 py-2 rounded-full border border-success/30">
                        <x-ri-check-line class="text-success size-5" />
                        <span class="font-semibold">{{ theme('hero_trust_badge_1', '30-Day Money Back') }}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-background-secondary/50 px-4 py-2 rounded-full border border-success/30">
                        <x-ri-check-line class="text-success size-5" />
                        <span class="font-semibold">{{ theme('hero_trust_badge_2', 'Instant Activation') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Uptime Stat -->
                <div class="text-center transform transition-all duration-300 hover:scale-105 animate-fade-in-up">
                    <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-success/30 hover:border-success/50 rounded-2xl p-8 shadow-2xl hover:shadow-success/20">
                        <div class="bg-success/20 text-success p-4 rounded-full inline-flex mb-6">
                            <x-ri-time-line class="size-8" />
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-success mb-3">99.9%</div>
                        <div class="text-color-base font-bold text-lg mb-2">{{ theme('stats_uptime_title', 'Uptime Guarantee') }}</div>
                        <div class="text-color-muted text-sm">{{ theme('stats_uptime_subtitle', 'Industry-leading reliability') }}</div>
                    </div>
                </div>
                
                <!-- Support Stat -->
                <div class="text-center transform transition-all duration-300 hover:scale-105 animate-fade-in-up animation-delay-100">
                    <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-primary/30 hover:border-primary/50 rounded-2xl p-8 shadow-2xl hover:shadow-primary/20">
                        <div class="bg-primary/20 text-primary p-4 rounded-full inline-flex mb-6">
                            <x-ri-customer-service-line class="size-8" />
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-primary mb-3">24/7</div>
                        <div class="text-color-base font-bold text-lg mb-2">{{ theme('stats_support_title', 'Expert Support') }}</div>
                        <div class="text-color-muted text-sm">{{ theme('stats_support_subtitle', 'Always here to help') }}</div>
                    </div>
                </div>
                
                <!-- Users Stat -->
                <div class="text-center transform transition-all duration-300 hover:scale-105 animate-fade-in-up animation-delay-200">
                    <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-purple-500/30 hover:border-purple-500/50 rounded-2xl p-8 shadow-2xl hover:shadow-purple-500/20">
                        <div class="bg-purple-500/20 text-purple-500 p-4 rounded-full inline-flex mb-6">
                            <x-ri-user-heart-line class="size-8" />
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-purple-500 mb-3">
                            {{ number_format(\App\Models\User::count() + theme('users_base_add', 0)) }}+
                        </div>
                        <div class="text-color-base font-bold text-lg mb-2">{{ theme('stats_users_title', 'Happy Gamers') }}</div>
                        <div class="text-color-muted text-sm">{{ theme('stats_users_subtitle', 'Join our community') }}</div>
                    </div>
                </div>
                
                <!-- Servers Stat -->
                <div class="text-center transform transition-all duration-300 hover:scale-105 animate-fade-in-up animation-delay-300">
                    <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-orange-500/30 hover:border-orange-500/50 rounded-2xl p-8 shadow-2xl hover:shadow-orange-500/20">
                        <div class="bg-orange-500/20 text-orange-500 p-4 rounded-full inline-flex mb-6">
                            <x-ri-database-line class="size-8" />
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-orange-500 mb-3">
                            {{ number_format(\App\Models\Service::count() + theme('servers_base_add', 0)) }}+
                        </div>
                        <div class="text-color-base font-bold text-lg mb-2">{{ theme('stats_servers_title', 'Servers Deployed') }}</div>
                        <div class="text-color-muted text-sm">{{ theme('stats_servers_subtitle', 'Powering gaming worldwide') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-color-base mb-4">
                    {{ theme('quick_actions_title', 'Get Started in Seconds') }}
                </h2>
                <p class="text-xl text-color-muted max-w-2xl mx-auto">{{ theme('quick_actions_subtitle', 'Everything you need is just one click away') }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-primary/30 rounded-3xl p-8 lg:p-12 shadow-2xl animate-fade-in-up backdrop-blur-sm">
                <h2 class="text-2xl font-bold text-color-base mb-8 flex items-center gap-3">
                    <div class="bg-primary/20 text-primary p-3 rounded-2xl">
                        <x-ri-flashlight-fill class="size-6" />
                    </div>
                    {{ theme('quick_actions_header', 'Quick Actions') }}
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Buy Server -->
                    <a href="{{ theme('buy_server_link', 'https://yourserver.com') }}" class="group bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary text-white p-6 rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-primary/30">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <x-ri-server-fill class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">{{ theme('buy_server_title', 'Buy Server') }}</h3>
                                <p class="text-sm text-white/80">{{ theme('buy_server_subtitle', 'Game Hosting') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/70">{{ theme('buy_server_description', 'Deploy instantly with our 1-click setup') }}</p>
                    </a>

                    <!-- Help/Discord -->
                    <a href="{{ theme('help_link', 'https://help.yourserver.com') }}" target="_blank" class="group bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-6 rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-purple-500/30">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <x-ri-question-answer-fill class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">{{ theme('help_title', 'Get Help') }}</h3>
                                <p class="text-sm text-white/80">{{ theme('help_subtitle', '24/7 Discord') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/70">Join our community of {{ number_format(\App\Models\User::count()) }}+ builders</p>
                    </a>

                    <!-- Login/Dashboard -->
                    @guest
                    <a href="{{ route('login') }}" class="group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-6 rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-blue-500/30">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <x-ri-login-box-line class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">{{ theme('login_title', 'Login') }}</h3>
                                <p class="text-sm text-white/80">{{ theme('login_subtitle', 'Access Account') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/70">{{ theme('login_description', 'Manage your servers from anywhere') }}</p>
                    </a>
                    @else
                    <a href="{{ route('dashboard') }}" class="group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-6 rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-blue-500/30">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <x-ri-dashboard-fill class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">{{ theme('dashboard_title', 'Dashboard') }}</h3>
                                <p class="text-sm text-white/80">{{ theme('dashboard_subtitle', 'Manage Services') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/70">{{ theme('dashboard_description', 'Control all your servers from one place') }}</p>
                    </a>
                    @endguest

                    <!-- Documentation -->
                    <a href="{{ theme('docs_link', 'https://docs.yourserver.com') }}" target="_blank" class="group bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white p-6 rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-emerald-500/30">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <x-ri-book-open-fill class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">{{ theme('docs_title', 'Documentation') }}</h3>
                                <p class="text-sm text-white/80">{{ theme('docs_subtitle', 'Get Started') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/70">{{ theme('docs_description', 'Comprehensive guides and tutorials') }}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-bold text-color-base mb-6 animate-fade-in-up">
                    {{ theme('features_title', 'Why Choose Us?') }}
                </h2>
                <p class="text-xl text-color-muted max-w-3xl mx-auto animate-fade-in-up leading-relaxed">
                    {{ theme('features_subtitle', 'We\'ve built the most advanced game hosting platform with features that give you the competitive edge') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <!-- Security Feature -->
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-primary/30 hover:border-primary/50 p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-primary/20 animate-fade-in-up">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-br from-primary/20 to-primary/10 text-primary p-4 rounded-2xl mr-6 transition-colors hover:from-primary/30 hover:to-primary/20">
                            <x-ri-shield-check-line class="size-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-color-base">{{ theme('feature_1_title', 'Military-Grade Security') }}</h3>
                    </div>
                    <p class="text-color-muted text-lg leading-relaxed">{{ theme('feature_1_desc', 'Advanced DDoS protection, encrypted connections, and real-time threat monitoring keep your servers safe from attacks 24/7.') }}</p>
                </div>

                <!-- Analytics Feature -->
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-secondary/30 hover:border-secondary/50 p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-secondary/20 animate-fade-in-up animation-delay-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-br from-secondary/20 to-secondary/10 text-secondary p-4 rounded-2xl mr-6 transition-colors hover:from-secondary/30 hover:to-secondary/20">
                            <x-ri-dashboard-line class="size-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-color-base">{{ theme('feature_2_title', 'Smart Analytics') }}</h3>
                    </div>
                    <p class="text-color-muted text-lg leading-relaxed">{{ theme('feature_2_desc', 'Real-time performance monitoring, player analytics, and detailed insights help you optimize your server performance and player experience.') }}</p>
                </div>

                <!-- Setup Feature -->
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-success/30 hover:border-success/50 p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-success/20 animate-fade-in-up animation-delay-200">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-br from-success/20 to-success/10 text-success p-4 rounded-2xl mr-6 transition-colors hover:from-success/30 hover:to-success/20">
                            <x-ri-flashlight-line class="size-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-color-base">{{ theme('feature_3_title', 'Lightning Setup') }}</h3>
                    </div>
                    <p class="text-color-muted text-lg leading-relaxed">{{ theme('feature_3_desc', 'Deploy your game servers in under 60 seconds with our automated setup and instant configuration system. No technical knowledge required.') }}</p>
                </div>

                <!-- Performance Feature -->
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-warning/30 hover:border-warning/50 p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-warning/20 animate-fade-in-up animation-delay-300">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-br from-warning/20 to-warning/10 text-warning p-4 rounded-2xl mr-6 transition-colors hover:from-warning/30 hover:to-warning/20">
                            <x-ri-cpu-line class="size-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-color-base">{{ theme('feature_4_title', 'Extreme Performance') }}</h3>
                    </div>
                    <p class="text-color-muted text-lg leading-relaxed">{{ theme('feature_4_desc', 'Premium NVMe SSD storage, high-frequency CPUs, and optimized network routing deliver zero-lag gaming experience for your players.') }}</p>
                </div>

                <!-- Global Feature -->
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-info/30 hover:border-info/50 p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-info/20 animate-fade-in-up animation-delay-400">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-br from-info/20 to-info/10 text-info p-4 rounded-2xl mr-6 transition-colors hover:from-info/30 hover:to-info/20">
                            <x-ri-global-line class="size-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-color-base">{{ theme('feature_5_title', 'Global Reach') }}</h3>
                    </div>
                    <p class="text-color-muted text-lg leading-relaxed">{{ theme('feature_5_desc', '15+ datacenter locations across 6 continents ensure your players get ultra-low latency wherever they are in the world.') }}</p>
                </div>

                <!-- AI Feature -->
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border-2 border-error/30 hover:border-error/50 p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-error/20 animate-fade-in-up animation-delay-500">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-br from-error/20 to-error/10 text-error p-4 rounded-2xl mr-6 transition-colors hover:from-error/30 hover:to-error/20">
                            <x-ri-line-chart-line class="size-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-color-base">{{ theme('feature_6_title', 'AI-Powered Optimization') }}</h3>
                    </div>
                    <p class="text-color-muted text-lg leading-relaxed">{{ theme('feature_6_desc', 'Machine learning algorithms continuously optimize server performance and predict issues before they impact your players.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-color-base mb-6">
                    {{ theme('testimonials_title', 'What Gamers Say') }}
                </h2>
                <p class="text-xl text-color-muted max-w-2xl mx-auto">
                    {{ theme('testimonials_subtitle', 'Join thousands of satisfied customers who\'ve made the switch to premium hosting') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gradient-to-br from-background-secondary/80 to-background-secondary/40 backdrop-blur-sm border border-primary/20 p-8 rounded-2xl transform transition-all duration-300 hover:scale-105 hover:border-primary/40">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-full mr-4 overflow-hidden">
                            <img src="{{ asset('assets/extended/pratham.jpg') }}" alt="{{ theme('testimonial_1_name', 'John Doe') }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-color-base">{{ theme('testimonial_1_name', 'John Doe') }}</h4>
                            <p class="text-color-muted text-sm">{{ theme('testimonial_1_role', 'Minecraft Server Admin') }}</p>
                        </div>
                    </div>
                    <p class="text-color-muted italic mb-4 text-lg">"{{ theme('testimonial_1_quote', 'Best hosting service I\'ve ever used. Zero downtime and lightning-fast support response!') }}"</p>
                    <div class="flex text-warning text-lg">
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gradient-to-br from-background-secondary/80 to-background-secondary/40 backdrop-blur-sm border border-secondary/20 p-8 rounded-2xl transform transition-all duration-300 hover:scale-105 hover:border-secondary/40">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-full mr-4 overflow-hidden">
                            <img src="{{ asset('assets/extended/nikit.jpg') }}" alt="{{ theme('testimonial_1_name', 'John Doe') }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-color-base">{{ theme('testimonial_2_name', 'Sarah Anderson') }}</h4>
                            <p class="text-color-muted text-sm">{{ theme('testimonial_2_role', 'Gaming Community Leader') }}</p>
                        </div>
                    </div>
                    <p class="text-color-muted italic mb-4 text-lg">"{{ theme('testimonial_2_quote', 'The performance boost was incredible. My players love the zero-lag experience!') }}"</p>
                    <div class="flex text-warning text-lg">
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gradient-to-br from-background-secondary/80 to-background-secondary/40 backdrop-blur-sm border border-success/20 p-8 rounded-2xl transform transition-all duration-300 hover:scale-105 hover:border-success/40">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-full mr-4 overflow-hidden">
                            <img src="{{ asset('assets/extended/nishkarsh.jpg') }}" alt="{{ theme('testimonial_1_name', 'John Doe') }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-color-base">{{ theme('testimonial_3_name', 'Sarah Anderson') }}</h4>
                            <p class="text-color-muted text-sm">{{ theme('testimonial_3_role', 'Gaming Community Leader') }}</p>
                        </div>
                    </div>
                    <p class="text-color-muted italic mb-4 text-lg">"{{ theme('testimonial_3_quote', 'Professional grade hosting at an affordable price. Couldn\'t ask for more!') }}"</p>
                    <div class="flex text-warning text-lg">
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />
                        <x-ri-star-fill class="size-5 text-warning" />       
                     </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Services/Categories Section -->
    <div id="services" class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-color-base mb-6">
                    {{ theme('services_title', 'Our Services') }}
                </h2>
                <p class="text-xl text-color-muted max-w-2xl mx-auto animate-fade-in-up">
                    {{ theme('services_subtitle', 'Choose from our comprehensive range of game hosting solutions') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach ($categories as $category)
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral hover:border-primary/30 p-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl animate-fade-in-up group">
                    @if(theme('small_images', false))
                    <div class="flex gap-x-4 items-center mb-4">
                        @if ($category->image)
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                             class="rounded-lg w-16 h-16 object-cover object-center flex-shrink-0">
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-color-base mb-2 group-hover:text-primary transition-colors">
                                {{ $category->name }}
                            </h3>
                            @if(theme('show_category_description', true))
                            <article class="prose dark:prose-invert prose-sm text-color-muted">
                                {!! $category->description !!}
                            </article>
                            @endif
                        </div>
                    </div>
                    @else
                    @if ($category->image)
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                         class="rounded-lg w-full object-cover object-center h-48 mb-4">
                    @endif
                    <h3 class="text-xl font-bold text-color-base mb-2 group-hover:text-primary transition-colors">
                        {{ $category->name }}
                    </h3>
                    @if(theme('show_category_description', true))
                    <article class="prose dark:prose-invert prose-sm text-color-muted mb-4">
                        {!! $category->description !!}
                    </article>
                    @endif
                    @endif
                    <a href="{{ route('category.show', ['category' => $category->slug]) }}" 
                       wire:navigate 
                       class="group/nav inline-flex items-center bg-primary/10 hover:bg-primary text-primary hover:text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 transform hover:scale-105">
                        <span class="mr-2">{{ __('general.view') }}</span>
                        <x-ri-arrow-right-line class="size-4 group-hover/nav:translate-x-1 transition-transform" />
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- CTA Section -->
    <div class="py-20 bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 rounded-3xl">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-color-base mb-6 animate-fade-in-up">
                    {{ theme('cta_title', 'Ready to Get Started?') }}
                </h2>
                <p class="text-xl text-color-muted mb-10 animate-fade-in-up">
                    {{ theme('cta_subtitle', 'Join thousands of satisfied customers who trust our platform for their game hosting needs') }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up">
                    <a href="#services" class="group bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                        <div class="flex items-center gap-3">
                            <x-ri-price-tag-3-line class="size-5 group-hover:scale-110 transition-transform" />
                            <span>{{ theme('cta_primary_text', 'View Pricing') }}</span>
                            <x-ri-arrow-right-line class="size-5 group-hover:translate-x-1 transition-transform" />
                        </div>
                    </a>
                    <a href="{{ theme('help_link', 'https://help.yourserver.com') }}" class="group bg-gradient-to-r from-background-secondary to-background-secondary/70 border border-neutral hover:border-primary/30 text-color-base px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                        <div class="flex items-center gap-3">
                            <x-ri-mail-line class="size-5 group-hover:scale-110 transition-transform" />
                            <span>{{ theme('cta_secondary_text', 'Chat Support') }}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
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
                        <h2 class="text-4xl md:text-5xl font-bold text-color-base mb-6">
                            {{ theme('faq_title', 'Frequently Asked Questions') }}
                        </h2>
                        <p class="text-xl text-color-muted max-w-2xl mx-auto">
                            {{ theme('faq_subtitle', 'Find answers to common questions about our services') }}
                        </p>
                    </div>

                    <div class="max-w-4xl mx-auto">
                        <div class="space-y-4">
                            @foreach($featuredFaqs as $faq)
                                <div class="group bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                                    <button class="w-full text-left p-6 focus:outline-none focus:ring-2 focus:ring-primary/20" 
                                            onclick="toggleHomeFAQ({{ $faq->id }})"
                                            aria-expanded="false"
                                            aria-controls="home-faq-answer-{{ $faq->id }}">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold text-color-base group-hover:text-primary transition-colors duration-300 pr-4">
                                                {{ $faq->question }}
                                            </h3>
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                    <x-ri-star-fill class="size-3 mr-1" />
                                                    Featured
                                                </span>
                                                <x-ri-arrow-down-s-line class="size-5 text-color-muted transform transition-transform duration-300 home-faq-arrow-{{ $faq->id }}" />
                                            </div>
                                        </div>
                                    </button>
                                    
                                    <div id="home-faq-answer-{{ $faq->id }}" 
                                         class="home-faq-answer hidden px-6 pb-6 border-t border-neutral/50">
                                        <div class="pt-4">
                                            <article class="prose dark:prose-invert text-color-muted leading-relaxed max-w-none">
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

    <div class="mt-16 mb-8">
        {!! hook('pages.home') !!}
    </div>
</div>