<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardComponent extends Component
{
    use AttendanceDetailTrait;

    public function render()
    {
        /** @var Collection<Attendance>  */
        $attendances = Attendance::where('date', date('Y-m-d'))->get();

        /** @var Collection<User>  */
        $employees = User::where('group', 'user')
            ->paginate(20)
            ->through(function (User $user) use ($attendances) {
                return $user->setAttribute(
                    'attendance',
                    $attendances
                        ->where(fn (Attendance $attendance) => $attendance->user_id === $user->id)
                        ->first(),
                );
            });

        $employeesCount = User::where('group', 'user')->count();
        $presentCount = $attendances->where(fn ($attendance) => $attendance->status === 'present')->count();
        $lateCount = $attendances->where(fn ($attendance) => $attendance->status === 'late')->count();
        $excusedCount = $attendances->where(fn ($attendance) => $attendance->status === 'excused')->count();
        $sickCount = $attendances->where(fn ($attendance) => $attendance->status === 'sick')->count();
        $absentCount = $employeesCount - ($presentCount + $lateCount + $excusedCount + $sickCount);

        
        $days = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $statsData = Attendance::whereIn('date', $days)
            ->select('date', 'status', DB::raw('count(*) as total'))
            ->groupBy('date', 'status')
            ->get();

        $chartLabels = $days->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
        
        $chartHadir = $days->map(function($date) use ($statsData) {
            return $statsData->where('date', $date)->whereIn('status', ['present', 'late'])->sum('total');
        });

        $chartIzin = $days->map(function($date) use ($statsData) {
            return $statsData->where('date', $date)->where('status', 'excused')->sum('total');
        });

        $chartSakit = $days->map(function($date) use ($statsData) {
            return $statsData->where('date', $date)->where('status', 'sick')->sum('total');
        });

        return view('livewire.admin.dashboard', [
            'employees' => $employees,
            'employeesCount' => $employeesCount,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'excusedCount' => $excusedCount,
            'sickCount' => $sickCount,
            'absentCount' => $absentCount,
            // Variabel Chart
            'chartLabels' => $chartLabels,
            'chartHadir' => $chartHadir,
            'chartIzin' => $chartIzin,
            'chartSakit' => $chartSakit,
        ]);
    }
}