<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Create New Form') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">Build a new survey form</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card-gradient-alt card-shadow rounded-2xl p-8">
                <form action="{{ route('forms.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="title" class="block text-gray-700 text-sm font-medium mb-2">
                            {{ __('Form Title') }}
                        </label>
                        <input id="title" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200" 
                               type="text" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required 
                               autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 text-sm font-medium mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end space-x-4 mt-8">
                        <a href="{{ route('forms.index') }}" class="btn-secondary px-6 py-2 rounded-xl font-medium">
                            {{ __('Cancel') }}
                        </a>
                        
                        <button type="submit" class="btn-primary px-6 py-2 rounded-xl font-semibold">
                            {{ __('Create Form') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>