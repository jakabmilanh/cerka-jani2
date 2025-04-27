<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentScheduleController extends Controller
{
    public function show()
    {
        return Inertia::render('StudentSchedule');
    }
}
