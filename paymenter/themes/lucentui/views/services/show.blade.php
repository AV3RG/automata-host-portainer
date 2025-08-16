<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    @if($invoice = $service->invoices()->where('status', 'pending')->first())
    <div class="w-full mb-4">
        <div class="bg-gradient-to-r from-warning/20 to-warning/10 border border-warning/30 rounded-2xl p-4 backdrop-blur-sm">
            <p class="font-medium text-warning flex items-center gap-2">
                <x-ri-error-warning-fill class="size-5" /> 
                {{ __('services.outstanding_invoice') }}
                <a href="{{ route('invoices.show', $invoice)}}"
                    class="underline hover:text-warning/80 underline-offset-2 transition-colors duration-200">{{ __('services.view_and_pay') }}</a>.
            </p>
        </div>
    </div>
    @endif

    <!-- Main Service Container -->
    <div class="relative overflow-hidden bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/30 rounded-xl shadow-2xl backdrop-blur-sm mt-4 mb-8">
        
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-primary/10 via-primary/5 to-transparent rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-gradient-to-tr from-primary/8 via-primary/4 to-transparent rounded-full blur-2xl opacity-40"></div>
        
        <!-- Content Layer -->
        <div class="relative z-10 p-8 lg:p-12">
            
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
                <div class="mb-6 lg:mb-0">
                    <h1 class="text-4xl lg:text-4xl font-black text-color-base leading-tight mb-2 bg-gradient-to-r from-color-base via-color-base to-primary bg-clip-text flex items-center">
                        <x-ri-box-3-fill class="size-10 mr-4 text-primary" /> 
                        {{ __('services.services') }}
                        <span class="text-xl font-normal text-color-muted ml-3">#{{ $service->id }}</span>
                    </h1>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-primary/60 rounded-full ml-14"></div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Status Badge -->
                    @if ($service->status == 'active')
                        <div class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-success/20 to-success/10 border border-success/30 rounded-2xl backdrop-blur-sm">
                            <x-ri-checkbox-circle-fill class="size-6 text-success" />
                            <span class="text-success font-bold text-lg">{{ __('services.statuses.' . $service->status) }}</span>
                        </div>
                    @elseif($service->status == 'suspended')
                        <div class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-warning/20 to-warning/10 border border-warning/30 rounded-2xl backdrop-blur-sm">
                            <x-ri-forbid-fill class="size-6 text-warning" />
                            <span class="text-warning font-bold text-lg">{{ __('services.statuses.' . $service->status) }}</span>
                        </div>
                    @elseif($service->status == 'cancelled')
                        <div class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-inactive/20 to-inactive/10 border border-inactive/30 rounded-2xl backdrop-blur-sm">
                            <x-ri-close-circle-fill class="size-6 text-inactive" />
                            <span class="text-inactive font-bold text-lg">{{ __('services.statuses.' . $service->status) }}</span>
                        </div>
                    @elseif($service->status == 'pending')
                        <div class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-warning/20 to-warning/10 border border-warning/30 rounded-2xl backdrop-blur-sm">
                            <x-ri-error-warning-fill class="size-6 text-warning" />
                            <span class="text-warning font-bold text-lg">{{ __('services.statuses.' . $service->status) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Service Details Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
                
                <!-- Product Details Card -->
                <div class="bg-gradient-to-br from-background/50 to-background/20 border border-neutral/20 rounded-2xl p-6 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <x-ri-information-line class="size-6 text-primary" />
                        <h3 class="uppercase font-black text-color-base text-sm tracking-wider">{{ __('services.product_details') }}</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 px-4 bg-background/50 rounded-xl">
                            <span class="text-color-muted text-sm font-medium">{{ __('services.name') }}</span>
                            <span class="font-bold text-color-base">{{ $service->product->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 px-4 bg-background/50 rounded-xl">
                            <span class="text-color-muted text-sm font-medium">{{ __('services.price') }}</span>
                            <span class="font-bold text-success text-lg">{{ $service->formattedPrice }}</span>
                        </div>
                        @if($service->plan->type == 'recurring')
                            <div class="flex items-center justify-between py-3 px-4 bg-background/50 rounded-xl">
                                <span class="text-color-muted text-sm font-medium">{{ __('services.billing_cycle') }}</span>
                                <span class="font-bold text-color-base">
                                    {{ __('services.every_period', [
                                        'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                                        'unit' => trans_choice(__('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                                    ]) }}
                                </span>
                            </div>
                        @endif
                        @if($service->expires_at)
                            <div class="flex items-center justify-between py-3 px-4 bg-background/50 rounded-xl">
                                <span class="text-color-muted text-sm font-medium">Due At</span>
                                <span class="font-bold text-color-base">{{ $service->expires_at->format('d M Y') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-3 px-4 bg-background/50 rounded-xl">
                            <span class="text-color-muted text-sm font-medium">{{ __('services.status') }}</span>
                            <span class="font-bold @if ($service->status == 'active') text-success @elseif($service->status == 'cancelled') text-inactive @else text-warning @endif">
                                {{ __('services.statuses.' . $service->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                @if($service->cancellable || $service->upgradable || count($buttons) > 0)
                <div class="bg-gradient-to-br from-background/50 to-background/20 border border-neutral/20 rounded-2xl p-6 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <x-ri-flashlight-fill class="size-6 text-primary" />
                        <h3 class="uppercase font-black text-color-base text-sm tracking-wider">{{ __('services.actions') }}</h3>
                    </div>
                    <div class="space-y-4">
                        @if($service->upgradable)
                            <a href="{{ route('services.upgrade', $service->id) }}" class="block">
                                <button class="group w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-primary to-primary/80 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                    <x-ri-upload-cloud-2-line class="size-5 transition-transform group-hover:scale-110" />
                                    <span>{{ __('services.upgrade') }}</span>
                                </button>
                            </a>
                        @endif
                        @if($service->upgrade()->where('status', 'pending')->exists())
                            <button class="group w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-warning/80 to-warning/60 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl"
                                @click="Alpine.store('notifications').addNotification([{message: '{{ __('services.upgrade_pending') }}', type: 'error'}])">
                                <x-ri-upload-cloud-2-line class="size-5 transition-transform group-hover:scale-110" />
                                <span>{{ __('services.upgrade') }}</span>
                            </button>
                        @endif
                        @if($service->cancellable)
                            <button class="group w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-error to-error/80 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl" wire:click="$set('showCancel', true)">
                                <x-ri-spam-line class="size-5 transition-transform group-hover:scale-110" />
                                <span wire:loading.remove wire:target="$set('showCancel', true)">{{ __('services.cancel') }}</span>
                                <x-loading target="$set('showCancel', true)" class="ml-2" />
                            </button>
                        @endif

                        @if(($service->upgradable || $service->cancellable) && count($buttons) > 0)
                            <div class="border-t border-neutral/20 my-4"></div>
                        @endif

                        @foreach ($buttons as $button)
                            @if (isset($button['function']))
                                <button class="group w-full flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-background to-background/80 border border-neutral/30 text-color-base font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:bg-background-secondary" wire:click="goto('{{ $button['function'] }}')">
                                    <x-ri-link-m class="size-5 transition-transform group-hover:scale-110" />
                                    {{ $button['label'] }}
                                </button>
                            @else
                            <a href="{{ $button['url'] }}" class="block"
                                @if(!empty($button['target'])) target="{{ $button['target'] }}" @endif
                                @if(($button['target'] ?? null) === '_blank') rel="noopener noreferrer" @endif>
                                <button class="group w-full flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-background to-background/80 border border-neutral/30 text-color-base font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:bg-background-secondary">
                                    <x-ri-link-m class="size-5 transition-transform group-hover:scale-110" />
                                    {{ $button['label'] }}
                                </button>
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($showCancel)
        <x-modal open="true"
            title="{{ __('services.cancellation', ['service' => $service->product->name]) }}"
            width="max-w-3xl">
            <livewire:services.cancel :service="$service" />
            <x-slot name="closeTrigger">
                <div class="flex gap-4">
                    <button wire:click="$set('showCancel', false)" @click="open = false"
                        class="text-color-muted hover:text-color-base transition-colors duration-200 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <x-ri-close-fill class="size-7" />
                    </button>
                </div>
            </x-slot>
        </x-modal>
    @endif

    @if (count($views) > 0)
        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/30 p-6 md:p-8 rounded-xl shadow-lg mt-6 relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-warning/10 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-info/10 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-6000"></div>

            @if (count($views) > 1)
                <div class="flex w-full mb-4 border-b border-neutral/50 overflow-x-auto scrollbar-hide relative z-10"> 
                    @foreach ($views as $view)
                        <button wire:click="changeView('{{ $view['name'] }}')"
                            class="flex-shrink-0 px-6 py-3 -mb-px text-lg font-semibold transition-colors duration-200 whitespace-nowrap
                                {{ $view['name'] == $currentView ? 'border-b-2 border-primary text-primary-light' : 'text-color-muted hover:text-color-base hover:border-neutral-focus' }}">
                            {{ $view['label'] }}
                        </button>
                    @endforeach
                </div>
            @endif

            <x-loading target="changeView" class="!fill-primary mx-auto my-8 size-10" /> 
            <div wire:loading.remove wire:target="changeView" class="relative z-10">
                {!! $extensionView !!}
            </div>
        </div>
    @endif
</div>