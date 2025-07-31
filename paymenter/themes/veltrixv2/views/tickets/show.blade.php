<div class="bg-primary-800 p-6 rounded-xl shadow-lg">
    <h1 class="text-2xl font-semibold text-white mb-4">Ticket: {{ $ticket->subject }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Ticket details sidebar - appears first on mobile, right side on desktop -->
        <div class="order-first md:order-last md:col-span-1">
            <div class="bg-primary-700/50 rounded-xl overflow-hidden">
                <h2 class="text-xl font-semibold p-4 border-b border-primary-600">
                    {{ __('ticket.ticket_details') }}
                </h2>

                <div class="divide-y divide-primary-600">
                    <div class="p-4">
                        <h4 class="text-sm text-gray-300">{{ __('ticket.subject') }}</h4>
                        <p class="font-medium mt-1">{{ $ticket->subject }}</p>
                    </div>

                    <div class="p-4">
                        <h4 class="text-sm text-gray-300">{{ __('ticket.status') }}</h4>
                        <p class="font-medium mt-1">{{ ucfirst($ticket->status) }}</p>
                    </div>

                    <div class="p-4">
                        <h4 class="text-sm text-gray-300">{{ __('ticket.priority') }}</h4>
                        <p class="font-medium mt-1">{{ ucfirst($ticket->priority) }}</p>
                    </div>

                    <div class="p-4">
                        <h4 class="text-sm text-gray-300">{{ __('ticket.created_at') }}</h4>
                        <p class="font-medium mt-1">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>

                    @if ($ticket->department)
                        <div class="p-4">
                            <h4 class="text-sm text-gray-300">{{ __('ticket.department') }}</h4>
                            <p class="font-medium mt-1">{{ $ticket->department }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Messages and reply section -->
        <div class="md:col-span-3">
            <!-- Messages list with auto-polling -->
            <div class="flex flex-col gap-4 max-h-[60vh] overflow-y-auto pr-2 mb-6" wire:poll.5s>
                @foreach ($ticket->messages()->with('user')->get() as $message)
                    <div
                        class="bg-background-secondary border border-neutral p-4 rounded-xl w-full max-w-[85%] transition-colors duration-200
                        {{ $message->user_id === $ticket->user_id ? 'ml-auto' : 'mr-auto' }}"
                        @if ($loop->last) x-data x-init="$el.scrollIntoView({behavior: 'smooth'})" @endif>
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h2 class="font-semibold">{{ $message->user->name }}</h2>
                                <p class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            {!! Str::markdown(nl2br(e($message->message)), ['allow_unsafe_links' => false]) !!}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            <div class="bg-background-secondary border border-neutral rounded-xl p-4">
                <h3 class="text-lg font-medium mb-3">{{ __('ticket.reply') }}</h3>
                <form wire:submit.prevent="save">
                    <div wire:ignore>
                        <textarea id="editor" class="w-full"></textarea>
                        <x-easymde-editor />
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-button.primary class="gradient-button">
                            {{ __('ticket.reply') }}
                        </x-button.primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
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