<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Surveys') }}
            </h2>
            <a href="{{ route('surveys.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Create New Survey') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Surveys') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($surveys as $survey)
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $survey->title }}</h4>
                                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($survey->description, 100) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            {{ __('Created') }} {{ $survey->created_at->diffForHumans() }}
                                        </span>
                                        <span class="px-2 py-1 text-xs {{ $survey->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full">
                                            {{ $survey->is_published ? __('Published') : __('Draft') }}
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
                                            <a href="{{ route('surveys.edit', $survey) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Edit') }}</a>
                                            <a href="{{ route('surveys.show', $survey) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('View Details') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No surveys') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('Get started by creating a new survey.') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
