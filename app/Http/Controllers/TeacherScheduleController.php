<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherScheduleController extends Controller
{
    public function show(Request $request)
    {
        // Tegyük fel, hogy egy tanár pontosan egy classGroup‑hoz tartozik:
        $classGroup = $request->user()->classGroups()->first();

        return Inertia::render('TeacherSchedule', [
            'classGroupId' => $classGroup ? $classGroup->id : null
        ]);
    }

    public function index()
    {
        $subjects = Subject::select('id', 'name')->get();
        return response()->json($subjects);
    }
}
