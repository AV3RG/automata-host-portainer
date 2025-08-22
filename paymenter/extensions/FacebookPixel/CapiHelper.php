<?php

namespace Paymenter\Extensions\Others\FacebookPixel;

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\Gender;
use FacebookAds\Object\ServerSide\UserData;
use League\ISO3166\ISO3166;

class CapiHelper {

    public static function init($access_token) {
        // Check if Facebook SDK classes are available
        if (!class_exists('FacebookAds\Api')) {
            throw new \Exception('Facebook PHP SDK is not installed. Please ensure facebook/php-business-sdk is properly installed via composer.');
        }
        
        try {
            Api::init(null, null, $access_token);
            $api = Api::instance();
            $api->setLogger(new CurlLogger());
            return $api;
        } catch (\Exception $e) {
            throw new \Exception('Failed to initialize Facebook API: ' . $e->getMessage());
        }
    }

    private static function hashValue($value) {
        if (empty($value)) {
            return null;
        }
        return hash('sha256', strtolower(trim($value)));
    }

    private static function getPropertyValue($user, $key) {
        return $user->properties()->where('key', $key)->first()?->value;
    }

    private static function countryCode($country) {
        if (empty($country)) {
            return null;
        }
        try {
            $iso = new ISO3166();
            $data = $iso->alpha2($country);
            return $data['alpha2'];
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function buildUserData($user) {
        // Check if required classes exist
        if (!class_exists('FacebookAds\Object\ServerSide\UserData')) {
            throw new \Exception('Facebook SDK UserData class not found. Please ensure facebook/php-business-sdk is properly installed.');
        }
        
        try {
            $user_data = (new UserData());
            $user_data->setEmails(array($user->email));
            $user_data->setPhones(array(self::getPropertyValue($user, 'phone')));
            $user_data->setLastNames(array($user->last_name));
            $user_data->setFirstNames(array($user->first_name));
            $user_data->setCities(array(self::getPropertyValue($user, 'city')));
            $user_data->setStates(array(self::getPropertyValue($user, 'state')));
            $user_data->setZipCodes(array(self::getPropertyValue($user, 'zip')));
            $user_data->setCountryCodes(array(self::countryCode(self::getPropertyValue($user, 'country'))));
        
            return $user_data;
        } catch (\Exception $e) {
            throw new \Exception('Failed to build user data: ' . $e->getMessage());
        }
    }

    public static function buildPurchaseDataForOrder($order) {
        $customData = (new CustomData());
        $customData->setValue($order->total);
        $customData->setCurrency($order->currency->code);
        $customData->setContentType("product");
        $planIds = $order->services->pluck('plan_id')->toArray();
        $planIds = array_map(function($id) {
            return (string)$id;
        }, $planIds);   
        $customData->setContentIds($planIds);
        return $customData;
    }

    public static function buildPurchaseDataForInvoice($invoice) {
        $customData = (new CustomData());
        $customData->setValue($invoice->total);
        $customData->setCurrency($invoice->currency->code);
        $customData->setContentType("product");
        $planIds = array();
        $invoice->items->each(function($item) use (&$planIds) {
            array_push($planIds, (string) $item->reference->plan_id);
        });
        $customData->setContentIds($planIds);
        return $customData;
    }

    public static function buildWebsiteEvent($eventName, $userData, $customData) {
        // Check if required classes exist
        if (!class_exists('FacebookAds\Object\ServerSide\Event')) {
            throw new \Exception('Facebook SDK Event class not found. Please ensure facebook/php-business-sdk is properly installed.');
        }
        
        try {
            $event = (new Event())
                ->setEventName($eventName)
                ->setEventTime(time())
                ->setUserData($userData)
                ->setActionSource("website");

            if ($customData) {
                $event->setCustomData($customData);
            }

            return $event;
        } catch (\Exception $e) {
            throw new \Exception('Failed to build website event: ' . $e->getMessage());
        }
    }

    public static function sendEvent($event, $pixel_id) {
        // Check if required classes exist
        if (!class_exists('FacebookAds\Object\ServerSide\EventRequest')) {
            throw new \Exception('Facebook SDK EventRequest class not found. Please ensure facebook/php-business-sdk is properly installed.');
        }
        
        try {
            $events = [$event];

            $request = (new EventRequest($pixel_id))
                ->setEvents($events);

            $response = $request->execute();
        } catch (\Exception $e) {
            \Log::error('Failed request data: '. json_encode($request->normalize()));
            throw new \Exception('Failed to send event: ' . $e->getMessage());
        }
    }   

    /**
     * Check if Facebook SDK is properly installed and accessible
     */
    public static function isSdkAvailable() {
        return class_exists('FacebookAds\Api') && 
               class_exists('FacebookAds\Object\ServerSide\UserData') &&
               class_exists('FacebookAds\Object\ServerSide\Event') &&
               class_exists('FacebookAds\Object\ServerSide\EventRequest');
    }

    /**
     * Try to manually load Facebook SDK autoloader
     */
    private static function tryLoadSdkAutoloader() {
        $possiblePaths = [
            __DIR__ . '/../../../vendor/autoload.php',
            __DIR__ . '/../../vendor/autoload.php',
            __DIR__ . '/../vendor/autoload.php',
            __DIR__ . '/vendor/autoload.php'
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                try {
                    require_once $path;
                    return true;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        return false;
    }

    /**
     * Initialize with fallback autoloader loading
     */
    public static function initWithFallback($access_token) {
        // First try normal initialization
        if (self::isSdkAvailable()) {
            return self::init($access_token);
        }

        // If not available, try to load autoloader
        if (self::tryLoadSdkAutoloader()) {
            if (self::isSdkAvailable()) {
                return self::init($access_token);
            }
        }

        throw new \Exception('Facebook SDK is not available even after trying to load autoloader. Please check installation.');
    }

    /**
     * Get comprehensive diagnostic information
     */
    public static function getDiagnostics() {
        $diagnostics = [
            'sdk_available' => self::isSdkAvailable(),
            'autoloader_paths' => [],
            'facebook_sdk_paths' => [],
            'class_availability' => [],
            'suggestions' => []
        ];

        // Check autoloader paths
        $autoloaderPaths = [
            __DIR__ . '/../../../vendor/autoload.php',
            __DIR__ . '/../../vendor/autoload.php',
            __DIR__ . '/../vendor/autoload.php',
            __DIR__ . '/vendor/autoload.php'
        ];

        foreach ($autoloaderPaths as $path) {
            $diagnostics['autoloader_paths'][$path] = [
                'exists' => file_exists($path),
                'readable' => is_readable($path),
                'size' => file_exists($path) ? filesize($path) : 0
            ];
        }

        // Check Facebook SDK paths
        $facebookPaths = [
            __DIR__ . '/../../../vendor/facebook/php-business-sdk',
            __DIR__ . '/../../vendor/facebook/php-business-sdk',
            __DIR__ . '/../vendor/facebook/php-business-sdk',
            __DIR__ . '/vendor/facebook/php-business-sdk'
        ];

        foreach ($facebookPaths as $path) {
            $diagnostics['facebook_sdk_paths'][$path] = [
                'exists' => is_dir($path),
                'has_src' => is_dir($path . '/src'),
                'has_composer_json' => file_exists($path . '/composer.json')
            ];
        }

        // Check individual class availability
        $classes = [
            'FacebookAds\Api',
            'FacebookAds\Object\ServerSide\UserData',
            'FacebookAds\Object\ServerSide\Event',
            'FacebookAds\Object\ServerSide\EventRequest'
        ];

        foreach ($classes as $class) {
            $diagnostics['class_availability'][$class] = class_exists($class);
        }

        // Generate suggestions
        if (!$diagnostics['sdk_available']) {
            $diagnostics['suggestions'][] = 'Facebook SDK is not available. Check if it\'s properly installed via composer.';
            
            if (empty(array_filter($diagnostics['facebook_sdk_paths'], function($path) { return $path['exists']; }))) {
                $diagnostics['suggestions'][] = 'Facebook SDK vendor directory not found. Run: composer require facebook/php-business-sdk:^18.0';
            }
            
            if (empty(array_filter($diagnostics['autoloader_paths'], function($path) { return $path['exists']; }))) {
                $diagnostics['suggestions'][] = 'Composer autoloader not found. Run: composer install';
            }
        }

        return $diagnostics;
    }
}
