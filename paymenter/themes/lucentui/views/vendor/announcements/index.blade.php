@if(count($announcements) > 0)
<div class="mx-auto container mt-8">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                <x-ri-notification-fill class="size-6" />
            </div>
            <div>
                <h1 class="text-3xl font-bold text-color-base">{{ __('Announcements') }}</h1>
                <p class="text-color-muted text-sm">Stay updated with our latest news and updates</p>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-2 text-color-muted">
            <x-ri-time-fill class="size-4" />
            <span class="text-sm">{{ count($announcements) }} {{ Str::plural('announcement', count($announcements)) }}</span>
            <span style="display:none;">70343 1754623521</span>

        </div>
    </div>

    {{-- Announcements Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($announcements as $index => $announcement)
        <div class="group bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-[1.02] overflow-hidden animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
            {{-- Card Header --}}
            <div class="p-6 pb-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="size-2 bg-primary rounded-full animate-pulse"></div>
                        <span class="text-xs font-medium text-primary uppercase tracking-wide">New</span>
                    </div>
                    <div class="text-color-muted group-hover:text-primary transition-colors duration-300">
                        <x-ri-arrow-right-up-fill class="size-4 transform transition-transform duration-300 group-hover:scale-110" />
                    </div>
                </div>
                
                <h2 class="text-xl font-bold text-color-base mb-3 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                    {{ $announcement->title }}
                </h2>
                
                <div class="text-color-muted text-sm mb-4 line-clamp-3 leading-relaxed">
                    {{ $announcement->description }}
                </div>
            </div>

            {{-- Card Footer --}}
            <div class="px-6 pb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-color-muted text-xs">
                        <x-ri-calendar-fill class="size-3" />
                        <span>{{ $announcement->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <a href="{{ route('announcements.show', $announcement) }}" wire:navigate 
                       class="group/btn inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:shadow-lg hover:scale-105">
                        {{ __('general.view') }}
                        <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                    </a>
                </div>
            </div>

            {{-- Hover Effect Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
        </div>
        @endforeach
    </div>

<style>
    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Enhanced hover effects */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
    
    .group/btn:hover .group-hover\/btn\:translate-x-1 {
        transform: translateX(0.25rem);
</style>

</div>
@endif