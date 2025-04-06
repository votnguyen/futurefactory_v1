<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Vehicle, Schedule};
use App\Http\Controllers\{
    ProfileController,
    MonteurController,
    PlannerController,
    KlantController,
    InkoperController,
    VehicleAssemblyController,
    VehicleTypeController,
    Inkoper\ModuleController
};

// ✅ Home & Dashboard
Route::view('/', 'welcome');
Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Profielbeheer
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Monteur Routes
Route::middleware(['auth', 'role:monteur'])->prefix('monteur')->name('monteur.')->group(function () {
    Route::get('/', [MonteurController::class, 'dashboard'])->name('dashboard');

    Route::prefix('assembly')->name('assembly.')->group(function () {
        Route::get('/', [VehicleAssemblyController::class, 'index'])->name('index');
        Route::get('/create', [VehicleAssemblyController::class, 'create'])->name('create');
        Route::post('/', [VehicleAssemblyController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [VehicleAssemblyController::class, 'show'])->name('show');
    });
});

// ✅ Planner Routes
Route::middleware(['auth', 'role:planner'])->prefix('planner')->name('planner.')->group(function () {
    Route::get('/', [PlannerController::class, 'dashboard'])->name('dashboard');
    Route::get('/planning', [PlannerController::class, 'index'])->name('index'); // JSON
    Route::post('/planning', [PlannerController::class, 'store'])->name('store');
    Route::get('/planning/{vehicle}', [PlannerController::class, 'show'])->name('show');
    Route::post('/schedule', [PlannerController::class, 'store'])->name('schedule.store');
    Route::get('/planner/planning', [PlannerController::class, 'showPlanning'])->name('planning');
    Route::get('/completed', [PlannerController::class, 'completed'])->name('completed');
});

// ✅ Klant Routes
Route::middleware(['auth', 'role:klant'])->prefix('klant')->name('klant.')->group(function () {
    Route::get('/', [KlantController::class, 'dashboard'])->name('dashboard');
});

// ✅ Inkoper Routes
Route::middleware(['auth', 'role:inkoper'])->prefix('inkoper')->name('inkoper.')->group(function () {
    Route::get('/', [InkoperController::class, 'dashboard'])->name('dashboard');

    // Modules (resource controller)
    Route::resource('modules', ModuleController::class);

    // Soft-delete functionaliteiten
    Route::get('modules/archived', [ModuleController::class, 'archived'])->name('modules.archived');
    Route::patch('modules/{module}/restore', [ModuleController::class, 'restore'])->name('modules.restore');
    Route::delete('modules/{module}/force-delete', [ModuleController::class, 'forceDelete'])->name('modules.forceDelete');
});

// ✅ Robot ophalen bij voertuig
Route::get('/vehicle/{id}/robot', function ($id) {
    $vehicle = Vehicle::findOrFail($id);
    return [
        'robot_id' => $vehicle->robot->id,
        'robot_name' => $vehicle->robot->name
    ];
});

// ✅ Beschikbare tijdslots checken
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

// ✅ Alle geplande schedules ophalen
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

// ✅ Events ophalen voor de kalender
Route::get('/get-scheduled-events', function () {
    $events = Schedule::all();
    return response()->json([
        'events' => $events->map(function ($event) {
            return [
                'title' => $event->vehicle->name,
                'start' => $event->start_time,
                'end' => $event->end_time,
            ];
        }),
    ]);
});

// ✅ Auth routes
require __DIR__ . '/auth.php';
