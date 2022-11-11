<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PoliticController extends Controller
{
    public function index()
    {
        return Inertia::render('ControlInterno/PoliticsIndex');
    }
}
