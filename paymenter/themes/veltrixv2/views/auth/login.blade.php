<div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 min-h-[600px] bg-white dark:bg-gray-950 rounded-3xl overflow-hidden shadow-2xl border-0 dark:border dark:border-gray-800/50 transition-all duration-500 ease-in-out">

    <!-- Left Column - Hero Section -->
    <div class="relative bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-900 dark:to-gray-950 p-12 flex flex-col justify-center border-r border-gray-200/50 dark:border-gray-800/50 group">
  
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden z-0">
            <div class="fmh-plasma-ball top-1/4 -left-20 opacity-80 mix-blend-overlay dark:opacity-60 dark:mix-blend-soft-light animate-pulse-slow"></div>
            <div class="fmh-plasma-ball bottom-1/4 -right-20 animation-delay-3000 opacity-80 mix-blend-overlay dark:opacity-60 dark:mix-blend-soft-light animate-pulse-slow"></div>
        </div>

            <!-- Content -->
            <div class="relative z-10 space-y-8 transform transition-all duration-500 group-hover:translate-x-1">
                <div class="space-y-6">
                    <h2 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] dark:from-[var(--dark-primary)] dark:to-[var(--dark-secondary)] bg-clip-text text-transparent leading-tight animate-text-shimmer">
                        {{ theme('hero_heading_line1', 'Elevate Your') }}<br>{{ theme('hero_heading_line2', 'Digital Experience') }}
                    </h2>
                    <p class="text-xl md:text-2xl max-w-md text-gray-700 dark:text-gray-300 leading-relaxed transition-all duration-300 hover:translate-x-1">
                        {{ theme('hero_paragraph', 'Streamline your workflow with our premium hosting platform. Everything you need in one unified dashboard.') }}
                    </p>
                </div>
            
            <!-- Animated Loading Indicators -->
            <div class="flex space-x-4 pt-4">
                <div class="h-2 w-16 bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] dark:from-[var(--dark-primary)] dark:to-[var(--dark-secondary)] rounded-full animate-pulse"></div>
                <div class="h-2 w-8 bg-[var(--primary)]/30 dark:bg-[var(--dark-primary)]/30 rounded-full transition-all duration-1000 hover:w-12"></div>
                <div class="h-2 w-8 bg-[var(--primary)]/30 dark:bg-[var(--dark-primary)]/30 rounded-full transition-all duration-1000 hover:w-12 delay-200"></div>
            </div>
        </div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="fmh-particle bg-[var(--primary)]/20 dark:bg-[var(--dark-primary)]/15" style="top: 25%; left: 25%; width: 10px; height: 10px;"></div>
            <div class="fmh-particle bg-[var(--secondary)]/20 dark:bg-[var(--dark-secondary)]/15" style="top: 65%; left: 75%; width: 12px; height: 12px;"></div>
            <div class="fmh-particle bg-[var(--primary)]/20 dark:bg-[var(--dark-primary)]/15" style="top: 35%; left: 55%; width: 8px; height: 8px;"></div>
        </div>
    </div>

    <!-- Right Column - Login Form -->
    <div class="relative p-12 flex flex-col justify-center bg-white dark:bg-gray-950 group">
        <form class="w-full max-w-md mx-auto" wire:submit="submit" id="login">
            <!-- Logo and Header -->
            <div class="flex flex-col items-center mb-12">
                <x-logo class="h-16 w-16 text-[var(--primary)] dark:text-[var(--dark-primary)] transition-all duration-500 hover:scale-110 hover:rotate-6" />
                <h1 class="text-3xl font-bold text-center mt-6 text-gray-800 dark:text-white">
                    Welcome Back
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Sign in to your account
                </p>
            </div>

            <!-- Form Inputs -->
            <div class="space-y-6">
                <x-form.input
                    name="email"
                    type="email"
                    :label="__('general.input.email')"
                    :placeholder="__('general.input.email_placeholder')"
                    wire:model="email"
                    hideRequiredIndicator
                    required
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-4 rounded-xl text-lg transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                />

                <x-form.input
                    name="password"
                    type="password"
                    :label="__('general.input.password')"
                    :placeholder="__('general.input.password_placeholder')"
                    wire:model="password"
                    hideRequiredIndicator
                    required
                    class="fmh-modern-input bg-white dark:bg-gray-900 border-gray-300/80 dark:border-gray-700/80 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] px-5 py-4 rounded-xl text-lg transition-all duration-300 hover:shadow-sm hover:border-[var(--primary)]/80 dark:hover:border-[var(--dark-primary)]/80"
                />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex flex-row items-center mt-6">
                <x-form.checkbox name="remember" label="Remember me" wire:model="remember" class="text-gray-800 dark:text-gray-200 text-lg" />
                <a class="ml-auto text-lg hover:underline text-[var(--primary)] dark:text-[var(--dark-primary)] hover:text-[var(--primary)]/80 dark:hover:text-[var(--dark-primary)]/80 transition-colors duration-300" href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            </div>

            <!-- CAPTCHA -->
            <x-captcha :form="'login'" class="mt-8" />

            <!-- Submit Button -->
            <x-button.primary class="w-full mt-8 py-4 bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] dark:from-[var(--dark-primary)] dark:to-[var(--dark-secondary)] hover:from-[var(--primary)]/90 hover:to-[var(--secondary)]/90 text-white font-bold rounded-xl transition-all duration-300 hover:shadow-xl text-lg shadow-md hover:shadow-[var(--primary)]/30 dark:hover:shadow-[var(--dark-primary)]/20 group-hover:shadow-lg">
                <div class="flex items-center justify-center space-x-2">
                    <span>{{ __('auth.sign_in') }}</span>
                    <svg class="w-6 h-6 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </x-button.primary>

            <!-- OAuth Providers -->
            @if (config('settings.oauth_github') || config('settings.oauth_google') || config('settings.oauth_discord'))
                <div class="flex flex-col items-center mt-12">
                    <div class="my-6 w-full relative">
                        <div class="absolute inset-0 flex items-center">
                            <span aria-hidden="true" class="h-px w-full bg-gray-300/80 dark:bg-gray-700/80"></span>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 text-lg font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-950">
                                Or continue with
                            </span>
                        </div>
                    </div>
                    
                    <!-- Provider Buttons -->
                    <div class="grid grid-cols-3 gap-4 w-full">
                        @foreach (['github', 'google', 'discord'] as $provider)
                            @if (config('settings.oauth_' . $provider))
                                <a href="{{ route('oauth.redirect', $provider) }}"
                                    class="flex items-center justify-center p-3 rounded-xl transition-all duration-200 bg-gray-100/80 dark:bg-gray-800/80 hover:bg-gray-200/80 dark:hover:bg-gray-700/80 border border-gray-200/80 dark:border-gray-700/80 shadow-sm hover:shadow-md hover:-translate-y-0.5"
                                >
                                    @if($provider === 'github')
                                        <svg class="w-6 h-6 text-gray-800 dark:text-gray-100 transition-transform duration-300 hover:scale-110" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill="currentColor" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                                        </svg>
                                    @elseif($provider === 'discord')
                                        <svg class="w-6 h-6 text-[#5865F2] transition-transform duration-300 hover:scale-110" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 00.031.057 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 00-.041-.106 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 transition-transform duration-300 hover:scale-110" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                            <path fill="none" d="M1 1h22v22H1z"/>
                                        </svg>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            
        @if(!config('settings.registration_disabled', false))
            <!-- Registration Link -->
            <div class="text-center mt-12 text-lg text-gray-600 dark:text-gray-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="hover:underline ml-2 font-bold text-[var(--primary)] dark:text-[var(--dark-primary)] hover:text-[var(--primary)]/80 dark:hover:text-[var(--dark-primary)]/80 transition-colors duration-300" wire:navigate>
                    Get started
                </a>
            </div>
        </form>
        @endif
        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="fmh-particle bg-[var(--primary)]/10 dark:bg-[var(--dark-primary)]/10" style="top: 15%; left: 70%; width: 8px; height: 8px;"></div>
            <div class="fmh-particle bg-[var(--secondary)]/10 dark:bg-[var(--dark-secondary)]/10" style="top: 75%; left: 30%; width: 6px; height: 6px;"></div>
        </div>
    </div>
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
        background: radial-gradient(circle, rgba(var(--primary), 0.3) 0%, rgba(0,0,0,0) 70%);
        filter: blur(30px);
        z-index: 0;
    }
    
    .animation-delay-3000 {
        animation-delay: 3s;
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
</style>
{!! theme('custom_layout_css', '') !!}