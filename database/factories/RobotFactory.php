<?php

namespace Database\Factories;

use App\Models\Robot;
use Illuminate\Database\Eloquent\Factories\Factory;

class RobotFactory extends Factory
{
    protected $model = Robot::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            // Voeg andere vereiste velden toe
        ];
    }
}