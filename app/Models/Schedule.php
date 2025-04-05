<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    // Definieer de invulbare velden
    protected $fillable = ['vehicle_id', 'robot_id', 'start_time', 'end_time', 'status', 'module_id'];

    // Relaties met andere modellen
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function robot()
    {
        return $this->belongsTo(Robot::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Controleer of het tijdslot beschikbaar is
    public static function isSlotAvailable($robotId, $startTime, $durationHours)
    {
        $endTime = Carbon::parse($startTime)->addHours($durationHours);

        // Controleer of er een overlap is met een bestaand schema voor deze robot
        return !self::where('robot_id', $robotId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $startTime)
                          ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();
    }

    // Opslaan van een nieuwe planning
    public function store(Request $request)
    {
        // Valideer de input van de gebruiker
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required|date|after:now',  // Zorg dat start_time na de huidige tijd is
            'end_time' => 'required|date|after:start_time',  // Zorg ervoor dat end_time na start_time is
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id'
        ]);

        try {
            DB::beginTransaction();

            // Haal het voertuig op
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

            // Haal de robot op voor het voertuig
            $robot = $this->determineRobot($vehicle);

            // Zet de starttijd van de planning
            $startTime = Carbon::parse($validated['start_time']);
            $endTime = Carbon::parse($validated['end_time']);

            // Itereer over de modules om de planning op te slaan
            foreach ($validated['modules'] as $moduleId) {
                $module = Module::findOrFail($moduleId);

                // Controleer de beschikbaarheid van het tijdslot voor de robot
                if (!self::isSlotAvailable($robot->id, $startTime, $module->assembly_time)) {
                    throw new \Exception('Tijdslot is al bezet voor robot ' . $robot->name);
                }

                // Maak de nieuwe planning aan, zorg ervoor dat de robot_id wordt doorgegeven
                Schedule::create([
                    'vehicle_id' => $vehicle->id,
                    'robot_id' => $robot->id,
                    'module_id' => $module->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'in_productie'  // Voeg de status toe als je wilt
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
            Log::error('Fout bij inplannen: ' . $e->getMessage());  // Log de foutmelding voor debugging
            return redirect()->back()
                             ->with('error', 'Fout bij inplannen: ' . $e->getMessage());
        }
    }

    // Methode om te bepalen welke robot gebruikt moet worden
    public function determineRobot(Vehicle $vehicle)
    {
        // Verander de logica zodat je een robot kunt kiezen op basis van het type voertuig of andere criteria
        // Dit kan bijvoorbeeld het type voertuig zijn, of een andere voorwaarde.
        $robot = Robot::where('vehicle_type', $vehicle->type)->first(); // Kies robot op basis van type voertuig

        if (!$robot) {
            throw new \Exception('Geen robot beschikbaar voor dit voertuig.');
        }

        return $robot;
    }
}
