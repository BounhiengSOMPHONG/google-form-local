<x-app-layout>

    <div class="py-4 sm:py-6">
        <div class="w-full max-w-[98%] mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
            <!-- Hero Section -->
            <div class="mb-12 p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-brand-yellow to-yellow-200 shadow-lg">
                <div class="max-w-none">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-fade-in-up">Welcome back, {{ Auth::user()->first_name ?? 'User' }}!</h1>
                    <p class="text-gray-800 text-lg mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">Create, manage, and analyze your surveys with ease. Start building your next form today.</p>
                    <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                        <a href="{{ route('forms.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-semibold shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Form
                        </a>
                        <a href="{{ route('forms.index') }}" class="btn-secondary inline-flex items-center px-6 py-3 rounded-xl font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All Forms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <div class="bg-white overflow-hidden card-gradient-alt card-shadow rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-card-yellow-50 shadow-sm">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $forms->count() }}</h3>
                            <p class="text-gray-600">Forms</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden card-gradient-alt card-shadow rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-card-yellow-100 shadow-sm">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $forms->sum(function($form) { return $form->responses->count(); }) }}
                            </h3>
                            <p class="text-gray-600">Total Responses</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden card-gradient-alt card-shadow rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-gray-100 shadow-sm">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $forms->sum(function($form) { return $form->questions->count(); }) }}</h3>
                            <p class="text-gray-600">Total Questions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms Section -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">{{ __('My Forms') }}</h3>
                    <a href="{{ route('forms.index') }}" class="text-sm text-brand hover:opacity-90 font-medium">{{ __('View All') }}</a>
                </div>
                
                @if($forms->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($forms as $form)
                            <div class="bg-white border border-gray-200 rounded-xl card-shadow card-hover transition-all duration-300 overflow-visible h-full flex flex-col">
                                <a href="{{ route('forms.edit', $form) }}" class="block hover:no-underline text-inherit flex-grow">
                                    <div class="p-5 sm:p-6">
                                        <h4 class="font-bold text-gray-900 text-lg mb-2 truncate" title="{{ $form->title }}">{{ $form->title }}</h4>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($form->description, 100) }}</p>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="inline-flex items-center text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $form->responses->count() }} {{ __('responses') }}
                                            </span>
                                            <span class="inline-flex items-center text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $form->questions->count() }} {{ __('questions') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 13a1 1 0 100-2 1 1 0 000 2zm0-5a1 1 0 100-2 1 1 0 000 2zm0 10a1 1 0 100-2 1 1 0 000 2z"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-cloak
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 border border-gray-200"
                                             style="display: none;">
                                            <a href="{{ route('forms.results', $form) }}"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg mx-1 my-1">{{ __('View Results') }}</a>
                                            <form method="POST" action="{{ route('forms.destroy', $form) }}" onsubmit="return confirm('Delete this form?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-lg mx-1 my-1">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border border-gray-200 rounded-xl card-shadow p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6 animate-float">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No forms yet') }}</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ __('Get started by creating your first form. It only takes a few minutes to set up your survey and start collecting responses.') }}</p>
                        <a href="{{ route('forms.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-semibold shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Create Your First Form') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
