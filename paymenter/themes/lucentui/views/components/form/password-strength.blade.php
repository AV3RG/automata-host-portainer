@props([
    'name' => 'password',
    'label' => null,
    'required' => false,
    'divClass' => null,
    'class' => null,
    'placeholder' => null,
    'id' => null,
    'hideRequiredIndicator' => false,
    'dirty' => false,
    'minStrengthLevel' => 3 // 1=red, 2=yellow, 3=blue, 4=green
])

<fieldset class="flex flex-col relative w-full mt-4 {{ $divClass ?? '' }}">
    @if ($label)
        <legend class="mb-1">
            <label for="{{ $name }}"
            class="text-sm font-bold text-base">
            {{ $label }}
            @if ($required && !$hideRequiredIndicator)
                <span class="text-red-500">*</span>
            @endif
            </label>
        </legend>
        @endif
        
        <div class="relative">
        <input 
            type="password" 
            id="{{ $id ?? $name }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder ?? ($label ?? '') }}"
            class="block w-full text-sm text-base bg-background-secondary/50 border border-neutral rounded-md shadow-sm focus:outline-none transition-all duration-300 ease-in-out disabled:bg-background-secondary/50 disabled:cursor-not-allowed px-2.5 py-2.5 pr-10 {{ $class ?? '' }}"
            @if ($dirty && isset($attributes['wire:model'])) wire:dirty.class="!border-yellow-600" @endif
            {{ $attributes->except(['placeholder', 'label', 'id', 'name', 'class', 'divClass', 'required', 'hideRequiredIndicator', 'dirty', 'minStrengthLevel']) }}
            @required($required)
            data-hs-strong-password='{
                "target": "#{{ $id ?? $name }}-strength",
                "hints": "#{{ $id ?? $name }}-hints",
                "minStrengthLevel": {{ $minStrengthLevel }},
                "stripClasses": "hs-strong-password:opacity-100 hs-strong-password-accepted:bg-teal-500 h-2 flex-auto rounded-full bg-blue-500 opacity-50 mx-1"
            }'
        >
    </div>
    <div id="{{ $id ?? $name }}-strength" class="flex mt-2 -mx-1" aria-live="polite"></div>
    <div id="{{ $id ?? $name }}-hints" class="mt-3">
        <div class="text-xs text-primary-100 mb-2 font-medium">Password requirements:</div>
        <div class="flex flex-wrap gap-2 text-xs">
            <div class="flex items-center gap-x-2" data-hs-strong-password-hints-rule-text="min-length">
                <span class="hidden" data-check>
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span data-uncheck>
                    <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span class="text-base">Minimum 8 characters</span>
            </div>
            
            <div class="flex items-center gap-x-2" data-hs-strong-password-hints-rule-text="lowercase">
                <span class="hidden" data-check>
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span data-uncheck>
                    <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span class="text-base">One lowercase letter</span>
            </div>
            
            <div class="flex items-center gap-x-2" data-hs-strong-password-hints-rule-text="uppercase">
                <span class="hidden" data-check>
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span data-uncheck>
                    <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span class="text-base">One uppercase letter</span>
            </div>
            
            <div class="flex items-center gap-x-2" data-hs-strong-password-hints-rule-text="numbers">
                <span class="hidden" data-check>
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span data-uncheck>
                    <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span class="text-base">One number</span>
            </div>
            
            <div class="flex items-center gap-x-2" data-hs-strong-password-hints-rule-text="special-characters">
                <span class="hidden" data-check>
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span data-uncheck>
                    <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span class="text-base">One special character (!@#$%^&*)</span>
            </div>
        </div>
    </div>

    @error($name)
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</fieldset>

<script>
// Enhanced password strength validation
document.addEventListener('DOMContentLoaded', function() {
    const passwordInputs = document.querySelectorAll('[data-hs-strong-password]');
    
    passwordInputs.forEach(function(input) {
        const config = JSON.parse(input.getAttribute('data-hs-strong-password'));
        const strengthBar = document.querySelector(config.target);
        const hints = document.querySelector(config.hints);
        const warningElement = document.querySelector(config.target.replace('-strength', '-warning'));
        const minStrengthLevel = config.minStrengthLevel || 3;
        
        // Initial setup - create empty bars
        if (strengthBar) {
            strengthBar.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const bar = document.createElement('div');
                bar.className = 'h-2 flex-auto rounded-full mx-1 bg-neutral-300 transition-all duration-300';
                strengthBar.appendChild(bar);
            }
        }
        
        // Input event listener
        input.addEventListener('input', function() {
            const strengthLevel = checkPasswordStrength(input.value, strengthBar, hints, warningElement, minStrengthLevel);
            
            // Set custom validity based on strength level
            if (input.value && strengthLevel < minStrengthLevel) {
                input.setCustomValidity('Password terlalu lemah! Minimal harus mencapai level orange.');
            } else {
                input.setCustomValidity('');
            }
        });
        
        // Prevent form submission if password is too weak
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const strengthLevel = getCurrentPasswordStrength(input.value);
                if (input.value && strengthLevel < minStrengthLevel) {
                    e.preventDefault();
                    input.focus();
                    
                    // Show warning
                    if (warningElement) {
                        warningElement.classList.remove('hidden');
                    }
                    
                    // Show validation message
                    input.reportValidity();
                }
            });
        }
    });
});

