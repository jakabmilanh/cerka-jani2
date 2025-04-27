<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Teacher;

class TeacherProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        return Inertia::render('Profile/EditTeacher', [
            'user' => [
                'address' => $teacher->address ?? '',
                'birth' => $teacher->birth ?? '',
                'contact' => $teacher->contact ?? '',
                'id_card' => $teacher->id_card ?? '',
            ],
            'status' => session('status')
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'birth' => 'required|date',
            'contact' => 'required|string|max:255',
            'id_card' => 'required|string|max:255',
        ]);
    
        $user = Auth::user();
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            []
        );
    
        // Frissítjük a user táblát is
        $user->update([
            'address' => $request->address,
            'birth' => $request->birth,
            'contact' => $request->contact,
            'id_card' => $request->id_card,
        ]);
    
        // Frissítjük a teacher táblát is
        $teacher->update([
            'address' => $request->address,
            'birth' => $request->birth,
            'contact' => $request->contact,
            'id_card' => $request->id_card,
        ]);
    
        return redirect()->back()->with('status', 'Tanári adatok frissítve!');
    }
}