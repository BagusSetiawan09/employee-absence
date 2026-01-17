<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-600 to-indigo-900">
    <div class="z-10">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-gray-800 shadow-2xl overflow-hidden border border-white/20 backdrop-blur-sm" 
         style="border-radius: 20px !important;">
        
        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang</h2>
            <p class="text-sm text-gray-500">Silakan login untuk akses sistem absensi</p>
        </div>
        
        {{ $slot }}
    </div>
</div>