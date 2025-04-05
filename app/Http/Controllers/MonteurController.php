<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class MonteurController extends Controller {
    public function dashboard() {
        $recentVehicles = Vehicle::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('monteur.dashboard', compact('recentVehicles'));
    }
    
}
