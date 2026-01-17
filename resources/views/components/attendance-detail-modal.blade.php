<x-modal wire:model="showDetail">
    <div class="px-6 py-4">
        @if ($currentAttendance)
            @php
                $status = $currentAttendance['status'] ?? '-';
                $isExcused = in_array($status, ['excused', 'sick']);
                
                // Logika Format Jam Masuk
                $timeIn = '-';
                if (!empty($currentAttendance['time_in'])) {
                    try {
                        $timeIn = \Carbon\Carbon::parse($currentAttendance['time_in'])->format('H:i:s');
                    } catch (\Exception $e) {
                        $timeIn = $currentAttendance['time_in'];
                    }
                }

                // Logika Format Jam Pulang
                $timeOut = '-';
                if (!empty($currentAttendance['time_out'])) {
                    try {
                        $timeOut = \Carbon\Carbon::parse($currentAttendance['time_out'])->format('H:i:s');
                    } catch (\Exception $e) {
                        $timeOut = $currentAttendance['time_out'];
                    }
                }
            @endphp

            <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Detail Absensi: {{ $currentAttendance['name'] ?? 'Nama Tidak Diketahui' }}
            </div>

            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {{-- NIM --}}
                    <div>
                        <x-label for="nim" value="NIM" />
                        <x-input id="nim" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" 
                                 value="{{ $currentAttendance['nim'] ?? '-' }}" disabled />
                    </div>

                    {{-- TANGGAL --}}
                    <div>
                        <x-label for="date" value="Tanggal" />
                        <x-input id="date" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" 
                                 value="{{ $currentAttendance['date'] ?? '-' }}" disabled />
                    </div>

                    {{-- STATUS --}}
                    <div class="md:col-span-2">
                        <x-label for="status" value="Status Kehadiran" />
                        <x-input id="status" type="text" 
                                 class="mt-1 block w-full bg-gray-100 font-bold uppercase cursor-not-allowed {{ $status == 'sick' ? 'text-red-600' : ($status == 'excused' ? 'text-blue-600' : 'text-gray-800') }}" 
                                 value="{{ __($status) }}" disabled />
                    </div>

                    @if ($isExcused)
                        {{-- TAMPILAN JIKA IZIN / SAKIT --}}
                        <div class="md:col-span-2">
                            <x-label for="note" value="Keterangan (Note)" />
                            <textarea id="note" rows="4" disabled
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed resize-none"
                            >{{ $currentAttendance['note'] ?? '-' }}</textarea>
                        </div>

                        @if (!empty($currentAttendance['attachment']))
                            <div class="md:col-span-2 mt-2">
                                <x-label value="Lampiran" class="mb-2" />
                                <a href="{{ $currentAttendance['attachment'] }}" target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Lihat File Lampiran
                                </a>
                            </div>
                        @endif

                    @else
                        {{-- TAMPILAN JIKA HADIR / TERLAMBAT --}}
                        <div>
                            <x-label value="Jam Masuk" />
                            <x-input type="text" class="mt-1 block w-full bg-gray-100 font-bold cursor-not-allowed" 
                                     value="{{ $timeIn }}" disabled />
                        </div>
                        <div>
                            <x-label value="Jam Pulang" />
                            <x-input type="text" class="mt-1 block w-full bg-gray-100 font-bold cursor-not-allowed" 
                                     value="{{ $timeOut }}" disabled />
                        </div>
                    @endif

                </div>
            </div>
        @endif
    </div>
</x-modal>