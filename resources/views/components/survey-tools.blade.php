<div class="fixed right-4 flex flex-col space-y-4" style="top: 84px;">
    <!-- Settings Modal -->
    <div x-data="{ isOpen: false }" @keydown.escape.window="isOpen = false">
        <!-- Settings Button -->
        <button 
            type="button"
            @click="isOpen = true"
            class="p-2 rounded-full bg-white shadow-md hover:bg-gray-50 focus:outline-none group relative"
            title="การตั้งค่า"
        >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <!-- Tooltip -->
            <div class="invisible group-hover:visible absolute right-full mr-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                การตั้งค่า
            </div>
        </button>

        <!-- Modal -->
        <div
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black opacity-30"></div>

            <!-- Modal content -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">การตั้งค่าแบบสอบถาม</h3>
                        <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">ปิด</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center space-x-3">
                            <input 
                                type="checkbox"
                                x-on:change="document.getElementById('allow_multiple_responses').value = $event.target.checked ? '1' : '0'"
                                :checked="document.getElementById('allow_multiple_responses').value === '1'"
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                            />
                            <span class="text-gray-700">อนุญาตให้ตอบได้หลายครั้ง</span>
                        </label>

                        <label class="flex items-center space-x-3">
                            <input 
                                type="checkbox"
                                x-on:change="document.getElementById('require_login').value = $event.target.checked ? '1' : '0'"
                                :checked="document.getElementById('require_login').value === '1'"
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                            />
                            <span class="text-gray-700">ต้องเข้าสู่ระบบก่อนตอบแบบสอบถาม</span>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button
                            type="button"
                            @click="isOpen = false"
                            class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                        >
                            เสร็จสิ้น
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Question Button -->
    <button 
        class="p-2 rounded-full bg-white shadow-md hover:bg-gray-50 focus:outline-none group relative" 
        title="เพิ่มคำถาม"
    >
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <!-- Tooltip -->
        <div class="invisible group-hover:visible absolute right-full mr-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
            เพิ่มคำถาม
        </div>
    </button>

    <!-- Add Image Button -->
    <button 
        class="p-2 rounded-full bg-white shadow-md hover:bg-gray-50 focus:outline-none group relative" 
        title="เพิ่มรูปภาพ"
    >
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <!-- Tooltip -->
        <div class="invisible group-hover:visible absolute right-full mr-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
            เพิ่มรูปภาพ
        </div>
    </button>

    <!-- Add Text Button -->
    <button 
        class="p-2 rounded-full bg-white shadow-md hover:bg-gray-50 focus:outline-none group relative" 
        title="เพิ่มข้อความ"
    >
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
        </svg>
        <!-- Tooltip -->
        <div class="invisible group-hover:visible absolute right-full mr-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
            เพิ่มข้อความ
        </div>
    </button>

    <!-- Format Button -->
    <button 
        class="p-2 rounded-full bg-white shadow-md hover:bg-gray-50 focus:outline-none group relative" 
        title="จัดรูปแบบ"
    >
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
        </svg>
        <!-- Tooltip -->
        <div class="invisible group-hover:visible absolute right-full mr-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
            จัดรูปแบบ
        </div>
    </button>

    <!-- Help Button -->
    <button 
        class="p-2 rounded-full bg-white shadow-md hover:bg-gray-50 focus:outline-none group relative" 
        title="ช่วยเหลือ"
    >
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <!-- Tooltip -->
        <div class="invisible group-hover:visible absolute right-full mr-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
            ช่วยเหลือ
        </div>
    </button>
</div>