<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Forms') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">Manage all your forms in one place</p>
            </div>
            <a href="{{ route('forms.create') }}" class="btn-primary inline-flex items-center px-4 py-2 rounded-xl font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Form
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-gradient-alt card-shadow rounded-2xl p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($forms->isEmpty())
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No forms yet</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">Get started by creating your first form. It only takes a few minutes to set up your survey and start collecting responses.</p>
                        <a href="{{ route('forms.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-semibold shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Your First Form
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($forms as $form)
                            <div class="bg-white border border-gray-200 rounded-xl card-shadow card-hover transition-all duration-300 overflow-hidden flex flex-col h-full">
                                <a href="{{ route('forms.edit', $form->id) }}" class="block p-5 flex-grow">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $form->title }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $form->responses->count() }} res
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($form->description, 100) }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $form->created_at->format('M d, Y') }}</span>
                                    </div>
                                </a>
                                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $form->responses->count() }} responses</span>
                                    </div>
                                    <a href="{{ route('forms.results', $form->id) }}" class="text-sm text-brand font-medium hover:underline">
                                        {{ __('View Results') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>