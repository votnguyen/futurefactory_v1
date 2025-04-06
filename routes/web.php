<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    MonteurController,
    PlannerController,
    KlantController,
    InkoperController,
    VehicleAssemblyController,
    Inkoper\ModuleController,
    CustomerDashboardController,
    VehicleController,
};

// Homepagina voor niet-ingelogde gebruikers
Route::view('/', 'welcome');

// Redirect naar rol-specifiek dashboard na inloggen
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('monteur')) {
        return redirect()->route('monteur.dashboard');
    }
    if ($user->hasRole('planner')) {
        return redirect()->route('planner.dashboard');
    }
    if ($user->hasRole('klant')) {
        return redirect()->route('klant.dashboard');
    }
    if ($user->hasRole('inkoper')) {
        return redirect()->route('inkoper.dashboard');
    }
    
    // Fallback voor gebruikers zonder rol
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profielbeheer
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Monteur Routes
Route::middleware(['auth', 'role:monteur'])->prefix('monteur')->name('monteur.')->group(function () {
    Route::get('/', [MonteurController::class, 'dashboard'])->name('dashboard');
    
    Route::prefix('assembly')->name('assembly.')->group(function () {
        Route::get('/', [VehicleAssemblyController::class, 'index'])->name('index');
        Route::get('/create', [VehicleAssemblyController::class, 'create'])->name('create');
        Route::post('/', [VehicleAssemblyController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [VehicleAssemblyController::class, 'show'])->name('show');
    });
});

// Planner Routes
Route::prefix('planner')->middleware(['auth', 'role:planner'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PlannerController::class, 'dashboard'])->name('planner.dashboard');

    // Planner overzichtspagina's
    Route::get('/', [PlannerController::class, 'index'])->name('planner.index');
    Route::get('/completed', [PlannerController::class, 'completed'])->name('planner.completed');

    // Voertuigen beheer
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [PlannerController::class, 'vehiclesIndex'])->name('planner.vehicles.index');
        Route::get('/completed', [PlannerController::class, 'vehiclesCompleted'])->name('planner.vehicles.completed');
    });

    // Planning
    Route::prefix('planning')->group(function () {
        Route::get('/', [PlannerController::class, 'planningIndex'])->name('planner.planning.index');
        Route::post('/', [PlannerController::class, 'storePlanning'])->name('planner.planning.store');
    });
});
// Klant Routes
Route::middleware(['auth', 'role:klant'])->prefix('klant')->name('klant.')->group(function () {
    Route::get('/', [KlantController::class, 'dashboard'])->name('dashboard');
});

// Inkoper Routes
Route::middleware(['auth', 'role:inkoper'])->prefix('inkoper')->name('inkoper.')->group(function () {
    Route::get('/', [InkoperController::class, 'dashboard'])->name('dashboard');
    Route::resource('modules', ModuleController::class);
    Route::get('modules/archived', [ModuleController::class, 'archived'])->name('modules.archived');
    Route::patch('modules/{module}/restore', [ModuleController::class, 'restore'])->name('modules.restore');
    Route::delete('modules/{module}/force-delete', [ModuleController::class, 'forceDelete'])->name('modules.forceDelete');
});

// API Routes voor kalender
Route::middleware('auth')->group(function () {
    Route::get('/vehicle/{id}/robot', function ($id) {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json([
            'robot_id' => $vehicle->robot->id,
            'robot_name' => $vehicle->robot->name
        ]);
    });

    Route::get('/slots/available', function (Request $request) {
        // Beschikbaarheid check implementatie
    });

    Route::get('/schedules', function () {
        // Schedules ophalen implementatie
    });

    Route::post('modules/{module}/restore', [ModuleController::class, 'restore'])
     ->name('inkoper.modules.restore');

     Route::delete('modules/{module}/force', [ModuleController::class, 'forceDelete'])
     ->name('inkoper.modules.force-delete');
     
     Route::prefix('planner')->middleware(['auth'])->group(function() {
        Route::get('planning', [PlannerController::class, 'index'])->name('planner.planning.index');
        Route::post('planning', [PlannerController::class, 'store'])->name('planner.planning.store');
    });

    Route::prefix('planner/planning')
    ->middleware(['auth', 'role:planner'])
    ->group(function () {
        Route::get('/', [PlannerController::class, 'index'])->name('planner.planning.index');
        Route::post('/', [PlannerController::class, 'store'])->name('planner.planning.store');
    });

    Route::get('/planner/dashboard', [PlannerController::class, 'dashboard'])
     ->name('planner.dashboard');


     Route::post('/vehicle-assembly', [VehicleAssemblyController::class, 'store'])
     ->name('vehicle.assembly.store');

     Route::middleware('auth')->group(function() {
        Route::resource('vehicles', MonteurController::class)->only(['create', 'store', 'show']);
    });

    Route::middleware(['auth', 'role:klant'])->group(function () {
        Route::get('/klant/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('klant.dashboard');
    });

    Route::patch('/vehicles/{vehicle}/status', [VehicleAssemblyController::class, 'update'])
    ->name('vehicles.status.update');
    
});




// Authenticatie routes
require __DIR__.'/auth.php';