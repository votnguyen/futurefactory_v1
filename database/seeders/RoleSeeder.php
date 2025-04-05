<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
    public function run() {
        $roles = ['monteur', 'planner', 'klant', 'inkoper'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}