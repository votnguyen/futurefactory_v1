<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'type' => $this->faker->randomElement(['chassis', 'aandrijving', 'wielen', 'stuur', 'stoelen']),
            'cost' => $this->faker->randomFloat(2, 100, 10000),
            'assembly_time' => $this->faker->numberBetween(1, 5),
            'specifications' => [
                'aantal_wielen' => $this->faker->randomElement([2, 4, 6, 8]),
                'vermogen' => $this->faker->numberBetween(50, 500),
                'diameter' => $this->faker->numberBetween(12, 22),
                'stoffering' => $this->faker->randomElement(['leer', 'stof', 'metaal']),
            ],
            'image_path' => $this->faker->imageUrl(640, 480, 'transport'),
        ];

        
    }
    public function chassis(): Factory
{
    return $this->state(function (array $attributes) {
        return [
            'type' => 'chassis',
            'specifications' => [
                'wielen' => 4,
                'voertuig_type' => 'personenauto',
                'afmetingen' => [
                    'length' => 400,
                    'width' => 186,
                    'height' => 165
                ],
                'geschikt_voor' => ['nikinella', 'centio']
            ]
        ];
    });
}

public function wielen(): Factory
{
    return $this->state(function (array $attributes) {
        return [
            'type' => 'wielen',
            'specifications' => [
                'band_type' => 'zomer',
                'diameter' => 17,
                'aantal' => 4,
                'geschikt_voor' => ['nikinella', 'centio']
            ]
        ];
    });
}
}
