
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                window.location.reload()

                preventDefault()
            }
        })
    });
})

Alpine.store('notifications', {
    init() {
        Livewire.on('notify', (e) => {
            Alpine.store('notifications').addNotification(e);
        });
    },
    notifications: [],
    addNotification(notification) {
        notification = notification[0]
        notification.show = false;
        notification.id = Date.now() + Math.floor(Math.random() * 1000);
        this.notifications.push(notification);

        // Update the notification to show it
        Alpine.nextTick(() => {
            this.notifications = this.notifications.map(notification => {
                if (notification.id === notification.id) {
                    notification.show = true;
                }
                return notification;
            });
        });

        setTimeout(() => {
            this.removeNotification(notification.id);
        }, notification.timeout || 5000);
    },
    removeNotification(id) {
        this.notifications = this.notifications.map(notification => {
            if (notification.id === id) {
                notification.show = false;
            }
            return notification;
        }).filter(notification => notification.show); // This line filters out notifications that are not shown
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Configuration
    const config = {
        rootMargin: '500px 0px', // Very aggressive pre-loading
        threshold: 0.001,
        delay: 50,
        lazyClass: 'lazy-auto', // We'll add this class automatically
        loadedClass: 'lazy-loaded',
        loadingClass: 'lazy-loading',
        errorClass: 'lazy-error',
        selectors: {
            images: 'img[src]:not([src=""]):not([loading="lazy"])',
            backgrounds: '[style*="background-image"]:not([data-lazy-ignore])',
            iframes: 'iframe[src]:not([src=""]):not([loading="lazy"])'
        }
    };

    // Auto-convert elements to lazy load
    const autoLazify = () => {
        // Find all candidate elements
        const candidates = [
            ...document.querySelectorAll(config.selectors.images),
            ...document.querySelectorAll(config.selectors.backgrounds),
            ...document.querySelectorAll(config.selectors.iframes)
        ];

        candidates.forEach(el => {
            // Skip if already processed or in critical areas
            if (el.closest('[data-lazy-ignore]') || 
                el.classList.contains(config.loadedClass) || 
                el.classList.contains(config.lazyClass)) {
                return;
            }

            // Handle images
            if (el.tagName === 'IMG') {
                el.dataset.src = el.src;
                el.removeAttribute('src');
                el.classList.add(config.lazyClass);
            }
            // Handle background images
            else if (el.style.backgroundImage) {
                const bgUrl = el.style.backgroundImage.match(/url\(["']?(.*?)["']?\)/i);
                if (bgUrl && bgUrl[1]) {
                    el.dataset.bgSrc = bgUrl[1];
                    el.style.backgroundImage = 'none';
                    el.classList.add(config.lazyClass);
                }
            }
            // Handle iframes
            else if (el.tagName === 'IFRAME') {
                el.dataset.src = el.src;
                el.removeAttribute('src');
                el.classList.add(config.lazyClass);
            }
        });

        return candidates.length > 0;
    };

    // Core lazy loading functionality
    const turboLazyLoad = () => {
        // First auto-convert elements
        const hasNewItems = autoLazify();
        const lazyItems = document.getElementsByClassName(config.lazyClass);
        const totalItems = lazyItems.length;
        
        if (!totalItems) return;

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const lazyItem = entry.target;
                        loadItem(lazyItem);
                        observer.unobserve(lazyItem);
                    }
                });
            }, config);

            Array.from(lazyItems).forEach(item => observer.observe(item));
        } else {
            // Fallback for older browsers
            const loadVisible = throttle(() => {
                Array.from(lazyItems).forEach(item => {
                    if (isInViewport(item)) {
                        loadItem(item);
                    }
                });
            }, 100);

            window.addEventListener('scroll', loadVisible);
            window.addEventListener('resize', loadVisible);
            loadVisible();
        }

        // If new items were added, check again after a short delay
        if (hasNewItems) {
            setTimeout(turboLazyLoad, 500);
        }
    };

    // Load individual item
    const loadItem = (element) => {
        if (!element.classList.contains(config.lazyClass)) return;

        element.classList.add(config.loadingClass);

        // Use requestIdleCallback for non-urgent work
        requestIdleCallback(() => {
            try {
                if (element.dataset.src) {
                    if (element.tagName === 'IMG') {
                        element.src = element.dataset.src;
                    } else if (element.tagName === 'IFRAME') {
                        element.src = element.dataset.src;
                    }
                }

                if (element.dataset.bgSrc) {
                    element.style.backgroundImage = `url(${element.dataset.bgSrc})`;
                }

                element.classList.remove(config.lazyClass, config.loadingClass);
                element.classList.add(config.loadedClass);

                // Dispatch event for potential listeners
                element.dispatchEvent(new CustomEvent('lazy-loaded', {
                    bubbles: true
                }));
            } catch (error) {
                element.classList.remove(config.loadingClass);
                element.classList.add(config.errorClass);
                console.error('Lazy load error:', error);
            }
        }, { timeout: 100 });
    };

    // Helper functions
    const isInViewport = (el) => {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= window.innerHeight * 2 &&
            rect.bottom >= 0 &&
            rect.left <= window.innerWidth * 2 &&
            rect.right >= 0
        );
    };

    const throttle = (func, limit) => {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    };

    // Initialize with a small delay
    setTimeout(turboLazyLoad, config.delay);

    // Support for dynamic content (Livewire, Alpine, etc.)
    const observeDOM = (target, callback) => {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach(() => {
                if (autoLazify()) {
                    setTimeout(turboLazyLoad, 100);
                }
            });
        });
        
        observer.observe(target, {
            childList: true,
            subtree: true,
            attributes: false,
            characterData: false
        });
    };

    // Observe the entire document for changes
    observeDOM(document.documentElement);

    // Expose for manual control
    window.turboLazyLoad = turboLazyLoad;
});
