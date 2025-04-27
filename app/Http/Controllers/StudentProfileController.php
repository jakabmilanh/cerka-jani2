<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentProfileController extends Controller
{
    public function edit()
    {
        return Inertia::render('Profile/EditStudent', [
            'user' => Auth::user(), // visszaadjuk a bejelentkezett diák adatait
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
            'student_card' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $user->update([
            'address' => $request->address,
            'birth' => $request->birth,
            'contact' => $request->contact,
            'id_card' => $request->id_card,
            'student_card' => $request->student_card,
        ]);

        return redirect()->back()->with('status', 'Diák adatok frissítve!');
    }
}
