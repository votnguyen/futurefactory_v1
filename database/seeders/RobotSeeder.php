<?php

namespace Database\Seeders;

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
                'compatible_types' => '',
            ],
            [
                'name' => 'HydroBoy',
                'compatible_types' => '',
            ],
            [
                'name' => 'HeavyD',
                'compatible_types' => '',
            ]
        ];
        
        foreach ($robots as $robot) {
            Robot::create($robot);
        }
    }
}
