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
    // Dashboard: Haalt voertuigen op die in de 'concept' status staan
    public function dashboard()
    {
        $vehicles = Vehicle::where('status', 'concept')->get();
        return view('planner.dashboard', compact('vehicles'));
    }

    // Index: Geeft alle voertuigen in de 'concept' status weer
    public function index()
    {
        $vehicles = Vehicle::where('status', 'concept')->get();
        return view('planner.index', compact('vehicles'));
    }

    // Store: Maakt een nieuw schema aan voor een voertuig met de geselecteerde modules
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required|date',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id', // Zorg ervoor dat alle module-id's bestaan
        ]);

        $vehicleId = (int) $request->input('vehicle_id');
        $vehicle = Vehicle::findOrFail($vehicleId);
        $robot = $this->determineRobot($vehicle);

        // Begin transactie voor veiligere databasemodificaties
        DB::beginTransaction();

        try {
            foreach ($request->modules as $moduleId) {
                $module = Module::findOrFail($moduleId);

                // Parse en formatteer de start- en eindtijd
                $startTime = Carbon::parse($request->start_time)->format('Y-m-d H:i:s');
                $endTime = Carbon::parse($startTime)->addHours($module->assembly_time)->format('Y-m-d H:i:s');

                // Controleer of er al een schema bestaat voor het voertuig, module en tijd
                $existingSchedule = Schedule::where('vehicle_id', $vehicle->id)
                    ->where('module_id', $module->id)
                    ->where('start_time', $startTime)
                    ->first();

                if ($existingSchedule) {
                    throw new \Exception("Er is al een schema voor de geselecteerde module op deze tijd.");
                }

                // Maak het nieuwe schema aan
                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id' => $module->id,
                    'robot_id' => $robot->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);
            }

            // Update de status van het voertuig naar 'in_productie'
            $vehicle->update(['status' => 'in_productie']);

            DB::commit();

            return redirect()->route('planner.index')
                ->with('success', 'Voertuig succesvol ingepland!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Foutafhandeling
            return redirect()->back()->with('error', 'Er is een fout opgetreden: ' . $e->getMessage());
        }
    }

    // Bepaal de juiste robot op basis van het voertuigtype (chassis)
    private function determineRobot(Vehicle $vehicle)
    {
        $chassis = $vehicle->modules()->where('type', 'chassis')->first();

        $voertuigType = $chassis->specifications['voertuig_type'] ?? null;

        // Selecteer robot op basis van het voertuigtype
        return match ($voertuigType) {
            'step', 'fiets', 'scooter' => Robot::where('name', 'TwoWheels')->first(),
            'vrachtwagen', 'bus' => Robot::where('name', 'HeavyD')->first(),
            default => Robot::where('name', 'HydroBoy')->first(),
        };
    }
}
