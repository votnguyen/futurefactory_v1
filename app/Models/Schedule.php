<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'module_id', 'robot_id', 'start_time', 'end_time'];

    // Relaties met andere modellen
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    
    public function robot()
    {
        return $this->belongsTo(Robot::class);
    }

    // Controleer of het tijdslot beschikbaar is
    public static function isSlotAvailable($robotId, $startTime, $durationHours)
    {
        $endTime = Carbon::parse($startTime)->addHours($durationHours);

        return !self::where('robot_id', $robotId)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                      });
            })
            ->exists();
    }

    // Opslaan van een nieuwe planning
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required|date',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id'
        ]);

        try {
            DB::beginTransaction();

            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $robot = $this->determineRobot($vehicle);
            $startTime = Carbon::parse($validated['start_time']);

            // Itereer over de modules om de planning op te slaan
            foreach ($validated['modules'] as $moduleId) {
                $module = Module::findOrFail($moduleId);
                $endTime = $startTime->copy()->addHours($module->assembly_time);

                // Controleer de beschikbaarheid van het tijdslot voor de robot
                if (!self::isSlotAvailable($robot->id, $startTime, $module->assembly_time)) {
                    throw new \Exception('Tijdslot is al bezet voor robot ' . $robot->name);
                }

                // Maak de nieuwe planning aan
                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'module_id' => $module->id,
                    'robot_id' => $robot->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ]);

                // Stel de starttijd voor de volgende module in
                $startTime = $endTime;
            }

            // Werk de status van het voertuig bij
            $vehicle->update(['status' => 'in_productie']);
            DB::commit();

            return redirect()->route('planner.index')
                             ->with('success', 'Voertuig succesvol ingepland!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', $e->getMessage());
        }
    }

    // Methode om te bepalen welke robot gebruikt moet worden
    public function determineRobot(Vehicle $vehicle)
    {
        // Logica om de juiste robot voor het voertuig te bepalen
        // Dit is een placeholder, voeg je eigen logica toe
        return Robot::first(); // Dit is een voorbeeld, pas dit aan naar je eigen logica
    }
}
