<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403, 'Profil guru tidak ditemukan.');

        $sessions = AttendanceSession::where('teacher_id', $teacher->id)
            ->with(['classroom', 'schedule.subject'])
            ->withCount('attendances')
            ->latest('attendance_date')
            ->paginate(15);

        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->with(['classroom', 'subject'])
            ->get();

        return view('teacher.attendance.index', compact('sessions', 'schedules'));
    }

    public function store(Request $request, AttendanceService $attendanceService)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $data = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'attendance_date' => ['required', 'date'],
        ]);

        $schedule = Schedule::where('id', $data['schedule_id'])
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $session = $attendanceService->createSession($schedule->id, $data['attendance_date']);
        $attendanceService->generateStudentAttendances($session);

        return redirect()->route('teacher.attendance.show', $session)
            ->with('success', 'Sesi presensi berhasil dibuka.');
    }

    public function show(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher && $session->teacher_id === $teacher->id, 403);

        $session->load([
            'classroom',
            'schedule.subject',
            'attendances.student',
        ]);

        return view('teacher.attendance.show', compact('session'));
    }

    public function update(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher && $session->teacher_id === $teacher->id, 403);

        $data = $request->validate([
            'attendances' => ['required', 'array'],
            'attendances.*.status' => ['required', 'in:present,absent,sick,permit,late'],
            'attendances.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($session, $data) {
            foreach ($data['attendances'] as $attendanceId => $item) {
                Attendance::where('id', $attendanceId)
                    ->where('attendance_session_id', $session->id)
                    ->update([
                        'status' => $item['status'],
                        'notes' => $item['notes'] ?? null,
                    ]);
            }
        });

        return back()->with('success', 'Data presensi siswa berhasil disimpan.');
    }
}
