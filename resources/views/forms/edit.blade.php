<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Form') }}
                </h2>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">{{ $form->title }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="showShareModal()" class="btn-primary inline-flex items-center px-4 py-2 rounded-xl font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    Share
                </button>
                <a href="{{ route('forms.index') }}" class="btn-secondary inline-flex items-center px-4 py-2 rounded-xl font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Forms
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Info -->
                <div class="card-gradient-alt card-shadow rounded-2xl p-8 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Form Details</h2>
                    <form action="{{ route('forms.update', $form->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="title">
                                Form Title
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"
                                   value="{{ old('title', $form->title) }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="description">
                                Description
                            </label>
                            <textarea name="description" id="description" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"
                                      >{{ old('description', $form->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold">
                                Update Form
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Add Question Form -->
                    <div class="lg:col-span-1">
                        <div class="card-gradient-alt card-shadow rounded-2xl p-6 sticky top-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Add New Question</h2>
                            <form id="add-question-form" method="POST" action="{{ route('forms.questions.add', $form->id) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="question_text">
                                        Question Text
                                    </label>
                                    <input type="text" name="question_text" id="question_text" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"
                                           required>
                                    @error('question_text')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="type">
                                        Question Type
                                    </label>
                                    <select name="type" id="type" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"
                                            onchange="toggleOptions(this.value)">
                                        <option value="short_text">Short Text</option>
                                        <option value="radio">Radio Buttons</option>
                                        <option value="checkbox">Checkboxes</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="date">Date</option>
                                    </select>
                                    @error('type')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="required" value="1" class="h-4 w-4 text-brand focus:ring-brand border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Required</span>
                                    </label>
                                </div>

                                <div id="options-container" class="hidden mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Options (press Enter to add)
                                    </label>
                                    <div id="options-list" class="mb-3"></div>
                                    <div class="flex">
                                        <input type="text" id="option-input" placeholder="Add option..." 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200">
                                        <button type="button" onclick="addOption()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 rounded-r-lg font-medium transition duration-200">
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end">
                                    <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold">
                                        Add Question
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="lg:col-span-2">
                        <div class="card-gradient-alt card-shadow rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">{{ __('Questions') }}</h2>
                                <span class="text-sm text-gray-600">{{ $questions->count() }} questions</span>
                            </div>
                            
                            <div id="questions-container">
                                @if($questions->count() > 0)
                                    <ul id="questions-sortable" class="space-y-4">
                                        @foreach($questions as $question)
                                            <li draggable="true" class="question-item border border-gray-200 rounded-xl p-5 bg-white card-shadow transition-all duration-300 hover:shadow-md" data-question-id="{{ $question->id }}">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <div class="flex items-start">
                                                            <div class="mr-3 cursor-move pt-1">
                                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M10 9h-5v6h5v-6zm7 0h-5v6h5v-6z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="flex items-center">
                                                                    <h3 class="font-semibold text-gray-900">{{ $question->question_text }}</h3>
                                                                    <span class="ml-3 text-xs px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                                                        {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                                        @if($question->required) <span class="text-red-500">*</span> @endif
                                                                    </span>
                                                                </div>
                                                                
                                                                @if($question->options)
                                                                    <div class="mt-3 space-y-2">
                                                                        @foreach($question->options as $option)
                                                                            <div class="flex items-center text-sm text-gray-600">
                                                                                @if($question->type === 'checkbox')
                                                                                    <div class="w-4 h-4 border border-gray-300 rounded mr-2 flex items-center justify-center">
                                                                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                        </svg>
                                                                                    </div>
                                                                                @elseif($question->type === 'radio')
                                                                                    <div class="w-4 h-4 border border-gray-300 rounded-full mr-2 flex items-center justify-center">
                                                                                        <div class="w-1.5 h-1.5 bg-transparent rounded-full"></div>
                                                                                    </div>
                                                                                @endif
                                                                                <span>{{ $option }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex space-x-2 ml-4">
                                                        <button onclick="openEditModal(this)"
                                                                data-question-id="{{ $question->id }}"
                                                                data-question-text="{{ e($question->question_text) }}"
                                                                data-question-type="{{ $question->type }}"
                                                                data-question-required="{{ $question->required ? '1' : '0' }}"
                                                                data-question-options='@json($question->options)'
                                                                class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <form action="{{ route('forms.questions.delete', [$form->id, $question->id]) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="p-2 rounded-lg bg-gray-100 hover:bg-red-100 text-gray-700 hover:text-red-600 transition duration-200"
                                                                    onclick="return confirm('Are you sure you want to delete this question?')">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center py-12">
                                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No questions yet</h3>
                                        <p class="text-gray-500 mb-4">Start building your form by adding your first question.</p>
                                        <a href="#" class="text-brand font-medium hover:underline">Learn how to create effective questions</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Question Modal -->
                <div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                    <div class="flex items-center justify-center min-height-screen pt-10 pb-20 px-4">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all">
                            <div class="p-6">
                                <div class="flex justify-between items-center pb-4 border-b">
                                    <h3 class="text-lg font-bold text-gray-900">Edit Question</h3>
                                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <form id="edit-question-form" method="POST" class="mt-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-medium mb-2" for="edit-question_text">
                                            Question Text
                                        </label>
                                        <input type="text" name="question_text" id="edit-question_text" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"
                                               required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-gray-700 text-sm font-medium mb-2" for="edit-type">
                                                Question Type
                                            </label>
                                            <select name="type" id="edit-type" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"
                                                    onchange="toggleEditOptions(this.value)">
                                                <option value="short_text">Short Text</option>
                                                <option value="radio">Radio Buttons</option>
                                                <option value="checkbox">Checkboxes</option>
                                                <option value="dropdown">Dropdown</option>
                                                <option value="date">Date</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="flex items-center mt-5">
                                                <input type="checkbox" name="required" id="edit-required" value="1" class="h-4 w-4 text-brand focus:ring-brand border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-gray-700">Required</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="edit-options-container" class="hidden mb-4">
                                        <label class="block text-gray-700 text-sm font-medium mb-2">
                                            Options (press Enter to add)
                                        </label>
                                        <div id="edit-options-list" class="mb-3"></div>
                                        <div class="flex">
                                            <input type="text" id="edit-option-input" placeholder="Add option..." 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200">
                                            <button type="button" onclick="addEditOption()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 rounded-r-lg font-medium transition duration-200">
                                                Add
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                                        <button type="button" onclick="closeEditModal()" class="btn-secondary px-6 py-2 rounded-xl font-medium">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn-primary px-6 py-2 rounded-xl font-semibold">
                                            Update Question
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Modal -->
                <div id="share-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                    <div class="flex items-center justify-center min-height-screen pt-10 pb-20 px-4">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all">
                            <div class="p-6">
                                <div class="flex justify-between items-center pb-4 border-b">
                                    <h3 class="text-lg font-bold text-gray-900">Share Form</h3>
                                    <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-4">
                                    <p class="text-gray-600 mb-4">Anyone with the link can view and submit this form.</p>
                                    <div class="mb-6">
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Share Link</label>
                                        <div class="flex">
                                            <input type="text" id="share-link" readonly value="{{ route('forms.public', $form) }}" 
                                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-l-xl focus:ring-2 focus:ring-brand focus:border-transparent">
                                            <button onclick="copyToClipboard()" class="btn-primary px-6 py-3 rounded-r-xl font-semibold">
                                                Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Accepting Responses</label>
                                        <div class="flex items-center">
                                            <div id="accepting-switch" class="relative inline-flex items-center cursor-pointer w-14 h-8" role="switch" aria-checked="{{ $form->accepting_responses ? 'true' : 'false' }}">
                                                <input id="accepting-checkbox" type="checkbox" class="sr-only peer" {{ $form->accepting_responses ? 'checked' : '' }}>
                                                <div class="peer w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-yellow-400"></div>
                                            </div>
                                            <span id="accepting-label" class="ml-3 text-sm text-gray-700">{{ $form->accepting_responses ? 'Open' : 'Closed' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end mt-6">
                                    <button onclick="closeShareModal()" class="btn-secondary px-6 py-2 rounded-xl font-medium">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentOptions = [];
        let editOptions = [];

        function toggleOptions(type) {
            const optionsContainer = document.getElementById('options-container');
            if (type === 'short_text' || type === 'date') {
                optionsContainer.classList.add('hidden');
            } else {
                optionsContainer.classList.remove('hidden');
            }
        }

        function toggleEditOptions(type) {
            const optionsContainer = document.getElementById('edit-options-container');
            if (type === 'short_text' || type === 'date') {
                optionsContainer.classList.add('hidden');
            } else {
                optionsContainer.classList.remove('hidden');
            }
        }

        function addOption() {
            const input = document.getElementById('option-input');
            if (input.value.trim() !== '') {
                currentOptions.push(input.value.trim());
                updateOptionsList();
                input.value = '';
                input.focus();
            }
        }

        function addEditOption() {
            const input = document.getElementById('edit-option-input');
            if (input.value.trim() !== '') {
                editOptions.push(input.value.trim());
                updateEditOptionsList();
                input.value = '';
                input.focus();
            }
        }

        function updateOptionsList() {
            const container = document.getElementById('options-list');
            container.innerHTML = '';
            currentOptions.forEach((option, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center mb-2';
                div.innerHTML = `
                    <input type="text" value="${option}" oninput="setOptionValue(${index}, this.value)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent" />
                    <button type="button" onclick="removeOption(${index})" class="ml-2 p-2 text-red-600 hover:bg-red-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function updateEditOptionsList() {
            const container = document.getElementById('edit-options-list');
            container.innerHTML = '';
            editOptions.forEach((option, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center mb-2';
                div.innerHTML = `
                    <input type="text" value="${option}" oninput="setEditOptionValue(${index}, this.value)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent" />
                    <button type="button" onclick="removeEditOption(${index})" class="ml-2 p-2 text-red-600 hover:bg-red-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        // Update option value for new-question options
        function setOptionValue(index, value) {
            currentOptions[index] = value;
        }

        // Update option value for edit-question modal
        function setEditOptionValue(index, value) {
            editOptions[index] = value;
        }

        function removeOption(index) {
            currentOptions.splice(index, 1);
            updateOptionsList();
        }

        function removeEditOption(index) {
            editOptions.splice(index, 1);
            updateEditOptionsList();
        }

        const optionInputElem = document.getElementById('option-input');
        if (optionInputElem) {
            optionInputElem.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addOption();
                    e.preventDefault();
                }
            });
        }

        const editOptionInputElem = document.getElementById('edit-option-input');
        if (editOptionInputElem) {
            editOptionInputElem.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addEditOption();
                    e.preventDefault();
                }
            });
        }

        // Edit question function
        function editQuestion(id, text, type, required, options) {
            document.getElementById('edit-question-form').action = `/forms/{{ $form->id }}/questions/${id}`;
            document.getElementById('edit-question_text').value = text;
            document.getElementById('edit-type').value = type;
            document.getElementById('edit-required').checked = required;
            
            // Set options
            editOptions = options ? options.slice() : [];
            updateEditOptionsList();
            
            toggleEditOptions(type);
            
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        // New handler that reads data-* attributes from the clicked button
        function openEditModal(buttonElem) {
            const id = buttonElem.getAttribute('data-question-id');
            const text = buttonElem.getAttribute('data-question-text') || '';
            const type = buttonElem.getAttribute('data-question-type') || 'short_text';
            const required = buttonElem.getAttribute('data-question-required') === '1';
            const options = JSON.parse(buttonElem.getAttribute('data-question-options') || 'null');

            editQuestion(id, text, type, required, options);
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
            // Reset form
            document.getElementById('edit-question-form').reset();
            document.getElementById('edit-type').value = 'short_text';
            editOptions = [];
            updateEditOptionsList();
            document.getElementById('edit-options-container').classList.add('hidden');
        }

        function showShareModal() {
            document.getElementById('share-modal').classList.remove('hidden');
        }

        function closeShareModal() {
            document.getElementById('share-modal').classList.add('hidden');
        }

        function copyToClipboard() {
            const linkInput = document.getElementById('share-link');
            linkInput.select();
            navigator.clipboard.writeText(linkInput.value).then(() => {
                // Show a simple feedback
                const originalText = linkInput.nextElementSibling.textContent;
                linkInput.nextElementSibling.textContent = 'Copied!';
                setTimeout(() => {
                    linkInput.nextElementSibling.textContent = 'Copy';
                }, 2000);
            });
        }

        // Apply accepting_responses change from Share modal (switch)
        (function() {
            const switchContainer = document.querySelector('#accepting-switch input');
            if (!switchContainer) return;
            const checkbox = document.getElementById('accepting-checkbox');
            const label = document.getElementById('accepting-label');

            checkbox.addEventListener('change', function() {
                const newValue = this.checked;

                fetch("/forms/{{ $form->id }}/accepting", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ accepting_responses: newValue ? 1 : 0 })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        // revert
                        this.checked = !newValue;
                        label.textContent = !newValue ? 'Open' : 'Closed';
                        alert('Failed to update');
                    } else {
                        label.textContent = newValue ? 'Open' : 'Closed';
                        closeShareModal();
                        location.reload();
                    }
                })
                .catch(() => {
                    this.checked = !newValue;
                    label.textContent = !newValue ? 'Open' : 'Closed';
                    alert('Failed to update');
                });
            });
        })();

        // Form submission handling
        document.getElementById('add-question-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Add options to form data
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'options';
            input.value = JSON.stringify(currentOptions);
            this.appendChild(input);
            
            this.submit();
        });

        document.getElementById('edit-question-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Add options to form data
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'options';
            input.value = JSON.stringify(editOptions);
            this.appendChild(input);
            
            this.submit();
        });

        // Initialize drag and drop for questions
        document.addEventListener('DOMContentLoaded', function() {
            const questionsList = document.getElementById('questions-sortable');
            if (!questionsList) return;

            let draggedItem = null;

            questionsList.addEventListener('dragstart', function(e) {
                draggedItem = e.target.closest('li') || e.target;
                if (draggedItem) {
                    draggedItem.classList.add('opacity-50');
                    e.dataTransfer.effectAllowed = 'move';
                    // Add visual feedback
                    draggedItem.style.transform = 'rotate(2deg)';
                }
            });

            questionsList.addEventListener('dragend', function(e) {
                const item = e.target.closest('li') || e.target;
                if (item) {
                    item.classList.remove('opacity-50');
                    item.style.transform = 'rotate(0deg)';
                }
                draggedItem = null;
            });

            questionsList.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                
                const afterElement = getDragAfterElement(questionsList, e.clientY);
                const draggable = document.querySelector('.dragging') || draggedItem;
                
                if (afterElement == null) {
                    questionsList.appendChild(draggable);
                } else {
                    questionsList.insertBefore(draggable, afterElement);
                }
            });

            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('li:not(.dragging)')];
                
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }

            function saveOrder() {
                const questionIds = [];
                document.querySelectorAll('#questions-sortable li').forEach(function(item) {
                    questionIds.push(item.dataset.questionId);
                });

                fetch(`/forms/{{ $form->id }}/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        question_ids: questionIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Order saved successfully');
                    }
                });
            }
        });
    </script>
</x-app-layout>