<?php

namespace App\Http\Controllers;

use App\Models\Module; // Voeg deze namespace toe
use Illuminate\Http\Request;

class InkoperController extends Controller
{
    public function dashboard()
    {
        return view('inkoper.dashboard', [
            'modulesCount' => Module::count(),
            'latestModule' => Module::latest()->first(),
            'moduleTypesCount' => Module::select('type')->distinct()->count(),
            'recentModules' => Module::latest()->take(5)->get()
        ]);
    }
}