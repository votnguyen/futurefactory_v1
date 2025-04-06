<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VehicleAssemblyController extends Controller
{
    public function create()
    {
        // Haal alle modules op en groepeer ze per type (bv. chassis, aandrijving, etc.)
        $modules = Module::all()->groupBy('type');
        
        // Haal de klanten op die de rol 'klant' hebben
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'klant');
        })->get();

        return view('monteur.assembly.create', compact('modules', 'customers'));
    }

    public function store(Request $request)
    {
        // Valideer de vereiste invoervelden en individuele module velden
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'customer_id'  => 'required|exists:users,id',
            'chassis'      => 'required|exists:modules,id',
            'aandrijving'  => 'required|exists:modules,id',
            'wielen'       => 'required|exists:modules,id',
            'stuur'        => 'required|exists:modules,id',
            'stoelen'      => 'nullable|exists:modules,id',
        ]);

        // Combineer de module IDs in een array (filter eventuele null-waarden eruit)
        $moduleIds = array_filter([
            $validated['chassis'],
            $validated['aandrijving'],
            $validated['wielen'],
            $validated['stuur'],
            $validated['stoelen'] ?? null,
        ]);

        // Haal de geselecteerde modules op
        $selectedModules = Module::findMany($moduleIds);

        // Extra validatie: controleer of de vereiste modules aanwezig zijn en compatibel zijn
        $this->validateModuleCompatibility($selectedModules);

        // Bereken de totalen voor kosten en assembly time
        $totalCost = $selectedModules->sum('cost');
        $totalTime = $selectedModules->sum('assembly_time');

        // Maak het voertuig aan in de database
        $vehicle = Vehicle::create([
            'name'                  => $validated['name'],
            'user_id'               => $validated['customer_id'],
            'status'                => 'concept',
            'total_cost'            => $totalCost,
            'total_assembly_time'   => $totalTime,
        ]);

        // Koppel de modules aan het voertuig in oplopende montagevolgorde
        $order = 1;
        foreach ($selectedModules as $module) {
            $vehicle->modules()->attach($module->id, ['assembly_order' => $order++]);
        }

        // Plan het voertuig voor montage
        $this->planVehicleAssembly($vehicle, $selectedModules);

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

    /**
     * Controleer of de geselecteerde modules aan de vereiste criteria voldoen.
     *
     * @param \Illuminate\Support\Collection $modules
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateModuleCompatibility($modules)
    {
        // Controleer of alle verplichte componenten aanwezig zijn
        $requiredTypes = ['chassis', 'aandrijving', 'wielen', 'stuur'];
        foreach ($requiredTypes as $type) {
            if ($modules->where('type', $type)->count() < 1) {
                throw ValidationException::withMessages([
                    $type => "Selecteer een $type."
                ]);
            }
        }

        // Controleer de compatibiliteit van wielen met het gekozen chassis
        $chassis = $modules->where('type', 'chassis')->first();
        $wheels = $modules->where('type', 'wielen');

        foreach ($wheels as $wheel) {
            $compatibleChassis = $wheel->specifications['geschikt_voor'] ?? [];
            if (!in_array($chassis->name, $compatibleChassis)) {
                throw ValidationException::withMessages([
                    'wielen' => "Wielen '{$wheel->name}' passen niet bij chassis '{$chassis->name}'."
                ]);
            }
        }
    }

    /**
     * Plan het voertuig in de planner met tijdsloten voor de modules.
     *
     * @param \App\Models\Vehicle $vehicle
     * @param \Illuminate\Support\Collection $modules
     * @return void
     */
    private function planVehicleAssembly(Vehicle $vehicle, $modules)
    {
        // Automatische planning uitgeschakeld.
        return;
    }

    
    
}
