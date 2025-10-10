<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Results: {{ $form->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Results: {{ $form->title }}</h1>
        <div>
            <!-- View Form button removed per user request -->
            <a href="{{ route('forms.export', $form->id) }}" class="bg-brand hover:opacity-90 text-brand font-bold py-2 px-4 rounded inline-block">
                Export CSV
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-card-yellow-50 p-4 rounded-lg border border-card-yellow-100">
                <h3 class="text-lg font-semibold text-brand">Total Responses</h3>
                <p class="text-3xl font-bold text-brand">{{ $totalResponses }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <h3 class="text-lg font-semibold text-green-800">Questions</h3>
                <p class="text-3xl font-bold text-green-600">{{ $form->questions->count() }}</p>
            </div>
            <div class="bg-card-yellow-100 p-4 rounded-lg border border-card-yellow-100">
                <h3 class="text-lg font-semibold text-brand">Created</h3>
                <p class="text-xl font-bold text-brand">{{ $form->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <div x-data="{ tab: 'overview' }">
            <div class="mb-6 border-b">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button @click.prevent="tab = 'overview'" :class="tab === 'overview' ? 'border-b-2 border-brand text-brand' : 'text-gray-600'" class="px-3 py-2 font-medium">Overview</button>
                    <button @click.prevent="tab = 'individual'" :class="tab === 'individual' ? 'border-b-2 border-brand text-brand' : 'text-gray-600'" class="px-3 py-2 font-medium">แนวทางการ</button>
                </nav>
            </div>

            <div x-show="tab === 'overview'" x-cloak>
                @foreach($questionStats as $questionId => $stats)
            <div class="mb-10 border-b pb-10">
                <h2 class="text-xl font-bold mb-4 flex items-center">
                    <span>{{ $stats['question']->question_text }}</span>
                    <span class="ml-2 text-sm px-2 py-1 rounded bg-gray-100 text-gray-700">
                        {{ ucfirst(str_replace('_', ' ', $stats['question']->type)) }}
                    </span>
                    @if($stats['question']->required)
                        <span class="ml-2 text-red-500">*</span>
                    @endif
                </h2>

                @if(in_array($stats['question']->type, ['radio', 'checkbox', 'dropdown']))
                    <!-- Bar chart for multiple choice answers -->
                    <div class="mb-6">
                        @php
                            // If question has defined options, iterate those to preserve order
                            $displayOptions = is_array($stats['question']->options) ? $stats['question']->options : array_keys($stats['answers']);
                        @endphp

                        @foreach($displayOptions as $option)
                            @php $data = $stats['answers'][$option] ?? ['count' => 0, 'percentage' => 0]; @endphp
                            <div class="mb-2">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $option }}</span>
                                    <span class="text-sm font-medium">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-brand h-2.5 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Total responses for this question: {{ $stats['total_answers'] }}
                    </div>
                @else
                    <!-- Table for text/date answers -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answer</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php 
                                    $index = 1;
                                @endphp
                                @foreach($form->responses as $response)
                                    @foreach($response->responseAnswers as $answer)
                                        @if($answer->question_id == $stats['question']->id)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $index++ }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($stats['question']->type === 'checkbox')
                                                        {{ implode(', ', json_decode($answer->answer, true) ?: []) }}
                                                    @else
                                                        {{ $answer->answer }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($stats['total_answers'] === 0)
                        <p class="text-gray-500 italic">No responses yet for this question.</p>
                    @endif
                @endif
            </div>
                @endforeach
            </div>

            <div x-show="tab === 'individual'" x-cloak>
                <div class="space-y-4">
                    @php
                        $responsesPerPage = 20;
                        $responses = $form->responses()->latest()->take($responsesPerPage)->get();
                    @endphp

                    @if($responses->isEmpty())
                        <p class="text-gray-500">No responses yet.</p>
                    @else
                        @foreach($responses as $idx => $response)
                            <div x-data="{ open: false }" class="bg-white border rounded-lg shadow-sm">
                                <button @click="open = !open" class="w-full px-4 py-3 flex items-center justify-between text-left">
                                    <div>
                                        <div class="font-semibold">ต้องการคำที่ {{ $loop->iteration }} | {{ $response->created_at->format('M d, Y H:i') }}</div>
                                        <div class="text-sm text-gray-500">ตอบเมื่อ {{ $response->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="ml-4">
                                        <svg x-show="!open" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        <svg x-show="open" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                    </div>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-4 pb-4">
                                    <div class="py-3 text-sm text-gray-600">ส่วนที่ {{ $loop->iteration }} จาก {{ $totalResponses }}</div>
                                    <div class="space-y-3">
                                        @foreach($form->questions as $qIndex => $question)
                                            @php
                                                $answer = $response->responseAnswers->firstWhere('question_id', $question->id);
                                                $displayAnswer = '';
                                                if($answer) {
                                                    if($question->type === 'checkbox') {
                                                        $displayAnswer = implode(', ', json_decode($answer->answer, true) ?: []);
                                                    } else {
                                                        $displayAnswer = $answer->answer;
                                                    }
                                                }
                                            @endphp

                                            <div class="bg-gray-50 p-3 rounded border">
                                                <div class="font-medium">{{ $qIndex + 1 }}. {{ $question->question_text }}</div>
                                                <div class="text-gray-800 mt-1">{{ $displayAnswer ?? '-' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($form->responses()->count() > $responsesPerPage)
                            <div class="mt-4 text-center">
                                <button id="load-more-responses" class="inline-block bg-brand text-white px-4 py-2 rounded">Load More</button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</x-app-layout>