<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-8">
        {{ __('ticket.create_ticket') }}
    </h1>

    <div class="bg-background-secondary/70 border border-neutral/50 p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('ticket.ticket_details') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <x-form.input wire:model="subject" label="{{ __('ticket.subject') }}" name="subject" required />

            @if (count($departments) > 0)
                <x-form.select wire:model="department" label="{{ __('ticket.department') }}" name="department" required>
                    <option value="">{{ __('ticket.select_department') }}</option>
                    @foreach ($departments as $departmentOption) 
                        <option value="{{ $departmentOption }}">{{ $departmentOption }}</option>
                    @endforeach
                </x-form.select>
            @endif

            <x-form.select wire:model="priority" label="{{ __('ticket.priority') }}" name="priority" required>
                <option value="">{{ __('ticket.select_priority') }}</option>
                <option value="low">{{ __('ticket.low') }}</option>
                <option value="medium">{{ __('ticket.medium') }}</option>
                <option value="high">{{ __('ticket.high') }}</option>
            </x-form.select>

            <x-form.select wire:model="service" label="{{ __('ticket.service') }}" name="service">
                <option value="">{{ __('ticket.select_service') }}</option>
                @foreach ($services as $product)
                    <option value="{{ $product->id }}">{{ $product->product->name }} ({{ ucfirst($product->status) }})
                        @if ($product->expires_at)
                            - {{ $product->expires_at->format('Y-m-d') }}
                        @endif
                    </option>
                @endforeach
            </x-form.select>

            <div class="col-span-1 md:col-span-2">
                <label for="editor" class="block text-sm font-medium text-color-muted mb-2">Message</label>
                <div wire:ignore>
                    <textarea id="editor" wire:model="message" placeholder="Your Message Here." class="form-input h-40"></textarea>
                </div>
                <x-easymde-editor wire:model="message" target-id="editor" />
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <x-button.primary class="py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5" wire:click="create">
                {{ __('ticket.create_ticket') }}
            </x-button.primary>
        </div>
    </div>
</div>