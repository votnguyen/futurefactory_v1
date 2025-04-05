<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
use App\Models\Robot;
<<<<<<< HEAD
use App\Models\Chassis;
=======
use app\Models\Robot;

>>>>>>> parent of 9126aec (Planner controller etc.)
=======
=======
use app\Models\Robot;
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
use app\Models\Robot;
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
use app\Models\Robot;
>>>>>>> parent of 9126aec (Planner controller etc.)
=======
use app\Models\Robot;
>>>>>>> parent of 9126aec (Planner controller etc.)

=======
use app\Models\Robot;
>>>>>>> parent of 9126aec (Planner controller etc.)

>>>>>>> parent of 4f325ed (fucked up code now)

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
