<x-public-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8 max-w-3xl">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card-gradient-alt card-shadow rounded-2xl p-8 mb-4">
                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Progress</span>
                            <span class="text-sm font-medium text-gray-700" id="progress-text">0%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" id="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <div class="inline-block p-3 rounded-full bg-yellow-100 mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $form->title }}</h1>
                        @if($form->description)
                            <p class="text-gray-600 text-lg">{{ $form->description }}</p>
                        @endif
                    </div>

                    @if(! $form->accepting_responses)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            This form is currently not accepting responses.
                        </div>
                    @endif

                    <form action="{{ route('forms.submit', $form) }}" method="POST">
                        @csrf
                        @if(isset($responseId) && $responseId)
                            <input type="hidden" name="response_id" value="{{ $responseId }}">
                        @endif

                                                @foreach($questions as $question)
                            @if($question->type === 'section')
                                <div class="mb-8 pt-6">
                                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">{{ $question->question_text }}</h2>
                                    @if($question->description)
                                        <p class="text-gray-600 mt-3">{{ $question->description }}</p>
                                    @endif
                                </div>
                            @else
                                <div class="mb-8 p-6 bg-white rounded-xl card-shadow transition-all duration-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <label class="block text-gray-800 text-lg font-semibold" for="question_{{ $question->id }}">
                                            {{ $question->question_text }}
                                            @if($question->required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                        </span>
                                    </div>

                                    @error('question_' . $question->id)
                                        <p class="text-red-500 text-sm italic mb-4">{{ $message }}</p>
                                    @enderror

                                    @if($question->type === 'short_text')
                                        <input type="text" 
                                               name="question_{{ $question->id }}" 
                                               id="question_{{ $question->id }}"
                                               value="{{ old('question_' . $question->id, $prefill['question_' . $question->id] ?? '') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 transition duration-200"
                                               @if($question->required) required @endif>
                                    @elseif($question->type === 'radio')
                                        <div class="space-y-3">
                                            @foreach($question->options as $option)
                                                <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                                                    <input type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           id="question_{{ $question->id }}_{{ $loop->index }}"
                                                           value="{{ $option }}"
                                                           {{ (string)old('question_' . $question->id, $prefill['question_' . $question->id] ?? '') === (string)$option ? 'checked' : '' }}
                                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500"
                                                           @if($question->required) required @endif>
                                                    <label for="question_{{ $question->id }}_{{ $loop->index }}" class="ml-3 block text-gray-700">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'checkbox')
                                        <div class="space-y-3">
                                            @foreach($question->options as $option)
                                                <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                                                    <input type="checkbox" 
                                                           name="question_{{ $question->id }}[]" 
                                                           id="question_{{ $question->id }}_{{ $loop->index }}"
                                                           value="{{ $option }}"
                                                           {{ in_array($option, (array) old('question_' . $question->id, $prefill['question_' . $question->id] ?? [])) ? 'checked' : '' }}
                                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 rounded">
                                                    <label for="question_{{ $question->id }}_{{ $loop->index }}" class="ml-3 block text-gray-700">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'dropdown')
                                        <select name="question_{{ $question->id }}" 
                                                id="question_{{ $question->id }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 transition duration-200"
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
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 transition duration-200"
                                               @if($question->required) required @endif>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                        <div class="flex items-center justify-center mt-10">
                            <button type="submit" class="btn-primary px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transform transition duration-300 hover:scale-105" {{ ! $form->accepting_responses ? 'disabled' : '' }}>
                                <span class="flex items-center">
                                    Submit Form
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update progress bar based on form completion
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            
            // Calculate total number of required questions
            const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            const totalRequired = requiredInputs.length;
            
            // Calculate how many required questions have been answered
            function updateProgress() {
                let answered = 0;
                
                requiredInputs.forEach(input => {
                    if (input.type === 'radio' || input.type === 'checkbox') {
                        const name = input.name;
                        const checked = form.querySelectorAll(`input[name="${name}"]:checked`);
                        if (checked.length > 0) answered++;
                    } else if (input.type === 'checkbox') {
                        // For checkbox arrays
                        const name = input.name.replace('[]', '');
                        const checked = form.querySelectorAll(`input[name="${name}[]"]:checked`);
                        if (checked.length > 0) answered++;
                    } else {
                        // For text inputs, selects, etc.
                        if (input.value.trim() !== '') answered++;
                    }
                });
                
                const percentage = totalRequired > 0 ? Math.round((answered / totalRequired) * 100) : 0;
                progressBar.style.width = percentage + '%';
                progressText.textContent = percentage + '%';
            }
            
            // Listen for changes on form elements
            form.addEventListener('input', updateProgress);
            form.addEventListener('change', updateProgress);
            
            // Initial update
            updateProgress();
        });
    </script>
</x-public-layout>