@if($announcements->count() > 0) 
<div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 rounded-2xl shadow-lg overflow-hidden">
    <div class="p-6 pb-4 border-b border-neutral/30">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-primary/10 text-primary p-3 rounded-full shadow-md">
                    <x-ri-megaphone-fill class="size-6" />
                </div>
                <div>
                    <h2 class="text-xl font-bold text-color-base">{{ __('Announcements') }}</h2>
                    <p class="text-color-muted text-sm">Latest updates and news</p>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-2 text-color-muted">
                <div class="size-2 bg-primary rounded-full animate-pulse"></div>
                <span class="text-sm font-medium">{{ $announcements->count() }} {{ Str::plural('new', $announcements->count()) }}</span>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
        @foreach($announcements as $index => $announcement)
        <a href="{{ route('announcements.show', $announcement) }}" wire:navigate 
           class="group block animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
            <div class="bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 hover:border-primary/30 p-4 rounded-xl transition-all duration-300 hover:shadow-lg hover:scale-[1.02] relative overflow-hidden">
                {{-- Content --}}
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="bg-primary/10 text-primary p-2 rounded-full shrink-0 group-hover:bg-primary/20 transition-colors duration-300">
                            <x-ri-newspaper-line class="size-4" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-color-base group-hover:text-primary transition-colors duration-300 line-clamp-2 mb-1">
                                {{ $announcement->title }}
                            </h3>
                            <p class="text-color-muted text-sm line-clamp-2 leading-relaxed mb-2">
                                {{ $announcement->description }}
                            </p>
                            <div class="flex items-center gap-2 text-color-muted text-xs">
                                <x-ri-time-fill class="size-3" />
                                <span>{{ $announcement->published_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-color-muted group-hover:text-primary transition-colors duration-300 shrink-0">
                        <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover:translate-x-1" />
                    </div>
                </div>

                <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="p-6 pt-4 border-t border-neutral/30">
        <a href="{{ route('announcements.index') }}" wire:navigate 
           class="group w-full bg-background-tertiary hover:bg-background-tertiary/80 border border-neutral/50 hover:border-primary/30 text-color-base hover:text-primary px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:shadow-lg flex items-center justify-center gap-2">
            {{ __('dashboard.view_all') }}
            <x-ri-arrow-right-fill class="size-4 transform transition-transform duration-300 group-hover:translate-x-1" />
        </a>
    </div>
</div>
@endif