<?php
// app/Http/Controllers/CustomerDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class CustomerDashboardController extends Controller
{
    public function dashboard()
    {
        // Haal alle voertuigen op voor de ingelogde klant
        $vehicles = Vehicle::with(['modules' => function($query) {
                        $query->orderBy('assembly_order');
                    }])
                    ->where('user_id', auth()->id())
                    ->orderBy('status')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('klant.dashboard', compact('vehicles'));
    }
}