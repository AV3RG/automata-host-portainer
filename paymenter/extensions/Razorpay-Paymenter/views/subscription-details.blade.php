@extends('layouts.app')

@section('title', 'Subscription Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Subscription Details</h1>
                    <p class="text-gray-600">Manage your subscription settings and billing</p>
                </div>
                <a href="{{ route('extensions.gateways.razorpay.subscriptions') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← Back to Subscriptions
                </a>
            </div>
        </div>

        <!-- Subscription Information -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Subscription Information</h2>
            </div>
            
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Subscription ID</h3>
                        <p class="text-lg text-gray-900 font-mono">{{ $subscriptionId }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Status</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Created Date</h3>
                        <p class="text-lg text-gray-900">{{ now()->format('M d, Y') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Next Billing Date</h3>
                        <p class="text-lg text-gray-900">{{ now()->addMonth()->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Details -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Plan Details</h2>
            </div>
            
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Plan Name</h3>
                        <p class="text-lg text-gray-900">Premium Hosting Plan</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Billing Cycle</h3>
                        <p class="text-lg text-gray-900">Monthly</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Amount</h3>
                        <p class="text-lg text-gray-900">₹999.00</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Currency</h3>
                        <p class="text-lg text-gray-900">INR</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Payment Method</h2>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-xs text-gray-600">Card</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">•••• •••• •••• 1234</p>
                        <p class="text-sm text-gray-500">Expires 12/25</p>
                    </div>
                    <button class="ml-auto text-sm text-indigo-600 hover:text-indigo-500">
                        Update
                    </button>
                </div>
            </div>
        </div>

        <!-- Billing History -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Billing History</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Premium Hosting Plan</p>
                            <p class="text-sm text-gray-500">December 2024</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">₹999.00</p>
                            <p class="text-sm text-gray-500">Paid</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Premium Hosting Plan</p>
                            <p class="text-sm text-gray-500">November 2024</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">₹999.00</p>
                            <p class="text-sm text-gray-500">Paid</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Premium Hosting Plan</p>
                            <p class="text-sm text-gray-500">October 2024</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">₹999.00</p>
                            <p class="text-sm text-gray-500">Paid</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">
                    View all billing history →
                </a>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Subscription Actions</h2>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <button onclick="pauseSubscription()" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-yellow-300 shadow-sm text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pause Subscription
                    </button>
                    
                    <button onclick="updatePaymentMethod()" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Update Payment Method
                    </button>
                    
                    <button onclick="cancelSubscription()" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Subscription
                    </button>
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>If you cancel your subscription, you will lose access to your services at the end of your current billing period. You can reactivate your subscription at any time.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function pauseSubscription() {
    if (confirm('Are you sure you want to pause this subscription?')) {
        // Implement pause logic
        console.log('Pausing subscription');
    }
}

function updatePaymentMethod() {
    // Implement payment method update logic
    console.log('Updating payment method');
}

function cancelSubscription() {
    if (confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')) {
        // Implement cancellation logic
        console.log('Cancelling subscription');
    }
}
</script>
@endsection
