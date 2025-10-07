<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">Manage your forms</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Create Form') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $forms->count() }}</h3>
                            <p class="text-sm text-gray-500">Forms</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $forms->sum(function($form) { return $form->responses->count(); }) }}
                            </h3>
                            <p class="text-sm text-gray-500">Total Responses</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gray-100">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $forms->sum(function($form) { return $form->questions->count(); }) }}</h3>
                            <p class="text-sm text-gray-500">Total Questions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms Section -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('My Forms') }}</h3>
                    <a href="{{ route('forms.index') }}" class="text-sm text-blue-600 hover:text-blue-800">{{ __('View All') }}</a>
                </div>
                
                @if($forms->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($forms as $form)
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $form->title }}</h4>
                                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($form->description, 100) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            {{ $form->responses->count() }} {{ __('responses') }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $form->questions->count() }} {{ __('questions') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 border-t flex justify-end">
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 13a1 1 0 100-2 1 1 0 000 2zm0-5a1 1 0 100-2 1 1 0 000 2zm0 10a1 1 0 100-2 1 1 0 000 2z"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                            <a href="{{ route('forms.edit', $form) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Edit Form') }}</a>
                                            <a href="{{ route('forms.public', $form) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">{{ __('View Form') }}</a>
                                            <a href="{{ route('forms.results', $form) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('View Results') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No forms') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Get started by creating a new form.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Form') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
