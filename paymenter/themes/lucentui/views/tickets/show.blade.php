<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-2">
        {{ __('ticket.tickets') }}
    </h1>
    <p class="text-lg text-color-muted font-light max-w-2xl mb-8">
        Subject: {{ $ticket->subject }}
    </p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-3 order-2 md:order-1">
            <div class="bg-background-secondary/50 border border-neutral p-6 rounded-xl shadow-lg mb-6">
                <div class="flex flex-col gap-5 max-h-[60vh] overflow-y-auto pr-4 custom-scrollbar" wire:poll.5s>
                    @foreach ($ticket->messages()->with('user')->get() as $index => $message)
                        <div
                            class="bg-background/10 border border-neutral/20 p-4 rounded-lg
                                   w-full max-w-[80%] {{ $message->user_id === auth()->id() ? 'ml-auto bg-primary/10 border-primary/20' : 'mr-auto' }}
                                   transition-all duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="font-semibold text-color-base text-lg">{{ $message->user->name }}</h3>
                                    <p class="text-sm text-neutral-400">{{ $message->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="mt-2 prose dark:prose-invert max-w-none text-color-base">
                                {!! Str::markdown(nl2br(e($message->message)), ['allow_unsafe_links' => false]) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg mt-6">
                <form wire:submit.prevent="save">
                    <div wire:ignore> 
                        <textarea id="editor" class="form-input h-40"></textarea>
                    </div>
                    <x-easymde-editor />

                    <div class="flex justify-end mt-4">
                        <x-button.primary class="py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5">
                            {{ __('ticket.reply') }}
                        </x-button.primary>
                    </div>
                </form>
            </div>
        </div>

        <div class="md:col-span-1 order-1 md:order-2">
            <div class="bg-background-secondary/50 border border-neutral p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold text-color-base mb-4">{{ __('ticket.ticket_details') }}</h2>

                <div class="space-y-4"> 
                    {{-- Subject --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-neutral-400">{{ __('ticket.subject') }}:</span>
                        <span class="text-lg font-semibold text-color-base">{{ $ticket->subject }}</span>
                    </div>

                    {{-- Status --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-neutral-400 mb-1">{{ __('ticket.status') }}:</span>
                        <div class="text-sm font-semibold px-3 py-1 rounded-full w-fit flex items-center gap-1.5
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
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-neutral-400">{{ __('ticket.priority') }}:</span>
                        <span class="text-base text-color-base">{{ ucfirst($ticket->priority) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-neutral-400">{{ __('ticket.created_at') }}:</span>
                        <span class="text-base text-color-base">{{ $ticket->created_at->format('d M Y, H:i') }} ({{ $ticket->created_at->diffForHumans() }})</span>
                    </div>
                    @if ($ticket->department)
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-neutral-400">{{ __('ticket.department') }}:</span>
                            <span class="text-base text-color-base">{{ $ticket->department }}</span>
                        </div>
                    @endif
                    @if ($ticket->service)
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-neutral-400">{{ __('ticket.service') }}:</span>
                            <span class="text-base text-color-base">
                                <a href="{{ route('services.show', $ticket->service) }}" class="text-primary font-bold hover:underline" wire:navigate>
                                    {{ $ticket->service->product->name }}
                                </a>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>