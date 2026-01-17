@push('styles')
<style>
    body {
        background-image: url('{{ asset('image/background.jpg') }}') !important;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
    }

    .min-h-screen.bg-gray-100, 
    .min-h-screen.dark\:bg-gray-900 {
        background-color: rgba(255, 255, 255, 0.5) !important; /* Putih Transparan (Glass) */
        backdrop-filter: blur(5px); /* Efek Blur */
    }
</style>
@endpush

@php
  $date = Carbon\Carbon::now();
@endphp
<div>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce

  <div class="flex flex-col justify-between sm:flex-row">
    <h3 class="mb-4 text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Absensi Hari Ini
    </h3>
    <h3 class="mb-4 text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Jumlah Magang: {{ $employeesCount }}
    </h3>
  </div>

  {{-- Statistik Ringkasan Harian --}}
  <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
    <div class="rounded-md bg-green-200 px-8 py-4 text-gray-800 dark:bg-green-900 dark:text-white dark:shadow-gray-700">
      <span class="text-2xl font-semibold md:text-3xl">Hadir: {{ $presentCount }}</span><br>
      <span>Terlambat: {{ $lateCount }}</span>
    </div>
    <div class="rounded-md bg-blue-200 px-8 py-4 text-gray-800 dark:bg-blue-900 dark:text-white dark:shadow-gray-700">
      <span class="text-2xl font-semibold md:text-3xl">Izin: {{ $excusedCount }}</span><br>
      <span>Izin/Cuti</span>
    </div>
    <div class="rounded-md bg-purple-200 px-8 py-4 text-gray-800 dark:bg-purple-900 dark:text-white dark:shadow-gray-700">
      <span class="text-2xl font-semibold md:text-3xl">Sakit: {{ $sickCount }}</span>
    </div>
    <div class="rounded-md bg-red-200 px-8 py-4 text-gray-800 dark:bg-red-900 dark:text-white dark:shadow-gray-700">
      <span class="text-2xl font-semibold md:text-3xl">Tidak Hadir: {{ $absentCount }}</span><br>
      <span>Tidak/Belum Hadir</span>
    </div>
  </div>

  {{-- Tabel Absensi Harian --}}
  <div class="mb-4 overflow-x-scroll">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Nama') }}
          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('NIM') }}
          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Divisi') }}
          </th>
          <th scope="col" class="text-nowrap border border-gray-300 px-1 py-3 text-center text-xs font-medium text-gray-500 dark:border-gray-600 dark:text-gray-300">
            Status
          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Waktu Masuk') }}
          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Waktu Pulang') }}
          </th>
          <th scope="col" class="relative">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @php
          $class = 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white';
        @endphp
        @foreach ($employees as $employee)
          @php
            $attendance = $employee->attendance;
            $timeIn = $attendance ? $attendance?->time_in?->format('H:i:s') : null;
            $timeOut = $attendance ? $attendance?->time_out?->format('H:i:s') : null;
            $isWeekend = $date->isWeekend();
            $status = ($attendance ?? [
                'status' => $isWeekend || !$date->isPast() ? '-' : 'absent',
            ])['status'];
          @endphp
          <tr wire:key="{{ $employee->id }}" class="group">
            <td class="{{ $class }} text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">{{ $employee->name }}</td>
            <td class="{{ $class }} group-hover:bg-gray-100 dark:group-hover:bg-gray-700">{{ $employee->nim }}</td>
            <td class="{{ $class }} text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">{{ $employee->division?->name ?? '-' }}</td>
            
            <td class="text-nowrap px-1 py-3 text-center text-sm font-medium text-gray-900 dark:text-white">{{ __($status) }}</td>
            <td class="{{ $class }} group-hover:bg-gray-100 dark:group-hover:bg-gray-700">{{ $timeIn ?? '-' }}</td>
            <td class="{{ $class }} group-hover:bg-gray-100 dark:group-hover:bg-gray-700">{{ $timeOut ?? '-' }}</td>
            <td class="cursor-pointer text-center text-sm font-medium text-gray-900 group-hover:bg-gray-100 dark:text-white dark:group-hover:bg-gray-700">
              <div class="flex items-center justify-center gap-3">
                @if ($attendance && ($attendance->attachment || $attendance->note || $attendance->lat_lng))
                  <x-button type="button" wire:click="show({{ $attendance->id }})"
                    onclick="setLocation({{ $attendance->latitude ?? 0 }}, {{ $attendance->longitude ?? 0 }})">
                    {{ __('Detail') }}
                  </x-button>
                @else
                  -
                @endif
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $employees->links() }}

  <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-100 dark:border-gray-700">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide italic">
            Grafik Tren Kehadiran Magang (7 Hari Terakhir)
        </h3>
        <div class="flex gap-4 text-xs font-semibold">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span> Hadir</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Izin</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Sakit</span>
        </div>
    </div>
    <div class="relative w-full" style="height: 350px;">
        <canvas id="attendanceLineChart"></canvas>
    </div>
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('livewire:initialized', () => {
        const ctx = document.getElementById('attendanceLineChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Hadir',
                            data: @json($chartHadir),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 4,
                            pointBackgroundColor: '#10b981',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Izin',
                            data: @json($chartIzin),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 4,
                            pointBackgroundColor: '#3b82f6',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Sakit',
                            data: @json($chartSakit),
                            borderColor: '#a855f7',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 4,
                            pointBackgroundColor: '#a855f7',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: 'rgba(156, 163, 175, 0.1)' }
                        },
                        x: { 
                            ticks: { color: '#9ca3af' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
  </script>
  @endpush
</div>