<?php

namespace Paymenter\Extensions\Gateways\Razorpay;

use App\Models\Service;


class RazorpayUtils {

    public static function fetchAllPlans($api): array
    {
        $base_response = $api->plan->all(array(
            'count' => 100, // Adjust count as needed
            'skip' => 0 // Start from the first plan
        ));

        $plans = [];
        if (isset($base_response->items) && is_array($base_response->items)) {
            foreach ($base_response->items as $item) {
                $plans[] = [
                    'id' => $item->id,
                    'name' => $item->item->name,
                    'amount' => $item->item->amount,
                    'currency' => $item->item->currency,
                    'interval' => $item->interval,
                    'period' => $item->period,
                    'description' => $item->item->description ?? null
                ];
            }
        }

        if (isset($base_response->count) && $base_response->count > 100) {
            // If there are more plans, fetch them in batches
            $skip = 100;
            while ($skip < $base_response->count) {
                $response = $api->plan->all(array(
                    'count' => 100,
                    'skip' => $skip
                ));

                if (isset($response->items) && is_array($response->items)) {
                    foreach ($response->items as $item) {
                        $plans[] = [
                            'id' => $item->id,
                            'name' => $item->item->name,
                            'amount' => $item->item->amount,
                            'currency' => $item->item->currency,
                            'interval' => $item->interval,
                            'period' => $item->period,
                            'description' => $item->item->description ?? null
                        ];
                    }
                }

                $skip += 100;
            }
        }

        return $plans;

    }

    public static function makeDescription($invoice) {
        $description = "";
        foreach ($invoice->items as $item) {
            $description .= $item->reference->product->name . ", ";
        }
        $description = rtrim($description, ", ");
        return $description;
    }

    public static function planDetailsGenerator($invoice, $total)
    {

        \Log::info('Invoice: ' . json_encode($invoice));

        return [
            'name' => "v2" . "-" . $invoice->items->first()->reference->plan->billing_unit . "-" . $invoice->items->first()->reference->plan->billing_period . "-" . $total,
            'amount' => $total * 100,
            'currency' => $invoice->currency->code,
            'description' => self::makeDescription($invoice),
            'notes' => [
                'services_ids' => $invoice->items->filter(function ($item) {
                    return $item->reference_type === Service::class;
                })->pluck('reference_id')->join(',') . '',
                'user_id' => $invoice->user->id . '',
            ]
        ];
    }

    public static function createPlan($api, $invoice, $total) {
        $planDetails = self::planDetailsGenerator($invoice, $total);
        \Log::info("Creating Razorpay plan with details: " . json_encode($planDetails));
        $plan = $api->plan->create(array(
            'period' => RazorpayUtils::convertBillingUnitToRazorpay($invoice->items->first()->reference->plan->billing_unit),
            'interval' => $invoice->items->first()->reference->plan->billing_period,
            'item' => array(
                'name' => $planDetails['name'],
                'amount' => $planDetails['amount'],
                'currency' => $planDetails['currency'],
                'description' => $planDetails['description'],
            ),
            'notes' => $planDetails['notes']
        ));
        return $plan;
    }

    public static function createCustomer($api, $invoice) {
        return $api->customer->create(array(
            'name' => $invoice->user->name,
            'email' => $invoice->user->email,
            'contact' => $invoice->user->properties()->where('key', 'phone')->first()->value ?? null,
            'notes' => [
                'user_id' => $invoice->user->id . '',
            ],
        ));
    }

    public static function totalCountCalculator($invoice) {
        $billingUnit = strtolower($invoice->billing_unit);

        switch ($billingUnit) {
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

    public static function createSubscription($api, $invoice, $planId, $customerId) {
        return $api->subscription->create(array(
            'plan_id' => $planId,
            'customer_id' => $customerId,
            'total_count' => self::totalCountCalculator($invoice),
            'notes' => [
                'services_ids' => $invoice->items->filter(function ($item) {
                    return $item->reference_type === Service::class;
                })->pluck('reference_id')->join(',') . '',
                'user_id' => $invoice->user->id . '',
            ],
        ));
    }

    public static function convertBillingUnitToRazorpay($billingUnit) {
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
        return $periodMap[$normalizedUnit] ?? 'monthly';
    }

    public static function convertBillingUnitToPaymenter($billingUnit) {
        $periodMap = [
            'day' => 'day',
            'days' => 'day',
            'daily' => 'day',
            'week' => 'week',
            'weeks' => 'week',
            'weekly' => 'week',
            'month' => 'month',
            'months' => 'month',
            'monthly' => 'month',
            'year' => 'year',
            'years' => 'year',
            'yearly' => 'year'
        ];

        $normalizedUnit = strtolower($billingUnit);
        return $periodMap[$normalizedUnit] ?? 'month';
    }

}