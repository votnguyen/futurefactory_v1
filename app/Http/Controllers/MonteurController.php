<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonteurController extends Controller {
    public function dashboard() {
        return view('monteur.dashboard');
    }
}