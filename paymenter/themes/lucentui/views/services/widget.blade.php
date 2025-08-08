<div class="space-y-4">
    @foreach ($services as $service)
    <a href="{{ route('services.show', $service) }}" wire:navigate>
        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral p-4 rounded-lg mb-4">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
            <div class="bg-secondary/10 p-2 rounded-full">
                <x-ri-instance-line class="size-5 text-secondary" />
            </div>
            <span class="font-medium">{{ $service->product->name }}</span>
            </div>
            <div class="size-5 rounded-full p-0.5
                @if ($service->status == 'active') text-success bg-success/20 
                @elseif($service->status == 'suspended') text-inactive bg-inactive/20
                @else text-warning bg-warning/20 
                @endif"
                @if ($service->status == 'active')
                    <x-ri-checkbox-circle-fill />
                @elseif($service->status == 'suspended')
                    <x-ri-forbid-fill />
                @elseif($service->status == 'pending')
                    <x-ri-error-warning-fill />
                @endif
            </div>
        </div>
        <p class="text-base text-sm"><strong>Product(s):</strong> {{ $service->product->category->name }} - Every {{ $service->plan->billing_period > 1 ? $service->plan->billing_period : '' }}
                            {{ Str::plural($service->plan->billing_unit, $service->plan->billing_period) }}</p>
        </div>
    </a>
    @endforeach
</div>
