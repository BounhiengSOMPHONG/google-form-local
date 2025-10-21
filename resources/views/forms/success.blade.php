<x-public-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8 max-w-3xl">
                <div class="card-gradient-alt card-shadow rounded-2xl p-12 text-center">
                    <div class="inline-block p-6 rounded-full bg-green-100 mb-6 animate-float">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Thank you!</h1>
                    <p class="text-gray-600 text-lg mb-8">Your response has been recorded for <strong>{{ $form->title }}</strong>.</p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('forms.public', $form) }}" class="btn-primary px-6 py-3 rounded-xl font-semibold shadow-lg">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to form
                            </span>
                        </a>
                        @php $respId = session('response_id'); @endphp
                        @if($respId)
                            <a href="{{ route('forms.public', $form) }}?response_id={{ $respId }}#edit" class="btn-secondary px-6 py-3 rounded-xl font-semibold">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit your response
                                </span>
                            </a>
                        @else
                            <a href="{{ route('forms.public', $form) }}#edit" class="btn-secondary px-6 py-3 rounded-xl font-semibold">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit your response
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>


