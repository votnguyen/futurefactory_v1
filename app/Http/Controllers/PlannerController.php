<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Robot;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PlannerController extends Controller
{
    /**
     * Toon het planner dashboard.
     */
    public function dashboard()
    {
        // Haal zowel concept als in_productie voertuigen op
        $vehicles = Vehicle::whereIn('status', ['concept', 'in_productie'])->get();
    
        return view('planner.dashboard', compact('vehicles'));
    }
    
    /**
     * Toon alle voertuigen met status 'concept'.
     */
    public function index()
    {
        // Haal voertuigen op die nog in concept zijn
        $vehicles = Vehicle::where('status', 'concept')
            ->with(['customer', 'modules'])
            ->get();
    
        // Filter per voertuig de modules die al ingepland zijn
        foreach ($vehicles as $vehicle) {
            $vehicle->unscheduled_modules = $vehicle->modules->filter(function($module) use ($vehicle) {
                return Schedule::where('vehicle_id', $vehicle->id)
                        ->where('module_id', $module->id)
                        ->exists();
            });
        }
    
        return view('planner.planning.index', compact('vehicles'));
    }
    


    
    

    /**
     * Sla de planning van een voertuig op.
     */
    public function store(Request $request)
    {
        // Validatie van de ingediende gegevens
        $validated = $request->validate([
            'vehicle_id'  => 'required|exists:vehicles,id',
            'start_time'  => 'required|date',
            'modules'     => 'required|array|min:1',
            'modules.*'   => 'exists:modules,id',
        ]);

        // Haal het voertuig op en laad de modules
        $vehicle = Vehicle::with('modules')->findOrFail($validated['vehicle_id']);

        // Zorg dat de geselecteerde modules ook bij dit voertuig horen
        $vehicleModuleIds = $vehicle->modules->pluck('id')->toArray();
        foreach ($validated['modules'] as $moduleId) {
            if (!in_array($moduleId, $vehicleModuleIds)) {
                return response()->json([
                    'success' => false,
                    'message' => "Module met ID {$moduleId} behoort niet tot het geselecteerde voertuig."
                ], 422);
            }
        }

        // Bepaal welke robot gebruikt moet worden voor dit voertuig
        $robot = $this->determineRobot($vehicle->first());

        // Zet de starttijd om naar een Carbon instance
        $currentTime = Carbon::parse($validated['start_time']);

        // Begin de database-transactie
        DB::beginTransaction();

        try {
            // Plan de geselecteerde modules sequentieel in
            foreach ($validated['modules'] as $moduleId) {
                $module = Module::findOrFail($moduleId);

                // Stel de start- en eindtijd in op basis van de huidige tijd
                $start = $currentTime->copy();
                $end = $currentTime->copy()->addHours($module->assembly_time);

                // Controleer op conflicts
                $conflict = Schedule::where('vehicle_id', $vehicle->id)
                    ->where('module_id', $module->id)
                    ->where('start_time', $start)
                    ->exists();

                if ($conflict) {
                    throw new \Exception("Module '{$module->name}' is al ingepland op dit tijdstip.");
                }

                // Maak de schedule entry voor de module
                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id'  => $module->id,
                    'robot_id'   => $robot->id,
                    'start_time' => $start,
                    'end_time'   => $end,
                ]);

                // Update de huidige tijd voor de volgende module (sequentieel inplannen)
                $currentTime = $end;
            }

            // Werk de status van het voertuig bij
            $vehicle->update(['status' => 'in_productie']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Voertuig succesvol ingepland!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Fout bij inplannen: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Bepaal welke robot verantwoordelijk is voor assemblage op basis van het voertuigtype.
     */
    private function determineRobot(Vehicle $vehicle)
    {
        $chassis = $vehicle->modules()->where('type', 'chassis')->first();
        $voertuigType = $chassis->specifications['voertuig_type'] ?? null;

        return match ($voertuigType) {
            'step', 'fiets', 'scooter' => Robot::where('name', 'TwoWheels')->first(),
            'vrachtwagen', 'bus'       => Robot::where('name', 'HeavyD')->first(),
            default                    => Robot::where('name', 'HydroBoy')->first(),
        };
    }

    /**
     * Toon de planning met alle ingeplande events.
     */
    public function showPlanning()
    {
        $vehicles = Vehicle::with('customer')->get();
        // Correct gebruik van het Schedule-model ipv een niet-bestaande Planning-model
        $scheduledEvents = Schedule::all();

        return view('planner.planning', compact('vehicles', 'scheduledEvents'));
    }

    /**
     * Toon de voltooide voertuigen met hun verwachte opleverdatum.
     */
    public function completed()
    {
        $vehicles = Vehicle::where('status', 'voltooid')
            ->with('customer')
            ->get();

        foreach ($vehicles as $vehicle) {
            $maxEndTime = Schedule::where('vehicle_id', $vehicle->id)->max('end_time');
            $vehicle->expected_delivery = $maxEndTime;
        }

        return view('planner.completed', compact('vehicles'));
    }

    /**
     * Toon alle voertuigen, ongeacht status, met aanvullende statuskleuren.
     */
    public function completedVehicles()
{
    $vehicles = Vehicle::withoutGlobalScopes()
                ->with(['customer', 'modules', 'schedules'])
                ->orderBy('id', 'desc')
                ->get();

    \Log::info('VOERTUIGEN GEHAALD', ['vehicles' => $vehicles->toArray()]);

    foreach ($vehicles as $vehicle) {
        // voortgang e.d. berekening (zoals eerder gegeven)
    }

    return view('planner.completed', [
        'vehicles' => $vehicles,
        'statusColors' => [
            'concept' => 'bg-gray-100 text-gray-800',
            'in_productie' => 'bg-yellow-100 text-yellow-800',
            'voltooid' => 'bg-green-100 text-green-800'
        ]
    ]);
}

    
}
