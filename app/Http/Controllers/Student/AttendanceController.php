<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $attendances = Attendance::where('student_id', $student->id)
            ->with(['attendanceSession.schedule.subject', 'attendanceSession.classroom'])
            ->latest('id')
            ->paginate(15);

        $summary = [
            'total' => Attendance::where('student_id', $student->id)->count(),
            'present' => Attendance::where('student_id', $student->id)->where('status', 'present')->count(),
            'sick' => Attendance::where('student_id', $student->id)->where('status', 'sick')->count(),
            'permit' => Attendance::where('student_id', $student->id)->where('status', 'permit')->count(),
            'absent' => Attendance::where('student_id', $student->id)->where('status', 'absent')->count(),
            'late' => Attendance::where('student_id', $student->id)->where('status', 'late')->count(),
        ];

        return view('student.attendance.index', compact('attendances', 'summary'));
    }
}
