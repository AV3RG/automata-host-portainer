<!-- Show saved! when the form is saved -->
<div class="flex flex-row items-center">
    <x-button.primary {{ $attributes }}>
        {{ $slot }}
    </x-button.primary>

    <div class="flex items-center justify-center ml-2 text-green-500 hidden opacity-0 transition-opacity ease-in-out delay-150 duration-300 mt-4" id="saved">
        {{ __('Saved!') }}
    </div>
    @script
        <script>
            $wire.on('saved', function () {
                setTimeout(() => {
                    document.getElementById('saved').classList.remove('hidden');
                }, 50);
                setTimeout(() => {
                    document.getElementById('saved').classList.remove('opacity-0');
                    document.getElementById('saved').classList.add('opacity-100');
                }, 70);
                setTimeout(() => {
                    document.getElementById('saved').classList.remove('opacity-100');
                    document.getElementById('saved').classList.add('opacity-0');
                }, 2500);
            });
        </script>
    @endscript
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