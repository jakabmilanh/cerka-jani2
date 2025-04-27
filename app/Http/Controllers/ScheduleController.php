<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    // GET /class-groups/{classGroup}/schedules?start=YYYY-MM-DD&end=YYYY-MM-DD
    public function index($classGroup, Request $request)
    {
        $data = $request->validate([
            'start' => 'required|date',  // az aktuális hétfő
            'end'   => 'required|date',  // az aktuális vasárnap
        ]);

        // 1) Parse-oljuk a hétfő dátumát
        $startWeek = Carbon::parse($data['start']);

        // 2) Beolvassuk az összes mentett órarend-bejegyzést (sablonként)
        $all = Schedule::with('subject')
            ->where('class_group_id', $classGroup)
            ->where('teacher_id', Auth::id())
            ->get();

        // 3) Átszámoljuk őket az aktuális hét napjaira
        $list = $all->map(function ($sched) use ($startWeek) {
            $origDate = Carbon::parse($sched->date);
            $offset   = $origDate->dayOfWeekIso - 1;
            $newDate  = $startWeek->copy()->addDays($offset);

            return [
                'date'    => $newDate->format('Y-m-d'),
                'time'    => $sched->time,       // <<< itt nincs ->format()
                'subject' => $sched->subject,
            ];
        })
            ->filter(
                fn($ev) =>
                Carbon::parse($ev['date'])->between($data['start'], $data['end'])
            )
            ->sortBy('time')
            ->values();

        return response()->json($list);
    }

    // POST /class-groups/{classGroup}/schedules
    public function store($classGroup, Request $request)
    {
        $data = $request->validate([
            'date'       => 'required|date',
            'time'       => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $data['class_group_id'] = $classGroup;
        $data['teacher_id']     = Auth::id();

        $sched = Schedule::create($data);

        return response()->json($sched, 201);
    }




    public function selfSchedule(Request $request)
    {
        // 1) Lekérjük a diák osztálycsoportját
        $group = Auth::user()->classGroups->first();
        if (! $group) {
            return response()->json(['message' => 'Nincs hozzárendelt osztályod'], 404);
        }

        $data = $request->validate([
            'start' => 'required|date',  // az aktuális hétfő
            'end'   => 'required|date',  // az aktuális vasárnap
        ]);

        // 1) Parse-oljuk a hétfő dátumát
        $startWeek = Carbon::parse($data['start']);

        // 2) Beolvassuk az összes mentett órarend-bejegyzést (sablonként)
        $all = Schedule::with('subject')
            ->where('class_group_id', $group->id)
            ->get();

        // 3) Átszámoljuk őket az aktuális hét napjaira
        $list = $all->map(function ($sched) use ($startWeek) {
            $origDate = Carbon::parse($sched->date);
            $offset   = $origDate->dayOfWeekIso - 1;
            $newDate  = $startWeek->copy()->addDays($offset);

            return [
                'date'    => $newDate->format('Y-m-d'),
                'time'    => $sched->time,       // <<< itt nincs ->format()
                'subject' => $sched->subject,
            ];
        })
            ->filter(
                fn($ev) =>
                Carbon::parse($ev['date'])->between($data['start'], $data['end'])
            )
            ->sortBy('time')
            ->values();

        return response()->json($list);
    }
}
