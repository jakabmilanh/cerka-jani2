<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassGroupController extends Controller
{
    public function index(Request $request)
    {
        $q = ClassGroup::where('teacher_id', Auth::id());
        if ($request->has('name')) {
            // pontos egyezés; ha részleges kell, használd ->where('name','like',"%{$request->name}%")
            $q->where('name', $request->query('name'));
        }
        return response()->json($q->get());
    }

    // POST /api/class-groups
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $data['teacher_id'] = Auth::id();
        $cg = ClassGroup::create($data);
        return response()->json($cg, 201);
    }

    // GET /api/class-groups/{id}/students
    public function students(ClassGroup $classGroup)
    {
        // feltételezzük, hogy ClassGroup modellben van:
        // public function students() { return $this->belongsToMany(User::class, 'class_group_student'); }
        return response()->json(
            $classGroup->students()->get()
        );
    }

    // POST /api/class-groups/{id}/students
    public function addStudent(Request $request, ClassGroup $classGroup)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        // syncWithoutDetaching, hogy ne töröljünk másokat
        $classGroup->students()->syncWithoutDetaching([$data['user_id']]);
        return response()->json(null, 204);
    }

    // DELETE /api/class-groups/{id}/students/{userId}
    public function removeStudent(ClassGroup $classGroup, $userId)
    {
        $classGroup->students()->detach($userId);
        return response()->json(null, 204);
    }




    public function indexForTeacher()
    {
        $groups = ClassGroup::where('teacher_id', Auth::id())->get();
        return response()->json($groups);
    }


    public function selfStudents(Request $request)
    {
        $user = Auth::user();
        // csak egy osztály van hozzárendelve
        $group = $user->classGroups->first();
        if (! $group) {
            return response()->json(['message' => 'Nincs hozzárendelt osztálycsoport'], 404);
        }
        // lekérdezzük a tagokat
        $students = $group->students()
            ->select('users.id','users.name','users.email')
            ->get();

        return response()->json($students);
    }
}
