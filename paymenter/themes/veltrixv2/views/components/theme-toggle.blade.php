<!-- Dark Mode Toggle Button -->
<!-- I sadly removed the easter egg (made it 500 clicks :) -->
<button 
    @click="handleDarkModeToggle()"
    type="button" 
    class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-neutral transition"
    x-data="{
        clickHistory: [],
        handleDarkModeToggle() {
            this.darkMode = !this.darkMode;
            // Track clicks
            this.clickHistory.push(Date.now());
            // Keep only clicks from last 2 seconds
            this.clickHistory = this.clickHistory.filter(t => Date.now() - t < 2000);
            // If 5 clicks in 2 seconds, show Easter egg
            if (this.clickHistory.length >= 5000000) {
                window.showDarkModeEasterEgg();
                this.clickHistory = [];
            }
        }
    }"
>
    <template x-if="!darkMode">
        <x-ri-sun-fill class="size-5 text-base" />
    </template>
    <template x-if="darkMode">
        <x-ri-moon-fill class="size-5 text-base" />
    </template>
</button>

<!-- Easter Egg Script -->
<script>
    // Make function globally available
    window.showDarkModeEasterEgg = function() {
        // Check if popup already exists
        if (document.getElementById('darkmode-easter-egg-overlay')) return;
        
        // Create overlay
        const overlay = document.createElement('div');
        overlay.id = 'darkmode-easter-egg-overlay';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        
        // Create popup
        const popup = document.createElement('div');
        popup.className = 'bg-background dark:bg-background-dark rounded-lg shadow-xl p-6 max-w-md w-full mx-4 border border-gray-200 dark:border-gray-700';

        const messages = [
            "Whoa there! Enjoying the light show?",
            "Toggle much? Here's a cookie 🍪",
            "Day... night... day... night... make up your mind!",
            "You've unlocked: Professional Theme Tosser",
            "Congratulations! You've discovered the secret theme-stress-test mode!"
        ];
        const randomMessage = messages[Math.floor(Math.random() * messages.length)];
        const isDarkMode = document.documentElement.classList.contains('dark');

        popup.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-primary dark:text-primary-dark">Theme Toggler Extraordinaire!</h3>
                <button id="close-easter-egg-x" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="text-foreground dark:text-foreground-dark">
                <p>${randomMessage}</p>
                <div class="mt-4 flex justify-center">
                    <span class="text-4xl">${isDarkMode ? '🌙' : '☀️'}</span>
                </div>
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    You clicked the theme toggle 5 times in 2 seconds!
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button id="close-easter-egg-btn" class="px-4 py-2 bg-primary dark:bg-primary-dark text-white rounded hover:bg-opacity-90 transition">
                    Close
                </button>
            </div>
        `;

        overlay.appendChild(popup);
        document.body.appendChild(overlay);

        // Add event listeners for close buttons
        document.getElementById('close-easter-egg-x').addEventListener('click', function() {
            document.getElementById('darkmode-easter-egg-overlay').remove();
        });

        document.getElementById('close-easter-egg-btn').addEventListener('click', function() {
            document.getElementById('darkmode-easter-egg-overlay').remove();
        });
    }
</script>
{!! theme('custom_layout_css', '') !!}