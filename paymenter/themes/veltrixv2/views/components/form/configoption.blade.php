<div class="flex flex-col gap-1">
    @switch($config->type)
        @case('select')
            <x-form.select name="{{ $name }}" :label="__($config->label ?? $config->name)" :required="$config->required ?? false"
                :selected="config('configs.' . $config->name)" :multiple="$config->multiple ?? false"
                wire:model.live="{{ $name }}" :placeholder="$config->placeholder ?? ''">
                {{ $slot }}
            </x-form.select>
        @break

        @case('slider')
            <div x-data="{
                options: @js($config->children->map(fn($child) => ['option' => $child->name, 'value' => $child->id, 'price' => ($showPriceTag && $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? (string)$child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : ''])),
                showPriceTag: @js($showPriceTag),
                selectedIndex: 0,
                isDragging: false,
                startX: 0,
                sliderWidth: 0,
                sliderOffsetLeft: 0,
                backendValue: $wire.entangle('{{ $name }}').live,

                init() {
                    // Initialize with current value
                    const initialValue = this.$wire.get('{{ $name }}');
                    const foundIndex = this.options.findIndex(plan => plan.value == initialValue);
                    if (foundIndex !== -1) {
                        this.selectedIndex = foundIndex;
                    }
                    
                    // Get slider dimensions
                    this.updateSliderDimensions();
                    
                    // Watch for window resize
                    window.addEventListener('resize', () => this.updateSliderDimensions());
                    
                    // Watch for selectedIndex changes
                    this.$watch('selectedIndex', Alpine.debounce(() => {
                        this.backendValue = this.options[this.selectedIndex].value;
                    }, 100));
                },
                
                updateSliderDimensions() {
                    const slider = this.$refs.sliderTrack;
                    if (slider) {
                        this.sliderWidth = slider.offsetWidth;
                        this.sliderOffsetLeft = slider.getBoundingClientRect().left;
                    }
                },
                
                getProgress() {
                    return this.options.length > 1 ? 
                        (this.selectedIndex / (this.options.length - 1)) * 100 + '%' : 
                        '0%';
                },
                
                getSegmentWidth() {
                    return this.options.length > 1 ? 
                        100 / (this.options.length - 1) + '%' : 
                        '0%';
                },
                
                handleClick(index) {
                    this.selectedIndex = index;
                },
                
                handleMouseDown(e) {
                    this.isDragging = true;
                    this.startX = e.clientX;
                    this.updateSliderDimensions();
                    document.addEventListener('mousemove', this.handleMouseMove.bind(this));
                    document.addEventListener('mouseup', this.handleMouseUp.bind(this));
                },
                
                handleMouseMove(e) {
                    if (!this.isDragging) return;
                    
                    const x = e.clientX - this.sliderOffsetLeft;
                    let percent = Math.min(Math.max(x / this.sliderWidth, 0), 1);
                    this.selectedIndex = Math.round(percent * (this.options.length - 1));
                },
                
                handleMouseUp() {
                    this.isDragging = false;
                    document.removeEventListener('mousemove', this.handleMouseMove.bind(this));
                    document.removeEventListener('mouseup', this.handleMouseUp.bind(this));
                },
                
                handleTouchStart(e) {
                    this.isDragging = true;
                    this.startX = e.touches[0].clientX;
                    this.updateSliderDimensions();
                    document.addEventListener('touchmove', this.handleTouchMove.bind(this));
                    document.addEventListener('touchend', this.handleTouchEnd.bind(this));
                },
                
                handleTouchMove(e) {
                    if (!this.isDragging) return;
                    
                    const x = e.touches[0].clientX - this.sliderOffsetLeft;
                    let percent = Math.min(Math.max(x / this.sliderWidth, 0), 1);
                    this.selectedIndex = Math.round(percent * (this.options.length - 1));
                },
                
                handleTouchEnd() {
                    this.isDragging = false;
                    document.removeEventListener('touchmove', this.handleTouchMove.bind(this));
                    document.removeEventListener('touchend', this.handleTouchEnd.bind(this));
                }
            }" class="flex flex-col gap-2 relative">
                <!-- Slider Track -->
                <div 
                    x-ref="sliderTrack"
                    @mousedown="handleMouseDown($event)"
                    @touchstart="handleTouchStart($event)"
                    class="relative w-full h-8 flex items-center cursor-pointer"
                >
                    <!-- Background Track -->
                    <div class="absolute left-0 right-0 h-2 bg-background-secondary rounded-full"></div>
                    
                    <!-- Progress Track -->
                    <div 
                        class="absolute left-0 h-2 bg-primary rounded-full transition-all duration-700 ease-in-out will-change-[width]"
                        :style="`width: ${getProgress()}`"
                    ></div>
                    
                    <!-- Thumb -->
                    <div 
                        class="absolute top-1/2 -translate-x-1/2 -translate-y-1/2 h-5 w-5 bg-white rounded-full shadow-md z-10 transition-all duration-700 ease-in-out will-change-[left]"
                        :style="`left: ${getProgress()}`"
                    ></div>
                    
                    <!-- Segments -->
                    <template x-for="(_, index) in options" :key="index">
                        <div 
                            class="absolute top-1/2 -translate-x-1/2 -translate-y-1/2 h-3 w-3 rounded-full transition-all duration-700 ease-in-out"
                            :class="{
                                'bg-primary': index <= selectedIndex,
                                'bg-background-secondary': index > selectedIndex
                            }"
                            :style="`left: ${(index / (options.length - 1)) * 100}%`"
                            @click.stop="handleClick(index)"
                        ></div>
                    </template>
                </div>
                
                <!-- Labels -->
                <div class="flex justify-between w-full mt-2">
                    <template x-for="(plan, index) in options" :key="index">
                        <div class="flex flex-col items-center text-center" style="width: calc(100% / (options.length));">
                            <button 
                                @click="handleClick(index)"
                                class="flex flex-col items-center"
                            >
                                <span 
                                    class="text-xs font-medium transition-all duration-700 ease-in-out"
                                    :class="{
                                        'text-primary font-bold scale-110': index === selectedIndex,
                                        'text-light': index !== selectedIndex
                                    }"
                                    x-text="plan.option"
                                ></span>
                                <span 
                                    x-show="showPriceTag && plan.price"
                                    class="text-xs mt-1"
                                    :class="{
                                        'text-primary font-bold': index === selectedIndex,
                                        'text-light': index !== selectedIndex
                                    }"
                                    x-text="plan.price"
                                ></span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        @break

        @case('text')
        @case('password')
        @case('email')
        @case('number')
        @case('color')
        @case('file')
            <x-form.input name="{{ $name }}" :type="$config->type" :label="__($config->label ?? $config->name)"
                :placeholder="$config->default ?? ''" :required="$config->required ?? false" wire:model.live="{{ $name }}" :placeholder="$config->placeholder ?? ''" />
        @break

        @case('checkbox')
            <x-form.checkbox name="{{ $name }}" type="checkbox" :label="__($config->label ?? $config->name)"
                :required="$config->required ?? false" :checked="config('configs.' . $config->name) ? true : false" wire:model="{{ $name }}" />
        @break

        @case('radio')
            <x-form.radio name="{{ $name }}" :label="__($config->label ?? $config->name)"
                :selected="config('configs.' . $config->name)" :required="$config->required ?? false" wire:model="{{ $name }}">
                {{  $slot }}
            </x-form.radio>
        @break

        @default
    @endswitch

    @isset($config->description)
        @isset($config->link)
            <a href="{{ $config->link }}" class="text-xs text-primary-500 hover:underline hover:text-secondary group">
                {{ $config->description }}
                <x-ri-arrow-right-long-line class="ml-1 size-3 inline-block -rotate-45 group-hover:rotate-0 transition" />
            </a>
        @else
            <p class="text-xs text-primary-500">{{ $config->description }}</p>
        @endisset
    @endisset
</div>
