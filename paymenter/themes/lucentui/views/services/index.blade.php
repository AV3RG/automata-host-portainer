<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-8">
        {{ __('navigation.services') }}
    </h1>

    {{-- Status Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12 bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 p-2 rounded-xl">
        <div class="bg-success/10 border border-success/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <x-ri-checkbox-circle-fill class="size-6 text-success mr-2" />
                <span class="text-2xl font-bold text-success">{{ $services->where('status', 'active')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-success">{{ __('Active') }}</p>
        </div>

        <div class="bg-warning/10 border border-warning/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <x-ri-error-warning-fill class="size-6 text-warning mr-2" />
                <span class="text-2xl font-bold text-warning">{{ $services->where('status', 'pending')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-warning">{{ __('Pending') }}</p>
        </div>

        <div class="bg-error/10 border border-error/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
            <x-ri-forbid-fill class="size-6 text-red-500 mr-2" />
            <span class="text-2xl font-bold text-red-500">{{ $services->where('status', 'suspended')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-red-500">{{ __('Suspended') }}</p>
        </div>

        <div class="bg-inactive/10 border border-inactive/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <x-ri-close-circle-fill class="size-6 text-inactive mr-2" />
                <span class="text-2xl font-bold text-inactive">{{ $services->where('status', 'cancelled')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-inactive">{{ __('Cancelled') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 md:gap-5 ">
        @forelse ($services as $service)
            <a href="{{ route('services.show', $service) }}" wire:navigate class="block">
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 hover:bg-secondary/25 border border-neutral/50 p-6 rounded-xl shadow-lg
                            transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:border-primary/50
                            flex flex-col md:flex-row items-start md:items-center justify-between gap-4">

                    <div class="flex items-start md:items-center gap-4 flex-grow">
                        <div class="bg-primary/10 p-3 rounded-full flex-shrink-0 shadow-sm">
                            <x-ri-instance-line class="size-6 text-primary" />
                        </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-color-base leading-tight">{{ $service->product->name }}</span>
                        <p class="text-color-muted text-sm mt-1">
                            <strong>{{ __('services.services') }}:</strong> {{ $service->product->category->name }}
                            @if(in_array($service->plan->type, ['recurring']))
                                - {{ __('services.every_period', [
                                    'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                                    'unit' => trans_choice(__('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                                ]) }}
                            @else
                                - {{ __('One time Payment') }}
                            @endif
                        </p>
                        @if($service->expires_at)
                            <p class="text-xs mt-1">
                                <span class="text-primary"><strong>{{ __('Due at') }}:</strong></span> <span class="text-primary/75">{{ $service->expires_at->format('d M Y') }}</span>
                            </p>
                        @endif
                    </div>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0 mt-3 md:mt-0">
                        <div class="text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1.5
                            @if ($service->status == 'active') text-success bg-success/20
                            @elseif($service->status == 'suspended') text-red-500 bg-red-500/20
                            @elseif($service->status == 'cancelled') text-inactive bg-inactive/20
                            @else text-warning bg-warning/20
                            @endif">
                            @if ($service->status == 'active')
                                <x-ri-checkbox-circle-fill class="size-4" />
                                {{ __('Active') }}
                            @elseif($service->status == 'suspended')
                                <x-ri-forbid-fill class="size-4" />
                                {{ __('Suspended') }}
                            @elseif($service->status == 'cancelled')
                                <x-ri-close-circle-fill class="size-4" />
                                {{ __('Canceled') }}
                            @elseif($service->status == 'pending')
                                <x-ri-error-warning-fill class="size-4" />
                                {{ __('Pending') }}
                            @endif
                        </div>
                        <x-ri-arrow-right-s-line class="size-6 text-color-muted group-hover:text-primary transition-colors duration-200" />
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 rounded-xl shadow-lg p-6 lg:p-8 border border-neutral/50 text-center">
                <x-ri-inbox-line class="size-16 text-color-muted mx-auto mb-4 opacity-60" />
                <h3 class="text-xl font-bold text-color-base mb-2">No services yet.</h3>
            </div>
        @endforelse

        {{ $services->links() }}
    </div>
</div>