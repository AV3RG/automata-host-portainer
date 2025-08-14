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
        callback_url: "{{ route('extensions.gateways.razorpay.callback', ['invoiceNumber' => $invoiceNumber]) }}",
        modal: {
            ondismiss: function() {
                window.location.href = "{{ route('extensions.gateways.razorpay.cancel', ['invoiceNumber' => $invoiceNumber]) }}";
            }
        },
        notes: {
            invoiceId: "{{ $invoiceId }}",
            invoiceNumber: "{{ $invoiceNumber }}",
            subscriptionId: "{{ $subscriptionId }}",
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
                
                <div class="text-center">
                    <button onclick="rzp.open()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Complete Subscription Setup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
