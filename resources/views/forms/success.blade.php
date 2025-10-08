<x-public-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $form->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8 max-w-3xl">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 text-center">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Thank you!</h1>
                    <p class="text-gray-600 mb-6">Your response has been recorded for <strong>{{ $form->title }}</strong>.</p>
                    <div class="flex items-center justify-center space-x-4">
                        <a href="{{ route('forms.public', $form) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back to form</a>
                        @php $respId = session('response_id'); @endphp
                        @if($respId)
                            <a href="{{ route('forms.public', $form) }}?response_id={{ $respId }}#edit" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">แก้ไขคำตอบของคุณ</a>
                        @else
                            <a href="{{ route('forms.public', $form) }}#edit" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">แก้ไขคำตอบของคุณ</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>


