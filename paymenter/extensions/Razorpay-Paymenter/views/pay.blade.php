@assets
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endassets

@script
<script>
    const checkout = {
        key: "{{ $keyId }}",
        amount: "{{ $orderAmount }}",
        currency: "INR",
        name: "{{ config('app.name', 'Paymenter') }}",
        order_id: "{{ $id }}",
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
            type: "{{ $isSubscription ? 'subscription' : 'one_time' }}"
        }
    };

    const razorpay = new Razorpay(checkout);
    razorpay.open();
</script>
@endscript

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ $isSubscription ? 'Setting up your subscription' : 'Complete your payment' }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ $isSubscription ? 'Redirecting to Razorpay to complete your subscription setup...' : 'Redirecting to Razorpay to complete your payment...' }}
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Details</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Amount:</span>
                            <span class="font-medium">₹{{ number_format($orderAmount / 100, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Currency:</span>
                            <span class="font-medium">INR</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Type:</span>
                            <span class="font-medium">{{ $isSubscription ? 'Subscription' : 'One-time Payment' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <button onclick="razorpay.open()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $isSubscription ? 'Complete Subscription Setup' : 'Complete Payment' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>