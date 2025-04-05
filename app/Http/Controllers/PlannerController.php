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
        $vehicles = Vehicle::where('status', 'concept')->get();

        return view('planner.dashboard', compact('vehicles'));
    }

    /**
     * Toon alle voertuigen met status 'concept'.
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'concept')
            ->with(['modules', 'customer']) // eager loading
            ->get();

        return view('planner.planning.index', compact('vehicles'));
    }

    /**
     * Toon de planningsgegevens voor een specifiek voertuig.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['modules', 'customer']);

        return view('planner.planning.show', compact('vehicle'));
    }

    /**
     * Sla een nieuw planningsschema op voor een voertuig.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'  => 'required|exists:vehicles,id',
            'start_time'  => 'required|date',
            'modules'     => 'required|array|min:1',
            'modules.*'   => 'exists:modules,id',
        ]);

        $vehicle = Vehicle::with('modules')->where('id', $validated['vehicle_id'])->firstOrFail();
        $robot = $this->determineRobot($vehicle);
        $startTime = Carbon::parse($validated['start_time']);

        DB::beginTransaction();

        try {
            foreach ($validated['modules'] as $moduleId) {
                $module = Module::findOrFail($moduleId);

                $start = $startTime->copy();
                $end = $start->copy()->addHours($module->assembly_time);

                $conflict = Schedule::where('vehicle_id', $vehicle->id)
                    ->where('module_id', $module->id)
                    ->where('start_time', $start)
                    ->exists();

                if ($conflict) {
                    throw new \Exception("Module '{$module->name}' is al ingepland op dit tijdstip.");
                }

                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id'  => $module->id,
                    'robot_id'   => $robot->id,
                    'start_time' => $start,
                    'end_time'   => $end,
                ]);
            }

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
    public function showPlanning()
{
    $vehicles = Vehicle::with('customer')->get();
    $scheduledEvents = Planning::all(); // Haal de geplande evenementen op

    return view('planner.planning', compact('vehicles', 'scheduledEvents'));
}


    
}
