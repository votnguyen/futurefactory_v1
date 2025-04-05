<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Robot;
use App\Models\Module; // Vergeet dit niet!
=======
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
>>>>>>> parent of 9126aec (Planner controller etc.)
use Illuminate\Http\Request;

class PlannerController extends Controller {
    public function dashboard() {
        return view('planner.dashboard');
    }
<<<<<<< HEAD
<<<<<<< HEAD

    public function index()
    {
        $vehicles = Vehicle::where('status', 'concept')->get();
        return view('planner.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required|date',
            'modules' => 'required|array'
        ]);

        $vehicleId = (int) $request->input('vehicle_id');
        $vehicle = Vehicle::findOrFail($vehicleId);
        $robot = $this->determineRobot($vehicle);

        foreach ($request->modules as $moduleId) {
            $module = Module::findOrFail($moduleId);

            Schedule::create([
                'vehicle_id' => $vehicle->id,
                'module_id' => $module->id,
                'robot_id' => $robot->id,
                'start_time' => $request->start_time,
                'end_time' => Carbon::parse($request->start_time)
                    ->addHours($module->assembly_time)
            ]);
        }

        $vehicle->update(['status' => 'in_productie']);

        return redirect()->route('planner.index')
            ->with('success', 'Voertuig ingepland!');
    }

    private function determineRobot(Vehicle $vehicle)
    {
        $chassis = $vehicle->modules()->where('type', 'chassis')->first();

        return match ($chassis->specifications['voertuig_type']) {
            'step', 'fiets', 'scooter' => Robot::where('name', 'TwoWheels')->first(),
            'vrachtwagen', 'bus' => Robot::where('name', 'HeavyD')->first(),
            default => Robot::where('name', 'HydroBoy')->first(),
        };
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
=======
}
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
}
>>>>>>> parent of 9126aec (Planner controller etc.)
