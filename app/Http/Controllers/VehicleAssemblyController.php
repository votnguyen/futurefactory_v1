<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class VehicleAssemblyController extends Controller
{
    public function create()
    {
        // Groepeer modules per type zodat je ze apart kunt tonen (bv. chassis, aandrijving, enz.)
        $modules = Module::all()->groupBy('type');
    $customers = User::whereHas('roles', function($query) {
        $query->where('name', 'klant');
    })->get();

    return view('monteur.assembly.create', compact('modules', 'customers'));
}
    public function store(Request $request)
    {
        // Valideer de inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'required|exists:users,id',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id'
        ]);

        // Laad de geselecteerde modules
        $selectedModules = Module::whereIn('id', $validated['modules'])->get();

        // Extra validatie: check of de selectie voldoet aan eisen (zoals 1 chassis)
        $this->validateModuleCompatibility($selectedModules);

        // Bereken totalen
        $totalCost = $selectedModules->sum('cost');
        $totalTime = $selectedModules->sum('assembly_time');

        // Maak voertuig aan
        $vehicle = Vehicle::create([
            'name' => $validated['name'],
            'user_id' => $validated['customer_id'],
            'status' => 'concept',
            'total_cost' => $totalCost,
            'total_assembly_time' => $totalTime,
        ]);

        // Koppel modules in montagevolgorde (eenvoudig oplopend)
        $order = 1;
        foreach ($selectedModules as $module) {
            $vehicle->modules()->attach($module->id, ['assembly_order' => $order++]);
        }

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

    private function validateModuleCompatibility($modules)
    
    {

        $chassis = $modules->where('type', 'chassis')->first();
        $wheels = $modules->where('type', 'wielen');

          // Check wielcompatibiliteit
         foreach ($wheels as $wheel) {
        if (!in_array($chassis->id, $wheel->compatible_chassis)) {
            throw ValidationException::withMessages([
                'wielen' => 'Deze wielen zijn niet compatibel met het geselecteerde chassis'
            ]);
        }
    }
        // Controleer op exact één chassis
        if ($modules->where('type', 'chassis')->count() !== 1) {
            throw ValidationException::withMessages([
                'modules' => 'Selecteer precies één chassis.'
            ]);
        }

        // Controleer op minstens één aandrijving
        if ($modules->where('type', 'aandrijving')->count() < 1) {
            throw ValidationException::withMessages([
                'modules' => 'Selecteer minstens één aandrijving.'
            ]);
        }

        // Controleer of wielen compatibel zijn met het gekozen chassis
        $chassis = $modules->where('type', 'chassis')->first();
        $wheels = $modules->where('type', 'wielen');

        foreach ($wheels as $wheel) {
            $compatible = $wheel->specifications['compatible_chassis'] ?? [];
            if (!in_array($chassis->name, $compatible)) {
                throw ValidationException::withMessages([
                    'modules' => "Wielen '{$wheel->name}' zijn niet compatibel met chassis '{$chassis->name}'."
                ]);
            }
        }

        

        // Extra checks (optioneel):
        // - stuur aanwezig
        // - minimum aantal stoelen?
        // - validatie voor afmetingen, etc.

        
    }
}
