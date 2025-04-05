<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonteurController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\KlantController;
use App\Http\Controllers\InkoperController;
use App\Http\Controllers\VehicleAssemblyController;
use App\Http\Controllers\VehicleTypeController;


<<<<<<< HEAD
=======

<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
>>>>>>> parent of 9126aec (Planner controller etc.)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rolgebaseerde dashboards
Route::middleware(['auth', 'role:monteur'])->group(function () {
    Route::get('/monteur', [MonteurController::class, 'dashboard'])->name('monteur.dashboard');
});

Route::middleware(['auth', 'role:planner'])->group(function () {
    Route::get('/planner', [PlannerController::class, 'dashboard'])->name('planner.dashboard');
});

Route::middleware(['auth', 'role:klant'])->group(function () {
    Route::get('/klant', [KlantController::class, 'dashboard'])->name('klant.dashboard');
});

Route::middleware(['auth', 'role:inkoper'])->group(function () {
    Route::get('/inkoper', [InkoperController::class, 'dashboard'])->name('inkoper.dashboard');
});

Route::prefix('monteur/assembly')->middleware(['auth', 'role:monteur'])->group(function () {
    Route::get('/create', [VehicleAssemblyController::class, 'create'])->name('monteur.assembly.create');
    Route::post('/', [VehicleAssemblyController::class, 'store'])->name('monteur.assembly.store');
    Route::get('/{vehicle}', [VehicleAssemblyController::class, 'show'])->name('monteur.assembly.show');
    Route::get('/monteur/assembly', [VehicleAssemblyController::class, 'index'])->name('monteur.assembly.index');

});
<<<<<<< HEAD

Route::prefix('planner')->middleware(['auth', 'role:planner'])->group(function () {
    Route::get('/planning', [PlanningController::class, 'index'])->name('planner.index');
    Route::post('/planning', [PlanningController::class, 'store'])->name('planner.store');
    Route::get('/planning/{vehicle}', [PlanningController::class, 'show'])->name('planner.show');
<<<<<<< HEAD
<<<<<<< HEAD
});
<<<<<<< HEAD

Route::get('/vehicle/{id}/robot', function($id) {
    $vehicle = Vehicle::findOrFail($id);
    return [
        'robot_id' => $vehicle->robot->id,
        'robot_name' => $vehicle->robot->name
    ];
});

Route::get('/slots/available', function(Request $request) {
    $robotId = $request->query('robot_id');
    $start = Carbon::parse($request->query('start'));
    $end = Carbon::parse($request->query('end'));

    $conflicting = Schedule::where('robot_id', $robotId)
        ->where(function($query) use ($start, $end) {
            $query->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end]);
        })->exists();

    return response()->json(!$conflicting);
});

Route::prefix('planner')->middleware(['auth', 'role:planner'])->group(function () {
<<<<<<< HEAD
    Route::get('/planning', [PlannerController::class, 'index'])->name('planner.index');
    Route::post('/planning', [PlannerController::class, 'store'])->name('planner.store');
=======
    Route::get('/planning', [PlanningController::class, 'index'])->name('planner.index');
    Route::post('/planning', [PlanningController::class, 'store'])->name('planner.store');
    Route::get('/planning/{vehicle}', [PlanningController::class, 'show'])->name('planner.show');
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
>>>>>>> parent of 9126aec (Planner controller etc.)
});

Route::get('/schedules', function() {
    return Schedule::with(['vehicle', 'module', 'robot'])
        ->get()
        ->map(function($schedule) {
            return [
                'title' => $schedule->vehicle->name . ' - ' . $schedule->module->name,
                'start' => $schedule->start_time->toIso8601String(),
                'end' => $schedule->end_time->toIso8601String(),
                'color' => '#3b82f6', // Vaste blauwe kleur
                'extendedProps' => [
                    'robot' => $schedule->robot->name
                ]
            ];
        });
});
=======
>>>>>>> parent of 426174f (User story 3, Planner en robot)
=======
>>>>>>> parent of 4f325ed (fucked up code now)
require __DIR__.'/auth.php';
