<?php

namespace App\Http\Controllers;

use App\Models\Event;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NaptarController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        // Példa: assume 'day' oszlopban van a dátum (YYYY-MM-DD formátumban)
        $query = Event::query();

        if ($start && $end) {
            $query->whereBetween('day', [$start, $end]);
        }

        $events = $query->get();
        return response()->json($events);
    }

    public function destroy($id)
    {
        // opcionálisan ellenőrizheted, hogy az esemény valóban a userhez tartozik:
        $event = Event::where('user_id', Auth::id())
            ->findOrFail($id);

        $event->delete();

        return response()->json([
            'message' => 'Esemény sikeresen törölve.',
        ]);
    }
}
