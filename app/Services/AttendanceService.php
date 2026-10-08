<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function createSession(
        int $scheduleId,
        string $attendanceDate
    ): AttendanceSession {
        $schedule = \App\Models\Schedule::with([
            'schoolYear',
            'semester',
            'classroom',
            'teacher',
        ])->findOrFail($scheduleId);

        $existing = AttendanceSession::where(
            'schedule_id',
            $scheduleId
        )
            ->where(
                'attendance_date',
                $attendanceDate
            )
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'attendance_date' =>
                'Sesi absensi untuk jadwal dan tanggal tersebut sudah ada.',
            ]);
        }

        return AttendanceSession::create([
            'school_year_id' => $schedule->school_year_id,
            'semester_id' => $schedule->semester_id,
            'schedule_id' => $schedule->id,
            'classroom_id' => $schedule->classroom_id,
            'teacher_id' => $schedule->teacher_id,
            'attendance_date' => $attendanceDate,
            'opened_at' => now()->format('H:i:s'),
            'status' => 'open',
        ]);
    }

    public function generateStudentAttendances(
        AttendanceSession $session
    ): void {
        $students = $session->classroom
            ->enrollments()
            ->where('semester_id', $session->semester_id)
            ->where('status', 'active')
            ->with('student')
            ->get();

        DB::transaction(function () use ($session, $students) {
            foreach ($students as $enrollment) {
                Attendance::firstOrCreate(
                    [
                        'attendance_session_id' => $session->id,
                        'student_id' => $enrollment->student_id,
                    ],
                    [
                        'status' => 'absent',
                    ]
                );
            }
        });
    }
}
