<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::where('role_id', 1);

        if ($request->filled('query')) {
            $term = $request->query('query');
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        // csak a szükséges mezőket adjuk vissza
        $students = $q->get(['id', 'name', 'email']);

        return response()->json($students);
    }
}
