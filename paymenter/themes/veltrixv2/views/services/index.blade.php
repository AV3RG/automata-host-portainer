<div>
    <div class="space-y-4">
        <x-navigation.breadcrumb />
        @foreach ($services as $service)
        <a href="{{ route('services.show', $service) }}" wire:navigate>
            <div class="bg-background-secondary hover:bg-background-secondary/80 border border-neutral p-4 rounded-lg mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="bg-secondary/10 p-2 rounded-lg">
                            <x-ri-instance-line class="size-5 text-secondary" />
                        </div>
                        <span class="font-medium">{{ $service->product->name }}</span>
                    </div>
                    <div class="size-5 rounded-md p-0.5
                        @if ($service->status == 'active') text-success bg-success/20 
                        @elseif($service->status == 'suspended' || $service->status == 'cancelled') text-inactive bg-inactive/20
                        @else text-warning bg-warning/20 
                        @endif">
                        @if ($service->status == 'active')
                            <x-ri-checkbox-circle-fill />
                        @elseif($service->status == 'suspended' || $service->status == 'cancelled')
                            <x-ri-forbid-fill />
                        @elseif($service->status == 'pending')
                            <x-ri-error-warning-fill />
                        @endif
                    </div>
                </div>
                <p class="text-base text-sm">Product(s): {{ $service->product->category->name }} {{ in_array($service->plan->type, ['recurring']) ? ' - ' . __('services.every_period', [
                    'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                    'unit' => trans_choice(__('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                ]) : '' }}</p>
            </div>
        </a>
        @endforeach
        {{ $services->links() }}
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

    /* Gradient Button */
    .gradient-button {
        background: linear-gradient(to bottom right, var(--primary), var(--secondary));
        transition: background 0.3s ease;
    }

    .gradient-button:hover {
        background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
    }

    /* Particle and Plasma Ball Styles */
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
    </style>

    {!! theme('custom_layout_css', '') !!}
</div>
