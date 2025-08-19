// Password strength initializer extracted from app.js to keep things modular

function initPasswordStrengthIndicators() {
    try {
        const passwordInputs = document.querySelectorAll('[data-hs-strong-password]');
        if (!passwordInputs || passwordInputs.length === 0) return;

        passwordInputs.forEach((input) => {
            if (input.hasAttribute('data-password-strength-initialized')) return;

            const raw = input.getAttribute('data-hs-strong-password');
            if (!raw) return;
            let config;
            try { config = JSON.parse(raw); } catch { return; }

            const strengthBar = document.querySelector(config.target);
            const hints = document.querySelector(config.hints);
            const warningElement = document.querySelector(config.target.replace('-strength', '-warning'));
            const minStrengthLevel = config.minStrengthLevel || 3;

            input.setAttribute('data-password-strength-initialized', 'true');

            // Build bars if missing
            if (strengthBar && strengthBar.children.length === 0) {
                strengthBar.innerHTML = '';
                for (let i = 0; i < 5; i++) {
                    const bar = document.createElement('div');
                    bar.className = 'h-2 flex-auto rounded-full mx-1 bg-neutral-300 transition-all duration-300';
                    strengthBar.appendChild(bar);
                }
            }

            const getCurrentPasswordStrength = (password) => {
                const checks = {
                    'min-length': password.length >= 8,
                    'lowercase': /[a-z]/.test(password),
                    'uppercase': /[A-Z]/.test(password),
                    'numbers': /\d/.test(password),
                    'special-characters': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                };
                return Object.values(checks).filter(Boolean).length;
            };

            const checkPasswordStrength = (password) => {
                const checks = {
                    'min-length': password.length >= 8,
                    'lowercase': /[a-z]/.test(password),
                    'uppercase': /[A-Z]/.test(password),
                    'numbers': /\d/.test(password),
                    'special-characters': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                };
                const passed = Object.values(checks).filter(Boolean).length;

                if (strengthBar) {
                    const bars = strengthBar.querySelectorAll('div');
                    bars.forEach((bar, index) => {
                        bar.className = 'h-2 flex-auto rounded-full mx-1 transition-all duration-300';
                        if (index < passed) {
                            if (passed <= 2) bar.classList.add('bg-red-500');
                            else if (passed === 3) bar.classList.add('bg-yellow-500');
                            else if (passed === 4) bar.classList.add('bg-blue-500');
                            else if (passed === 5) bar.classList.add('bg-green-500');
                        } else {
                            bar.classList.add('bg-neutral-300');
                        }
                    });
                }

                if (warningElement) {
                    if (password && passed < minStrengthLevel) warningElement.classList.remove('hidden');
                    else warningElement.classList.add('hidden');
                }

                if (hints) {
                    Object.entries({
                        'min-length': password.length >= 8,
                        'lowercase': /[a-z]/.test(password),
                        'uppercase': /[A-Z]/.test(password),
                        'numbers': /\d/.test(password),
                        'special-characters': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                    }).forEach(([rule, ok]) => {
                        const ruleElement = hints.querySelector(`[data-hs-strong-password-hints-rule-text="${rule}"]`);
                        if (!ruleElement) return;
                        const checkIcon = ruleElement.querySelector('[data-check]');
                        const uncheckIcon = ruleElement.querySelector('[data-uncheck]');
                        if (ok) {
                            checkIcon?.classList.remove('hidden');
                            uncheckIcon?.classList.add('hidden');
                        } else {
                            checkIcon?.classList.add('hidden');
                            uncheckIcon?.classList.remove('hidden');
                        }
                    });
                }

                return passed;
            };

            input.addEventListener('input', () => {
                const level = checkPasswordStrength(input.value);
                if (input.value && level < minStrengthLevel) input.setCustomValidity('Password terlalu lemah! Minimal harus mencapai level orange.');
                else input.setCustomValidity('');
            }, { passive: true });

            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    const level = getCurrentPasswordStrength(input.value);
                    if (input.value && level < minStrengthLevel) {
                        e.preventDefault();
                        input.focus();
                        if (warningElement) warningElement.classList.remove('hidden');
                        input.reportValidity();
                    }
                });
            }
        });
    } catch {}
}

// Expose globally (used by app.js lifecycle hooks)
window.initPasswordStrengthIndicators = initPasswordStrengthIndicators;

// Hook into Livewire SPA lifecycle to re-init after navigations
document.addEventListener('livewire:init', () => {
    // On initial livewire boot
    setTimeout(() => window.initPasswordStrengthIndicators(), 0);
    // After DOM morph updates
    Livewire.hook('morph.updated', () => setTimeout(() => window.initPasswordStrengthIndicators(), 0));
    // After successful requests
    Livewire.hook('request', ({ succeed }) => {
        succeed(() => setTimeout(() => window.initPasswordStrengthIndicators(), 0));
    });
});

// After SPA navigation completes
document.addEventListener('livewire:navigated', () => setTimeout(() => window.initPasswordStrengthIndicators(), 0));


