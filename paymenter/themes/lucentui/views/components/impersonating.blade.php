@if(session()->has('impersonating'))
    <div class="fixed bottom-0 right-0 z-50 flex gap-4 justify-between items-center bg-background-secondary shadow-lg p-4 w-full border-t border-neutral animate-slide-up">
        <div class="flex items-center gap-2">
            <x-ri-user-line class="text-primary w-5 h-5" />
            <p class="text-sm text-color-base">
                {{ __('You are currently impersonating') }}: 
                <strong class="text-primary">{{ auth()->user()->name }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-4">
            <a href="/admin/users/{{ auth()->user()->id }}/edit">
                <x-button.primary class="bg-red-500 hover:bg-red-700 px-4 py-2 text-sm flex items-center gap-2">
                    <x-ri-logout-box-line class="w-4 h-4" />
                    {{ __('Leave') }}
                </x-button.primary>
            </a>
        </div>
    </div>
@endif