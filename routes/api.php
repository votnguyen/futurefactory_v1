<?php

use Illuminate\Support\Facades\Route;
use App\Models\Schedule;

Route::get('/schedules', function () {
    // Ophalen van schedules met gerelateerde vehicle, module en robot
    $schedules = Schedule::with(['vehicle', 'module', 'robot'])->get();
    
    // Transformatie van data met overzichtelijke structuur
    $transformedSchedules = $schedules->map(function ($schedule) {
        return [
            'title' => $schedule->vehicle->name . ' - ' . $schedule->module->name,
            'start' => $schedule->start_time,
            'end'   => $schedule->end_time,
            'color' => $schedule->robot->color, // Toegevoegd: kleurveld voor robot
        ];
    });

    // Retourneer een gestructureerde JSON response
    return response()->json($transformedSchedules);
});
