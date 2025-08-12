<?php

namespace Paymenter\Extensions\Others\FAQ;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\HtmlString;
use Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource;
use Paymenter\Extensions\Others\FAQ\FAQService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class FAQ extends Extension
{
    /**
     * Get all the configuration for the extension
     * 
     * @param array $values
     * @return array
     */
    public function getConfig($values = [])
    {
        try {
            return [
                [
                    'name' => 'Notice',
                    'type' => 'placeholder',
                    'label' => new HtmlString('You can use this extension to manage FAQ questions for existing product categories, go to <a class="text-primary-600" href="' . FAQQuestionResource::getUrl() . '">FAQ Management</a> to get started.'),
                ],
            ];
        } catch (\Exception $e) {
            return [
                [
                    'name' => 'Notice',
                    'type' => 'placeholder',
                    'label' => new HtmlString('You can use this extension to manage FAQ questions for existing product categories, you\'ll need to enable this extension above to get started.'),
                ],
            ];
        }
    }

    public function enabled()
    {
        // Run migrations
        Artisan::call('migrate', ['--path' => 'extensions/Others/FAQ/database/migrations', '--force' => true]);
    }

    public function boot()
    {
        // Load configuration
        $this->loadConfig();
        
        // Register the FAQService with the service container
        $this->app->singleton('Paymenter\Extensions\Others\FAQ\FAQService', function ($app) {
            return new FAQService();
        });
        
        // Register views namespace
        View::addNamespace('others.faq', __DIR__ . '/resources/views');
    }

    /**
     * Load the extension configuration
     */
    private function loadConfig()
    {
        $configPath = __DIR__ . '/config/paymenter.php';
        if (file_exists($configPath)) {
            $config = require $configPath;
            foreach ($config as $key => $value) {
                Config::set("paymenter.{$key}", $value);
            }
        }
    }
}

