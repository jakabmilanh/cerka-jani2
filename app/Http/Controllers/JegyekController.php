<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Inertia\Inertia;
use Illuminate\Http\Request;

class JegyekController extends Controller
{
    public function index()
    {
        return Inertia::render("jegyek");      
    }
}
