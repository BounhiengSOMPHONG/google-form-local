<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('แบบสอบถามของฉัน') }}
            </h2>
            <a href="{{ route('surveys.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('สร้างแบบสอบถามใหม่') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('แบบสอบถามล่าสุด') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($surveys as $survey)
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $survey->title }}</h4>
                                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($survey->description, 100) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            {{ __('สร้างเมื่อ') }} {{ $survey->created_at->diffForHumans() }}
                                        </span>
                                        <span class="px-2 py-1 text-xs {{ $survey->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full">
                                            {{ $survey->is_published ? __('เผยแพร่แล้ว') : __('ฉบับร่าง') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 border-t flex justify-end space-x-3">
                                    <a href="{{ route('surveys.edit', $survey) }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('แก้ไข') }}</a>
                                    <a href="{{ route('surveys.show', $survey) }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('ดูรายละเอียด') }}</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ไม่มีแบบสอบถาม') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('เริ่มต้นด้วยการสร้างแบบสอบถามใหม่') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
