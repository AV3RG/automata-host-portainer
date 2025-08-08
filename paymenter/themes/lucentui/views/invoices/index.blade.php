<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-8">
        {{ __('navigation.invoices') }}
    </h1>

    <div class="grid grid-cols-3 md:grid-cols-3 gap-4 mb-12 bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 p-2 rounded-xl">
        <div class="bg-success/10 border border-success/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <x-ri-checkbox-circle-fill class="size-6 text-success mr-2" />
                <span class="text-2xl font-bold text-success">{{ $invoices->where('status', 'paid')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-success">{{ __('Paid') }}</p>
        </div>

        <div class="bg-warning/10 border border-warning/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <x-ri-error-warning-fill class="size-6 text-warning mr-2" />
                <span class="text-2xl font-bold text-warning">{{ $invoices->where('status', 'pending')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-warning">{{ __('Pending') }}</p>
        </div>

        <div class="bg-inactive/10 border border-inactive/50 rounded-xl p-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <x-ri-close-circle-fill class="size-6 text-inactive mr-2" />
                <span class="text-2xl font-bold text-inactive">{{ $invoices->where('status', 'cancelled')->count() }}</span>
            </div>
            <p class="text-sm font-medium text-inactive">{{ __('Cancelled') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 md:gap-5">
        @forelse ($invoices as $invoice)
            <a href="{{ route('invoices.show', $invoice) }}" wire:navigate class="block">
                <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 hover:bg-secondary/25 border border-neutral/50 p-6 rounded-xl shadow-lg
                            transition-all duration-300 hover:scale-[1.01] hover:shadow-xl hover:border-primary/50
                            flex flex-col md:flex-row items-start md:items-center justify-between gap-4">

                    <div class="flex items-start md:items-center gap-4 flex-grow">
                        <div class="bg-blue-500/10 p-3 rounded-full flex-shrink-0 shadow-sm"> 
                            <x-ri-bill-line class="size-6 text-blue-500" /> 
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-color-base leading-tight">{{ __('Invoice') }} #{{ $invoice->number }}</span>
                            <p class="text-color-muted text-sm mt-1">
                                {{ __('Total') }}: <span class="font-bold text-primary">{{ $invoice->formattedTotal }}</span>
                                <span class="mx-2 text-color-muted/50">•</span> 
                                {{ __('invoices.invoice_date') }}: {{ $invoice->created_at->format('d M Y') }}
                            </p>
                            @if($invoice->due_date)
                                <p class="text-color-muted text-xs mt-1">
                                    {{ __('Jatuh Tempo') }}: {{ $invoice->due_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0 mt-3 md:mt-0">
                        <div class="text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1.5
                            @if ($invoice->status == 'paid') text-success bg-success/20
                            @elseif($invoice->status == 'cancelled') text-inactive bg-inactive/20
                            @else text-warning bg-warning/20
                            @endif">
                            @if ($invoice->status == 'paid')
                                <x-ri-checkbox-circle-fill class="size-4" />
                                {{ __('Paid') }}
                            @elseif($invoice->status == 'cancelled')
                                <x-ri-close-circle-fill class="size-4" /> 
                                {{ __('Cancelled') }}
                            @elseif($invoice->status == 'pending')
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
                <x-ri-file-info-line class="size-16 text-color-muted mx-auto mb-4 opacity-60" />
                <h3 class="text-xl font-bold text-color-base mb-2">No invoices yet.</h3>
            </div>
        @endforelse

        {{ $invoices->links() }}
    </div>
</div>