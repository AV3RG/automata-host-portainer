<?php

/**
 * LucentUI Theme
 *
 * LucentUI theme for Paymenter, providing a modern and sleek
 * design tailored for gaming and server hosting platforms.
 *
 * @version v1.2.2-05dc38bc14ae6ecd1610d556b1bfe890-true-1754623521-Darren_14
 */

return [
    'name' => 'LucentUI',
    'author' => 'AquaGeprek',
    'url' => 'https://docs.zaqua.studio/lucentui',
    'version' => 'v1.2.2',
    'description' => 'A modern and sleek theme for Paymenter, designed to enhance user experience with a focus on gaming and server hosting.',

    'settings' => [
        // General Settings
        [
            'name' => 'direct_checkout',
            'label' => 'Direct Checkout',
            'type' => 'checkbox',
            'default' => false,
            'description' => 'Don\'t show the product overview page, go directly to the checkout page',
        ],
        [
            'name' => 'show_category_image_banner',
            'label' => 'Show Category Image Banner',
            'type' => 'checkbox',
            'default' => true,
            'description' => 'Display the category image as a Banner on the product listing page',
        ],
        [
            'name' => 'small_images',
            'label' => 'Small Images',
            'type' => 'checkbox',
            'default' => false,
        ],
        [
            'name' => 'show_category_description',
            'label' => 'Show Category Description',
            'type' => 'checkbox',
            'default' => true,
        ],

        [
            'name' => 'background_image_url',
            'label' => 'Background Image URL (Optional)',
            'type' => 'text',
            'default' => '',
            'description' => 'Provide the URL for the background image (recommended: 1920x1080 or larger)',
        ],
        [
            'name' => 'background_image_opacity',
            'label' => 'Background Image Opacity',
            'type' => 'text',
            'default' => 30,
            'description' => 'Opacity of background image (0-100%)',
        ],
        [
            'name' => 'background_image_blur',
            'label' => 'Background Image Blur',
            'type' => 'text',
            'default' => 5,
            'description' => 'Blur intensity for background image (0-20px)',
        ],

        // SEO Settings
        [
            'name' => 'seo_title',
            'label' => 'SEO - Site Title',
            'type' => 'text',
            'default' => 'Premium Game Hosting - Fast & Reliable Servers',
            'description' => 'Custom title for SEO (will override default site name)',
        ],
        [
            'name' => 'seo_description',
            'label' => 'SEO - Meta Description',
            'type' => 'textarea',
            'default' => 'Experience premium game hosting with 99.9% uptime, instant deployment, and 24/7 support. Join thousands of satisfied gamers worldwide.',
            'description' => 'Meta description for search engines (150-160 characters recommended)',
        ],
        [
            'name' => 'seo_keywords',
            'label' => 'SEO - Keywords',
            'type' => 'text',
            'default' => 'game hosting, minecraft hosting, server hosting, gaming servers, dedicated servers',
            'description' => 'Comma-separated keywords for SEO',
        ],
        [
            'name' => 'seo_author',
            'label' => 'SEO - Author',
            'type' => 'text',
            'default' => 'LucentUI Gaming',
            'description' => 'Author meta tag',
        ],
        [
            'name' => 'og_image',
            'label' => 'SEO - Open Graph Image URL',
            'type' => 'text',
            'default' => '',
            'description' => 'Image URL for social media sharing (1200x630px recommended)',
        ],

        // Custom HTML Snippets
        [
            'name' => 'custom_head_html',
            'label' => 'Custom HTML - Head Section',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Insert custom HTML in the &lt;head&gt; section. Perfect for analytics, fonts, or meta tags. Example: Google Analytics, Facebook Pixel',
        ],
        [
            'name' => 'custom_body_top_html',
            'label' => 'Custom HTML - Body Top',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Insert custom HTML at the beginning of &lt;body&gt;. Good for tracking pixels or notifications. Example: Google Tag Manager (noscript)',
        ],
        [
            'name' => 'custom_body_bottom_html',
            'label' => 'Custom HTML - Body Bottom',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Insert custom HTML before closing &lt;/body&gt;. Perfect for chat widgets, analytics, or custom scripts. Example: Tawk.to, WhatsApp chat, Discord widget',
        ],
        [
            'name' => 'custom_dashboard_html',
            'label' => 'Custom HTML - Dashboard Only',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Insert custom HTML only on dashboard pages. Perfect for user-specific widgets or announcements',
        ],
        [
            'name' => 'custom_homepage_html',
            'label' => 'Custom HTML - Homepage Only',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Insert custom HTML only on homepage. Perfect for promotional banners or special offers',
        ],

        // Advanced Custom Code
        [
            'name' => 'custom_css',
            'label' => 'Custom CSS',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Add custom CSS styles. Will be wrapped in &lt;style&gt; tags automatically. Example: Override theme colors, add animations',
        ],
        [
            'name' => 'custom_js',
            'label' => 'Custom JavaScript',
            'type' => 'textarea',
            'default' => '',
            'description' => 'Add custom JavaScript code. Will be wrapped in &lt;script&gt; tags automatically. Example: Custom interactions, third-party integrations',
        ],

        // Homepage Hero Section
        [
            'name' => 'homepage_hero_badge',
            'label' => 'Hero Badge Text',
            'type' => 'text',
            'default' => '🚀 Welcome to Paymenter - Now with 50% faster deployment!',
        ],
        [
            'name' => 'homepage_hero_title1',
            'label' => 'Hero Title Part 1',
            'type' => 'text',
            'default' => 'Premium',
        ],
        [
            'name' => 'homepage_hero_title2',
            'label' => 'Hero Title Part 2 (Highlighted)',
            'type' => 'text',
            'default' => 'Game Hosting',
        ],
        [
            'name' => 'homepage_hero_desc',
            'label' => 'Hero Description',
            'type' => 'textarea',
            'default' => 'Experience next-generation game hosting with blazing-fast SSD performance, enterprise-grade security, and 24/7 expert support. Deploy your servers instantly across our global network of premium datacenters and enjoy zero-lag gaming with 99.9% uptime guarantee. Join thousands of satisfied gamers who trust our platform for their competitive edge.',
            'description' => 'Description text for the homepage hero section',
        ],
        [
            'name' => 'hero_cta_primary_text',
            'label' => 'Primary CTA Button Text',
            'type' => 'text',
            'default' => 'Start Gaming Now',
        ],
        [
            'name' => 'hero_cta_secondary_text',
            'label' => 'Secondary CTA Button Text',
            'type' => 'text',
            'default' => 'Explore Features',
        ],
        [
            'name' => 'hero_trust_badge_1',
            'label' => 'Trust Badge 1 Text',
            'type' => 'text',
            'default' => '30-Day Money Back',
        ],
        [
            'name' => 'hero_trust_badge_2',
            'label' => 'Trust Badge 2 Text',
            'type' => 'text',
            'default' => 'Instant Activation',
        ],

        // Stats Section
        [
            'name' => 'stats_uptime_title',
            'label' => 'Uptime Stat Title',
            'type' => 'text',
            'default' => 'Uptime Guarantee',
        ],
        [
            'name' => 'stats_uptime_subtitle',
            'label' => 'Uptime Stat Subtitle',
            'type' => 'text',
            'default' => 'Industry-leading reliability',
        ],
        [
            'name' => 'stats_support_title',
            'label' => 'Support Stat Title',
            'type' => 'text',
            'default' => 'Expert Support',
        ],
        [
            'name' => 'stats_support_subtitle',
            'label' => 'Support Stat Subtitle',
            'type' => 'text',
            'default' => 'Always here to help',
        ],
        [
            'name' => 'stats_users_title',
            'label' => 'Users Stat Title',
            'type' => 'text',
            'default' => 'Happy Gamers',
        ],
        [
            'name' => 'stats_users_subtitle',
            'label' => 'Users Stat Subtitle',
            'type' => 'text',
            'default' => 'Join our community',
        ],
        [
            'name' => 'stats_servers_title',
            'label' => 'Servers Stat Title',
            'type' => 'text',
            'default' => 'Servers Active',
        ],
        [
            'name' => 'stats_servers_subtitle',
            'label' => 'Servers Stat Subtitle',
            'type' => 'text',
            'default' => 'Powering gaming worldwide',
        ],

        // Quick Actions Section
        [
            'name' => 'quick_actions_title',
            'label' => 'Quick Actions Section Title',
            'type' => 'text',
            'default' => 'Get Started in Seconds',
        ],
        [
            'name' => 'quick_actions_subtitle',
            'label' => 'Quick Actions Section Subtitle',
            'type' => 'text',
            'default' => 'Everything you need is just one click away',
        ],
        [
            'name' => 'quick_actions_header',
            'label' => 'Quick Actions Header',
            'type' => 'text',
            'default' => 'Quick Actions',
        ],
        [
            'name' => 'buy_server_title',
            'label' => 'Buy Server Card Title',
            'type' => 'text',
            'default' => 'Buy Server',
        ],
        [
            'name' => 'buy_server_subtitle',
            'label' => 'Buy Server Card Subtitle',
            'type' => 'text',
            'default' => 'Game Hosting',
        ],
        [
            'name' => 'buy_server_description',
            'label' => 'Buy Server Card Description',
            'type' => 'text',
            'default' => 'Deploy instantly with our 1-click setup',
        ],
        [
            'name' => 'buy_server_link',
            'label' => 'Buy Server Link',
            'type' => 'text',
            'default' => 'https://yourserver.com',
            'description' => 'Link to the product page for buying a server',
        ],
        [
            'name' => 'help_title',
            'label' => 'Help Card Title',
            'type' => 'text',
            'default' => 'Get Help',
        ],
        [
            'name' => 'help_subtitle',
            'label' => 'Help Card Subtitle',
            'type' => 'text',
            'default' => '24/7 Discord',
        ],
        [
            'name' => 'help_link',
            'label' => 'Help/Discord Link',
            'type' => 'text',
            'default' => 'https://help.yourserver.com',
            'description' => 'Link to the help or Discord support page',
        ],
        [
            'name' => 'login_title',
            'label' => 'Login Card Title',
            'type' => 'text',
            'default' => 'Login',
        ],
        [
            'name' => 'login_subtitle',
            'label' => 'Login Card Subtitle',
            'type' => 'text',
            'default' => 'Access Account',
        ],
        [
            'name' => 'login_description',
            'label' => 'Login Card Description',
            'type' => 'text',
            'default' => 'Manage your servers from anywhere',
        ],
        [
            'name' => 'dashboard_title',
            'label' => 'Dashboard Card Title',
            'type' => 'text',
            'default' => 'Dashboard',
        ],
        [
            'name' => 'dashboard_subtitle',
            'label' => 'Dashboard Card Subtitle',
            'type' => 'text',
            'default' => 'Manage Services',
        ],
        [
            'name' => 'dashboard_description',
            'label' => 'Dashboard Card Description',
            'type' => 'text',
            'default' => 'Control all your servers from one place',
        ],
        [
            'name' => 'docs_title',
            'label' => 'Documentation Card Title',
            'type' => 'text',
            'default' => 'Documentation',
        ],
        [
            'name' => 'docs_subtitle',
            'label' => 'Documentation Card Subtitle',
            'type' => 'text',
            'default' => 'Get Started',
        ],
        [
            'name' => 'docs_description',
            'label' => 'Documentation Card Description',
            'type' => 'text',
            'default' => 'Comprehensive guides and tutorials',
        ],
        [
            'name' => 'docs_link',
            'label' => 'Documentation Link',
            'type' => 'text',
            'default' => 'https://docs.yourserver.com',
            'description' => 'Link to the documentation page',
        ],

        // Features Section
        [
            'name' => 'features_title',
            'label' => 'Features Section Title',
            'type' => 'text',
            'default' => 'Why Choose Us?',
        ],
        [
            'name' => 'features_subtitle',
            'label' => 'Features Section Subtitle',
            'type' => 'text',
            'default' => 'We\'ve built the most advanced game hosting platform with features that give you the competitive edge',
        ],
        [
            'name' => 'feature_1_title',
            'label' => 'Feature 1 Title',
            'type' => 'text',
            'default' => 'Military-Grade Security',
        ],
        [
            'name' => 'feature_1_desc',
            'label' => 'Feature 1 Description',
            'type' => 'text',
            'default' => 'Advanced DDoS protection, encrypted connections, and real-time threat monitoring keep your servers safe from attacks 24/7.',
        ],
        [
            'name' => 'feature_2_title',
            'label' => 'Feature 2 Title',
            'type' => 'text',
            'default' => 'Smart Analytics',
        ],
        [
            'name' => 'feature_2_desc',
            'label' => 'Feature 2 Description',
            'type' => 'text',
            'default' => 'Real-time performance monitoring, player analytics, and detailed insights help you optimize your server performance and player experience.',
        ],
        [
            'name' => 'feature_3_title',
            'label' => 'Feature 3 Title',
            'type' => 'text',
            'default' => 'Lightning Setup',
        ],
        [
            'name' => 'feature_3_desc',
            'label' => 'Feature 3 Description',
            'type' => 'text',
            'default' => 'Deploy your game servers in under 60 seconds with our automated setup and instant configuration system. No technical knowledge required.',
        ],
        [
            'name' => 'feature_4_title',
            'label' => 'Feature 4 Title',
            'type' => 'text',
            'default' => 'Extreme Performance',
        ],
        [
            'name' => 'feature_4_desc',
            'label' => 'Feature 4 Description',
            'type' => 'text',
            'default' => 'Premium NVMe SSD storage, high-frequency CPUs, and optimized network routing deliver zero-lag gaming experience for your players.',
        ],
        [
            'name' => 'feature_5_title',
            'label' => 'Feature 5 Title',
            'type' => 'text',
            'default' => 'Global Reach',
        ],
        [
            'name' => 'feature_5_desc',
            'label' => 'Feature 5 Description',
            'type' => 'text',
            'default' => '15+ datacenter locations across 6 continents ensure your players get ultra-low latency wherever they are in the world.',
        ],
        [
            'name' => 'feature_6_title',
            'label' => 'Feature 6 Title',
            'type' => 'text',
            'default' => 'AI-Powered Optimization',
        ],
        [
            'name' => 'feature_6_desc',
            'label' => 'Feature 6 Description',
            'type' => 'text',
            'default' => 'Machine learning algorithms continuously optimize server performance and predict issues before they impact your players.',
        ],

        // Testimonials Section
        [
            'name' => 'testimonials_title',
            'label' => 'Testimonials Section Title',
            'type' => 'text',
            'default' => 'What Gamers Say',
        ],
        [
            'name' => 'testimonials_subtitle',
            'label' => 'Testimonials Section Subtitle',
            'type' => 'text',
            'default' => 'Join thousands of satisfied customers who\'ve made the switch to premium hosting',
        ],
        [
            'name' => 'testimonial_1_name',
            'label' => 'Testimonial 1 - Name',
            'type' => 'text',
            'default' => 'John Doe',
        ],
        [
            'name' => 'testimonial_1_role',
            'label' => 'Testimonial 1 - Role',
            'type' => 'text',
            'default' => 'Minecraft Server Admin',
        ],
        [
            'name' => 'testimonial_1_quote',
            'label' => 'Testimonial 1 - Quote',
            'type' => 'textarea',
            'default' => 'Best hosting service I\'ve ever used. Zero downtime and lightning-fast support response!',
        ],
        [
            'name' => 'testimonial_2_name',
            'label' => 'Testimonial 2 - Name',
            'type' => 'text',
            'default' => 'Sarah Anderson',
        ],
        [
            'name' => 'testimonial_2_role',
            'label' => 'Testimonial 2 - Role',
            'type' => 'text',
            'default' => 'Gaming Community Leader',
        ],
        [
            'name' => 'testimonial_2_quote',
            'label' => 'Testimonial 2 - Quote',
            'type' => 'textarea',
            'default' => 'The performance boost was incredible. My players love the zero-lag experience!',
        ],
        [
            'name' => 'testimonial_3_name',
            'label' => 'Testimonial 3 - Name',
            'type' => 'text',
            'default' => 'Mike Rodriguez',
        ],
        [
            'name' => 'testimonial_3_role',
            'label' => 'Testimonial 3 - Role',
            'type' => 'text',
            'default' => 'Esports Team Manager',
        ],
        [
            'name' => 'testimonial_3_quote',
            'label' => 'Testimonial 3 - Quote',
            'type' => 'textarea',
            'default' => 'Professional grade hosting at an affordable price. Couldn\'t ask for more!',
        ],

        // Services Section
        [
            'name' => 'services_title',
            'label' => 'Services Section Title',
            'type' => 'text',
            'default' => 'Our Services',
        ],
        [
            'name' => 'services_subtitle',
            'label' => 'Services Section Subtitle',
            'type' => 'text',
            'default' => 'Choose from our comprehensive range of game hosting solutions',
        ],

        // Call to Action Section
        [
            'name' => 'cta_title',
            'label' => 'CTA Section Title',
            'type' => 'text',
            'default' => 'Ready to Get Started?',
        ],
        [
            'name' => 'cta_subtitle',
            'label' => 'CTA Section Subtitle',
            'type' => 'markdown',
            'default' => 'Join thousands of satisfied customers who trust our platform for their game hosting needs',
        ],
        [
            'name' => 'cta_primary_text',
            'label' => 'CTA Primary Button Text',
            'type' => 'text',
            'default' => 'View Pricing',
        ],
        [
            'name' => 'cta_secondary_text',
            'label' => 'CTA Secondary Button Text',
            'type' => 'text',
            'default' => 'Chat Support',
        ],

        // Banner Announcement Settings
        [
            'name' => 'banner_enabled',
            'label' => 'Enable Banner Announcement',
            'type' => 'checkbox',
            'default' => false,
            'description' => 'Show announcement banner above the dashboard welcome screen',
        ],
        [
            'name' => 'banner_type',
            'label' => 'Banner Type',
            'type' => 'select',
            'options' => [
                'critical' => 'Critical (Red)',
                'warning' => 'Warning (Orange)',
                'info' => 'Info (Primary)', 
                'success' => 'Success (Green)'
            ],
            'default' => 'info',
            'description' => 'Choose the banner type and color scheme',
        ],
        [
            'name' => 'banner_message',
            'label' => 'Banner Message',
            'type' => 'textarea',
            'default' => 'This is an important announcement for all users.',
            'description' => 'Main message content for the banner',
        ],
        [
            'name' => 'banner_dismissible',
            'label' => 'Allow Users to Dismiss Banner',
            'type' => 'checkbox',
            'default' => true,
            'description' => 'Allow users to close/dismiss the banner announcement',
        ],

        // Color Scheme Settings
        [
            'name' => 'primary',
            'label' => 'Primary - Brand Color (Light)',
            'type' => 'color',
            'default' => 'hsl(340, 85%, 55%)',
        ],
        [
            'name' => 'secondary',
            'label' => 'Secondary - Brand Color (Light)',
            'type' => 'color',
            'default' => 'hsl(45, 90%, 60%)',
        ],
        [
            'name' => 'neutral',
            'label' => 'Borders, Accents... (Light)',
            'type' => 'color',
            'default' => 'hsl(210, 20%, 80%)',
        ],
        [
            'name' => 'base',
            'label' => 'Base - Text Color (Light)',
            'type' => 'color',
            'default' => 'hsl(0, 0%, 10%)',
        ],
        [
            'name' => 'muted',
            'label' => 'Muted - Text Color (Light)',
            'type' => 'color',
            'default' => 'hsl(200, 25%, 35%)',
        ],
        [
            'name' => 'inverted',
            'label' => 'Inverted - Text Color (Light)',
            'type' => 'color',
            'default' => 'hsl(0, 0%, 100%)',
        ],
        [
            'name' => 'background',
            'label' => 'Background - Color (Light)',
            'type' => 'color',
            'default' => 'hsl(0, 0%, 98%)',
        ],
        [
            'name' => 'background-secondary',
            'label' => 'Background - Secondary Color (Light)',
            'type' => 'color',
            'default' => 'hsl(0, 0%, 95%)',
        ],
        [
            'name' => 'dark-primary',
            'label' => 'Primary - Brand Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(340, 85%, 55%)',
        ],
        [
            'name' => 'dark-secondary',
            'label' => 'Secondary - Brand Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(45, 90%, 60%)',
        ],
        [
            'name' => 'dark-neutral',
            'label' => 'Borders, Accents... (Dark)',
            'type' => 'color',
            'default' => 'hsl(210, 20%, 25%)',
        ],
        [
            'name' => 'dark-base',
            'label' => 'Base - Text Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(0, 0%, 95%)',
        ],
        [
            'name' => 'dark-muted',
            'label' => 'Muted - Text Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(200, 25%, 40%)',
        ],
        [
            'name' => 'dark-inverted',
            'label' => 'Inverted - Text Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(0, 0%, 20%)',
        ],
        [
            'name' => 'dark-background',
            'label' => 'Background - Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(220, 30%, 10%)',
        ],
        [
            'name' => 'dark-background-secondary',
            'label' => 'Background - Secondary Color (Dark)',
            'type' => 'color',
            'default' => 'hsl(220, 25%, 15%)',
        ],

        // Footer and Social Media URLs
        [
            'name' => 'footer_text',
            'label' => 'Footer Text',
            'type' => 'markdown',
            'default' => 'Welcome to Paymenter!',
        ],
        [
            'name' => 'privacy_policy_url',
            'label' => 'Privacy Policy URL',
            'type' => 'text',
            'default' => 'https://yourserver.com/privacy',
            'description' => 'URL for the privacy policy page',
        ],
        [
            'name' => 'terms_of_service_url',
            'label' => 'Terms of Service URL',
            'type' => 'text',
            'default' => 'https://yourserver.com/tos',
            'description' => 'URL for the terms of service page',
        ],
        [
            'name' => 'support_url',
            'label' => 'Support URL',
            'type' => 'text',
            'default' => 'https://yourserver.com/contact',
            'description' => 'URL for the contact us page',
        ],
        [
            'name' => 'instagram_url',
            'label' => 'Instagram URL',
            'type' => 'text',
            'default' => 'https://instagram.com/yourprofile',
            'description' => 'URL for the Instagram profile',
        ],
        [
            'name' => 'youtube_url',
            'label' => 'YouTube URL',
            'type' => 'text',
            'default' => 'https://youtube.com/yourchannel',
            'description' => 'URL for the YouTube channel',
        ],
        [
            'name' => 'tiktok_url',
            'label' => 'TikTok URL',
            'type' => 'text',
            'default' => 'https://tiktok.com/yourprofile',
            'description' => 'URL for the TikTok profile',
        ],
        [
            'name' => 'github_url',
            'label' => 'GitHub URL',
            'type' => 'text',
            'default' => 'https://github.com/yourprofile',
            'description' => 'URL for the GitHub profile',
        ],
    ],
];