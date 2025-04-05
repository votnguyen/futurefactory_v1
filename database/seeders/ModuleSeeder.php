<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            // Chassis
            [
                'name' => 'Chassis-X200',
                'type' => 'chassis',
                'cost' => 4500,
                'assembly_time' => 120,
                'specifications' => [
                    'wheels' => 4,
                    'vehicle_type' => 'personenauto',
                    'dimensions' => ['length' => 420, 'width' => 180, 'height' => 150]
                ],
                'image_path' => 'chassis-x200.png'
            ],
            
            // Aandrijving
            [
                'name' => 'Waterstof-A150',
                'type' => 'aandrijving',
                'cost' => 12500,
                'assembly_time' => 180,
                'specifications' => [
                    'type' => 'waterstof',
                    'power' => 150
                ],
                'image_path' => 'waterstof-a150.png'
            ],
            
            // Voeg meer modules toe voor wielen, stuur, stoelen...
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}