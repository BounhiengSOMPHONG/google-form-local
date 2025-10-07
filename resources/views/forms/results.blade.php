@extends('layouts.app')

@section('title', 'Results: ' . $form->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Results: {{ $form->title }}</h1>
        <div>
            <a href="{{ route('forms.public', $form->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2" target="_blank">
                View Form
            </a>
            <a href="{{ route('forms.export', $form->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Export CSV
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h3 class="text-lg font-semibold text-blue-800">Total Responses</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $totalResponses }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <h3 class="text-lg font-semibold text-green-800">Questions</h3>
                <p class="text-3xl font-bold text-green-600">{{ $form->questions->count() }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                <h3 class="text-lg font-semibold text-purple-800">Created</h3>
                <p class="text-xl font-bold text-purple-600">{{ $form->created_at->format('M d, Y') }}</p>
            </div>
        </div>

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
                        @foreach($stats['answers'] as $answer => $data)
                            <div class="mb-2">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $answer }}</span>
                                    <span class="text-sm font-medium">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
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
</div>
@endsection