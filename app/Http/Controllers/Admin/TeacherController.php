<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'ilike', "%{$search}%")
                    ->orWhere('nip', 'ilike', "%{$search}%")
                    ->orWhere('nuptk', 'ilike', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('full_name')->paginate(15)->withQueryString();
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::where('status', 'active')->count();

        return view('admin.teachers.index', compact('teachers', 'totalTeachers', 'activeTeachers'));
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['subjects', 'schedules.classroom']);
        return view('admin.teachers.show', compact('teacher'));
    }
}
