<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleStatusController extends Controller
{
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'status' => 'required|in:in_productie,gereed_voor_levering,geleverd'
        ]);

        $vehicle->update(['status' => $request->status]);

        return back()->with('success', 'Status succesvol bijgewerkt!');
    }
}
