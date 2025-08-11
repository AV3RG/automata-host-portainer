@extends('layouts.app')

@section('title', 'My Subscriptions')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Subscriptions</h1>
            <p class="text-gray-600">Manage your active subscriptions and billing</p>
        </div>

        <!-- Subscriptions List -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Active Subscriptions</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse(auth()->user()->services()->whereHas('plan', function($q) { $q->where('type', 'recurring'); })->get() as $service)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $service->product->name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $service->product->category->name }} - 
                                            Every {{ $service->plan->billing_period > 1 ? $service->plan->billing_period . ' ' : '' }}
                                            {{ Str::plural($service->plan->billing_unit, $service->plan->billing_period) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="text-lg font-semibold text-gray-900">{{ $service->formattedPrice }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($service->plan->type == 'recurring')
                                            per {{ $service->plan->billing_unit }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($service->status == 'active') bg-green-100 text-green-800
                                        @elseif($service->status == 'suspended') bg-yellow-100 text-yellow-800
                                        @elseif($service->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                @if($service->expires_at)
                                    <span>Next billing: {{ $service->expires_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('services.show', $service) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    View Details
                                </a>
                                
                                @if($service->status == 'active')
                                    <button onclick="pauseSubscription({{ $service->id }})" 
                                            class="inline-flex items-center px-3 py-2 border border-yellow-300 shadow-sm text-sm leading-4 font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        Pause
                                    </button>
                                    
                                    <button onclick="cancelSubscription({{ $service->id }})" 
                                            class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Cancel
                                    </button>
                                @elseif($service->status == 'suspended')
                                    <button onclick="resumeSubscription({{ $service->id }})" 
                                            class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Resume
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No subscriptions</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have any active subscriptions yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Browse Products
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Billing History -->
        @if(auth()->user()->invoices()->where('status', 'paid')->count() > 0)
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Billing History</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach(auth()->user()->invoices()->where('status', 'paid')->orderBy('created_at', 'desc')->take(5)->get() as $invoice)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($invoice->service)
                                        {{ $invoice->service->product->name }}
                                    @else
                                        Invoice #{{ $invoice->id }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">{{ $invoice->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $invoice->formattedTotal }}</p>
                                <p class="text-sm text-gray-500">{{ $invoice->currency_code }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="{{ route('invoices.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    View all invoices →
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function pauseSubscription(serviceId) {
    if (confirm('Are you sure you want to pause this subscription?')) {
        // Implement pause logic
        console.log('Pausing subscription for service:', serviceId);
    }
}

function cancelSubscription(serviceId) {
    if (confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')) {
        // Implement cancellation logic
        console.log('Cancelling subscription for service:', serviceId);
    }
}

function resumeSubscription(serviceId) {
    if (confirm('Are you sure you want to resume this subscription?')) {
        // Implement resume logic
        console.log('Resuming subscription for service:', serviceId);
    }
}
</script>
@endsection
