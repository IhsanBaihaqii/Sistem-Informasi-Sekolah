<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use App\Services\AttendanceService;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $schedule = Schedule::firstOrFail();

        $service = app(AttendanceService::class);

        $session = AttendanceSession::firstOrCreate(
            [
                'schedule_id' => $schedule->id,
                'attendance_date' => '2026-10-08',
            ],
            [
                'school_year_id' => $schedule->school_year_id,
                'semester_id' => $schedule->semester_id,
                'classroom_id' => $schedule->classroom_id,
                'teacher_id' => $schedule->teacher_id,
                'opened_at' => '08:00:00',
                'status' => 'open',
            ]
        );

        $service->generateStudentAttendances($session);

        $firstAttendance = $session
            ->attendances()
            ->orderBy('id')
            ->first();

        if ($firstAttendance) {
            $firstAttendance->update([
                'status' => 'present',
                'check_in' => '07:55:00',
            ]);
        }
    }
}
