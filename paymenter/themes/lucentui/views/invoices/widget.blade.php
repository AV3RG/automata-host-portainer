<div class="space-y-4">
    @foreach ($invoices as $invoice)
    <a href="{{ route('invoices.show', $invoice) }}" wire:navigate>
        <div class="bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/50 p-4 rounded-lg mb-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="bg-secondary/10 p-2 rounded-full">
                        <x-ri-bill-line class="size-5 text-secondary" />
                    </div>
                    <span class="font-medium">Invoice #{{$invoice->number }}</span>
                    <span class="text-base/50 font-semibold">
                        <x-ri-circle-fill class="size-1 text-base/20" />
                    </span>
                    <span class="text-base text-sm">{{ $invoice->formattedTotal }}</span>
                </div>
                <div class="size-5 rounded-full p-0.5
                @if ($invoice->status == 'paid') text-success bg-success/20
                @elseif($invoice->status == 'cancelled') text-info bg-info/20
                @else text-warning bg-warning/20
                @endif">
                @if ($invoice->status == 'paid')
                    <x-ri-checkbox-circle-fill />
                @elseif($invoice->status == 'cancelled')
                    <x-ri-forbid-fill />
                @elseif($invoice->status == 'pending')
                    <x-ri-error-warning-fill />
                @endif
            </div>
            </div>
            <div class="space-y-2">
                @foreach ($invoice->items as $item)
                <div class="flex items-start gap-3">
                    <div>
                        <p class="text-base font-medium">{{ $item->description }}</p>
                        
                        <p class="text-sm text-base/50">
                            {{ __('invoices.invoice_date') }}: {{ $invoice->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </a>
    @endforeach
</div>
