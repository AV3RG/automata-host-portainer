// Optimized app.js
const passiveOptions = { passive: true };

document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                window.location.reload();
                preventDefault();
            }
        });
    });
});

Alpine.store('notifications', {
    notifications: [],
    
    init() {
        Livewire.on('notify', (e) => {
            this.addNotification(e);
        });
    },
    
    addNotification(notification) {
        notification = notification[0];
        notification.show = false;
        notification.id = Date.now() + Math.floor(Math.random() * 1000);
        this.notifications.push(notification);

        requestAnimationFrame(() => {
            this.notifications = this.notifications.map(n => {
                if (n.id === notification.id) {
                    n.show = true;
                }
                return n;
            });
        });

        setTimeout(() => {
            this.removeNotification(notification.id);
        }, notification.timeout || 5000);
    },
    
    removeNotification(id) {
        this.notifications = this.notifications.filter(n => {
            if (n.id === id) {
                n.show = false;
            }
            return n.show;
        });
    }
});

let timeUpdateInterval;
const laravelLocale = document.documentElement.lang || 'en';

const localeMapping = {
    'ar': 'ar-SA',  // Arabic (Saudi Arabia)
    'de': 'de-DE',  // German (Germany)
    'en': 'en-US',  // English (US)
    'es': 'es-ES',  // Spanish (Spain)
    'fi': 'fi-FI',  // Finnish (Finland)
    'fr': 'fr-FR',  // French (France)
    'it': 'it-IT',  // Italian (Italy)
    'sv': 'sv-SE',  // Swedish (Sweden)
    'uk': 'uk-UA',  // Ukrainian (Ukraine)
    'ko': 'ko-KR',  // Korean (South Korea)
    'lv': 'lv-LV',  // Latvian (Latvia)
    'nl': 'nl-NL',  // Dutch (Netherlands)
    'no': 'no-NO',  // Norwegian (Norway)
    'pt': 'pt-PT',  // Portuguese (Portugal)
    'sr': 'sr-RS',  // Serbian (Serbia)
    'id': 'id-ID'   // Indonesian (Bahasa Indonesia)
};

function updateDateTime() {
    const now = new Date();
    const timeElement = document.getElementById('current-time');
    const dateElement = document.getElementById('current-date');
    
    const jsLocale = localeMapping[laravelLocale] || 'en-US'; // Default to 'id-ID' if not mapped

    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString(jsLocale, {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    if (dateElement) {
        dateElement.textContent = now.toLocaleDateString(jsLocale, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
}

const debounce = (func, wait) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
};

const throttle = (func, limit) => {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};

let revealObserver;
function initRevealAnimations() {
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    if (revealElements.length === 0) return;

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -10% 0px',
        threshold: 0.1
    };

    revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transform = 'translateY(0)';
                entry.target.style.opacity = '1';
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    revealElements.forEach(el => {
        el.style.transform = 'translateY(20px)';
        el.style.opacity = '0';
        el.style.transition = 'transform 0.6s ease-out, opacity 0.6s ease-out';
        revealObserver.observe(el);
    });
}

const buttonClickStates = new WeakMap();
function preventDoubleClick(selector) {
    const buttons = document.querySelectorAll(selector);
    
    buttons.forEach(button => {
        if (buttonClickStates.has(button)) return; 
        
        const clickHandler = function(e) {
            if (buttonClickStates.get(button)) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            buttonClickStates.set(button, true);
            button.classList.add('btn-loading');
            
            const resetButton = () => {
                buttonClickStates.set(button, false);
                button.classList.remove('btn-loading');
            };
            
            if (window.requestIdleCallback) {
                setTimeout(() => requestIdleCallback(resetButton), 2000);
            } else {
                setTimeout(resetButton, 2000);
            }
        };
        
        button.addEventListener('click', clickHandler, passiveOptions);
        buttonClickStates.set(button, false);
    });
}

let imageObserver;
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    if (images.length === 0) return;

    imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    }, { rootMargin: '50px' });

    images.forEach(img => imageObserver.observe(img));
}

