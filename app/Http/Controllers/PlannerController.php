<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlannerController extends Controller {
    public function dashboard() {
        return view('planner.dashboard');
    }
}
