<x-public-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $form->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8 max-w-3xl">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800">{{ $form->title }}</h1>
            @if($form->description)
                <p class="text-gray-600 mt-2">{{ $form->description }}</p>
            @endif
        </div>

        <form action="{{ route('forms.submit', $form) }}" method="POST">
            @csrf
            @if(isset($responseId) && $responseId)
                <input type="hidden" name="response_id" value="{{ $responseId }}">
            @endif

            @foreach($questions as $question)
                <div class="mb-6 p-4 border border-gray-200 rounded">
                    <div class="flex justify-between items-start mb-2">
                        <label class="block text-gray-700 text-lg font-bold mb-2" for="question_{{ $question->id }}">
                            {{ $question->question_text }}
                            @if($question->required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                    </div>

                    @error('question_' . $question->id)
                        <p class="text-red-500 text-xs italic mb-2">{{ $message }}</p>
                    @enderror

                    @if($question->type === 'short_text')
                        <input type="text" 
                               name="question_{{ $question->id }}" 
                               id="question_{{ $question->id }}"
                               value="{{ old('question_' . $question->id, $prefill['question_' . $question->id] ?? '') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('question_' . $question->id) border-red-500 @enderror"
                               @if($question->required) required @endif>
                    @elseif($question->type === 'radio')
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <div class="flex items-center">
                                    <input type="radio" 
                                           name="question_{{ $question->id }}" 
                                           id="question_{{ $question->id }}_{{ $loop->index }}"
                                           value="{{ $option }}"
                                           {{ (string)old('question_' . $question->id, $prefill['question_' . $question->id] ?? '') === (string)$option ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                           @if($question->required) required @endif>
                                    <label for="question_{{ $question->id }}_{{ $loop->index }}" class="ml-2 block text-gray-700">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @elseif($question->type === 'checkbox')
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="question_{{ $question->id }}[]" 
                                           id="question_{{ $question->id }}_{{ $loop->index }}"
                                           value="{{ $option }}"
                                           {{ in_array($option, (array) old('question_' . $question->id, $prefill['question_' . $question->id] ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <label for="question_{{ $question->id }}_{{ $loop->index }}" class="ml-2 block text-gray-700">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @elseif($question->type === 'dropdown')
                        <select name="question_{{ $question->id }}" 
                                id="question_{{ $question->id }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('question_' . $question->id) border-red-500 @enderror"
                                @if($question->required) required @endif>
                            <option value="">Select an option</option>
                            @foreach($question->options as $option)
                                <option value="{{ $option }}" {{ (string)old('question_' . $question->id, $prefill['question_' . $question->id] ?? '') === (string)$option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif($question->type === 'date')
                        <input type="date" 
                               name="question_{{ $question->id }}" 
                               id="question_{{ $question->id }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('question_' . $question->id) border-red-500 @enderror"
                               @if($question->required) required @endif>
                    @endif
                </div>
            @endforeach

            <div class="flex items-center justify-between mt-8">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit Form
                </button>
            </div>
        </form>
    </div>
        </div>
    </div>
</x-app-layout>