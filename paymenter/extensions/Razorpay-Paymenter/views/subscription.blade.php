@assets
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endassets

@script
<script>
    const options = {
        key: "{{ $keyId }}",
        subscription_id: "{{ $subscriptionId }}",
        name: "{{ config('app.name', 'Paymenter') }}",
        description: "{{ $subscriptionDetails['description'] ?? 'Subscription' }}",
        currency: "INR",
        callback_url: "{{ route('extensions.gateways.razorpay.callback', ['invoiceId' => $invoiceId]) }}",
        modal: {
            ondismiss: function() {
                window.location.href = "{{ route('extensions.gateways.razorpay.cancel', ['invoiceId' => $invoiceId]) }}";
            }
        },
        prefill: {
            email: "{{ auth()->user()->email ?? '' }}",
            contact: "{{ auth()->user()->phone ?? '' }}",
        },
        notes: {
            invoice_id: "{{ $invoiceId }}",
            subscription_id: "{{ $subscriptionId }}",
            type: "subscription"
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
</script>
@endscript

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Setting up your subscription
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Redirecting to Razorpay to complete your subscription setup...
            </p>
        </div>
        
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="space-y-6">
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        If you are not redirected automatically, please wait or refresh the page.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Subscription Details</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Plan:</span>
                            <span class="font-medium">{{ $subscriptionDetails['description'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Amount:</span>
                            <span class="font-medium">₹{{ number_format($subscriptionDetails['amount'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Billing Cycle:</span>
                            <span class="font-medium">
                                Every {{ $subscriptionDetails['billing_period'] ?? 1 }} 
                                {{ Str::plural($subscriptionDetails['billing_unit'] ?? 'month', $subscriptionDetails['billing_period'] ?? 1) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <button onclick="rzp.open()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Complete Subscription Setup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
