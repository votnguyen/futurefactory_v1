<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [

            // ✅ CHASSIS
            [
                'name' => 'Sport Chassis',
                'type' => 'chassis',
                'cost' => 5500,
                'assembly_time' => 120,
                'specifications' => [
                    'wielen' => 4,
                    'type' => 'sportwagen',
                    'afmetingen' => '400x180x120cm'
                ],
                'image_path' => 'modules/chassis-sport.png'
            ],
            [
                'name' => 'SUV Chassis',
                'type' => 'chassis',
                'cost' => 7000,
                'assembly_time' => 150,
                'specifications' => [
                    'wielen' => 4,
                    'type' => 'SUV',
                    'afmetingen' => '460x200x160cm'
                ],
                'image_path' => 'modules/chassis-suv.png'
            ],

            // ✅ AANDRIJVING
            [
                'name' => 'Elektrisch 150kW',
                'type' => 'aandrijving',
                'cost' => 12000,
                'assembly_time' => 180,
                'specifications' => [
                    'type' => 'elektrisch',
                    'vermogen' => '150kW'
                ],
                'image_path' => 'modules/aandrijving-elektrisch.png'
            ],
            [
                'name' => 'Hybride 90kW',
                'type' => 'aandrijving',
                'cost' => 9000,
                'assembly_time' => 160,
                'specifications' => [
                    'type' => 'hybride',
                    'vermogen' => '90kW'
                ],
                'image_path' => 'modules/aandrijving-hybride.png'
            ],

            // ✅ WIELEN
            [
                'name' => '19" Sportwielen',
                'type' => 'wielen',
                'cost' => 2000,
                'assembly_time' => 60,
                'specifications' => [
                    'type' => 'zomerband',
                    'diameter' => '19 inch'
                ],
                'image_path' => 'modules/wielen-sport.png'
            ],
            [
                'name' => 'All-season 17"',
                'type' => 'wielen',
                'cost' => 1500,
                'assembly_time' => 50,
                'specifications' => [
                    'type' => 'all-season',
                    'diameter' => '17 inch'
                ],
                'image_path' => 'modules/wielen-allseason.png'
            ],

            // ✅ STUUR
            [
                'name' => 'Sportstuur',
                'type' => 'stuur',
                'cost' => 800,
                'assembly_time' => 30,
                'specifications' => [
                    'materiaal' => 'leder',
                    'verwarming' => true
                ],
                'image_path' => 'modules/stuur-sport.png'
            ],
            [
                'name' => 'Comfort Stuur',
                'type' => 'stuur',
                'cost' => 600,
                'assembly_time' => 25,
                'specifications' => [
                    'materiaal' => 'kunststof',
                    'verwarming' => false
                ],
                'image_path' => 'modules/stuur-comfort.png'
            ],

            // ✅ STOELEN
            [
                'name' => 'Sportstoelen',
                'type' => 'stoelen',
                'cost' => 1500,
                'assembly_time' => 90,
                'specifications' => [
                    'aantal' => 2,
                    'materiaal' => 'alcantara'
                ],
                'image_path' => 'modules/stoelen-sport.png'
            ],
            [
                'name' => 'Comfortstoelen',
                'type' => 'stoelen',
                'cost' => 1000,
                'assembly_time' => 80,
                'specifications' => [
                    'aantal' => 5,
                    'materiaal' => 'stof'
                ],
                'image_path' => 'modules/stoelen-comfort.png'
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
