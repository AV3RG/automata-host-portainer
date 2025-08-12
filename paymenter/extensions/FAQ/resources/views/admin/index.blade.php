@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">FAQ Management</h1>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">FAQ Questions</h2>
            <p class="text-blue-700 mb-4">Add and manage FAQ questions for existing product categories. Questions will be organized by the product categories you already have in your system.</p>
            <a href="{{ route('filament.admin.resources.faq-questions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Manage FAQ Questions
            </a>
        </div>
        
        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-green-900 mb-4">How It Works</h2>
            <ul class="text-green-700 space-y-2">
                <li>• Select from your existing product categories when creating FAQ questions</li>
                <li>• Questions are automatically organized by product category</li>
                <li>• No need to create new categories - use what you already have</li>
                <li>• Mark important questions as featured for better visibility</li>
            </ul>
        </div>
    </div>
</div>
@endsection

