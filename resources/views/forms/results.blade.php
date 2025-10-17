<x-app-layout>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }
        .results-fullscreen {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }
        .results-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .results-content {
            flex: 1;
            align-items: center;
            justify-content: center;
            padding: 0rem 1rem 4rem 1rem;
            overflow: auto;
        }
        .responses-box {
    max-height: 300px;
    overflow-y: auto;
}
        .results-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            overflow-y: auto;
            padding: 1rem;
        }
        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            max-height: 500px;
            overflow: auto;
        }
        .chart-card:hover {
            transform: translateY(-5px);
        }
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem;
            border-radius: 0.75rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>

    <div class="results-fullscreen">
        <div class="results-header">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $form->title }}</h1>
                    <p class="text-sm text-gray-600">Results Dashboard</p>
                </div>
                <a href="{{ route('forms.export', $form->id) }}" class="bg-brand hover:opacity-90 text-brand font-bold py-2 px-6 rounded-lg shadow-lg transition">
                    Export CSV
                </a>
            </div>
            
            <div class="stats-bar mt-4">
                <div class="stat-card">
                    <p class="text-3xl font-bold text-purple-600">{{ $totalResponses }}</p>
                    <p class="text-sm text-gray-600">Total Responses</p>
                </div>
                <div class="stat-card">
                    <p class="text-3xl font-bold text-purple-600">{{ $form->questions->count() }}</p>
                    <p class="text-sm text-gray-600">Questions</p>
                </div>
                <div class="stat-card">
                    <p class="text-xl font-bold text-purple-600">{{ $form->created_at->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600">Created</p>
                </div>
            </div>
        </div>

        <div class="results-content">
            <div class="results-grid">
                @foreach($questionStats as $questionId => $stats)
                    <div class="chart-card">
                        <h3 class="text-lg font-bold mb-3 text-gray-800">
                            {{ $stats['question']->question_text }}
                        </h3>
                        <span class="text-xs px-2 py-1 rounded bg-purple-100 text-purple-700 mb-3 inline-block">
                            {{ ucfirst(str_replace('_', ' ', $stats['question']->type)) }}
                        </span>

                        @if(in_array($stats['question']->type, ['radio', 'checkbox', 'dropdown']))
                            <div class="flex-1 flex items-center justify-center my-4">
                                <canvas id="chart-{{ $questionId }}" style="max-height: 220px; max-width: 220px;"></canvas>
                            </div>
                            
                            <div class="mt-4 space-y-2 max-h-32 overflow-y-auto">
                                @php
                                    $displayOptions = is_array($stats['question']->options) ? $stats['question']->options : array_keys($stats['answers']);
                                    $colors = ['#FFD500', '#FFA500', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DFE6E9', '#74B9FF', '#A29BFE'];
                                @endphp

                                @foreach($displayOptions as $index => $option)
                                    @php $data = $stats['answers'][$option] ?? ['count' => 0, 'percentage' => 0]; @endphp
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                                            <span class="text-gray-700 truncate">{{ $option }}</span>
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
                                                labels: {!! json_encode($displayOptions) !!},
                                                datasets: [{
                                                    data: [
                                                        @foreach($displayOptions as $option)
                                                            {{ $stats['answers'][$option]['count'] ?? 0 }},
                                                        @endforeach
                                                    ],
                                                    backgroundColor: {!! json_encode(array_slice($colors, 0, count($displayOptions))) !!},
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
                                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                                    <div class="text-sm font-semibold text-purple-900 mb-3">
                                        Responses ({{ $stats['total_answers'] }})
                                    </div>
                                    <div class="responses-box space-y-2 max-h-48 overflow-y-auto">
                                        @php $index = 1; @endphp
                                        @foreach($form->responses as $response)
                                            @foreach($response->responseAnswers as $answer)
                                                @if($answer->question_id == $stats['question']->id)
                                                    <div class="bg-white rounded p-3 border border-purple-100">
                                                        <div class="flex items-start">
                                                            <span class="bg-purple-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2 flex-shrink-0">{{ $index++ }}</span>
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
                                <div class="flex-1 flex items-center justify-center">
                                    <p class="text-gray-400 italic text-sm">No responses yet</p>
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