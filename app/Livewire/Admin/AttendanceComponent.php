<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceComponent extends Component
{
    use AttendanceDetailTrait;
    use WithPagination, InteractsWithBanner;

    # filter
    public ?string $month;
    public ?string $week = null;
    public ?string $date = null;
    public ?string $division = null;
    public ?string $search = null;

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function updating($key): void
    {
        if ($key === 'search' || $key === 'division') {
            $this->resetPage();
        }
        if ($key === 'month') {
            $this->resetPage();
            $this->week = null;
            $this->date = null;
        }
        if ($key === 'week') {
            $this->resetPage();
            $this->month = null;
            $this->date = null;
        }
        if ($key === 'date') {
            $this->resetPage();
            $this->month = null;
            $this->week = null;
        }
    }

    public function render()
    {
        // LOGIKA PENENTUAN TANGGAL
        if ($this->date) {
            $dates = [Carbon::parse($this->date)];
        } else if ($this->week) {
            $start = Carbon::parse($this->week)->startOfWeek();
            $end = Carbon::parse($this->week)->endOfWeek();
            $dates = $start->range($end)->toArray();
        } else if ($this->month) {
            $start = Carbon::parse($this->month)->startOfMonth();
            $end = Carbon::parse($this->month)->endOfMonth();
            $dates = $start->range($end)->toArray();
        } else {
            $this->date = date('Y-m-d'); 
            $dates = [Carbon::parse($this->date)];
        }

        $employees = User::where('group', 'user')
            ->when($this->search, function (Builder $q) {
                return $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nip', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%');
            })
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->paginate(20)->through(function (User $user) {
                
                // PENGAMBILAN DATA ABSENSI
                if ($this->date) {
                    // Logika Harian (Termasuk Default Hari Ini)
                    $attendances = new Collection(Cache::remember(
                        "attendance-$user->id-$this->date",
                        now()->addDay(),
                        function () use ($user) {
                            $attendances = Attendance::filter(
                                userId: $user->id,
                                date: $this->date,
                            )->get();

                            return $this->formatAttendance($attendances);
                        }
                    ) ?? []);
                } else if ($this->week) {
                    // Logika Mingguan
                    $attendances = new Collection(Cache::remember(
                        "attendance-$user->id-$this->week",
                        now()->addDay(),
                        function () use ($user) {
                            $attendances = Attendance::filter(
                                userId: $user->id,
                                week: $this->week,
                            )->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note']);

                            return $this->formatAttendance($attendances);
                        }
                    ) ?? []);
                } else if ($this->month) {
                    // Logika Bulanan
                    $my = Carbon::parse($this->month);
                    $attendances = new Collection(Cache::remember(
                        "attendance-$user->id-$my->month-$my->year",
                        now()->addDay(),
                        function () use ($user) {
                            $attendances = Attendance::filter(
                                month: $this->month,
                                userId: $user->id,
                            )->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note']);

                            return $this->formatAttendance($attendances);
                        }
                    ) ?? []);
                } else {
                    // Fallback aman
                    $attendances = collect([]);
                }
                $user->attendances = $attendances;
                return $user;
            });
            
        return view('livewire.admin.attendance', ['employees' => $employees, 'dates' => $dates]);
    }

    private function formatAttendance($attendances)
    {
        return $attendances->map(
            function (Attendance $v) {
                $v->setAttribute('coordinates', $v->lat_lng);
                $v->setAttribute('lat', $v->latitude);
                $v->setAttribute('lng', $v->longitude);
                if ($v->attachment) {
                    $v->setAttribute('attachment', $v->attachment_url);
                }
                return $v->getAttributes();
            }
        )->toArray();
    }
}