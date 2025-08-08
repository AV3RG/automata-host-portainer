<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">

    <div @if ($checkPayment) wire:poll="checkPaymentStatus" @endif>
        @if ($this->pay)
            <x-modal title="{{ __('invoices.payment_pending') }} #{{ $invoice->number }}" open>
                <div class="mt-8">
                    {{ $this->pay }}
                </div>
                <x-slot name="closeTrigger">
                    <div class="flex items-center gap-4">
                        <span class="text-color-muted text-lg">{{ __('invoices.amount') }}: <span class="font-bold text-color-base">{{ $invoice->formattedRemaining }}</span></span>
                        <button wire:confirm="{{ __('Are you sure you want to exit payment?') }}" wire:click="exitPay" @click="open = false"
                            class="text-color-muted hover:text-color-base transition-colors duration-200 p-2 rounded-full">
                            <x-ri-close-fill class="size-6" />
                        </button>
                    </div>
                </x-slot>
            </x-modal>
        @endif
    </div>

    <div class="relative overflow-hidden bg-gradient-to-br from-background-secondary/50 to-background-secondary/30 border border-neutral/30 rounded-xl shadow-2xl backdrop-blur-sm mt-4 mb-8">

        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-primary/10 via-primary/5 to-transparent rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-gradient-to-tr from-primary/8 via-primary/4 to-transparent rounded-full blur-2xl opacity-40"></div>
        
        <div class="relative z-10 p-8 lg:p-12">
            
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
                <div class="mb-6 lg:mb-0">
                    <h1 class="text-4xl lg:text-4xl font-black text-color-base leading-tight mb-2 bg-gradient-to-r from-color-base via-color-base to-primary bg-clip-text">
                        {{ __('invoices.invoice', ['id' => $invoice->number]) }}
                    </h1>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-primary/60 rounded-full"></div>
                </div>
                <div class="flex items-center gap-4">
                    @if ($invoice->status == 'paid')
                        <div class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-success/20 to-success/10 border border-success/30 rounded-2xl backdrop-blur-sm">
                            <x-ri-checkbox-circle-fill class="size-6 text-success" />
                            <span class="text-success font-bold text-lg">{{ __('invoices.paid') }}</span>
                        </div>
                    @elseif ($invoice->status == 'pending')
                        <div class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-warning/20 to-warning/10 border border-warning/30 rounded-2xl backdrop-blur-sm">
                            <x-ri-error-warning-fill class="size-6 text-warning" />
                            <span class="text-warning font-bold text-lg">{{ __('invoices.payment_pending') }}</span>
                        </div>
                    @endif
                    
                    <button wire:click="downloadPDF" class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-primary/80 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                        <x-ri-download-line class="size-5 transition-transform group-hover:scale-110" />
                        <span>Download</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-12">
                
                <div class="bg-gradient-to-br from-background/80 to-background/60 border border-neutral/20 rounded-2xl p-6 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <x-ri-user-line class="size-6 text-primary" />
                        <h3 class="uppercase font-black text-color-base text-sm tracking-wider">{{ __('invoices.issued_to') }}</h3>
                    </div>
                    <div class="space-y-2">
                        <p class="text-color-base text-xl font-bold mb-2">{{ $invoice->user->name }}</p>
                        <p class="text-color-muted text-sm leading-relaxed">{{ $invoice->user->address }}</p>
                        <p class="text-color-muted text-sm">{{ $invoice->user->city }} {{ $invoice->user->zip }}</p>
                        <p class="text-color-muted text-sm">{{ $invoice->user->state }} {{ $invoice->user->country }}</p>
                        @foreach($invoice->user->properties()->with('parent_property')->whereHas('parent_property', function ($query) {
                            $query->where('show_on_invoice', true);
                        })->get() as $property)
                            <p class="text-color-muted text-sm">{{ $property->value }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gradient-to-br from-background/80 to-background/60 border border-neutral/20 rounded-2xl p-6 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <x-ri-building-line class="size-6 text-primary" />
                        <h3 class="uppercase font-black text-color-base text-sm tracking-wider">{{ __('invoices.bill_to') }}</h3>
                    </div>
                    <div class="space-y-2">
                        <p class="text-color-base text-xl font-bold mb-2">{{ config('settings.company_name') }}</p>
                        <p class="text-color-muted text-sm leading-relaxed">{{ config('settings.company_address') }} {{ config('settings.company_address2') }}</p>
                        <p class="text-color-muted text-sm">{{ config('settings.company_zip') }} {{ config('settings.company_city') }}</p>
                        <p class="text-color-muted text-sm">{{ config('settings.company_state') }} {{ config('settings.company_country') }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-background/80 to-background/60 border border-neutral/20 rounded-2xl p-6 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <x-ri-file-list-3-line class="size-6 text-primary" />
                        <h3 class="uppercase font-black text-color-base text-sm tracking-wider">Invoice Details</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 px-3 bg-background/50 rounded-xl">
                            <span class="text-color-muted text-sm font-medium">{{ __('invoices.invoice_date')}}</span>
                            <span class="font-bold text-color-base">{{ $invoice->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-background/50 rounded-xl">
                            <span class="text-color-muted text-sm font-medium">{{ __('invoices.invoice_no')}}</span>
                            <span class="font-bold text-color-base">#{{ $invoice->number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($invoice->status == 'pending')
                <div class="bg-gradient-to-r from-background/60 to-background/40 border border-neutral/20 rounded-2xl p-8 mb-12 backdrop-blur-sm">
                    <h3 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-3">
                        <x-ri-wallet-3-line class="size-6 text-primary" />
                        Payment Options
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-end">
                        <div class="space-y-4">
                            @if(Auth::user()->credits()->where('currency_code', $invoice->currency_code)->exists() && Auth::user()->credits()->where('currency_code', $invoice->currency_code)->first()->amount > 0)
                                @php
                                    $credit = Auth::user()->credits()
                                            ->where('currency_code', $invoice->currency_code)
                                            ->where('amount', '>', 0)
                                            ->first();
                                    $itemHasCredit = $invoice->items()->where('reference_type', App\Models\Credit::class)->exists();
                                @endphp
                                @if($credit && !$itemHasCredit)
                                    <div class="p-4 bg-primary/5 border border-primary/20 rounded-xl">
                                        <x-form.checkbox wire:model="use_credits" name="use_credits" :label="__('product.use_credits')" />
                                    </div>
                                @endif
                            @endif
                            
                            @if(count($gateways) > 1)
                                <x-form.select wire:model.live="gateway" :label="__('product.payment_method')" class="w-full" name="gateway">
                                    @foreach ($gateways as $gateway)
                                        <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                                    @endforeach
                                </x-form.select>
                            @endif
                        </div>
                        
                        <div class="flex flex-col gap-4">
                            <div class="text-right">
                                <p class="text-color-muted text-lg mb-1">Amount Due</p>
                                <p class="text-3xl font-black text-primary">{{ $invoice->formattedRemaining }}</p>
                            </div>
                            
                            <div class="flex gap-3">
                                <x-button.primary wire:click="pay" wire:loading.attr="disabled" wire:target="pay" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all duration-300 transform hover:scale-105">
                                    <span wire:loading wire:target="pay" class="flex items-center justify-center gap-2">
                                        <x-ri-loader-5-fill class="size-4 animate-spin" />
                                        Processing...
                                    </span>
                                    <span wire:loading.remove wire:target="pay">{{ __('product.checkout') }}</span>
                                </x-button.primary>
                                
                                
                                @if ($checkPayment)
                                    <button wire:click="checkPaymentStatus" wire:loading.attr="disabled" wire:target="checkPaymentStatus"
                                        class="px-4 py-2 bg-background border border-neutral/30 hover:bg-background-secondary text-color-base rounded-lg transition-all duration-300 flex items-center gap-2">
                                        <x-ri-refresh-line class="size-4" wire:loading.remove wire:target="checkPaymentStatus" />
                                        <x-ri-loader-5-fill class="size-4 animate-spin" wire:loading wire:target="checkPaymentStatus" />
                                        <span wire:loading.remove wire:target="checkPaymentStatus" class="font-medium">Check Status</span>
                                        <span wire:loading wire:target="checkPaymentStatus">{{ __('invoices.checking_payment') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-12">
                <h3 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-3">
                    <x-ri-file-list-3-line class="size-6 text-primary" />
                    Invoice Items
                </h3>
                
                <div class="overflow-hidden border border-neutral/20 rounded-2xl backdrop-blur-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-background to-background/80 border-b border-neutral/20">
                                    <th scope="col" class="px-8 py-6 text-left text-xs font-black text-color-base uppercase tracking-wider">
                                        {{ __('invoices.item') }}
                                    </th>
                                    <th scope="col" class="px-8 py-6 text-left text-xs font-black text-color-base uppercase tracking-wider">
                                        {{ __('invoices.price') }}
                                    </th>
                                    <th scope="col" class="px-8 py-6 text-left text-xs font-black text-color-base uppercase tracking-wider">
                                        {{ __('invoices.quantity') }}
                                    </th>
                                    <th scope="col" class="px-8 py-6 text-right text-xs font-black text-color-base uppercase tracking-wider">
                                        {{ __('invoices.total') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral/10">
                                @foreach ($invoice->items as $item)
                                    <tr class="bg-background-secondary/40 hover:bg-background-secondary/60 transition-all duration-300 group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-1 h-8 bg-gradient-to-b from-primary to-primary/40 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                                @if(in_array($item->reference_type, ['App\Models\Service', 'App\Models\ServiceUpgrade']))
                                                    <a href="{{ route('services.show', $item->reference_type == 'App\Models\Service' ? $item->reference_id : $item->reference->service_id) }}"
                                                        class="text-color-base font-bold hover:text-primary transition-colors duration-200 hover:underline underline-offset-2">
                                                        {{ $item->description }}
                                                    </a>
                                                @else
                                                    <span class="text-color-base font-bold">{{ $item->description }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-color-base font-semibold">{{ $item->formattedPrice }}</td>
                                        <td class="px-8 py-6 text-color-base font-semibold">{{ $item->quantity }}</td>
                                        <td class="px-8 py-6 text-right text-color-base font-bold text-lg">{{ $item->formattedTotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mb-12">
                <div class="w-full sm:w-96">
                    <div class="space-y-4">
                        @if ($invoice->formattedTotal->tax > 0)
                            <div class="flex justify-between items-center py-3 px-4 bg-background/40 rounded-xl">
                                <span class="text-color-muted font-bold uppercase text-sm tracking-wider">{{ __('invoices.subtotal') }}</span>
                                <span class="text-color-base font-bold text-lg">
                                    {{ $invoice->formattedTotal->format($invoice->formattedTotal->price - $invoice->formattedTotal->tax) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-background/40 rounded-xl">
                                <span class="text-color-muted font-bold uppercase text-sm tracking-wider">
                                    {{ \App\Classes\Settings::tax()->name }}
                                </span>
                                <span class="text-color-base font-bold text-lg">
                                    {{ $invoice->formattedTotal->formatted->tax }}
                                </span>
                            </div>
                        @endif
                        <div class="border-t border-neutral/20 pt-4">
                            <div class="flex justify-between items-center py-4 px-4 bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl border border-primary/20">
                                <span class="text-color-base font-black uppercase text-lg tracking-wider">{{ __('invoices.total') }}</span>
                                <span class="text-primary font-black text-3xl">
                                    {{ $invoice->formattedTotal }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($invoice->transactions->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-color-base mb-6 flex items-center gap-3">
                        <x-ri-exchange-dollar-line class="size-6 text-primary" />
                        {{ __('invoices.transactions') }}
                    </h3>
                    
                    <div class="overflow-hidden border border-neutral/20 rounded-2xl backdrop-blur-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-background to-background/80 border-b border-neutral/20">
                                        <th scope="col" class="px-8 py-6 text-left text-xs font-black text-color-base uppercase tracking-wider">
                                            {{ __('invoices.date') }}
                                        </th>
                                        <th scope="col" class="px-8 py-6 text-left text-xs font-black text-color-base uppercase tracking-wider">
                                            {{ __('invoices.transaction_id') }}
                                        </th>
                                        <th scope="col" class="px-8 py-6 text-left text-xs font-black text-color-base uppercase tracking-wider">
                                            {{ __('invoices.gateway') }}
                                        </th>
                                        <th scope="col" class="px-8 py-6 text-right text-xs font-black text-color-base uppercase tracking-wider">
                                            {{ __('invoices.amount') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral/10">
                                    @foreach ($invoice->transactions as $transaction)
                                        <tr class="bg-background-secondary/40 hover:bg-background-secondary/60 transition-all duration-300 group">
                                            <td class="px-8 py-6 flex items-center gap-3">
                                                <div class="w-1 h-8 bg-gradient-to-b from-success to-success/40 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                                <span class="text-color-base font-bold">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                            </td>
                                            <td class="px-8 py-6 text-color-base font-semibold">{{ $transaction->transaction_id }}</td>
                                            <td class="px-8 py-6 text-color-base font-semibold">{{ $transaction->gateway?->name }}</td>
                                            <td class="px-8 py-6 text-right text-color-base font-bold text-lg">{{ $transaction->formattedAmount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>