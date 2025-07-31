{{-- resources/views/auth/two-factor-challenge.blade.php --}}

{{-- Push custom styles to the 'styles' stack --}}
@push('styles')
<style>
    /* Base CSS variables */
    :root {
        --fmh-primary-glare: conic-gradient(
            from 180deg at 50% 50%,
            #3b82f6 0deg, /* blue-500 */
            #6366f1 120deg, /* indigo-500 */
            #8b5cf6 240deg, /* purple-500 */
            #3b82f6 360deg
        );
        --fmh-plasma-effect: radial-gradient(
            circle at 50% 0%,
            rgba(59, 130, 246, 0.3) 0%,
            transparent 70%
        );
        --fmh-particle-color: rgba(99, 102, 241, 0.4); /* indigo-500 with alpha */
    }

    /* Dark theme variables */
    .dark {
        --fmh-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); /* slate-900 to slate-800 */
        --fmh-form-bg: linear-gradient(145deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.6) 100%); /* slate-900/80 to slate-700/60 */
        --fmh-form-border: 1px solid rgba(255, 255, 255, 0.05);
        --fmh-form-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), inset 0 1px 0 0 rgba(255, 255, 255, 0.05);
        --fmh-text-primary: #ffffff; /* White text */
        --fmh-text-secondary: #e2e8f0; /* slate-200 */
        --fmh-text-tertiary: #94a3b8; /* slate-400 placeholder */
        /* --- MODIFIED: Use a darker, more solid background for better text contrast --- */
        --fmh-input-bg: #1e293b; /* slate-800 - More opaque */
        --fmh-input-shadow: inset 3px 3px 5px rgba(0, 0, 0, 0.2), inset -3px -3px 5px rgba(255, 255, 255, 0.05);
        --fmh-input-focus: 0 0 0 2px rgba(99, 102, 241, 0.5), inset 2px 2px 4px rgba(0, 0, 0, 0.1), inset -2px -2px 4px rgba(255, 255, 255, 0.05); /* Focus ring with indigo-500/50 */
    }

    /* Light theme variables (Add .light class to the main div to activate) */
    .light {
        --fmh-bg-gradient: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); /* slate-100 to slate-200 */
        --fmh-form-bg: linear-gradient(145deg, rgba(255, 255, 255, 0.9) 0%, rgba(241, 245, 249, 0.8) 100%); /* white/90 to slate-100/80 */
        --fmh-form-border: 1px solid rgba(0, 0, 0, 0.05);
        --fmh-form-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08), inset 0 1px 0 0 rgba(0, 0, 0, 0.02);
        --fmh-text-primary: #1e293b; /* slate-800 text */
        --fmh-text-secondary: #334155; /* slate-700 */
        --fmh-text-tertiary: #64748b; /* slate-500 placeholder */
        /* --- MODIFIED: Use a lighter, more solid background for better text contrast --- */
        --fmh-input-bg: #f8fafc; /* slate-50 - More opaque */
        --fmh-input-shadow: inset 3px 3px 5px rgba(0, 0, 0, 0.05), inset -3px -3px 5px rgba(255, 255, 255, 0.5);
        --fmh-input-focus: 0 0 0 2px rgba(99, 102, 241, 0.3), inset 2px 2px 4px rgba(0, 0, 0, 0.05), inset -2px -2px 4px rgba(255, 255, 255, 0.5); /* Focus ring with indigo-500/30 */
    }

    /* Neumorphic input styling */
    /* Apply this class to your x-form.input component if possible */
    .fmh-neu-input {
        background: var(--fmh-input-bg);
        box-shadow: var(--fmh-input-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--fmh-text-primary); /* Ensure text color is applied */
        border: none; /* Remove default border */
        appearance: none; /* Remove default appearance */
        width: 100%; /* Ensure it takes full width */
        padding: 0.875rem 1.25rem; /* Corresponds to py-3.5 px-5 */
        border-radius: 0.75rem; /* Corresponds to rounded-xl */
    }

    .fmh-neu-input::placeholder {
        color: var(--fmh-text-tertiary); /* Style placeholder */
        opacity: 1; /* Ensure placeholder is visible */
    }

    .fmh-neu-input:focus {
        box-shadow: var(--fmh-input-focus);
        outline: none; /* Remove default focus outline */
        /* The focus ring style is included in --fmh-input-focus */
    }

    /* Animated plasma ball background element */
    .fmh-plasma-ball {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: var(--fmh-primary-glare);
        filter: blur(60px);
        opacity: 0.15;
        animation: fmh-float 12s ease-in-out infinite alternate;
        z-index: 0; /* Behind content */
    }

    .animation-delay-3000 {
        animation-delay: 3s;
    }

    /* Floating animation for plasma balls */
    @keyframes fmh-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(20px, -20px) scale(1.05); }
        50% { transform: translate(-20px, 10px) scale(0.95); }
        75% { transform: translate(10px, 20px) scale(1.03); }
    }

    /* Verify button styling */
    /* Apply this class to your x-button.primary component */
    .fmh-verify-btn {
        background: linear-gradient(45deg, #6366f1, #8b5cf6); /* indigo-500 to purple-500 */
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3); /* Shadow using indigo-500 */
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
        color: white; /* Ensure button text is white */
        padding: 1rem 1.5rem; /* Corresponds to py-4 px-6 */
        border-radius: 0.75rem; /* Corresponds to rounded-xl */
        font-weight: 500; /* medium */
        width: 100%;
        transition: all 0.3s ease;
        display: inline-flex; /* Align icon and text */
        align-items: center;
        justify-content: center;
    }

    /* Hover shine effect for verify button */
    .fmh-verify-btn::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to bottom right,
            rgba(255, 255, 255, 0.3) 0%,
            rgba(255, 255, 255, 0) 60%
        );
        transform: rotate(30deg);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1; /* Above background gradient, below text */
    }

    .fmh-verify-btn:hover::before {
        left: 100%;
        top: 100%;
    }

     /* Ensure button content (text/icon) is above the shine effect */
    .fmh-verify-btn > * {
        position: relative;
        z-index: 2;
    }

    .fmh-verify-btn:hover {
         box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
         transform: translateY(-2px);
    }

     .fmh-verify-btn:active {
         transform: translateY(0px);
         box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
     }


    /* Main form container styling */
    .fmh-form-container {
        background: var(--fmh-form-bg);
        border: var(--fmh-form-border);
        box-shadow: var(--fmh-form-shadow);
        backdrop-filter: blur(20px); /* Keep blur for the container */
        border-radius: 1.5rem; /* rounded-3xl */
        padding: 2.5rem; /* p-10 */
        position: relative; /* Needed for particle positioning */
        overflow: hidden; /* Keep particles inside */
        transition: all 0.5s ease;
        width: 100%;
        max-width: 28rem; /* max-w-md */
        z-index: 1; /* Above plasma balls */
    }

     /* Form glow effect */
     .fmh-form-container::after {
        content: '';
        position: absolute;
        inset: -1px; /* -inset-1 */
        background: linear-gradient(to bottom right, rgba(99, 102, 241, 0.1), transparent, rgba(139, 92, 246, 0.1)); /* indigo-500/10 to purple-500/10 */
        border-radius: inherit; /* Match parent rounding */
        z-index: -1; /* Behind the main background but visible */
        pointer-events: none;
     }


    /* Floating particle styling */
    .fmh-particle {
        position: absolute;
        border-radius: 50%;
        background-color: var(--fmh-particle-color);
        animation: fmh-particle-float 15s ease-in-out infinite alternate; /* Added alternate */
        z-index: 0; /* Behind form content but above form background */
        pointer-events: none; /* Prevent particles from interfering */
    }

    /* Floating animation for particles */
    @keyframes fmh-particle-float {
        0%, 100% { transform: translate(0, 0) scale(1); opacity: var(--start-opacity, 0.5); } /* Use CSS var for start opacity */
        25% { transform: translate(10px, -15px) scale(0.9); opacity: calc(var(--start-opacity, 0.5) * 0.8); }
        50% { transform: translate(-15px, 5px) scale(1.1); opacity: calc(var(--start-opacity, 0.5) * 0.6); }
        75% { transform: translate(5px, 15px) scale(1); opacity: calc(var(--start-opacity, 0.5) * 0.9); }
    }
