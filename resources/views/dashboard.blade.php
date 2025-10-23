<x-app-layout>

    <div class="py-4 sm:py-6">
        <div class="w-full max-w-[98%] mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
            <!-- Hero Section -->
            <div class="mb-12 p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-brand-yellow to-yellow-200 shadow-lg">
                <div class="max-w-none">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-fade-in-up">Welcome back, {{ Auth::user()->first_name ?? 'User' }}!</h1>
                    <p class="text-gray-800 text-lg mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">Create, manage, and analyze your surveys with ease. Start building your next form today.</p>
                    <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                        <a href="{{ route('forms.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-semibold shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Form
                        </a>
                        <a href="{{ route('forms.index') }}" class="btn-secondary inline-flex items-center px-6 py-3 rounded-xl font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All Forms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 sm:gap-6 mb-8">
                <div class="bg-white overflow-hidden card-gradient-alt card-shadow rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-card-yellow-50 shadow-sm">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $forms->count() }}</h3>
                            <p class="text-gray-600">Forms</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden card-gradient-alt card-shadow rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-card-yellow-100 shadow-sm">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $forms->sum(function($form) { return $form->responses->count(); }) }}
                            </h3>
                            <p class="text-gray-600">Total Responses</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden card-gradient-alt card-shadow rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-gray-100 shadow-sm">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $forms->sum(function($form) { return $form->questions->count(); }) }}</h3>
                            <p class="text-gray-600">Total Questions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms Chart Section -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">{{ __('Responses by Month') }}</h3>
                    <a href="{{ route('forms.index') }}" class="text-sm text-brand hover:opacity-90 font-medium">{{ __('View All') }}</a>
                </div>


                @if($forms->count() == 0)
                    <div class="mt-6 bg-white border border-gray-200 rounded-xl card-shadow p-12 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No forms yet') }}</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ __('Create your first form to see response statistics here.') }}</p>
                        <a href="{{ route('forms.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-semibold shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Create Your First Form') }}
                        </a>
                    </div>
                @endif

                <div class="bg-white border border-gray-200 rounded-xl card-shadow p-6" style="height:320px;">
                    <canvas id="formsResponsesChart" class="w-full h-full"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const canvas = document.getElementById('formsResponsesChart');
                        if (!canvas) return;
                        const ctx = canvas.getContext('2d');

                        // Aggregate responses across all forms by month (YYYY-MM)
                        const labels = {!! json_encode($forms->flatMap(function($f){ return $f->responses; })->groupBy(function($r){ return $r->created_at->format('Y-m'); })->sortKeys()->keys()->values()) !!};
                        const data = {!! json_encode($forms->flatMap(function($f){ return $f->responses; })->groupBy(function($r){ return $r->created_at->format('Y-m'); })->sortKeys()->map(function($g){ return $g->count(); })->values()) !!};

                        // If there are no responses, show a friendly message instead of an empty chart
                        if (!labels || labels.length === 0 || (Array.isArray(data) && data.reduce((a,b)=>a+b,0) === 0)) {
                            canvas.parentElement.innerHTML = `
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    {{ __('No responses yet') }}
                                </div>
                            `;
                            return;
                        }

                        // Destroy previous chart instance if re-rendering (guard for Turbo/Livewire)
                        if (canvas._chartInstance) {
                            canvas._chartInstance.destroy();
                        }

                        // Create a vertical gradient for the filled area
                        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
                        gradient.addColorStop(0, 'rgba(250, 204, 21, 0.9)');
                        gradient.addColorStop(1, 'rgba(250, 204, 21, 0.12)');

                        canvas._chartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: '{{ __('Responses') }}',
                                    data: data,
                                    fill: true,
                                    backgroundColor: gradient,
                                    borderColor: 'rgba(250, 204, 21, 1)',
                                    borderWidth: 2,
                                    tension: 0.35,
                                    pointRadius: 4,
                                    pointBackgroundColor: 'rgba(250, 204, 21, 1)'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: { display: false }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: { precision: 0 }
                                    }
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { mode: 'index', intersect: false }
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
