<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [

            // --- CHASSIS MODULES ---
            [
                'name' => 'Step LightFrame',
                'type' => 'chassis',
                'cost' => 3000,
                'assembly_time' => 2,
                'specifications' => [
                    'wielen' => 2,
                    'voertuig_type' => 'Step',
                    'afmetingen' => ['length' => 120, 'width' => 40, 'height' => 80]
                ],
                'image_path' => 'chassis-step.png'
            ],
            [
                'name' => 'Fiets StandardFrame',
                'type' => 'chassis',
                'cost' => 2500,
                'assembly_time' => 2,
                'specifications' => [
                    'wielen' => 2,
                    'voertuig_type' => 'Fiets',
                    'afmetingen' => ['length' => 180, 'width' => 50, 'height' => 100]
                ],
                'image_path' => 'chassis-fiets.png'
            ],
            [
                'name' => 'Scooter ProFrame',
                'type' => 'chassis',
                'cost' => 3500,
                'assembly_time' => 2,
                'specifications' => [
                    'wielen' => 2,
                    'voertuig_type' => 'Scooter',
                    'afmetingen' => ['length' => 200, 'width' => 70, 'height' => 120]
                ],
                'image_path' => 'chassis-scooter.png'
            ],
            [
                'name' => 'Nikinella Chassis',
                'type' => 'chassis',
                'cost' => 4400,
                'assembly_time' => 4,
                'specifications' => [
                    'wielen' => 4,
                    'voertuig_type' => 'Personenauto',
                    'afmetingen' => ['length' => 400, 'width' => 186, 'height' => 165]
                ],
                'image_path' => 'chassis-sport.png'
            ],
            [
                'name' => 'Frame TGP India',
                'type' => 'chassis',
                'cost' => 6500,
                'assembly_time' => 6,
                'specifications' => [
                    'wielen' => 6,
                    'voertuig_type' => 'Vrachtwagen',
                    'afmetingen' => ['length' => 500, 'width' => 200, 'height' => 180]
                ],
                'image_path' => 'chassis-suv.png'
            ],
            [
                'name' => 'Bus MegaFrame',
                'type' => 'chassis',
                'cost' => 8000,
                'assembly_time' => 8,
                'specifications' => [
                    'wielen' => 6,
                    'voertuig_type' => 'Bus',
                    'afmetingen' => ['length' => 800, 'width' => 250, 'height' => 300]
                ],
                'image_path' => 'chassis-bus.png'
            ],

            // --- AANDRIJVING MODULES ---
            [
                'name' => 'E-Step Motor 3pk',
                'type' => 'aandrijving',
                'cost' => 8000,
                'assembly_time' => 2,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '3pk',
                    'compatibele_voertuigen' => ['Step']
                ],
                'image_path' => 'aandrijving-step-elektrisch.png'
            ],
            [
                'name' => 'E-Bike Power+ 7pk',
                'type' => 'aandrijving',
                'cost' => 12000,
                'assembly_time' => 2,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '7pk',
                    'compatibele_voertuigen' => ['Fiets', 'Scooter']
                ],
                'image_path' => 'aandrijving-ebike.png'
            ],
            [
                'name' => 'E-Auto 204pk',
                'type' => 'aandrijving',
                'cost' => 25000,
                'assembly_time' => 4,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '204pk',
                    'compatibele_voertuigen' => ['Personenauto']
                ],
                'image_path' => 'aandrijving-elektrisch-auto.png'
            ],
            [
                'name' => 'E-Bus 408pk',
                'type' => 'aandrijving',
                'cost' => 60000,
                'assembly_time' => 6,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '408pk',
                    'compatibele_voertuigen' => ['Bus']
                ],
                'image_path' => 'aandrijving-elektrisch-bus.png'
            ],
            [
                'name' => 'waterstof138',
                'type' => 'aandrijving',
                'cost' => 32000,
                'assembly_time' => 6,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '138pk',
                    'compatibele_voertuigen' => ['Personenauto']
                ],
                'image_path' => 'aandrijving-waterstof-auto.png'
            ],
            [
                'name' => 'H2-Scooter Cell 13pk',
                'type' => 'aandrijving',
                'cost' => 15000,
                'assembly_time' => 2,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '13pk',
                    'compatibele_voertuigen' => ['Scooter']
                ],
                'image_path' => 'aandrijving-waterstof-scooter.png'
            ],
            [
                'name' => 'H2-Vracht Xtreme 400pk',
                'type' => 'aandrijving',
                'cost' => 75000,
                'assembly_time' => 8,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '400pk',
                    'compatibele_voertuigen' => ['Vrachtwagen']
                ],
                'image_path' => 'aandrijving-waterstof-vracht.png'
            ],
            [
                'name' => 'H2-Bus Mega 350pk',
                'type' => 'aandrijving',
                'cost' => 80000,
                'assembly_time' => 8,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '350pk',
                    'compatibele_voertuigen' => ['Bus']
                ],
                'image_path' => 'aandrijving-waterstof-bus.png'
            ],

            // --- WIELEN MODULES ---
            [
                'name' => 'Z15-4',
                'type' => 'wielen',
                'cost' => 1200,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'zomerband',
                    'diameter' => '15 inch',
                    'aantal' => 4,
                    'geschikt_voor' => ['Nikinella Chassis', 'Frame TGP India', 'Bus MegaFrame']
                ],
                'image_path' => 'wielen-zomer.png'
            ],
            [
                'name' => 'Auto Premium 18"',
                'type' => 'wielen',
                'cost' => 2000,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'all-season',
                    'diameter' => '18 inch',
                    'aantal' => 4,
                    'geschikt_voor' => ['Nikinella Chassis', 'Frame TGP India', 'Bus MegaFrame']
                ],
                'image_path' => 'wielen-auto-premium.png'
            ],
            [
                'name' => 'Vrachtwagen Banden 22"',
                'type' => 'wielen',
                'cost' => 5000,
                'assembly_time' => 4,
                'specifications' => [
                    'band_type' => 'winter',
                    'diameter' => '22 inch',
                    'aantal' => 6,
                    'geschikt_voor' => ['Frame TGP India']
                ],
                'image_path' => 'wielen-vrachtwagen.png'
            ],
            [
                'name' => 'Bus Banden 20"',
                'type' => 'wielen',
                'cost' => 4000,
                'assembly_time' => 4,
                'specifications' => [
                    'band_type' => 'zomerband',
                    'diameter' => '20 inch',
                    'aantal' => 6,
                    'geschikt_voor' => ['Bus MegaFrame']
                ],
                'image_path' => 'wielen-bus.png'
            ],
            [
                'name' => 'Step Wielen 10"',
                'type' => 'wielen',
                'cost' => 800,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'all-season',
                    'diameter' => '10 inch',
                    'aantal' => 2,
                    'geschikt_voor' => ['Step LightFrame']
                ],
                'image_path' => 'wielen-step.png'
            ],
            [
                'name' => 'Fiets Wielen 28"',
                'type' => 'wielen',
                'cost' => 600,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'zomerband',
                    'diameter' => '28 inch',
                    'aantal' => 2,
                    'geschikt_voor' => ['Fiets StandardFrame']
                ],
                'image_path' => 'wielen-fiets.png'
            ],
            [
                'name' => 'Scooter Wielen 12"',
                'type' => 'wielen',
                'cost' => 900,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'winter',
                    'diameter' => '12 inch',
                    'aantal' => 2,
                    'geschikt_voor' => ['Scooter ProFrame']
                ],
                'image_path' => 'wielen-scooter.png'
            ],

            // --- STUUR MODULES ---
            [
                'name' => 'schapenstadium',
                'type' => 'stuur',
                'cost' => 400,
                'assembly_time' => 2,
                'specifications' => [
                    'speciale_aanpassingen' => 'schapenvacht',
                    'vorm' => 'stadium'
                ],
                'image_path' => 'stuur-schapenstadium.png'
            ],
            [
                'name' => 'Auto Sportstuur',
                'type' => 'stuur',
                'cost' => 600,
                'assembly_time' => 2,
                'specifications' => [
                    'speciale_aanpassingen' => 'multifunctioneel',
                    'vorm' => 'ovaal'
                ],
                'image_path' => 'stuur-auto-sport.png'
            ],
            [
                'name' => 'Vrachtwagen Stuur',
                'type' => 'stuur',
                'cost' => 900,
                'assembly_time' => 2,
                'specifications' => [
                    'speciale_aanpassingen' => 'verhoogde weerstand',
                    'vorm' => 'hexagon'
                ],
                'image_path' => 'stuur-vrachtwagen.png'
            ],
            [
                'name' => 'Bus Stuur',
                'type' => 'stuur',
                'cost' => 800,
                'assembly_time' => 2,
                'specifications' => [
                    'speciale_aanpassingen' => 'ergonomisch',
                    'vorm' => 'rond'
                ],
                'image_path' => 'stuur-bus.png'
            ],
            [
                'name' => 'Step Stuur',
                'type' => 'stuur',
                'cost' => 200,
                'assembly_time' => 2,
                'specifications' => [
                    'speciale_aanpassingen' => 'lichtgewicht',
                    'vorm' => 'rond'
                ],
                'image_path' => 'stuur-step.png'
            ],
            [
                'name' => 'Heavy Duty Stuur',
                'type' => 'stuur',
                'cost' => 800,
                'assembly_time' => 2,
                'specifications' => [
                    'speciale_aanpassingen' => 'versterkt',
                    'vorm' => 'hexagon'
                ],
                'image_path' => 'stuur-heavy-duty.png'
            ],

            // --- STOELEN/ZADEL MODULES ---
            [
                'name' => 'Luxe Stoelenset',
                'type' => 'stoelen',
                'cost' => 1600,
                'assembly_time' => 2,
                'specifications' => [
                    'aantal' => 5,
                    'stoffering' => 'leer'
                ],
                'image_path' => 'stoelen-luxe.png'
            ],
            [
                'name' => 'Auto Stoelen Sport',
                'type' => 'stoelen',
                'cost' => 2500,
                'assembly_time' => 2,
                'specifications' => [
                    'aantal' => 5,
                    'stoffering' => 'sportleer'
                ],
                'image_path' => 'stoelen-auto-sport.png'
            ],
            [
                'name' => 'Vrachtwagen Stoel',
                'type' => 'stoelen',
                'cost' => 3500,
                'assembly_time' => 2,
                'specifications' => [
                    'aantal' => 2,
                    'stoffering' => 'geventileerd leer'
                ],
                'image_path' => 'stoelen-vrachtwagen.png'
            ],
            [
                'name' => 'Bus Stoelen Set',
                'type' => 'stoelen',
                'cost' => 6000,
                'assembly_time' => 6,
                'specifications' => [
                    'aantal' => 20,
                    'stoffering' => 'stof'
                ],
                'image_path' => 'stoelen-bus.png'
            ],
            [
                'name' => 'Fiets Zadel',
                'type' => 'stoelen',
                'cost' => 150,
                'assembly_time' => 2,
                'specifications' => [
                    'aantal' => 1,
                    'stoffering' => 'kunstleer'
                ],
                'image_path' => 'zadel-fiets.png'
            ]
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}