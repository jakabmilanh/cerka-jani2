<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->events;
        return response()->json($events, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'endTime' => 'required|date_format:H:i',
                'title' => 'required|string|max:255',
            ]);

            Event::create([
                'day' => $request->date,
                'time' => $request->time,
                'end_time' => $request->endTime,
                'title' => $request->title,
                'user_id' => Auth::user()->id
            ]);

        } catch (ValidationException $th) {
            return response()->json(['success' => false, 'message' => $th->errors()], 201, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['success' => true, 'message' => 'Event successfully added!'], 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }
}