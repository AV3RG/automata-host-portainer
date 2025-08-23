<?php

/**
 * Resend SDK Debug Script
 * Run this script to diagnose Resend SDK installation issues
 */

echo "=== Resend SDK Debug Information ===\n\n";

// Check if we're in a Laravel context
if (class_exists('Illuminate\Support\Facades\App')) {
    echo "✅ Laravel framework detected\n";
} else {
    echo "✗ Laravel framework not detected\n";
}

// Check if Resend SDK classes exist
echo "\n=== Resend SDK Classes ===\n";

if (class_exists('Resend\Resend')) {
    echo "✅ Resend\\Resend class found\n";
} else {
    echo "❌ Resend\\Resend class not found\n";
}

if (class_exists('Resend\Exceptions\ResendException')) {
    echo "✅ Resend\\Exceptions\\ResendException class found\n";
} else {
    echo "❌ Resend\\Exceptions\\ResendException class not found\n";
}

// Check vendor directory
echo "\n=== Vendor Directory ===\n";
$vendorPath = __DIR__ . '/../../../vendor/resend/resend-php';
if (is_dir($vendorPath)) {
    echo "✅ Resend vendor directory found at: {$vendorPath}\n";
    
    $srcPath = $vendorPath . '/src';
    if (is_dir($srcPath)) {
        echo "✅ Resend src directory found\n";
        
        $resendFile = $srcPath . '/Resend.php';
        if (file_exists($resendFile)) {
            echo "✅ Resend.php file found\n";
            
            // Check file contents
            $content = file_get_contents($resendFile);
            if (strpos($content, 'class Resend') !== false) {
                echo "✅ Resend class definition found in file\n";
            } else {
                echo "❌ Resend class definition NOT found in file\n";
            }
        } else {
            echo "❌ Resend.php file not found\n";
        }
    } else {
        echo "❌ Resend src directory not found\n";
    }
} else {
    echo "❌ Resend vendor directory not found at: {$vendorPath}\n";
}

// Check composer.json
echo "\n=== Composer Information ===\n";
$composerPath = __DIR__ . '/../../../composer.json';
if (file_exists($composerPath)) {
    echo "✅ composer.json found\n";
    
    $composer = json_decode(file_get_contents($composerPath), true);
    if (isset($composer['require']['resend/resend-php'])) {
        echo "✅ Resend dependency found in composer.json: " . $composer['require']['resend/resend-php'] . "\n";
    } else {
        echo "❌ Resend dependency NOT found in composer.json\n";
    }
} else {
    echo "❌ composer.json not found\n";
}

// Check autoloader
echo "\n=== Autoloader ===\n";
$autoloadPath = __DIR__ . '/../../../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "✅ Composer autoloader found\n";
    
    // Try to include it
    try {
        require_once $autoloadPath;
        echo "✅ Autoloader included successfully\n";
        
        // Check if classes are available after autoloading
        if (class_exists('Resend\Resend')) {
            echo "✅ Resend\\Resend class available after autoloading\n";
        } else {
            echo "❌ Resend\\Resend class still not available after autoloading\n";
        }
    } catch (Exception $e) {
        echo "❌ Failed to include autoloader: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Composer autoloader not found\n";
}

echo "\n=== Recommendations ===\n";
echo "1. If Resend SDK is not found, run: composer require resend/resend-php:^0.8.0\n";
echo "2. If using Docker, rebuild the container after adding the dependency\n";
echo "3. Check that the vendor directory is properly mounted in Docker\n";
echo "4. Verify there are no class name conflicts in your codebase\n";

echo "\n=== Debug Complete ===\n";
