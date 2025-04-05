<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Vehicle, Schedule}; // Gebruik Schedule in plaats van Planning
use App\Http\Controllers\{
    ProfileController,
    MonteurController,
    PlannerController,
    KlantController,
    InkoperController,
    VehicleAssemblyController,
    VehicleTypeController
};

// Home & Dashboard
Route::get('/', fn() => view('welcome'));
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

// Profielbeheer
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Monteur
Route::middleware(['auth', 'role:monteur'])->prefix('monteur')->group(function () {
    Route::get('/', [MonteurController::class, 'dashboard'])->name('monteur.dashboard');
    
    Route::prefix('assembly')->group(function () {
        Route::get('/', [VehicleAssemblyController::class, 'index'])->name('monteur.assembly.index');
        Route::get('/create', [VehicleAssemblyController::class, 'create'])->name('monteur.assembly.create');
        Route::post('/', [VehicleAssemblyController::class, 'store'])->name('monteur.assembly.store');
        Route::get('/{vehicle}', [VehicleAssemblyController::class, 'show'])->name('monteur.assembly.show');
    });
});

// Planner
Route::middleware(['auth', 'role:planner'])->prefix('planner')->group(function () {
    Route::get('/', [PlannerController::class, 'dashboard'])->name('planner.dashboard');
    Route::get('/planning', [PlannerController::class, 'index'])->name('planner.index');  // Deze route levert JSON, geen view.
    Route::post('/planning', [PlannerController::class, 'store'])->name('planner.store');
    Route::get('/planning/{vehicle}', [PlannerController::class, 'show'])->name('planner.show');
    Route::post('/schedule', [PlannerController::class, 'store'])->name('planner.schedule.store');
    Route::get('/planner/planning', [PlannerController::class, 'showPlanning'])->name('planner.planning');
});

// Klant
Route::middleware(['auth', 'role:klant'])->group(function () {
    Route::get('/klant', [KlantController::class, 'dashboard'])->name('klant.dashboard');
});

// Inkoper
Route::middleware(['auth', 'role:inkoper'])->group(function () {
    Route::get('/inkoper', [InkoperController::class, 'dashboard'])->name('inkoper.dashboard');
});

// Robot ophalen bij voertuig
Route::get('/vehicle/{id}/robot', function ($id) {
    $vehicle = Vehicle::findOrFail($id);
    return [
        'robot_id' => $vehicle->robot->id,
        'robot_name' => $vehicle->robot->name
    ];
});

// Beschikbare tijdslots checken
Route::get('/slots/available', function (Request $request) {
    $robotId = $request->query('robot_id');
    $start = Carbon::parse($request->query('start'));
    $end = Carbon::parse($request->query('end'));

    $conflicting = Schedule::where('robot_id', $robotId)
        ->where(function ($query) use ($start, $end) {
            $query->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end]);
        })->exists();

    return response()->json(!$conflicting);
});

// Alle geplande schedules ophalen
Route::get('/schedules', function () {
    return Schedule::with(['vehicle', 'module', 'robot'])->get()->map(function ($schedule) {
        return [
            'title' => "{$schedule->vehicle->name} - {$schedule->module->name}",
            'start' => $schedule->start_time->toIso8601String(),
            'end' => $schedule->end_time->toIso8601String(),
            'color' => '#3b82f6',
            'extendedProps' => [
                'robot' => $schedule->robot->name
            ]
        ];
    });
});

// Eventen ophalen voor de kalender
Route::get('/get-scheduled-events', function() {
    $events = Schedule::all();  // Hier gebruiken we Schedule in plaats van Planning
    return response()->json([
        'events' => $events->map(function($event) {
            return [
                'title' => $event->vehicle->name,
                'start' => $event->start_time,
                'end' => $event->end_time,
            ];
        }),
    ]);
});

require __DIR__ . '/auth.php';
