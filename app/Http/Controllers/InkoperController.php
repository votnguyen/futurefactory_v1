<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InkoperController extends Controller {
    public function dashboard() {
        return view('inkoper.dashboard');
    }
}