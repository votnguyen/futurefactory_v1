<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KlantController extends Controller {
    public function dashboard() {
        return view('klant.dashboard');
    }
}