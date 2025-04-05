<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run() {
        // Monteur
        $monteur = User::create([
            'name' => 'Demo Monteur',
            'email' => 'monteur@test.nl',
            'password' => Hash::make('monteur123'),
        ]);
        $monteur->roles()->attach(Role::where('name', 'monteur')->first());

        // Planner
        $planner = User::create([
            'name' => 'Demo Planner',
            'email' => 'planner@test.nl',
            'password' => Hash::make('planner123'),
        ]);
        $planner->roles()->attach(Role::where('name', 'planner')->first());

        // Klant
        $klant = User::create([
            'name' => 'Demo Klant',
            'email' => 'klant@test.nl',
            'password' => Hash::make('klant123'),
        ]);
        $klant->roles()->attach(Role::where('name', 'klant')->first());

        // Inkoper
        $inkoper = User::create([
            'name' => 'Demo Inkoper',
            'email' => 'inkoper@test.nl',
            'password' => Hash::make('inkoper123'),
        ]);
        $inkoper->roles()->attach(Role::where('name', 'inkoper')->first());
    }
}