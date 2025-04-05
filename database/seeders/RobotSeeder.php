<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Robot;
use App\Models\Chassis;

class RobotSeeder extends Seeder
{
    public function run()
    {
        // Maak chassis eerst aan
        $chassis1 = Chassis::create(['name' => 'Nikinella Chassis', 'description' => 'Chassis voor HydroBoy']);
        $chassis2 = Chassis::create(['name' => 'Step LightFrame', 'description' => 'Chassis voor TwoWheels']);
        $chassis3 = Chassis::create(['name' => 'Frame TGP India', 'description' => 'Chassis voor HeavyD']);

        // Maak robots aan
        Robot::create([
            'name' => 'TwoWheels',
            'description' => 'Verantwoordelijk voor alle tweewielers',
            'chassis' => $chassis2->id, // Koppel de juiste chassis
        ]);

        Robot::create([
            'name' => 'HydroBoy',
            'description' => 'Waterstofvoertuigen specialist',
            'chassis' => $chassis1->id, // Koppel de juiste chassis
        ]);

        Robot::create([
            'name' => 'HeavyD',
            'description' => 'Zware voertuigen expert',
            'chassis' => $chassis3->id, // Koppel de juiste chassis
        ]);
    }
}
