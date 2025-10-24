<x-app-layout>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Allow vertical scrolling but prevent horizontal */
            min-height: 100vh;
        }
        .results-fullscreen {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        .results-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .results-content {
            flex: 1;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow: visible; /* Allow content to scroll naturally */
        }
        .responses-box {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 0.5rem;
        }
        .results-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
            max-width: 100vw;
        }
        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: var(--lifted-shadow);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            border: 1px solid rgba(255, 213, 0, 0.1);
        }
        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.25rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: var(--soft-shadow);
            border: 1px solid rgba(255, 213, 0, 0.1);
        }
        .stat-card .icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 0.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.2);
        }
    </style>

    <div class="results-fullscreen">
        <div class="results-header">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $form->title }}</h1>
                    <p class="text-sm text-gray-600">Results Dashboard</p>
                </div>
                <a href="{{ route('forms.export', $form->id) }}" class="btn-primary inline-flex items-center px-6 py-2 rounded-xl font-semibold shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
            
            <div class="stats-bar mt-4">
                <div class="stat-card">
                    <div class="icon bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalResponses }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Responses</p>
                </div>
                <div class="stat-card">
                    <div class="icon bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $form->questions->count() }}</p>
                    <p class="text-sm text-gray-600 mt-1">Questions</p>
                </div>
                <div class="stat-card">
                    <div class="icon bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-gray-900">{{ $form->created_at->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600 mt-1">Created</p>
                </div>
            </div>
        </div>

        <div class="results-content">
            <div class="results-grid">
                @foreach($questionStats as $questionId => $stats)
                    <div class="chart-card">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-gray-900 flex-1 pr-2">
                                {{ $stats['question']->question_text }}
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap">
                                {{ ucfirst(str_replace('_', ' ', $stats['question']->type)) }}
                            </span>
                        </div>

                        @if(in_array($stats['question']->type, ['radio', 'checkbox', 'dropdown']))
                            <div class="flex-1 flex items-center justify-center my-4">
                                <canvas id="chart-{{ $questionId }}" style="max-height: 220px; max-width: 220px;"></canvas>
                            </div>
                            
                            <div class="mt-4 space-y-2 max-h-32 overflow-y-auto">
                                @php
                                    $colors = ['#FFD500', '#FFA500', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DFE6E9', '#74B9FF', '#A29BFE'];

                                    // If controller provided display_options mapping (value => label), use it.
                                    // Otherwise, try to normalize question->options that may be in "label|value" form.
                                    if (!empty($stats['display_options']) && is_array($stats['display_options'])) {
                                        $optionKeys = array_keys($stats['display_options']); // stored values
                                        $optionLabels = array_values($stats['display_options']); // shown labels
                                    } elseif (is_array($stats['question']->options)) {
                                        $optionKeys = [];
                                        $optionLabels = [];
                                        foreach ($stats['question']->options as $opt) {
                                            if (is_string($opt) && strpos($opt, '|') !== false) {
                                                [$label, $value] = explode('|', $opt, 2);
                                            } else {
                                                $label = $opt;
                                                $value = $opt;
                                            }
                                            $optionKeys[] = $value;
                                            $optionLabels[] = $label;
                                        }
                                    } else {
                                        $optionKeys = array_keys($stats['answers']);
                                        $optionLabels = $optionKeys;
                                    }
                                @endphp

                                @foreach($optionKeys as $index => $key)
                                    @php $label = $optionLabels[$index] ?? $key; $data = $stats['answers'][$key] ?? ['count' => 0, 'percentage' => 0]; @endphp
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $colors[$index % count($colors)] }};"></span>
                                            <span class="text-gray-700 truncate">{{ $label }}</span>
                                        </div>
                                        <span class="font-semibold text-gray-800 ml-2 whitespace-nowrap">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                                    </div>
                                @endforeach
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctx = document.getElementById('chart-{{ $questionId }}');
                                    if (ctx) {
                                        new Chart(ctx, {
                                            type: 'pie',
                                            data: {
                                                labels: {!! json_encode($optionLabels ?? []) !!},
                                                datasets: [{
                                                    data: [
                                                        @foreach($optionKeys as $key)
                                                            {{ $stats['answers'][$key]['count'] ?? 0 }},
                                                        @endforeach
                                                    ],
                                                    backgroundColor: {!! json_encode(array_slice($colors, 0, count($optionKeys ?? []))) !!},
                                                    borderWidth: 3,
                                                    borderColor: '#ffffff'
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: true,
                                                plugins: {
                                                    legend: {
                                                        display: false
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                const label = context.label || '';
                                                                const value = context.parsed || 0;
                                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                                                return label + ': ' + value + ' (' + percentage + '%)';
                                                            }
                                                        }
                                                    },
                                                    datalabels: {
                                                        color: '#000',
                                                        font: {
                                                            weight: 'bold',
                                                            size: 14
                                                        },
                                                        formatter: function(value, context) {
                                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                                            return percentage > 5 ? percentage + '%' : '';
                                                        }
                                                    }
                                                }
                                            },
                                            plugins: [ChartDataLabels]
                                        });
                                    }
                                });
                            </script>
                        @else
                            <div class="flex-1 mt-4">
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                                    <div class="text-sm font-semibold text-yellow-900 mb-3">
                                        Responses ({{ $stats['total_answers'] }})
                                    </div>
                                    <div class="responses-box space-y-2 max-h-48 overflow-y-auto">
                                        @php $index = 1; @endphp
                                        @foreach($form->responses as $response)
                                            @foreach($response->responseAnswers as $answer)
                                                @if($answer->question_id == $stats['question']->id)
                                                    <div class="bg-white rounded-lg p-3 border border-yellow-100 shadow-sm">
                                                        <div class="flex items-start">
                                                            <span class="bg-yellow-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">{{ $index++ }}</span>
                                                            <span class="text-gray-800 text-sm break-words flex-1">
                                                                @if($stats['question']->type === 'checkbox')
                                                                    {{ implode(', ', json_decode($answer->answer, true) ?: []) }}
                                                                @else
                                                                    {{ $answer->answer }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            @if($stats['total_answers'] === 0)
                                <div class="flex-1 flex items-center justify-center py-8">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.291-1.1-5.291-2.709M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <p class="text-gray-500 italic">No responses yet</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add Chart.js library and DataLabels plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
</x-app-layout>