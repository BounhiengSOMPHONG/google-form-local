<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('สร้างแบบสอบถามใหม่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('surveys.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('ชื่อแบบสอบถาม')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('คำอธิบาย')" />
                            <textarea id="description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="description" rows="3">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="allow_multiple_responses" value="1" {{ old('allow_multiple_responses') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('อนุญาตให้ตอบได้หลายครั้ง') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="require_login" value="1" {{ old('require_login') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('ต้องเข้าสู่ระบบก่อนตอบแบบสอบถาม') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('บันทึก') }}</x-primary-button>
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">{{ __('ยกเลิก') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>