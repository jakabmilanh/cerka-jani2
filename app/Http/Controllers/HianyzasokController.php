<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hianyzasok;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HianyzasokController extends Controller
{
    public function index()
    {
        return Inertia::render("hianyzasok");
    }
}
