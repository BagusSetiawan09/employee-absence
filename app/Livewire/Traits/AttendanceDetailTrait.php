<?php

namespace App\Livewire\Traits;

use App\Models\Attendance;

trait AttendanceDetailTrait
{
    public bool $showDetail = false;
    public $currentAttendance = [];

    public function show($attendanceId)
    {
        $attendance = Attendance::with('user')->find($attendanceId);
        
        if ($attendance) {
            $this->showDetail = true;
            $this->currentAttendance = [
                'name' => $attendance->user->name ?? '-',
                'nim' => $attendance->user->nim ?? ($attendance->user->nip ?? '-'),
                'date' => $attendance->date ? (is_string($attendance->date) ? $attendance->date : $attendance->date->format('Y-m-d')) : '-',
                'status' => $attendance->status,
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out,
                'latitude' => $attendance->latitude,
                'longitude' => $attendance->longitude,
                'note' => $attendance->note,
                'attachment' => $attendance->attachment ? $attendance->attachment_url : null,
            ];
        }
    }
}