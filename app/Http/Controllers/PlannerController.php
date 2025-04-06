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
     * Toon het planner dashboard
     */
    public function dashboard()
    {
        $conceptCount = Vehicle::where('status', 'concept')->count();
        $inProductionCount = Vehicle::where('status', 'in_productie')->count();
        $completedCount = Vehicle::where('status', 'voltooid')->count();
    
        $recentVehicles = Vehicle::with('customer')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    
        // Voeg deze regel toe:
        $vehicles = Vehicle::all();
    
        return view('planner.dashboard', compact(
            'conceptCount',
            'inProductionCount',
            'completedCount',
            'recentVehicles',
            'vehicles' // <-- voeg dit toe
        ));
    }
    
    /**
     * Toon voertuigen voor planning
     */
    public function vehiclesIndex()
    {
        $vehicles = Vehicle::where('status', 'concept')
            ->with(['customer', 'modules'])
            ->get()
            ->map(function($vehicle) {
                $vehicle->unscheduled_modules = $vehicle->modules->reject(function($module) use ($vehicle) {
                    return Schedule::where('vehicle_id', $vehicle->id)
                        ->where('module_id', $module->id)
                        ->exists();
                });
                return $vehicle;
            });

        return view('planner.vehicles.index', compact('vehicles'));
    }

    /**
     * Toon voltooide voertuigen
     */
    public function vehiclesCompleted()
    {
        $vehicles = Vehicle::where('status', 'voltooid')
            ->with(['customer', 'schedules'])
            ->get()
            ->map(function($vehicle) {
                $vehicle->completion_date = $vehicle->schedules->max('end_time');
                return $vehicle;
            });

        return view('planner.vehicles.completed', compact('vehicles'));
    }

    /**
     * Toon planning interface
     */
    public function planningIndex()
    {
        $vehicles = Vehicle::whereIn('status', ['concept', 'in_productie'])
            ->with(['modules', 'schedules'])
            ->get();

        $robots = Robot::all();

        return view('planner.planning.index', compact('vehicles', 'robots'));
    }

    /**
     * Sla planning op
     */
    public function storePlanning(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'modules' => 'required|array|min:1',
            'modules.*.id' => 'exists:modules,id',
            'modules.*.duration' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();

        try {
            $vehicle = Vehicle::where('id', $validated['vehicle_id'])->firstOrFail();
            $robot = $this->determineRobot($vehicle);
            $currentTime = Carbon::parse($validated['start_date']);

            foreach ($validated['modules'] as $moduleData) {
                $module = Module::findOrFail($moduleData['id']);

                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id' => $module->id,
                    'robot_id' => $robot->id,
                    'start_time' => $currentTime,
                    'end_time' => $currentTime->copy()->addHours($moduleData['duration'])
                ]);

                $currentTime->addHours($moduleData['duration']);
            }

            $vehicle->update(['status' => 'in_productie']);
            DB::commit();

            return redirect()->route('planner.planning.index')
                ->with('success', 'Voertuig succesvol ingepland!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Fout bij inplannen: ' . $e->getMessage());
        }
    }

    private function determineRobot(Vehicle $vehicle)
    {
        $chassis = $vehicle->modules()->where('type', 'chassis')->first();
        $vehicleType = $chassis->specifications['voertuig_type'] ?? null;

        return match($vehicleType) {
            'step', 'fiets', 'scooter' => Robot::where('name', 'TwoWheels')->first(),
            'vrachtwagen', 'bus' => Robot::where('name', 'HeavyD')->first(),
            default => Robot::where('name', 'HydroBoy')->first()
        };
    }
    
}