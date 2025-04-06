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
    public function store(Request $request)
    {
        // Validatie van de ingediende gegevens
        $validated = $request->validate([
            'vehicle_id'  => 'required|exists:vehicles,id',   // Voertuig moet bestaan
            'start_time'  => 'required|date',                 // Starttijd moet een geldige datum zijn
            'modules'     => 'required|array|min:1',           // Er moet minimaal 1 module worden geselecteerd
            'modules.*'   => 'exists:modules,id',              // Elke geselecteerde module moet bestaan
        ]);
    
        // Haal het voertuig op en laadt de modules
        $vehicle = Vehicle::with('modules')->findOrFail($validated['vehicle_id']);
        
        // Bepaal welke robot gebruikt moet worden voor dit voertuig
        $robot = $this->determineRobot($vehicle);
    
        // Zet de starttijd om naar een Carbon instance
        $startTime = Carbon::parse($validated['start_time']);
    
        // Begin de database transactie
        DB::beginTransaction();
    
        try {
            // Loop door de geselecteerde modules en plan ze in
            foreach ($validated['modules'] as $moduleId) {
                $module = Module::findOrFail($moduleId);
    
                // Bepaal de start- en eindtijd voor de module
                $start = $startTime->copy();
                $end = $start->copy()->addHours($module->assembly_time);
    
                // Controleer of er geen conflict is voor dit voertuig, deze module, en dit tijdslot
                $conflict = Schedule::where('vehicle_id', $vehicle->id)
                    ->where('module_id', $module->id)
                    ->where('start_time', $start)
                    ->exists();
    
                // Als er een conflict is, gooi een fout
                if ($conflict) {
                    throw new \Exception("Module '{$module->name}' is al ingepland op dit tijdstip.");
                }
    
                // Maak een nieuw schema voor de module
                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id'  => $module->id,
                    'robot_id'   => $robot->id,
                    'start_time' => $start,
                    'end_time'   => $end,
                ]);
            }
    
            // Zet de status van het voertuig naar "in_productie"
            $vehicle->update(['status' => 'in_productie']);
    
            // Commit de transactie, alles is goed gegaan
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Voertuig succesvol ingepland!',
            ]);
        } catch (\Exception $e) {
            // Als er een fout optreedt, roll back de transactie
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'Fout bij inplannen: ' . $e->getMessage(),
            ], 422);  // 422 is een HTTP-status voor validatiefouten
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


public function completed()
{
   // We gaan ervan uit dat voertuigen met status 'voltooid' als compleet worden beschouwd.
   $vehicles = Vehicle::where('status', 'voltooid')
       ->with('customer')
       ->get();

   // Voor elk voertuig bepalen we de verwachte opleveringsdatum als de maximale eindtijd van alle schedules.
   foreach ($vehicles as $vehicle) {
       $maxEndTime = Schedule::where('vehicle_id', $vehicle->id)->max('end_time');
       $vehicle->expected_delivery = $maxEndTime;
   }

   return view('planner.completed', compact('vehicles'));
}
    
}