let devicePerformanceLevel = null;
function getDevicePerformance() {
    if (devicePerformanceLevel !== null) return devicePerformanceLevel;
    
    const factors = [];
    
    if (navigator.hardwareConcurrency) {
        factors.push(navigator.hardwareConcurrency >= 4 ? 1 : 0);
    }
    if (navigator.deviceMemory) {
        factors.push(navigator.deviceMemory >= 4 ? 1 : 0);
    }
    
    if (navigator.connection) {
        const conn = navigator.connection;
        factors.push(conn.effectiveType === '4g' ? 1 : 0);
    }
    
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    factors.push(isMobile ? 0 : 1);
    
    const score = factors.reduce((a, b) => a + b, 0) / factors.length;
    devicePerformanceLevel = score > 0.5 ? 'high' : 'low';
    
    return devicePerformanceLevel;
}

function optimizeFormInteractions() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        if (!submitBtn) return;
        
        const submitHandler = function(e) {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
            }, 8000);
        };
        
        form.addEventListener('submit', submitHandler, passiveOptions);
    });
}

function optimizeForMobile() {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        const viewport = document.querySelector('meta[name=viewport]');
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
                }
            }, passiveOptions);
            
            input.addEventListener('blur', () => {
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                }
            }, passiveOptions);
        });
        
        document.body.classList.add('mobile-optimized');
    }
}

function setupErrorHandling() {
    window.addEventListener('error', (e) => {
        console.error('Error:', e.error);
    });
    
    window.addEventListener('unhandledrejection', (e) => {
        console.error('Unhandled promise rejection:', e.reason);
    });
}

function showErrorNotification(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50 transition-opacity duration-300';
    errorDiv.textContent = message;
    
    document.body.appendChild(errorDiv);
    
    requestAnimationFrame(() => {
        errorDiv.style.opacity = '1';
    });
    
    setTimeout(() => {
        errorDiv.style.opacity = '0';
        setTimeout(() => errorDiv.remove(), 300);
    }, 4000);
}

function setupNetworkHandling() {
    let offlineNotification = null;
    
    window.addEventListener('offline', () => {
        if (offlineNotification) return;
        
        offlineNotification = document.createElement('div');
        offlineNotification.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-4 py-2 rounded shadow-lg z-50';
        offlineNotification.textContent = 'You are offline. Some features may not work.';
        document.body.appendChild(offlineNotification);
    });
    
    window.addEventListener('online', () => {
        if (offlineNotification) {
            offlineNotification.remove();
            offlineNotification = null;
        }
    });
}

function initializeApp() {
    updateDateTime();
    timeUpdateInterval = setInterval(updateDateTime, 1000);
    
    const performance = getDevicePerformance();
    
    if (performance === 'high') {
        initRevealAnimations();
        initLazyLoading();
    } else {
        document.body.classList.add('low-performance');
        document.querySelectorAll('.reveal-on-scroll').forEach(el => {
            el.classList.add('revealed');
        });
    }
    
    preventDoubleClick('.btn-primary, [href*="checkout"], [href*="add-to-cart"]');
    optimizeFormInteractions();
    optimizeForMobile();
    setupErrorHandling();
    setupNetworkHandling();
    
    if (performance === 'low') {
        const heavyElements = document.querySelectorAll('.animate-pulse-slowest, .animate-pulse-slow, .animate-pulse-fast');
        heavyElements.forEach(el => {
            el.style.animation = 'none';
            el.style.opacity = '0.1';
        });
    }
}

document.addEventListener('touchstart', () => {}, passiveOptions);
document.addEventListener('touchmove', () => {}, passiveOptions);

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
} else {
    initializeApp();
}

window.addEventListener('beforeunload', () => {
    if (timeUpdateInterval) clearInterval(timeUpdateInterval);
    if (revealObserver) revealObserver.disconnect();
    if (imageObserver) imageObserver.disconnect();
});

window.AppOptimizer = {
    debounce,
    throttle,
    getDevicePerformance,
    showErrorNotification
};

