<div class="space-y-4">
    @foreach ($tickets as $ticket)
    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="block">
        <div class="bg-background-secondary hover:bg-background-secondary/80 border border-neutral p-4 rounded-lg mb-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="bg-secondary/10 p-2 rounded-lg">
                        <x-ri-ticket-line class="size-5 text-secondary" />
                    </div>
                    <span class="font-medium">{{ $ticket->subject }}</span>
                    <span class="text-base/50 font-semibold">
                        <x-ri-circle-fill class="size-1 text-base/20" />
                    </span>
                    <span class="text-base text-sm">
                        {{ $ticket->department ?? 'No department' }}
                    </span>
                </div>
                <div class="size-5 rounded-md p-0.5
                    @if ($ticket->status == 'open') text-success bg-success/20
                    @elseif($ticket->status == 'closed') text-info bg-info/20
                    @else text-warning bg-warning/20
                    @endif">
                    @if ($ticket->status == 'open')
                        <x-ri-add-circle-fill class="size-4" />
                    @elseif($ticket->status == 'closed')
                        <x-ri-forbid-fill class="size-4" />
                    @elseif($ticket->status == 'replied')
                        <x-ri-chat-smile-2-fill class="size-4" />
                    @endif
                </div>
            </div>
            <p class="text-base text-sm text-wrap">
                {{ __('ticket.last_activity') }}: {{ $ticket->messages()->orderBy('created_at', 'desc')->first()->created_at->diffForHumans() }}
            </p>
        </div>
    </a>
    @endforeach
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
</style>
{!! theme('custom_layout_css', '') !!}