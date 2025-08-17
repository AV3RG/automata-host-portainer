<?php

/**
 * Facebook SDK Test Script
 * Simple test to verify Facebook SDK is working
 */

echo "=== Facebook SDK Test ===\n\n";

// Try to include autoloader
$autoloaderFound = false;
$autoloaderPaths = [
    __DIR__ . '/../../../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
];

foreach ($autoloaderPaths as $path) {
    if (file_exists($path)) {
        echo "Found autoloader at: {$path}\n";
        require_once $path;
        $autoloaderFound = true;
        break;
    }
}

if (!$autoloaderFound) {
    echo "ERROR: No autoloader found!\n";
    exit(1);
}

// Test Facebook SDK classes
echo "\nTesting Facebook SDK classes:\n";

$classes = [
    'FacebookAds\Api',
    'FacebookAds\Object\ServerSide\UserData',
    'FacebookAds\Object\ServerSide\Event',
    'FacebookAds\Object\ServerSide\EventRequest'
];

$allClassesAvailable = true;
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✓ {$class}\n";
    } else {
        echo "✗ {$class}\n";
        $allClassesAvailable = false;
    }
}

if (!$allClassesAvailable) {
    echo "\nERROR: Some Facebook SDK classes are missing!\n";
    exit(1);
}

// Test basic functionality
echo "\nTesting basic functionality:\n";

try {
    // Test UserData creation
    $userData = new FacebookAds\Object\ServerSide\UserData();
    echo "✓ UserData created successfully\n";
    
    // Test Event creation
    $event = new FacebookAds\Object\ServerSide\Event();
    echo "✓ Event created successfully\n";
    
    // Test EventRequest creation
    $eventRequest = new FacebookAds\Object\ServerSide\EventRequest('test_pixel_id');
    echo "✓ EventRequest created successfully\n";
    
    echo "\nSUCCESS: Facebook SDK is working correctly!\n";
    
} catch (Exception $e) {
    echo "\nERROR: Facebook SDK test failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== Test Complete ===\n";
