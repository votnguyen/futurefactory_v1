<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Robot;

class RobotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $robots = [
            [
                'name' => 'TwoWheels',
                'description' => 'Verantwoordelijk voor alle tweewielers'
            ],
            [
                'name' => 'HydroBoy',
                'description' => 'Waterstofvoertuigen specialist'
            ],
            [
                'name' => 'HeavyD',
                'description' => 'Zware voertuigen expert'
            ]
        ];
        
        foreach ($robots as $robot) {
            Robot::create($robot);
        }
    
    }
}
