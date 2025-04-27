<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        $classGroup = ClassGroup::where('teacher_id', $user->id)->first();
    
        return Inertia::render('Main', [
            'authUser'     => $user->fresh(),
            'classGroupId' => $classGroup?->id,
            'status'       => session('status'),
        ]);
    }
}
