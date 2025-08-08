<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 rounded-2xl">
    <x-navigation.breadcrumb class="mb-6" />

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-8">
        {{ __('account.credits') }}
    </h1>

    <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg mb-8">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('account.credits') }}</h2> 
        @if (Auth::user()->credits->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach (Auth::user()->credits as $credit)
                    <div class="flex flex-col bg-background-tertiary border border-neutral rounded-lg p-5 items-center justify-center text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                        <h5 class="text-xl font-bold text-color-base mb-1">{{ $credit->currency->code }}</h5>
                        <p class="text-lg text-secondary font-semibold">{{ $credit->formattedAmount }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-background-tertiary rounded-lg p-6 text-center border border-neutral">
                <p class="text-color-muted text-base">{{ __('account.no_credit') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('account.add_credit') }}</h2>

        <form wire:submit.prevent="addCredit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
                <x-form.select name="currency" :label="__('account.input.currency')" wire:model="currency" required>
                    <option value="" disabled selected>{{ __('account.input.currency') }}</option> 
                    @foreach(\App\Models\Currency::all() as $currency)
                        <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                    @endforeach
                </x-form.select>

                <x-form.input x-mask:dynamic="$money($input, '.', '', 2)" name="amount" type="text" {{-- Changed type to text for x-mask --}}
                    :label="__('account.input.amount')" :placeholder="__('account.input.amount_placeholder')"
                    wire:model="amount" required />

                <div class="md:col-span-2"> 
                    <x-form.select name="gateway" :label="__('product.payment_method')" wire:model="gateway" required>
                        <option value="" disabled selected>{{ __('product.payment_method') }}</option> 
                        @foreach(\App\Models\Gateway::all() as $gateway)
                            <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <x-button.primary type="submit" class="py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5">
                    {{ __('account.add_credit') }}
                </x-button.primary>
            </div>
        </form>
    </div>
</div>