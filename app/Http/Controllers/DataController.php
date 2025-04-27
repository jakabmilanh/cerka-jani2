<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function index()
    {
        return Inertia::render("profile");

    }

    public function indexUser()
    {
        // Auth::id() alapján mindig User modellt kapunk vissza
        $user = User::findOrFail(Auth::id());

        // csak a szükséges kulcsokat vesszük ki
        $data = Arr::only($user->toArray(), [
            'id',
            'name',
            'email',
            'email_verified_at',
            'role_id',
            'birth',
            'contact',
            'student_card',
            'id_card',
            'created_at',
            'updated_at',
        ]);

        return response()->json($data);
    }

    /**
     * Frissíti a user profiljának négy mezőjét.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'birth'        => 'nullable|date',
            'contact'      => 'nullable|string|max:255',
            'student_card' => 'nullable|string|max:50',
            'id_card'      => 'nullable|string|max:50',
        ]);

        // Ezzel most tuti User modellt kapunk vissza, nem csak az Authenticatable interfészt
        $user = User::findOrFail(Auth::id());

        // kitöltjük a mezőket
        $user->fill($validated);
        $user->save();

        // csak az utolsó négy mezőt küldjük vissza, plusz timestamp
        $updated = Arr::only($user->toArray(), [
            'birth',
            'contact',
            'student_card',
            'id_card',
            'updated_at',
        ]);

        return response()->json([
            'message' => 'Profil sikeresen frissítve',
            'user'    => $updated,
        ]);
    }

    
}
