<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <x-navigation.link :href="route('tickets.create')" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
            <x-ri-add-line class="size-5 mr-2" />
            <span>{{ __('ticket.create_ticket') }}</span>
        </x-navigation.link>
    </div>

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-8">
        {{ __('ticket.tickets') }}
    </h1>

    <div class="grid grid-cols-1 gap-6 md:gap-8">
        @forelse ($tickets as $ticket)
        <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="block group">
            <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 hover:bg-secondary/25 border border-neutral/50 p-6 rounded-xl shadow-lg
                        transition-all duration-300 hover:scale-[1.01] hover:shadow-xl hover:border-primary/50
                        flex flex-col md:flex-row items-start md:items-center justify-between gap-4">

                <div class="flex items-start md:items-center gap-4 flex-grow">
                    <div class="bg-secondary/10 p-3 rounded-full flex-shrink-0 shadow-sm">
                        <x-ri-ticket-line class="size-6 text-secondary" />
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-color-base leading-tight group-hover:text-primary transition-colors duration-200">
                            {{ $ticket->subject }}
                        </span>
                        <p class="text-color-muted text-sm mt-1">
                            {{ __('ticket.last_activity') }}: 
                            {{ $ticket->messages()->orderBy('created_at', 'desc')->first()->created_at->diffForHumans() }}
                            @if($ticket->department)
                                <span class="hidden md:inline"> - {{ $ticket->department }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0 mt-3 md:mt-0">
                    <div class="text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1.5
                        @if ($ticket->status == 'open') text-success bg-success/20
                        @elseif($ticket->status == 'closed') text-inactive bg-inactive/20
                        @elseif($ticket->status == 'replied') text-info bg-info/20
                        @else text-warning bg-warning/20
                        @endif">
                        @if ($ticket->status == 'open')
                            <x-ri-add-circle-fill class="size-4" />
                            Open
                        @elseif($ticket->status == 'closed')
                            <x-ri-forbid-fill class="size-4" />
                            Closed
                        @elseif($ticket->status == 'replied')
                            <x-ri-chat-smile-2-fill class="size-4" />
                            Replied
                        @else
                            <x-ri-error-warning-fill class="size-4" />
                            {{ ucfirst($ticket->status) }} 
                        @endif
                    </div>
                    <x-ri-arrow-right-s-line class="size-6 text-color-muted group-hover:text-primary transition-colors duration-200" />
                </div>
            </div>
        </a>
        @empty
        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 rounded-xl shadow-lg p-6 lg:p-8 border border-neutral/50 text-center">
            <x-ri-inbox-line class="size-16 text-color-muted mx-auto mb-4 opacity-60" />
            <h3 class="text-xl font-bold text-color-base mb-2">No Tickets yet.</h3>
        </div>
        @endforelse

    </div>
</div>