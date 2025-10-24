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
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path></svg>
                    Share
                </button>
                <a href="{{ route('forms.index') }}" class="btn-secondary inline-flex items-center px-4 py-2 rounded-xl font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Forms
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8">
                
                <div id="success-toast" class="hidden fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                    Update successful!
                </div>

                <!-- Form Info -->
                <div class="card-gradient-alt card-shadow rounded-2xl p-8 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Form Details</h2>
                    <form action="{{ route('forms.update', $form->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="title">Form Title</label>
                            <input type="text" name="title" id="title" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200" value="{{ old('title', $form->title) }}" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="description">Description</label>
                            <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200">{{ old('description', $form->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold">Update Form</button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Add Controls -->
                    <div class="lg:col-span-1">
                        <div class="card-gradient-alt card-shadow rounded-2xl p-6 sticky top-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Form Controls</h2>
                            
                            <div class="mb-4">
                                <button type="button" onclick="addSection()" class="btn-secondary w-full py-3 rounded-xl font-semibold flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M3 10h18M3 6h18M3 14h8m-8 4h8"></path></svg>
                                    Add Section
                                </button>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-2 border-b border-gray-200">Add New Question</h3>
                            <form id="add-question-form" method="POST" action="{{ route('forms.questions.add', $form->id) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="question_text">Question Text</label>
                                    <input type="text" name="question_text" id="question_text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="type">Question Type</label>
                                    <select name="type" id="type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200" onchange="toggleOptions(this.value)">
                                        <option value="short_text">Short Text</option>
                                        <option value="radio">Radio Buttons</option>
                                        <option value="checkbox">Checkboxes</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="required" value="1" class="h-4 w-4 text-brand focus:ring-brand border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Required</span>
                                    </label>
                                </div>

                                <div id="options-container" class="hidden mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2">Options (press Enter to add)</label>
                                    <div id="options-list" class="mb-3"></div>
                                    <div class="flex">
                                        <input type="text" id="option-input" placeholder="Add option..." class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200">
                                        <button type="button" onclick="addOption()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 rounded-r-lg font-medium transition duration-200">Add</button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end">
                                    <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold">Add Question</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="lg:col-span-2">
                        <div class="card-gradient-alt card-shadow rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">{{ __('Questions & Sections') }}</h2>
                                <span class="text-sm text-gray-600">{{ $questions->count() }} items</span>
                            </div>
                            
                            <div id="questions-container">
                                @if($questions->count() > 0)
                                    <ul id="questions-sortable" class="space-y-4">
                                        @foreach($questions as $question)
                                            @if($question->type == 'section')
                                                <li draggable="true" class="question-item border-t-4 border-brand rounded-xl p-5 bg-white card-shadow transition-all duration-300 hover:shadow-md" data-question-id="{{ $question->id }}">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="flex items-start">
                                                                <div class="mr-3 cursor-move pt-1">
                                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h-5v6h5v-6zm7 0h-5v6h5v-6z"></path></svg>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $question->question_text }}</h3>
                                                                    <p class="text-gray-600 mt-1">{{ $question->description }}</p>
                                                                    <span class="mt-2 inline-block text-xs px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 font-medium">Section Break</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-2 ml-4">
                                                            <button onclick="openEditModal(this)"
                                                                    data-question-id="{{ $question->id }}"
                                                                    data-question-text="{{ e($question->question_text) }}"
                                                                    data-question-description="{{ e($question->description) }}"
                                                                    data-question-type="{{ $question->type }}"
                                                                    class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                            </button>
                                                            <form action="{{ route('forms.questions.delete', [$form->id, $question->id]) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-2 rounded-lg bg-gray-100 hover:bg-red-100 text-gray-700 hover:text-red-600 transition duration-200" onclick="return confirm('Are you sure you want to delete this section?')">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </li>
                                            @else
                                                <li draggable="true" class="question-item border border-gray-200 rounded-xl p-5 bg-white card-shadow transition-all duration-300 hover:shadow-md" data-question-id="{{ $question->id }}">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="flex items-start">
                                                                <div class="mr-3 cursor-move pt-1">
                                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h-5v6h5v-6zm7 0h-5v6h5v-6z"></path></svg>
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
                                                                                        <div class="w-4 h-4 border border-gray-300 rounded mr-2"></div>
                                                                                    @elseif($question->type === 'radio')
                                                                                        <div class="w-4 h-4 border border-gray-300 rounded-full mr-2"></div>
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
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                            </button>
                                                            <form action="{{ route('forms.questions.delete', [$form->id, $question->id]) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-2 rounded-lg bg-gray-100 hover:bg-red-100 text-gray-700 hover:text-red-600 transition duration-200" onclick="return confirm('Are you sure you want to delete this question?')">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center py-12">...</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                    <div class="flex items-center justify-center min-h-screen pt-10 pb-20 px-4">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all">
                            <div class="p-6">
                                <div class="flex justify-between items-center pb-4 border-b">
                                    <h3 id="edit-modal-title" class="text-lg font-bold text-gray-900">Edit</h3>
                                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <form id="edit-question-form" method="POST" class="mt-4">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-medium mb-2" for="edit-question_text" id="edit-text-label">Question Text</label>
                                        <input type="text" name="question_text" id="edit-question_text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200" required>
                                    </div>

                                    <div id="edit-section-fields" class="hidden">
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-medium mb-2" for="edit-description">Section Description</label>
                                            <textarea name="description" id="edit-description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200"></textarea>
                                        </div>
                                    </div>

                                    <div id="edit-question-fields">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-gray-700 text-sm font-medium mb-2" for="edit-type">Question Type</label>
                                                <select name="type" id="edit-type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200" onchange="toggleEditOptions(this.value)">
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
                                            <label class="block text-gray-700 text-sm font-medium mb-2">Options</label>
                                            <div id="edit-options-list" class="mb-3"></div>
                                            <div class="flex">
                                                <input type="text" id="edit-option-input" placeholder="Add option..." class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200">
                                                <button type="button" onclick="addEditOption()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 rounded-r-lg font-medium transition duration-200">Add</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                                        <button type="button" onclick="closeEditModal()" class="btn-secondary px-6 py-2 rounded-xl font-medium">Cancel</button>
                                        <button type="submit" class="btn-primary px-6 py-2 rounded-xl font-semibold">Update</button>
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
                                            <label id="accepting-switch" onclick="toggleAccepting()" class="relative inline-flex items-center cursor-pointer w-14 h-8" role="switch" aria-checked="{{ $form->accepting_responses ? 'true' : 'false' }}">
                                                <input id="accepting-checkbox" type="checkbox" class="sr-only peer" {{ $form->accepting_responses ? 'checked' : '' }}>
                                                <div class="w-14 h-8 bg-gray-200 rounded-full transition-colors peer-checked:bg-yellow-400"></div>
                                                <span class="absolute left-1 top-[2px] bg-white border border-gray-300 rounded-full h-7 w-7 transition-transform peer-checked:translate-x-6"></span>
                                            </label>
                                            <span id="accepting-label" class="ml-3 text-sm text-gray-700">{{ $form->accepting_responses ? 'Open' : 'Closed' }}</span>
                                            <!-- Test toggle button (visible only to developer) -->
                                            <button id="accepting-test-toggle" type="button" onclick="testToggleAccepting()" class="btn-primary px-3 py-1 rounded-lg text-sm ml-4 inline-flex items-center justify-center" aria-label="Toggle accepting responses">Toggle</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Close button removed as requested -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let editOptions = [];

        function saveScrollPos() {
            try {
                sessionStorage.setItem('forms_edit_scroll', String(window.scrollY || window.pageYOffset || 0));
            } catch (e) {
                // ignore
            }
        }

        // --- FORM SUBMISSION LOGIC ---
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Edit Form Submission via AJAX
            const editForm = document.getElementById('edit-question-form');
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);

                // Manually append checkbox value if it exists
                const requiredCheckbox = document.getElementById('edit-required');
                if (requiredCheckbox) {
                    formData.append('required', requiredCheckbox.checked ? '1' : '0');
                }

                // Manually append options if they exist
                if (typeof editOptions !== 'undefined' && editOptions.length > 0) {
                    formData.append('options', JSON.stringify(editOptions));
                }

                // save scroll position before making the request so we can restore after DOM updates
                saveScrollPos();

                fetch(form.action, {
                    method: 'POST', // Using POST to handle FormData with _method=PUT
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(updatedItem => {
                    updateDOMItem(updatedItem);
                    closeEditModal();
                    showSuccessToast();
                // restore scroll position after updating DOM
                try {
                    const y = parseInt(sessionStorage.getItem('forms_edit_scroll') || '0', 10);
                    window.scrollTo(0, isNaN(y) ? 0 : y);
                } catch (e) {}
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Update failed: ' + (error.message || 'Please check console'));
                });
            });

            // Handle Add Question Form to reload page (as it was originally)
            const addQuestionForm = document.getElementById('add-question-form');
            addQuestionForm.addEventListener('submit', function(e) {
                // save scroll position before full-page submit
                saveScrollPos();
                const optionsInput = document.createElement('input');
                optionsInput.type = 'hidden';
                optionsInput.name = 'options';
                optionsInput.value = JSON.stringify(currentOptions || []);
                this.appendChild(optionsInput);
            });

            // Handle "Accepting Responses" toggle
            const acceptingCheckbox = document.getElementById('accepting-checkbox');
            const acceptingLabel = document.getElementById('accepting-label');
            if (acceptingCheckbox) {
                acceptingCheckbox.addEventListener('change', function() {
                    const isAccepting = this.checked;
                    const url = "{{ route('forms.setAccepting', $form->id) }}";

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            accepting_responses: isAccepting ? 1 : 0
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Revert the checkbox and label on failure
                            this.checked = !isAccepting;
                            if (acceptingLabel) acceptingLabel.textContent = !isAccepting ? 'Open' : 'Closed';
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (acceptingLabel) acceptingLabel.textContent = isAccepting ? 'Open' : 'Closed';
                            showSuccessToast(); // Optional: show a success message
                        }
                    })
                    .catch(error => {
                        console.error('Error updating accepting status:', error);
                        alert('Failed to update status. Please try again.');
                    });
                });
            }
        });

        // --- DOM UPDATE LOGIC ---
        function updateDOMItem(item) {
            const listItem = document.querySelector(`li[data-question-id="${item.id}"]`);
            if (!listItem) return;

            const editButton = listItem.querySelector('button[onclick^="openEditModal"]');

            if (item.type === 'section') {
                listItem.querySelector('h3').textContent = item.question_text;
                let p = listItem.querySelector('p');
                if (!p) { // If description was empty before, the <p> might not exist
                    p = document.createElement('p');
                    p.className = 'text-gray-600 mt-1';
                    listItem.querySelector('h3').after(p);
                }
                p.textContent = item.description || '';
                
                // Update data attributes for next edit
                editButton.setAttribute('data-question-text', item.question_text);
                editButton.setAttribute('data-question-description', item.description || '');
            } else {
                listItem.querySelector('h3').textContent = item.question_text;
                const typeBadge = listItem.querySelector('.bg-yellow-100');
                typeBadge.innerHTML = `${item.type.charAt(0).toUpperCase() + item.type.slice(1).replace('_', ' ')} ${item.required ? '<span class="text-red-500">*</span>' : ''}`;

                // Update data attributes for next edit
                editButton.setAttribute('data-question-text', item.question_text);
                editButton.setAttribute('data-question-type', item.type);
                editButton.setAttribute('data-question-required', item.required ? '1' : '0');
                editButton.setAttribute('data-question-options', JSON.stringify(item.options || []));

                // Update options display
                const optionsContainer = listItem.querySelector('.mt-3.space-y-2');
                if (optionsContainer) {
                    optionsContainer.innerHTML = '';
                    if (item.options && Array.isArray(item.options)) {
                        item.options.forEach(optionText => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'flex items-center text-sm text-gray-600';
                            let iconHtml = '';
                            if (item.type === 'checkbox') {
                                iconHtml = '<div class="w-4 h-4 border border-gray-300 rounded mr-2"></div>';
                            } else if (item.type === 'radio') {
                                iconHtml = '<div class="w-4 h-4 border border-gray-300 rounded-full mr-2"></div>';
                            }
                            optionDiv.innerHTML = `${iconHtml}<span>${optionText}</span>`;
                            optionsContainer.appendChild(optionDiv);
                        });
                    }
                }
            }
        }

        // --- MODAL & UI LOGIC ---
        let currentOptions = [];

        function addSection() {
            fetch("{{ route('forms.sections.add', $form->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            }).then(() => { location.reload(); }); // Simple reload for adding
        }

        // After full page loads, restore scroll position if present
        try {
            const saved = sessionStorage.getItem('forms_edit_scroll');
            if (saved) {
                const y = parseInt(saved, 10);
                if (!isNaN(y)) window.scrollTo(0, y);
                sessionStorage.removeItem('forms_edit_scroll');
            }
        } catch (e) {}

        function openEditModal(buttonElem) {
            const id = buttonElem.getAttribute('data-question-id');
            const type = buttonElem.getAttribute('data-question-type');
            const text = buttonElem.getAttribute('data-question-text') || '';
            
            const form = document.getElementById('edit-question-form');
            form.action = `/forms/{{ $form->id }}/questions/${id}`;

            const modalTitle = document.getElementById('edit-modal-title');
            const questionFields = document.getElementById('edit-question-fields');
            const sectionFields = document.getElementById('edit-section-fields');
            const textLabel = document.getElementById('edit-text-label');
            
            document.getElementById('edit-question_text').value = text;

            if (type === 'section') {
                modalTitle.textContent = 'Edit Section';
                textLabel.textContent = 'Section Title';
                questionFields.classList.add('hidden');
                sectionFields.classList.remove('hidden');
                const description = buttonElem.getAttribute('data-question-description') || '';
                document.getElementById('edit-description').value = description;
            } else {
                modalTitle.textContent = 'Edit Question';
                textLabel.textContent = 'Question Text';
                questionFields.classList.remove('hidden');
                sectionFields.classList.add('hidden');

                const required = buttonElem.getAttribute('data-question-required') === '1';
                const options = JSON.parse(buttonElem.getAttribute('data-question-options') || 'null');
                
                document.getElementById('edit-type').value = type;
                document.getElementById('edit-required').checked = required;
                
                editOptions = options ? options.slice() : [];
                updateEditOptionsList();
                toggleEditOptions(type);
            }
            
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function showSuccessToast() {
            const toast = document.getElementById('success-toast');
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        function toggleOptions(type) {
            const container = document.getElementById('options-container');
            if (type === 'radio' || type === 'checkbox' || type === 'dropdown') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                currentOptions = [];
                updateOptionsList();
            }
        }

        function toggleEditOptions(type) {
            const container = document.getElementById('edit-options-container');
            if (type === 'radio' || type === 'checkbox' || type === 'dropdown') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                editOptions = [];
                updateEditOptionsList();
            }
        }

        function addOption() {
            const input = document.getElementById('option-input');
            const value = (input.value || '').trim();
            if (!value) return;
            currentOptions.push(value);
            input.value = '';
            updateOptionsList();
        }

        function updateOptionsList() {
            const list = document.getElementById('options-list');
            list.innerHTML = '';
            currentOptions.forEach((opt, idx) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between bg-gray-50 p-2 rounded mb-2';
                div.innerHTML = `<span class="text-sm text-gray-700">${escapeHtml(opt)}</span><div class="flex items-center"><button type="button" onclick="removeOption(${idx})" class="text-red-500 mr-2">Remove</button></div>`;
                list.appendChild(div);
            });
        }

        function removeOption(idx) {
            currentOptions.splice(idx, 1);
            updateOptionsList();
        }

        function addEditOption() {
            const input = document.getElementById('edit-option-input');
            const value = (input.value || '').trim();
            if (!value) return;
            editOptions.push(value);
            input.value = '';
            updateEditOptionsList();
        }

        function updateEditOptionsList() {
            const list = document.getElementById('edit-options-list');
            list.innerHTML = '';
            editOptions.forEach((opt, idx) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between bg-gray-50 p-2 rounded mb-2';
                div.innerHTML = `<span class="text-sm text-gray-700">${escapeHtml(opt)}</span><div class="flex items-center"><button type="button" onclick="removeEditOption(${idx})" class="text-red-500 mr-2">Remove</button></div>`;
                list.appendChild(div);
            });
        }

        function removeEditOption(idx) {
            editOptions.splice(idx, 1);
            updateEditOptionsList();
        }

        function escapeHtml(unsafe) {
            return unsafe
              .replace(/&/g, "&amp;")
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/\"/g, "&quot;")
              .replace(/'/g, "&#039;");
        }
        function toggleAccepting() {
            const checkbox = document.getElementById('accepting-checkbox');
            if (!checkbox) return;
            // Toggle the checkbox state and trigger the existing change handler
            checkbox.checked = !checkbox.checked;
            const changeEvent = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(changeEvent);
        }

        function testToggleAccepting() {
            // Optimistic update for label so user sees immediate change
            const acceptingLabel = document.getElementById('accepting-label');
            const checkbox = document.getElementById('accepting-checkbox');
            const btn = document.getElementById('accepting-test-toggle');
            if (!checkbox) return;

            const origBtnText = btn ? btn.innerText : null;
            // show loading state on button
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-wait');
                btn.innerText = 'Updating...';
            }

            const newState = !checkbox.checked;
            if (acceptingLabel) acceptingLabel.textContent = newState ? 'Open' : 'Closed';

            // Send the same AJAX request as the change handler
            const url = "{{ route('forms.setAccepting', $form->id) }}";
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ accepting_responses: newState ? 1 : 0 })
            }).then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            }).then(data => {
                if (data.success) {
                    checkbox.checked = newState;
                    showSuccessToast();
                } else {
                    if (acceptingLabel) acceptingLabel.textContent = checkbox.checked ? 'Open' : 'Closed';
                    alert('Update failed');
                }
            }).catch(err => {
                console.error(err);
                if (acceptingLabel) acceptingLabel.textContent = checkbox.checked ? 'Open' : 'Closed';
                alert('Failed to update accepting status');
            }).finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-wait');
                    btn.innerText = origBtnText;
                }
            });
        }

        function showShareModal() { document.getElementById('share-modal').classList.remove('hidden'); }
        function closeShareModal() { document.getElementById('share-modal').classList.add('hidden'); }
        function copyToClipboard() {
            const input = document.getElementById('share-link');
            if (!input) return;

            const copyButton = document.querySelector('#share-modal button[onclick="copyToClipboard()"]');
            const originalBtnText = copyButton ? copyButton.innerText : null;

            const setTempText = (text) => {
                if (!copyButton) return;
                copyButton.innerText = text;
                setTimeout(() => { copyButton.innerText = originalBtnText; }, 1500);
            };

            const value = input.value || input.getAttribute('value') || '';

            // Prefer modern Clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(value).then(() => {
                    setTempText('Copied');
                }).catch(() => {
                    // Fallback
                    try {
                        input.select();
                        input.setSelectionRange(0, 99999);
                        document.execCommand('copy');
                        input.blur();
                        setTempText('Copied');
                    } catch (err) {
                        console.error('Copy failed:', err);
                        alert('Copy failed. Please select the link and press Ctrl+C');
                    }
                });
            } else {
                // Fallback for older browsers
                try {
                    input.select();
                    input.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    input.blur();
                    setTempText('Copied');
                } catch (err) {
                    console.error('Copy failed:', err);
                    alert('Copy failed. Please select the link and press Ctrl+C');
                }
            }
        }
    </script>
</x-app-layout>