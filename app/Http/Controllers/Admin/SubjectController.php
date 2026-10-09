<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::withCount('teachers')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.subjects.index', compact('subjects'));
    }
}