function getCurrentPasswordStrength(password) {
    const checks = {
        'min-length': password.length >= 8,
        'lowercase': /[a-z]/.test(password),
        'uppercase': /[A-Z]/.test(password),
        'numbers': /\d/.test(password),
        'special-characters': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };
    
    return Object.values(checks).filter(Boolean).length;
}

function checkPasswordStrength(password, strengthBar, hintsContainer, warningElement, minStrengthLevel) {
    const checks = {
        'min-length': password.length >= 8,
        'lowercase': /[a-z]/.test(password),
        'uppercase': /[A-Z]/.test(password),
        'numbers': /\d/.test(password),
        'special-characters': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };
    
    const passed = Object.values(checks).filter(Boolean).length;
    
    // Update strength bars
    if (strengthBar) {
        const bars = strengthBar.querySelectorAll('div');
        bars.forEach((bar, index) => {
            // Reset classes
            bar.className = 'h-2 flex-auto rounded-full mx-1 transition-all duration-300';
            
            if (index < passed) {
                if (passed <= 2) {
                    bar.classList.add('bg-red-500');
                } else if (passed === 3) {
                    bar.classList.add('bg-yellow-500'); // Orange/Yellow level
                } else if (passed === 4) {
                    bar.classList.add('bg-blue-500');
                } else if (passed === 5) {
                    bar.classList.add('bg-green-500');
                }
            } else {
                bar.classList.add('bg-neutral-300');
            }
        });
    }
    
    // Show/hide warning based on strength
    if (warningElement) {
        if (password && passed < minStrengthLevel) {
            warningElement.classList.remove('hidden');
        } else {
            warningElement.classList.add('hidden');
        }
    }
    
    // Update requirement icons
    if (hintsContainer) {
        Object.keys(checks).forEach(function(rule) {
            const ruleElement = hintsContainer.querySelector(`[data-hs-strong-password-hints-rule-text="${rule}"]`);
            if (ruleElement) {
                const checkIcon = ruleElement.querySelector('[data-check]');
                const uncheckIcon = ruleElement.querySelector('[data-uncheck]');
                
                if (checks[rule]) {
                    checkIcon.classList.remove('hidden');
                    uncheckIcon.classList.add('hidden');
                } else {
                    checkIcon.classList.add('hidden');
                    uncheckIcon.classList.remove('hidden');
                }
            }
        });
    }
    
    return passed;
}
</script>