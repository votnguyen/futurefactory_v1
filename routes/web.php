<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Http\Controllers\{
    ProfileController,
    MonteurController,
    PlannerController,
    KlantController,
    InkoperController,
    VehicleAssemblyController,
    Inkoper\ModuleController,
    CustomerDashboardController,
    VehicleStatusController,
};

// Homepagina voor niet-ingelogde gebruikers
Route::view('/', 'welcome');

// Redirect naar rol-specifiek dashboard na inloggen
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match (true) {
        $user->hasRole('monteur') => redirect()->route('monteur.dashboard'),
        $user->hasRole('planner') => redirect()->route('planner.dashboard'),
        $user->hasRole('klant') => redirect()->route('klant.dashboard'),
        $user->hasRole('inkoper') => redirect()->route('inkoper.dashboard'),
        default => view('dashboard'),
    };
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

    Route::resource('vehicles', MonteurController::class)->only(['create', 'store', 'show']);
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
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard']);
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
            'robot_name' => $vehicle->robot->name,
        ]);
    });

    Route::get('/slots/available', function (Request $request) {
        // Beschikbaarheid check implementatie
    });

    Route::get('/schedules', function () {
        // Schedules ophalen implementatie
    });

    Route::patch('/vehicles/{vehicle}/status', [VehicleStatusController::class, 'update'])->name('vehicles.status.update');

    Route::post('/vehicle-assembly', [VehicleAssemblyController::class, 'store'])->name('vehicle.assembly.store');
});

Route::get('/contact', function () {
    return view('contact'); // Zorg dat je 'resources/views/contact.blade.php' bestaat
})->name('contact');


// Authenticatie routes
require __DIR__.'/auth.php';
