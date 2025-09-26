<x-app-layout>
    <div class="min-h-screen bg-purple-50">
        <!-- Include Survey Tools Navigation -->
        <x-survey-tools />
        
        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Main Form Area -->
                <div class="flex-1">
                    <form method="POST" action="{{ route('surveys.store') }}">
                        @csrf
                        <!-- Title Card -->
                        <div class="bg-white rounded-lg shadow-sm mb-4 p-6">
                            <input
                                type="text"
                                name="title"
                                class="text-3xl font-medium w-full border-0 border-b-2 border-green-200 focus:border-green-500 focus:ring-0 p-2 mb-4"
                                placeholder="Untitled Form"
                                value="{{ old('title') }}"
                                required
                                autofocus
                            />
                            <textarea
                                name="description"
                                class="w-full border-0 focus:ring-0 text-gray-600 resize-none"
                                rows="2"
                                placeholder="Form description"
                            >{{ old('description') }}</textarea>
                        </div>

                        <!-- Hidden Settings Input -->
                        <input type="hidden" name="allow_multiple_responses" value="0" id="allow_multiple_responses">
                        <input type="hidden" name="require_login" value="0" id="require_login">


                        <!-- Fixed Action Buttons -->
                        <div class="fixed bottom-8 right-8 flex flex-col space-y-4">
                            <!-- Save Button -->
                            <button
                                type="submit"
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-green-500 text-white shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-150"
                                title="Save"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <!-- Cancel Button -->
                            <a 
                                href="{{ route('dashboard') }}"
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-red-500 text-white shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-150"
                                title="Cancel"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>