<?php

/**
 * Facebook SDK Debug Script
 * Run this script to diagnose Facebook SDK installation issues
 */

echo "=== Facebook SDK Debug Information ===\n\n";

// Check if we're in a Laravel context
if (class_exists('Illuminate\Support\Facades\App')) {
    echo "✓ Laravel framework detected\n";
} else {
    echo "✗ Laravel framework not detected\n";
}

// Try to include the CapiHelper class
$capiHelperPath = __DIR__ . '/CapiHelper.php';
if (file_exists($capiHelperPath)) {
    require_once $capiHelperPath;
    
    if (class_exists('Paymenter\Extensions\Others\FacebookPixel\CapiHelper')) {
        echo "✓ CapiHelper class loaded successfully\n";
        
        // Use the comprehensive diagnostic method
        $diagnostics = Paymenter\Extensions\Others\FacebookPixel\CapiHelper::getDiagnostics();
        
        echo "\n=== SDK Availability ===\n";
        echo ($diagnostics['sdk_available'] ? "✓" : "✗") . " Facebook SDK is " . ($diagnostics['sdk_available'] ? "available" : "NOT available") . "\n";
        
        echo "\n=== Autoloader Status ===\n";
        foreach ($diagnostics['autoloader_paths'] as $path => $info) {
            $status = $info['exists'] ? "✓" : "✗";
            echo "{$status} {$path}\n";
            if ($info['exists']) {
                echo "  - Readable: " . ($info['readable'] ? "Yes" : "No") . "\n";
                echo "  - Size: " . number_format($info['size']) . " bytes\n";
            }
        }
        
        echo "\n=== Facebook SDK Installation ===\n";
        foreach ($diagnostics['facebook_sdk_paths'] as $path => $info) {
            $status = $info['exists'] ? "✓" : "✗";
            echo "{$status} {$path}\n";
            if ($info['exists']) {
                echo "  - Has src: " . ($info['has_src'] ? "Yes" : "No") . "\n";
                echo "  - Has composer.json: " . ($info['has_composer_json'] ? "Yes" : "No") . "\n";
            }
        }
        
        echo "\n=== Class Availability ===\n";
        foreach ($diagnostics['class_availability'] as $class => $available) {
            $status = $available ? "✓" : "✗";
            echo "{$status} {$class}\n";
        }
        
        if (!empty($diagnostics['suggestions'])) {
            echo "\n=== Suggestions ===\n";
            foreach ($diagnostics['suggestions'] as $suggestion) {
                echo "• {$suggestion}\n";
            }
        }
        
    } else {
        echo "✗ Failed to load CapiHelper class\n";
    }
} else {
    echo "✗ CapiHelper.php not found\n";
}

echo "\n=== End Debug Information ===\n";
