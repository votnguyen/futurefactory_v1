<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonteurController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\KlantController;
use App\Http\Controllers\InkoperController;
use App\Http\Controllers\VehicleAssemblyController;

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
    Route::get('/', [VehicleAssemblyController::class, 'index'])->name('monteur.assembly.index');
    Route::get('/create', [VehicleAssemblyController::class, 'create'])->name('monteur.assembly.create');
    Route::post('/', [VehicleAssemblyController::class, 'store'])->name('monteur.assembly.store');
    Route::get('/{vehicle}', [VehicleAssemblyController::class, 'show'])->name('monteur.assembly.show');
});

require __DIR__.'/auth.php';
