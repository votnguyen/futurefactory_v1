<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleAssemblyController extends Controller
{
    public function create()
    {
        $modules = Module::all()->groupBy('type');
        return view('monteur.assembly.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id',
            'customer_id' => 'required|exists:users,id'
        ]);

        // Maak het voertuig aan
        $vehicle = Vehicle::create([
            'name' => $validated['name'],
            'user_id' => $validated['customer_id'],
            'status' => 'concept'
        ]);

        // Koppel modules met montagevolgorde
        $order = 1;
        foreach ($validated['modules'] as $moduleId) {
            $vehicle->modules()->attach($moduleId, ['assembly_order' => $order++]);
        }

        // Bereken totalen
        $vehicle->calculateTotals();

        return redirect()->route('monteur.assembly.show', $vehicle)
                        ->with('success', 'Voertuig succesvol samengesteld!');
    }

    public function show(Vehicle $vehicle)
    {
        return view('monteur.assembly.show', compact('vehicle'));
    }

    public function index()
    {
        $vehicles = Vehicle::where('status', 'concept')->get();
        return view('monteur.assembly.index', compact('vehicles'));
    }
}