</style>
@endpush

{{-- Main page container - Added dark class and style for gradient --}}
<div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden dark" style="background: var(--fmh-bg-gradient);">
    <div class="fmh-plasma-ball top-1/4 -left-20"></div>
    <div class="fmh-plasma-ball bottom-1/4 -right-20 animation-delay-3000"></div>

    <div class="fmh-form-container">
        {{-- Form content area - Added relative z-10 to be above particles/glow --}}
        <div class="relative z-10">
            {{-- Livewire Form --}}
            {{-- Removed gap-6, spacing handled by margins below --}}
            <form
                class="flex flex-col"
                wire:submit.prevent="verify">

                {{-- Logo and Title Section --}}
                <div class="flex flex-col items-center mb-10"> {{-- Increased bottom margin --}}
                    {{-- Your Logo Component --}}
                    {{-- Make sure logo component uses text-[var(--fmh-text-primary)] or similar if needed --}}
                    <x-logo class="w-14 h-14 mb-6 text-indigo-400" /> {{-- Adjusted size/margin --}}
                    {{-- Title using translation helper and CSS variable for color --}}
                    <h1 class="text-3xl font-bold text-center text-[var(--fmh-text-primary)] mb-2">
                        {{ __('auth.verify_2fa') }}
                    </h1>
                    {{-- Subtitle using CSS variable for color --}}
                    <p class="text-sm text-[var(--fmh-text-tertiary)] mt-1">
                        {{ __('Please enter the code from your authenticator app.') }}
                    </p>
                </div>

                {{-- Input Field Section --}}
                <div class="mb-6"> {{-- Added margin-bottom for spacing --}}
                    <label for="code" class="block text-sm font-medium text-[var(--fmh-text-secondary)] mb-2">{{ __('account.input.two_factor_code') }}</label>
                    {{-- Input Field using your Form Input Component --}}
                    {{-- Applied fmh-neu-input class. REMOVE conflicting style classes (like px, py, rounded) if they are default in your component --}}
                    <x-form.input
                        id="code" {{-- Added id to link label --}}
                        name="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        :placeholder="__('account.input.two_factor_code_placeholder')"
                        wire:model.defer="code"
                        required
                        autofocus
                        {{-- IMPORTANT: Pass the fmh-neu-input class. Adjust if your component uses a different prop for classes --}}
                        class="fmh-neu-input"
                        />
                    {{-- Display validation errors --}}
                    @error('code') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>

                {{-- Submit Button Section --}}
                <div class="mb-4"> {{-- Added margin-bottom for spacing --}}
                    {{-- Submit Button using your Button Component --}}
                    {{-- Applied fmh-verify-btn class. REMOVE conflicting style classes if they are default in your component --}}
                    <x-button.primary
                        {{-- IMPORTANT: Pass the fmh-verify-btn class. Adjust if your component uses a different prop for classes --}}
                        class="fmh-verify-btn"
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="verify">

                        {{-- Loading Indicator (Example) - Ensure SVG color contrasts --}}
                        <span wire:loading wire:target="verify" class="mr-2">
                             <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                              </svg>
                        </span>

                        {{-- Button Text --}}
                        <span wire:loading.remove wire:target="verify">
                            {{ __('auth.verify') }}
                             {{-- Optional: Add icon inside --}}
                             <svg class="w-5 h-5 ml-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                         <span wire:loading wire:target="verify">
                            {{ __('Verifying...') }}
                        </span>
                    </x-button.primary>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Push custom scripts to the 'scripts' stack --}}
