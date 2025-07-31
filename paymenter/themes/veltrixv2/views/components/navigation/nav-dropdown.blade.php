@props(['nav'])

<div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
    class="relative w-full mx-auto overflow-hidden text-sm font-normal divide-y divide-gray-200">
    <div class="cursor-pointer">
        <button @click="activeAccordion = !activeAccordion"
            class="flex items-center justify-between w-full p-3 text-sm font-semibold whitespace-nowrap rounded-lg hover:bg-primary/10">
            <div class="flex flex-row gap-2">
                @isset($nav['icon'])
                    <x-dynamic-component :component="$nav['icon']"
                        class="size-5 {{ $nav['active'] ? 'text-primary' : 'fill-base/50' }}" />
                @endisset
                <span>{{ $nav['name'] }}</span>
            </div>
            <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                class="size-4 text-base ease-out duration-300" />
        </button>
        <div x-show="activeAccordion" x-collapse x-cloak>
            <div class="p-4 pt-0 opacity-70">
                @foreach ($nav['children'] as $child)
                    <div class="flex items-center space-x-2">
                        <x-navigation.link :href="route($child['route'], $child['params'] ?? [])"
                            :spa="$child['spa'] ?? true"
                            class="{{ $child['active'] ? 'text-primary font-bold' : '' }}">
                            {{ $child['name'] }}
                        </x-navigation.link>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
{!! theme('custom_layout_css', '') !!}