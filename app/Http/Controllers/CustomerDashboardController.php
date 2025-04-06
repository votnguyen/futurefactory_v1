<?php

// app/Http/Controllers/CustomerDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class CustomerDashboardController extends Controller
{
    public function dashboard()
    {
        $vehicles = auth()->user()->vehicles()
                        ->with(['modules' => function($query) {
                            $query->orderBy('assembly_order');
                        }])
                        ->orderBy('status')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('klant.dashboard', compact('vehicles'));
    }
}