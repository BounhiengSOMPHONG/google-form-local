<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Edit: {{ $form->title }}</h1>
            <div>
                <button onclick="showShareModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Share
                </button>
                <a href="{{ route('forms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Forms
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Info -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
                    <form action="{{ route('forms.update', $form->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                Form Title
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                                   value="{{ old('title', $form->title) }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                Description
                            </label>
                            <textarea name="description" id="description" 
                                      rows="4"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                      >{{ old('description', $form->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Form
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Add Question Form -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
                    <h2 class="text-xl font-bold mb-4">Add New Question</h2>
                    <form id="add-question-form" method="POST" action="{{ route('forms.questions.add', $form->id) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="question_text">
                                    Question Text
                                </label>
                                <input type="text" name="question_text" id="question_text" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('question_text') border-red-500 @enderror"
                                       required>
                                @error('question_text')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                                    Question Type
                                </label>
                                <select name="type" id="type" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type') border-red-500 @enderror"
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
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                <input type="checkbox" name="required" value="1" class="mr-2">
                                Required
                            </label>
                        </div>

                        <div id="options-container" class="hidden mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Options (press Enter to add)
                            </label>
                            <div id="options-list" class="mb-2"></div>
                            <div class="flex">
                                <input type="text" id="option-input" placeholder="Add option..." 
                                       class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-full mr-2">
                                <button type="button" onclick="addOption()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Add Option
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Add Question
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Questions List -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
                    <h2 class="text-xl font-bold mb-4">Questions</h2>
                    
                    <div id="questions-container">
                        @if($questions->count() > 0)
                            <ul id="questions-sortable" class="space-y-4">
                                @foreach($questions as $question)
                                    <li draggable="true" class="question-item border border-gray-200 rounded p-4" data-question-id="{{ $question->id }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center">
                                                    <span class="mr-2 cursor-move">☰</span>
                                                    <h3 class="font-bold text-lg">{{ $question->question_text }}</h3>
                                                    <span class="ml-2 text-sm px-2 py-1 rounded bg-gray-100 text-gray-700">
                                                        {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                        @if($question->required) <span class="text-red-500">*</span> @endif
                                                    </span>
                                                </div>
                                                
                                                @if($question->options)
                                                    <div class="mt-2 ml-6 text-sm">
                                                        @foreach($question->options as $option)
                                                            <div class="flex items-center">
                                                                @if($question->type === 'checkbox')
                                                                    <input type="checkbox" disabled class="mr-2">
                                                                @elseif($question->type === 'radio')
                                                                    <input type="radio" disabled class="mr-2">
                                                                @endif
                                                                <span>{{ $option }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex space-x-2">
                                                <button onclick="editQuestion({{ $question->id }}, '{{ addslashes($question->question_text) }}', '{{ $question->type }}', {{ $question->required ? 'true' : 'false' }}, @json($question->options))" 
                                                        class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded text-sm">
                                                    Edit
                                                </button>
                                                <form action="{{ route('forms.questions.delete', [$form->id, $question->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded text-sm"
                                                            onclick="return confirm('Are you sure you want to delete this question?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-center py-4">No questions added yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Edit Question Modal -->
                <div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center pb-3 border-b">
                                <h3 class="text-lg font-bold">Edit Question</h3>
                                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <form id="edit-question-form" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-question_text">
                                        Question Text
                                    </label>
                                    <input type="text" name="question_text" id="edit-question_text" 
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-type">
                                            Question Type
                                        </label>
                                        <select name="type" id="edit-type" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                onchange="toggleEditOptions(this.value)">
                                            <option value="short_text">Short Text</option>
                                            <option value="radio">Radio Buttons</option>
                                            <option value="checkbox">Checkboxes</option>
                                            <option value="dropdown">Dropdown</option>
                                            <option value="date">Date</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            <input type="checkbox" name="required" id="edit-required" value="1" class="mr-2">
                                            Required
                                        </label>
                                    </div>
                                </div>

                                <div id="edit-options-container" class="hidden mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Options (press Enter to add)
                                    </label>
                                    <div id="edit-options-list" class="mb-2"></div>
                                    <div class="flex">
                                        <input type="text" id="edit-option-input" placeholder="Add option..." 
                                               class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-full mr-2">
                                        <button type="button" onclick="addEditOption()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Add Option
                                        </button>
                                    </div>
                                </div>

                                <div class="items-center justify-between pt-3 border-t">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Update Question
                                    </button>
                                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Share Modal -->
                <div id="share-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center pb-3 border-b">
                                <h3 class="text-lg font-bold">Share Form</h3>
                                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Anyone with the link can view and submit this form.</p>
                                <div class="mt-2">
                                    <input type="text" id="share-link" readonly value="{{ route('forms.public', $form) }}" 
                                           class="w-full bg-gray-100 border rounded py-2 px-3 text-gray-700">
                                    <button onclick="copyToClipboard()" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Copy Link
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
                div.className = 'flex items-center mb-1';
                div.innerHTML = `
                    <span class="bg-gray-100 px-2 py-1 rounded mr-2">${option}</span>
                    <button type="button" onclick="removeOption(${index})" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                div.className = 'flex items-center mb-1';
                div.innerHTML = `
                    <span class="bg-gray-100 px-2 py-1 rounded mr-2">${option}</span>
                    <button type="button" onclick="removeEditOption(${index})" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(div);
            });
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
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        }

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
                if (draggedItem) draggedItem.classList.add('opacity-50');
            });

            questionsList.addEventListener('dragend', function(e) {
                const item = e.target.closest('li') || e.target;
                if (item) item.classList.remove('opacity-50');
                draggedItem = null;
            });

            questionsList.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            questionsList.addEventListener('dragenter', function(e) {
                e.preventDefault();
                const li = e.target.closest('li');
                if (li) li.classList.add('border-blue-500', 'border-2');
            });

            questionsList.addEventListener('dragleave', function(e) {
                const li = e.target.closest('li');
                if (li) li.classList.remove('border-blue-500', 'border-2');
            });

            questionsList.addEventListener('drop', function(e) {
                e.preventDefault();
                const dropTarget = e.target.closest('li');
                if (!dropTarget) return;
                dropTarget.classList.remove('border-blue-500', 'border-2');

                if (draggedItem && draggedItem !== dropTarget) {
                    if (e.offsetY < dropTarget.offsetHeight / 2) {
                        dropTarget.parentNode.insertBefore(draggedItem, dropTarget);
                    } else {
                        dropTarget.parentNode.insertBefore(draggedItem, dropTarget.nextSibling);
                    }
                    
                    // Save new order
                    saveOrder();
                }
            });

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