<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Forms') }}
            </h2>
            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-brand uppercase tracking-widest hover:opacity-90 focus:opacity-90 active:opacity-95 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create New Form') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($forms->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">You don't have any forms yet.</p>
                            <a href="{{ route('forms.create') }}" class="inline-block mt-4 bg-brand hover:opacity-90 text-brand font-bold py-2 px-4 rounded">
                                Create Your First Form
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($forms as $form)
                                <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col">
                                    <a href="{{ route('forms.edit', $form->id) }}" class="block p-4 flex-grow">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $form->title }}</h4>
                                        <p class="text-gray-600 text-sm">{{ Str::limit($form->description, 100) }}</p>
                                    </a>
                                    <div class="px-4 pb-4 flex items-center justify-between border-t border-gray-100 pt-4">
                                        <span class="text-sm text-gray-500">
                                            {{ $form->responses->count() }} responses
                                        </span>
                                        <a href="{{ route('forms.results', $form->id) }}" class="text-sm text-brand hover:opacity-90">
                                            {{ __('Results') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>