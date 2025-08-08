<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 rounded-2xl">
    <x-navigation.breadcrumb class="mb-6" />

    <h1 class="text-3xl lg:text-4xl font-extrabold text-color-base mt-4 mb-8">
        {{ __('account.personal_details') }}
    </h1>

    <div class="bg-background-secondary/70 border border-neutral p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-color-base mb-6">{{ __('account.personal_details') }}</h2>

        <form wire:submit.prevent="submit"> 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input name="first_name" type="text" :label="__('general.input.first_name')"
                    :placeholder="__('general.input.first_name_placeholder')" wire:model="first_name" required dirty />
                <x-form.input name="last_name" type="text" :label="__('general.input.last_name')"
                    :placeholder="__('general.input.last_name_placeholder')" wire:model="last_name" required dirty />
                <x-form.input name="email" type="email" :label="__('general.input.email')"
                    :placeholder="__('general.input.email_placeholder')" required wire:model="email" dirty />
                <div class="md:col-span-2"> 
                    <x-form.properties :custom_properties="$custom_properties" :properties="$properties" dirty />
                </div>
            </div>

            <div class="flex justify-end mt-6"> 
                <x-button.primary type="submit" class="py-3 text-base font-bold transition-all transform rounded-lg shadow-lg hover:bg-primary focus:outline-none focus:ring-4 focus:ring-primary-500 hover:-translate-y-0.5">
                    {{ __('general.update') }}
                </x-button.primary>
            </div>
        </form>
    </div>
</div>