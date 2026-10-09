<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $classroomIds = DB::table('class_enrollments')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('classroom_id');

        $schedules = Schedule::whereIn('classroom_id', $classroomIds)
            ->where('is_active', true)
            ->with(['classroom', 'subject', 'teacher', 'room'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return view('student.schedule.index', compact('schedules', 'days'));
    }
}