@push('scripts')
{{-- Include Alpine.js if needed by components or future interactions --}}
{{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> --}}

<script>
    // Add floating particles to the form container background
    document.addEventListener('DOMContentLoaded', () => {
        // Select the main form container
        const container = document.querySelector('.fmh-form-container');
        // Check if container exists to prevent errors
        if (!container) {
            console.warn('fmh-form-container not found for particle effect.');
            return;
        };

        const particleCount = 15; // Number of particles

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'fmh-particle'; // Assign class for styling

            // Random properties for each particle
            const size = Math.random() * 5 + 2; // Size between 2px and 7px
            const posX = Math.random() * 100; // Random horizontal position (%)
            const posY = Math.random() * 100; // Random vertical position (%)
            const duration = Math.random() * 15 + 12; // Animation duration between 12s and 27s
            const delay = Math.random() * 8; // Animation delay up to 8s
            const initialOpacity = Math.random() * 0.4 + 0.2; // Initial opacity between 0.2 and 0.6

            // Apply styles
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${posX}%`;
            particle.style.top = `${posY}%`;
            // Set the initial opacity via CSS variable for the animation to use
            particle.style.setProperty('--start-opacity', initialOpacity);
            // Set animation with random duration and delay
            particle.style.animation = `fmh-particle-float ${duration}s ease-in-out ${delay}s infinite alternate`;

            // Append particle to the container (will be behind z-10 content)
            container.appendChild(particle); // Append directly to container
        }
    });
</script>
@endpush
{!! theme('custom_layout_css', '') !!}