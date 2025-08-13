@assets
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"></script>
@endassets

<div id="cf-turnstile" style="width: 100%; display: flex; justify-content: center;"></div>

<script>
    let turnstileWidgetId = null;

    function renderTurnstile() {
        const isDarkMode = localStorage.getItem('_x_darkMode') === 'true';
        const theme = isDarkMode ? 'dark' : 'light';

        const containerWidth =
            document.getElementById('cf-turnstile').getBoundingClientRect().width;
        const size = containerWidth < 300 ? 'compact' : 'normal';

        // If already rendered, reset instead of re-render
        if (turnstileWidgetId !== null) {
            turnstile.reset(turnstileWidgetId);
            return;
        }

        turnstileWidgetId = turnstile.render('#cf-turnstile', {
            sitekey: '{{ config('settings.captcha_site_key') }}',
            size: size,
            theme: theme,
            callback: (token) => @this.set('captcha', token, false),
        });
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('request', ({ succeed }) => {
            succeed(() => {
                if (turnstileWidgetId !== null) {
                    turnstile.reset(turnstileWidgetId);
                }
            });
        });
    });

    // Define waitForWidthAndRender in the same script block
    function waitForWidthAndRender() {
        const el = document.getElementById('cf-turnstile');
        const width = el.getBoundingClientRect().width;

        if (width > 0) {
            renderTurnstile();
        } else {
            requestAnimationFrame(waitForWidthAndRender);
        }
    }

    waitForWidthAndRender();
</script>