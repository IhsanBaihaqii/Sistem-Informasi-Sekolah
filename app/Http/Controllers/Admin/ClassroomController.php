<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $classrooms = Classroom::with(['schoolYear', 'major', 'homeroomTeacher', 'room'])
            ->withCount('enrollments')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function show(Classroom $classroom)
    {
        $classroom->load([
            'schoolYear',
            'major',
            'homeroomTeacher',
            'room',
            'enrollments.student',
            'schedules.subject',
            'schedules.teacher',
        ]);

        return view('admin.classrooms.show', compact('classroom'));
    }
}
