<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Robot;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PlannerController extends Controller
{
    public function dashboard()
    {
        $vehicles = Vehicle::where('status', 'concept')->get();
        return view('planner.dashboard', compact('vehicles'));
    }

    public function index()
    {
        $vehicles = Vehicle::where('status', 'concept')->get();
        return view('planner.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required|date',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id',
        ]);
        DB::beginTransaction();

        try {
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $robot = $this->determineRobot($vehicle);
            $startTime = Carbon::parse($validated['start_time']);
    
            foreach ($validated['modules'] as $moduleId) {
                $module = Module::findOrFail($moduleId);
    
                $existingSchedule = Schedule::where('start_time', $startTime)
                    ->where('module_id', $moduleId)
                    ->first();
    
                if ($existingSchedule) {
                    throw new \Exception("Tijdslot voor module '{$module->name}' is al bezet op {$startTime->format('d-m-Y H:i')}");
                }
    
                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id' => $module->id,
                    'robot_id' => $robot->id,
                    'start_time' => $startTime,
                    'end_time' => $startTime->copy()->addHours($module->assembly_time),
                ]);
            }
    
            $vehicle->update(['status' => 'in_productie']);
    
            DB::commit();
    
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Voertuig succesvol ingepland!']);
            }
    
            return redirect()->route('planner.index')->with('success', 'Voertuig succesvol ingepland!');
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Fout bij inplannen: ' . $e->getMessage()], 500);
            }
    
            return redirect()->back()->withInput()->with('error', 'Fout bij inplannen: ' . $e->getMessage());
        }
    }

    private function determineRobot(Vehicle $vehicle)
    {
        $chassis = $vehicle->modules()->where('type', 'chassis')->first();
        $specs = $chassis->specifications ?? [];
        $voertuigType = $specs['voertuig_type'] ?? null;

        switch ($voertuigType) {
            case 'Step':
                return $this->findRobot('HydroBoy', 'Step LightFrame');
            case 'Fiets':
                return $this->findRobot('HydroBoy', 'Fiets StandardFrame');
            case 'Scooter':
                return $this->findRobot('HydroBoy', 'Scooter ProFrame');
            case 'Personenauto':
                return $this->findRobot('HydroBoy', 'Nikinella Chassis');
            case 'Vrachtwagen':
                return $this->findRobot('HydroBoy', 'Frame TGP India');
            case 'Bus':
                return $this->findRobot('HydroBoy', 'Bus MegaFrame');
            default:
                return $this->findRobot('HydroBoy', 'Nikinella Chassis');
        }
    }

    private function findRobot(string $name, string $chassis)
{
    $robot = Robot::where('name', $name)
        ->whereRaw('LOWER(TRIM(chassis)) = ?', [strtolower(trim($chassis))])
        ->first();

    if (!$robot) {
        throw new \Exception("Robot '{$name}' niet gevonden voor chassis: {$chassis}");
    }

    return $robot;
}

}
=======
use Illuminate\Http\Request;

class PlannerController extends Controller {
    public function dashboard() {
        return view('planner.dashboard');
    }
}
>>>>>>> parent of 9126aec (Planner controller etc.)
