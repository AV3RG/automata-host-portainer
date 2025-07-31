<div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 min-h-[600px] bg-white dark:bg-gray-950 shadow-2xl border-0 dark:border dark:border-gray-800/50 relative overflow-hidden rounded-3xl transition-all duration-500 ease-in-out">
 
    <div class="relative p-12 flex flex-col justify-center bg-white dark:bg-gray-950 rounded-l-3xl group">
        <form class="w-full max-w-md mx-auto" wire:submit.prevent="submit" id="register">
            
            <div class="flex flex-col items-center mb-12">
                <x-logo class="h-16 w-16 text-[var(--primary)] dark:text-[var(--dark-primary)] transition-all duration-500 hover:scale-110 hover:rotate-6" style="color: var(--primary);" />
                <h1 class="text-3xl font-bold text-center mt-6 text-gray-800 dark:text-white">
                    {{ theme('register_title', 'Create Your Account') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    {{ theme('register_subtitle', 'Join our community today') }}
                </p>
            </div>

           
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input
                        name="first_name"
                        type="text"
                        :label="__('general.input.first_name')"
                        :placeholder="__('general.input.first_name_placeholder')"
                        wire:model="first_name"
                        required
                        class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-3 rounded-xl transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                    />
                    
                    <x-form.input
                        name="last_name"
                        type="text"
                        :label="__('general.input.last_name')"
                        :placeholder="__('general.input.last_name_placeholder')"
                        wire:model="last_name"
                        required
                        class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-3 rounded-xl transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                    />
                </div>

                <x-form.input
                    name="email"
                    type="email"
                    :label="__('general.input.email')"
                    :placeholder="__('general.input.email_placeholder')"
                    wire:model="email"
                    required
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-3 rounded-xl transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                />

                <x-form.input
                    name="password"
                    type="password"
                    :label="__('Password')"
                    :placeholder="__('Your password')"
                    wire:model="password"
                    required
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-3 rounded-xl transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                />

                <x-form.input
                    name="password_confirmation"
                    type="password"
                    :label="__('Confirm Password')"
                    :placeholder="__('Confirm your password')"
                    wire:model="password_confirmation"
                    required
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-3 rounded-xl transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                />

                <x-form.properties 
                    :custom_properties="$custom_properties" 
                    :properties="$properties"
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-3 rounded-xl transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                />
            </div>

           
            <x-captcha :form="'register'" class="mt-8" wire:model="captcha" />

          
            <x-button.primary class="w-full mt-8 py-4 bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] dark:from-[var(--dark-primary)] dark:to-[var(--dark-secondary)] hover:from-[var(--primary)]/90 hover:to-[var(--secondary)]/90 text-white font-bold rounded-xl transition-all duration-300 hover:shadow-xl text-lg shadow-md hover:shadow-[var(--primary)]/30 dark:hover:shadow-[var(--dark-primary)]/20 group-hover:shadow-lg">
                <div class="flex items-center justify-center space-x-2">
                    <span>{{ theme('register_button_text', 'Sign up') }}</span>
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </x-button.primary>

           
            <div class="text-center mt-8 text-lg text-gray-600 dark:text-gray-400">
                {{ theme('login_link_text', 'Already have an account?') }}
                <a href="{{ route('login') }}" class="hover:underline ml-2 font-bold text-[var(--primary)] dark:text-[var(--dark-primary)] hover:text-[var(--primary)]/80 dark:hover:text-[var(--dark-primary)]/80 transition-colors duration-300" wire:navigate>
                    {{ theme('login_link_button', 'Sign in') }}
                </a>
            </div>
        </form>

 
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="fmh-particle bg-[var(--primary)]/10 dark:bg-[var(--dark-primary)]/10" style="top: 15%; left: 70%; width: 8px; height: 8px;"></div>
            <div class="fmh-particle bg-[var(--secondary)]/10 dark:bg-[var(--dark-secondary)]/10" style="top: 75%; left: 30%; width: 6px; height: 6px;"></div>
            <div class="fmh-particle bg-[var(--primary)]/15 dark:bg-[var(--dark-primary)]/15" style="top: 20%; left: 10%; width: 12px; height: 12px; animation-delay: 2s;"></div>
            <div class="fmh-particle bg-[var(--secondary)]/15 dark:bg-[var(--dark-secondary)]/15" style="top: 70%; left: 15%; width: 8px; height: 8px; animation-delay: 4s;"></div>
            <div class="fmh-particle bg-[var(--primary)]/12 dark:bg-[var(--dark-primary)]/12" style="top: 40%; left: 5%; width: 6px; height: 6px; animation-delay: 1s;"></div>
        </div>
    </div>

    
    <div class="relative p-12 flex flex-col justify-center rounded-r-3xl bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-900 dark:to-gray-950 group">
        
        <div class="absolute inset-0 overflow-hidden z-0">
            <div class="fmh-plasma-ball top-1/4 -left-20 opacity-80 mix-blend-overlay dark:opacity-60 dark:mix-blend-soft-light animate-pulse-slow"></div>
            <div class="fmh-plasma-ball bottom-1/4 -right-20 animation-delay-3000 opacity-80 mix-blend-overlay dark:opacity-60 dark:mix-blend-soft-light animate-pulse-slow"></div>
        </div>

       
        <div class="relative z-10 space-y-8 transform transition-all duration-500 group-hover:translate-x-1">
            <div class="space-y-6">
                <h2 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] dark:from-[var(--dark-primary)] dark:to-[var(--dark-secondary)] bg-clip-text text-transparent leading-tight animate-text-shimmer">
                    {{ theme('lhero_heading_line1', 'Start Your Journey') }}<br>{{ theme('lhero_heading_line2', 'With Us') }}
                </h2>
                <p class="text-xl md:text-2xl max-w-md text-gray-700 dark:text-gray-300 leading-relaxed transition-all duration-300 hover:translate-x-1">
                    {{ theme('lhero_paragraph', 'Join thousands of satisfied users who trust our platform for their needs. Get started in just a few clicks.') }}
                </p>
            </div>
            
            <!-- Animated Loading Indicators -->
            <div class="flex space-x-4 pt-4">
                <div class="h-2 w-16 bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] dark:from-[var(--dark-primary)] dark:to-[var(--dark-secondary)] rounded-full animate-pulse"></div>
                <div class="h-2 w-8 bg-[var(--primary)]/30 dark:bg-[var(--dark-primary)]/30 rounded-full transition-all duration-1000 hover:w-12"></div>
                <div class="h-2 w-8 bg-[var(--primary)]/30 dark:bg-[var(--dark-primary)]/30 rounded-full transition-all duration-1000 hover:w-12 delay-200"></div>
            </div>
            
          
            <div class="space-y-4 pt-4">
                <div class="flex items-start">
                    <svg class="h-6 w-6 mt-1 mr-3" style="color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-lg text-gray-700 dark:text-gray-300">{{ theme('lfeature_1', 'Premium features at no extra cost') }}</p>
                </div>
                <div class="flex items-start">
                    <svg class="h-6 w-6 mt-1 mr-3" style="color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-lg text-gray-700 dark:text-gray-300">{{ theme('lfeature_2', '24/7 customer support') }}</p>
                </div>
                <div class="flex items-start">
                    <svg class="h-6 w-6 mt-1 mr-3" style="color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-lg text-gray-700 dark:text-gray-300">{{ theme('lfeature_3', 'Secure and reliable platform') }}</p>
                </div>
            </div>
        </div>

       
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="fmh-particle bg-[var(--primary)]/20 dark:bg-[var(--dark-primary)]/15" style="top: 25%; left: 25%; width: 10px; height: 10px;"></div>
            <div class="fmh-particle bg-[var(--secondary)]/20 dark:bg-[var(--dark-secondary)]/15" style="top: 65%; left: 75%; width: 12px; height: 12px;"></div>
            <div class="fmh-particle bg-[var(--primary)]/20 dark:bg-[var(--dark-primary)]/15" style="top: 35%; left: 55%; width: 8px; height: 8px;"></div>
            
        
            <div class="absolute top-0 bottom-0 left-0 w-1 bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] opacity-50"></div>
            <div class="fmh-connector-dot bg-[var(--primary)]" style="top: 30%; left: -5px;"></div>
            <div class="fmh-connector-dot bg-[var(--primary)] animation-delay-2000" style="top: 50%; left: -5px;"></div>
            <div class="fmh-connector-dot bg-[var(--primary)] animation-delay-4000" style="top: 70%; left: -5px;"></div>
        </div>
    </div>
    
    
    <div class="fmh-floating-orb"></div>
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
    }

    /* Dark mode CSS variables */
    .dark {
        --background: var(--dark-background);
        --background-secondary: var(--dark-background-secondary);
    }

    .fmh-modern-input:focus {
        ring-color: var(--primary);
        border-color: var(--primary);
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
        background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, rgba(0,0,0,0) 70%);
        filter: blur(30px);
        z-index: 0;
        opacity: 0.3;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-3000 {
        animation-delay: 3s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    .fmh-connector-dot {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        filter: blur(2px);
        animation: float-horizontal 8s infinite ease-in-out;
        z-index: 1;
        opacity: 0.6;
    }
    
    .fmh-floating-orb {
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(20px);
        z-index: 0;
        animation: orb-float 25s infinite ease-in-out;
        top: 30%;
        left: 48%;
        opacity: 0.15;
    }

    @keyframes pulse-slow {
        0%, 100% {
            opacity: 0.6;
        }
        50% {
            opacity: 0.9;
        }
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 6s infinite;
    }
    
    @keyframes text-shimmer {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    
    .animate-text-shimmer {
        background-size: 200% auto;
        animation: text-shimmer 8s ease infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        20% {
            transform: translate(15px, 15px) rotate(5deg);
        }
        40% {
            transform: translate(20px, -10px) rotate(-5deg);
        }
        60% {
            transform: translate(-15px, 20px) rotate(8deg);
        }
        80% {
            transform: translate(-10px, -5px) rotate(-3deg);
        }
    }
    
    @keyframes float-horizontal {
        0%, 100% {
            transform: translateX(0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        50% {
            transform: translateX(calc(100% - 10px));
        }
    }
    
    @keyframes orb-float {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        20% {
            transform: translate(20px, 30px) scale(1.1);
        }
        40% {
            transform: translate(-20px, 50px) scale(0.9);
        }
        60% {
            transform: translate(30px, -20px) scale(1.05);
        }
        80% {
            transform: translate(-30px, 10px) scale(0.95);
        }
    }
</style>
{!! theme('custom_layout_css', '') !!}