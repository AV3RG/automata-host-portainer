<?php

/**
 * Test script for Razorpay period mapping
 * Run this to verify the billing unit to period mapping works correctly
 */

// Simulate the mapping function
function mapBillingUnitToRazorpayPeriod($billingUnit, $billingPeriod) {
    $periodMap = [
        'day' => 'daily',
        'days' => 'daily',
        'week' => 'weekly', 
        'weeks' => 'weekly',
        'month' => 'monthly',
        'months' => 'monthly',
        'year' => 'yearly',
        'years' => 'yearly'
    ];

    $normalizedUnit = strtolower($billingUnit);
    $razorpayPeriod = $periodMap[$normalizedUnit] ?? 'monthly';

    return [
        'original_unit' => $billingUnit,
        'normalized_unit' => $normalizedUnit,
        'razorpay_period' => $razorpayPeriod,
        'billing_period' => $billingPeriod
    ];
}

// Simulate the total count calculation function
function calculateTotalCount($billingUnit, $billingPeriod) {
    $normalizedUnit = strtolower($billingUnit);
    
    switch ($normalizedUnit) {
        case 'month':
        case 'months':
            return 60; // 5 years × 12 months = 60 charges
        case 'year':
        case 'years':
            return 5; // 5 years × 1 year = 5 charges
        case 'week':
        case 'weeks':
            return 260; // 5 years × 52 weeks = 260 charges
        case 'day':
        case 'days':
            return 1825; // 5 years × 365 days = 1825 charges
        default:
            return 60; // Default to monthly equivalent
    }
}

// Test cases
$testCases = [
    ['billing_unit' => 'month', 'billing_period' => 1],
    ['billing_unit' => 'months', 'billing_period' => 3],
    ['billing_unit' => 'year', 'billing_period' => 1],
    ['billing_unit' => 'years', 'billing_period' => 2],
    ['billing_unit' => 'week', 'billing_period' => 1],
    ['billing_unit' => 'weeks', 'billing_period' => 4],
    ['billing_unit' => 'day', 'billing_period' => 1],
    ['billing_unit' => 'days', 'billing_period' => 7],
    ['billing_unit' => 'MONTH', 'billing_period' => 1], // Test case sensitivity
    ['billing_unit' => 'unknown', 'billing_period' => 1], // Test fallback
];

echo "Razorpay Period Mapping Test\n";
echo "============================\n\n";

foreach ($testCases as $testCase) {
    $periodResult = mapBillingUnitToRazorpayPeriod($testCase['billing_unit'], $testCase['billing_period']);
    $totalCount = calculateTotalCount($testCase['billing_unit'], $testCase['billing_period']);
    
    echo "Input: billing_unit='{$testCase['billing_unit']}', billing_period={$testCase['billing_period']}\n";
    echo "Output: period='{$periodResult['razorpay_period']}', total_count={$totalCount}\n";
    echo "Details: " . json_encode([
        'period_mapping' => $periodResult,
        'total_count_calculation' => [
            'billing_unit' => $testCase['billing_unit'],
            'total_count' => $totalCount,
            'subscription_duration' => '5 years'
        ]
    ], JSON_PRETTY_PRINT) . "\n";
    echo "---\n";
}

echo "\nExpected Razorpay API payload format:\n";
echo "period: daily|weekly|monthly|yearly\n";
echo "interval: positive integer\n";
echo "total_count: must be at least 1 (Razorpay requirement)\n\n";

echo "Note: Razorpay API constraints:\n";
echo "- period: must be one of: daily, weekly, monthly, yearly\n";
echo "- interval: must be positive integer\n";
echo "- total_count: must be >= 1 (0 is not accepted)\n";
echo "- amount: must be in paise (smallest currency unit)\n\n";

echo "Test completed!\n";
