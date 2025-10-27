<x-public-layout>
    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-6 max-w-3xl">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card-gradient-alt card-shadow rounded-2xl p-6 mb-4">
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Progress</span>
                            <span class="text-sm font-medium text-gray-700" id="progress-text">0%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" id="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="mb-6 text-center">
                        <div class="inline-block p-2 rounded-full bg-yellow-100 mb-3">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-normal text-gray-900 mb-2">{{ $form->title }}</h1>
                        @if($form->description)
                            <p class="text-gray-600">{{ $form->description }}</p>
                        @endif
                    </div>

                    @if(! $form->accepting_responses)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            This form is currently not accepting responses.
                        </div>
                    @endif

                    <form action="{{ route('forms.submit', $form) }}" method="POST" id="multi-step-form">
                        @csrf
                        @if(isset($responseId) && $responseId)
                            <input type="hidden" name="response_id" value="{{ $responseId }}">
                        @endif

                        <!-- Questions organized by sections -->
                        <div id="sections-container">
                            @php
                                $sections = [];
                                $currentSection = null;
                                $sectionIndex = 0;
                                
                                foreach($questions as $question) {
                                    if($question->type === 'section') {
                                        if($currentSection !== null) {
                                            $sections[] = $currentSection;
                                        }
                                        $currentSection = [
                                            'title' => $question->question_text,
                                            'description' => $question->description,
                                            'questions' => [],
                                            'index' => $sectionIndex++
                                        ];
                                    } else {
                                        if($currentSection === null) {
                                            $currentSection = [
                                                'title' => 'General Questions',
                                                'description' => 'Please answer the following questions.',
                                                'questions' => [],
                                                'index' => $sectionIndex++
                                            ];
                                        }
                                        $currentSection['questions'][] = $question;
                                    }
                                }
                                
                                if($currentSection !== null) {
                                    $sections[] = $currentSection;
                                }
                            @endphp

                            @foreach($sections as $section)
                                <div class="section-wrapper" id="section-{{ $section['index'] }}" style="{{ $loop->first ? '' : 'display: none;' }}">
                                    <!-- Section Header -->
                                    <div class="mb-6 pt-4">
                                        <h2 class="text-xl font-normal text-gray-800 border-b pb-2">{{ $section['title'] }}</h2>
                                        @if($section['description'])
                                            <p class="text-gray-600 mt-2">{{ $section['description'] }}</p>
                                        @endif
                                    </div>

                                    <!-- Section Questions -->
                                    @foreach($section['questions'] as $question)
                                        <div class="mb-6 p-4 bg-white rounded-xl card-shadow transition-all duration-300">
                                            <div class="flex justify-between items-start mb-4">
                                                <label class="block text-gray-800 text-lg font-normal" for="question_{{ $question->id }}">
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
                                                        @php
                                                            // Support "label|value" pairs; if no pipe present, use the whole string as both label and value
                                                            $label = $option;
                                                            $value = $option;
                                                            if (is_string($option) && strpos($option, '|') !== false) {
                                                                [$label, $value] = explode('|', $option, 2);
                                                            }
                                                        @endphp
                                                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                                                            <input type="radio" 
                                                                   name="question_{{ $question->id }}" 
                                                                   id="question_{{ $question->id }}_{{ $loop->index }}"
                                                                   value="{{ $value }}"
                                                                   {{ (string)old('question_' . $question->id, $prefill['question_' . $question->id] ?? '') === (string)$value ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-yellow-600 focus:ring-yellow-500"
                                                                   @if($question->required) required @endif>
                                                            <label for="question_{{ $question->id }}_{{ $loop->index }}" class="ml-3 block text-gray-700">
                                                                {{ $label }}
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
                                    @endforeach

                                    <!-- Navigation buttons for this section -->
                                    <div class="flex justify-between mt-6">
                                        @if($loop->first)
                                            <div></div> <!-- Empty div for alignment -->
                                        @else
                                            <button type="button" class="btn-secondary px-5 py-2.5 rounded-lg font-normal" onclick="showSection({{ $section['index'] - 1 }})">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                    </svg>
                                                    Previous
                                                </span>
                                            </button>
                                        @endif

                                        @if($loop->last)
                                            <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-normal shadow-lg transform transition duration-300 hover:scale-105" {{ ! $form->accepting_responses ? 'disabled' : '' }}>
                                                <span class="flex items-center">
                                                    Submit Form
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        @else
                                            <button type="button" class="btn-primary px-5 py-2.5 rounded-lg font-normal" onclick="showSection({{ $section['index'] + 1 }})">
                                                <span class="flex items-center">
                                                    Next
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Hidden section to show when form is submitted successfully -->
                        <div id="final-section" style="display: none;">
                            <div class="text-center py-8">
                                <div class="inline-block p-4 rounded-full bg-green-100 mb-4">
                                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-normal text-gray-900 mb-3">Form Submitted Successfully!</h2>
                                <p class="text-gray-600">Thank you for completing this form.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSectionIndex = 0;
        const totalSections = {{ count($sections) }};

        // Initialize the first section
        document.addEventListener('DOMContentLoaded', function() {
            updateProgressBar();
            
            // Add event listeners for form inputs to update progress
            const form = document.getElementById('multi-step-form');
            form.addEventListener('input', updateProgressBar);
            form.addEventListener('change', updateProgressBar);
        });

        function showSection(sectionIndex) {
            // Validate current section before navigating to the next section
            if (sectionIndex > currentSectionIndex) {
                if (!validateCurrentSection()) {
                    alert('Please fill in all required fields in the current section before proceeding.');
                    return;
                }
            }

            // Hide all sections
            const sections = document.querySelectorAll('.section-wrapper');
            sections.forEach((section, index) => {
                section.style.display = 'none';
            });

            // Show the requested section
            document.getElementById('section-' + sectionIndex).style.display = 'block';
            currentSectionIndex = sectionIndex;
            
            // Update progress bar
            updateProgressBar();
        }

        function validateCurrentSection() {
            const currentSection = document.getElementById('section-' + currentSectionIndex);
            const requiredInputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
            let allValid = true;
            
            requiredInputs.forEach(input => {
                if (input.type === 'radio') {
                    const name = input.name;
                    const checked = currentSection.querySelectorAll(`input[name="${name}"]:checked`);
                    if (checked.length === 0) allValid = false;
                } else if (input.type === 'checkbox') {
                    // For checkbox arrays
                    const name = input.name.replace('[]', '');
                    const checked = currentSection.querySelectorAll(`input[name="${name}[]"]:checked`);
                    if (input.required && checked.length === 0) allValid = false;
                } else {
                    // For text inputs, selects, etc.
                    if (input.required && input.value.trim() === '') allValid = false;
                }
            });
            
            return allValid;
        }

        // Update progress bar based on sections completed
        function updateProgressBar() {
            // Calculate total number of required questions across all sections
            const form = document.getElementById('multi-step-form');
            const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            const totalRequired = requiredInputs.length;
            
            if (totalRequired === 0) {
                document.getElementById('progress-bar').style.width = '0%';
                document.getElementById('progress-text').textContent = '0%';
                return;
            }
            
            // Calculate how many required questions have been answered
            let answered = 0;
            requiredInputs.forEach(input => {
                if (input.type === 'radio') {
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
            
            const percentage = Math.round((answered / totalRequired) * 100);
            document.getElementById('progress-bar').style.width = percentage + '%';
            document.getElementById('progress-text').textContent = percentage + '%';
        }
    </script>
</x-public-layout>