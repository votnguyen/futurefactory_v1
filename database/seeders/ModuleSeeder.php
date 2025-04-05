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
            // Geschikt voor: Step (2 wielen)
            [
                'name' => 'Step LightFrame',
                'type' => 'chassis',
                'cost' => 3000,
                'assembly_time' => 1,
                'specifications' => [
                    'wielen' => 2,
                    'voertuig_type' => 'Step',
                    'afmetingen' => ['length' => 120, 'width' => 40, 'height' => 80]
                ],
                'image_path' => 'chassis-step.png'
            ],
            // Geschikt voor: Fiets (2 wielen)
            [
                'name' => 'Fiets StandardFrame',
                'type' => 'chassis',
                'cost' => 2500,
                'assembly_time' => 1,
                'specifications' => [
                    'wielen' => 2,
                    'voertuig_type' => 'Fiets',
                    'afmetingen' => ['length' => 180, 'width' => 50, 'height' => 100]
                ],
                'image_path' => 'chassis-fiets.png'
            ],
            // Geschikt voor: Scooter (2 wielen)
            [
                'name' => 'Scooter ProFrame',
                'type' => 'chassis',
                'cost' => 3500,
                'assembly_time' => 1,
                'specifications' => [
                    'wielen' => 2,
                    'voertuig_type' => 'Scooter',
                    'afmetingen' => ['length' => 200, 'width' => 70, 'height' => 120]
                ],
                'image_path' => 'chassis-scooter.png'
            ],
            // Geschikt voor: Personenauto (4 wielen)
            [
                'name' => 'Nikinella Chassis',
                'type' => 'chassis',
                'cost' => 4400,
                'assembly_time' => 2,
                'specifications' => [
                    'wielen' => 4,
                    'voertuig_type' => 'Personenauto',
                    'afmetingen' => ['length' => 400, 'width' => 186, 'height' => 165]
                ],
                'image_path' => 'chassis-sport.png'
            ],
            // Geschikt voor: Vrachtwagen (6 wielen)
            [
                'name' => 'Frame TGP India',
                'type' => 'chassis',
                'cost' => 6500,
                'assembly_time' => 2,
                'specifications' => [
                    'wielen' => 6,
                    'voertuig_type' => 'Vrachtwagen',
                    'afmetingen' => ['length' => 500, 'width' => 200, 'height' => 180]
                ],
                'image_path' => 'chassis-suv.png'
            ],
            // Geschikt voor: Bus (6 wielen)
            [
                'name' => 'Bus MegaFrame',
                'type' => 'chassis',
                'cost' => 8000,
                'assembly_time' => 3,
                'specifications' => [
                    'wielen' => 6,
                    'voertuig_type' => 'Bus',
                    'afmetingen' => ['length' => 800, 'width' => 250, 'height' => 300]
                ],
                'image_path' => 'chassis-bus.png'
            ],

            // --- AANDRIJVING MODULES ---
             // --- ELEKTRISCHE AANDRIJVINGEN ---
             [
                'name' => 'E-Step Motor 1.0',
                'type' => 'aandrijving',
                'cost' => 8000,
                'assembly_time' => 1,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '2.5 kW',
                    'compatibele_voertuigen' => ['Step']
                ],
                'image_path' => 'aandrijving-step-elektrisch.png'
            ],
            [
                'name' => 'E-Bike Power+',
                'type' => 'aandrijving',
                'cost' => 12000,
                'assembly_time' => 1,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '5 kW',
                    'compatibele_voertuigen' => ['Fiets', 'Scooter']
                ],
                'image_path' => 'aandrijving-ebike.png'
            ],
            [
                'name' => 'E-Auto 150kW',
                'type' => 'aandrijving',
                'cost' => 25000,
                'assembly_time' => 2,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '150 kW (204 pk)',
                    'compatibele_voertuigen' => ['Personenauto']
                ],
                'image_path' => 'aandrijving-elektrisch-auto.png'
            ],
            [
                'name' => 'E-Bus 300kW',
                'type' => 'aandrijving',
                'cost' => 60000,
                'assembly_time' => 3,
                'specifications' => [
                    'soort' => 'elektriciteit',
                    'vermogen' => '300 kW (408 pk)',
                    'compatibele_voertuigen' => ['Bus']
                ],
                'image_path' => 'aandrijving-elektrisch-bus.png'
            ],

            // --- WATERSTOF AANDRIJVINGEN ---
            [
                'name' => 'H2-Scooter Cell',
                'type' => 'aandrijving',
                'cost' => 15000,
                'assembly_time' => 2,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '10 kW (13 pk)',
                    'compatibele_voertuigen' => ['Scooter']
                ],
                'image_path' => 'aandrijving-waterstof-scooter.png'
            ],
            [
                'name' => 'H2-Auto Pro',
                'type' => 'aandrijving',
                'cost' => 35000,
                'assembly_time' => 2,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '180 pk',
                    'compatibele_voertuigen' => ['Personenauto']
                ],
                'image_path' => 'aandrijving-waterstof-auto.png'
            ],
            [
                'name' => 'H2-Vracht Xtreme',
                'type' => 'aandrijving',
                'cost' => 75000,
                'assembly_time' => 4,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '400 pk',
                    'compatibele_voertuigen' => ['Vrachtwagen']
                ],
                'image_path' => 'aandrijving-waterstof-vracht.png'
            ],
            [
                'name' => 'H2-Bus Mega',
                'type' => 'aandrijving',
                'cost' => 80000,
                'assembly_time' => 4,
                'specifications' => [
                    'soort' => 'waterstof',
                    'vermogen' => '350 pk',
                    'compatibele_voertuigen' => ['Bus']
                ],
                'image_path' => 'aandrijving-waterstof-bus.png'
            ],

            // --- HYBRIDE AANDRIJVINGEN (Optioneel) ---
            [
                'name' => 'Hybrid-Eco 200',
                'type' => 'aandrijving',
                'cost' => 30000,
                'assembly_time' => 3,
                'specifications' => [
                    'soort' => 'hybride',
                    'vermogen' => '150 pk (elektrisch) + 100 pk (waterstof)',
                    'compatibele_voertuigen' => ['Personenauto', 'Bus']
                ],
                'image_path' => 'aandrijving-hybride.png'
            ],

            // --- WIELEN MODULES ---

            [
                'name' => 'Auto Premium 18"',
                'type' => 'wielen',
                'cost' => 2000,
                'assembly_time' => 1,
                'specifications' => [
                    'band_type' => 'all-season',
                    'diameter' => '18 inch',
                    'aantal' => 4,
                    'geschikt_voor' => ['Nikinella Chassis', 'Auto Luxe Chassis']
                ],
                'image_path' => 'wielen-auto-premium.png'
            ],
            // Vrachtwagen (6 wielen)
            [
                'name' => 'Vrachtwagen Banden 22"',
                'type' => 'wielen',
                'cost' => 5000,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'winter',
                    'diameter' => '22 inch',
                    'aantal' => 6,
                    'geschikt_voor' => ['Frame TGP India', 'Vrachtwagen Chassis']
                ],
                'image_path' => 'wielen-vrachtwagen.png'
            ],
            // Bus (6 wielen)
            [
                'name' => 'Bus Banden 20"',
                'type' => 'wielen',
                'cost' => 4000,
                'assembly_time' => 2,
                'specifications' => [
                    'band_type' => 'zomerband',
                    'diameter' => '20 inch',
                    'aantal' => 6,
                    'geschikt_voor' => ['Bus MegaFrame']
                ],
                'image_path' => 'wielen-bus.png'
            ],

            // Compatibel met: Step LightFrame
            [
                'name' => 'Step Wielen 10"',
                'type' => 'wielen',
                'cost' => 800,
                'assembly_time' => 1,
                'specifications' => [
                    'band_type' => 'all-season',
                    'diameter' => '10 inch',
                    'aantal' => 2,
                    'geschikt_voor' => ['Step LightFrame']
                ],
                'image_path' => 'wielen-step.png'
            ],
            // Compatibel met: Fiets StandardFrame
            [
                'name' => 'Fiets Wielen 28"',
                'type' => 'wielen',
                'cost' => 600,
                'assembly_time' => 1,
                'specifications' => [
                    'band_type' => 'zomerband',
                    'diameter' => '28 inch',
                    'aantal' => 2,
                    'geschikt_voor' => ['Fiets StandardFrame']
                ],
                'image_path' => 'wielen-fiets.png'
            ],
            // Compatibel met: Scooter ProFrame
            [
                'name' => 'Scooter Wielen 12"',
                'type' => 'wielen',
                'cost' => 900,
                'assembly_time' => 1,
                'specifications' => [
                    'band_type' => 'winter',
                    'diameter' => '12 inch',
                    'aantal' => 2,
                    'geschikt_voor' => ['Scooter ProFrame']
                ],
                'image_path' => 'wielen-scooter.png'
            ],

            // --- STUUR MODULES ---
             // Auto
             [
                'name' => 'Auto Sportstuur',
                'type' => 'stuur',
                'cost' => 600,
                'assembly_time' => 1,
                'specifications' => [
                    'speciale_aanpassingen' => 'multifunctioneel',
                    'vorm' => 'ovaal'
                ],
                'image_path' => 'stuur-auto-sport.png'
            ],
            // Vrachtwagen
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
            // Bus
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
            
            // Geschikt voor: Step/Scooter
            [
                'name' => 'Step Stuur',
                'type' => 'stuur',
                'cost' => 200,
                'assembly_time' => 1,
                'specifications' => [
                    'speciale_aanpassingen' => 'lichtgewicht',
                    'vorm' => 'rond'
                ],
                'image_path' => 'stuur-step.png'
            ],
            // Geschikt voor: Bus/Vrachtwagen
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
               // Auto
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
            // Vrachtwagen
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
            // Bus
            [
                'name' => 'Bus Stoelen Set',
                'type' => 'stoelen',
                'cost' => 6000,
                'assembly_time' => 3,
                'specifications' => [
                    'aantal' => 20,
                    'stoffering' => 'stof'
                ],
                'image_path' => 'stoelen-bus.png'
            ],

            // Optioneel voor Step/Fiets
            [
                'name' => 'Fiets Zadel',
                'type' => 'stoelen',
                'cost' => 150,
                'assembly_time' => 1,
                'specifications' => [
                    'aantal' => 1,
                    'stoffering' => 'kunstleer'
                ],
                'image_path' => 'zadel-fiets.png'
            ],
            // Verplicht voor Personenauto/Bus
            [
                'name' => 'Luxe Stoelenset',
                'type' => 'stoelen',
                'cost' => 3000,
                'assembly_time' => 2,
                'specifications' => [
                    'aantal' => 5,
                    'stoffering' => 'leer'
                ],
                'image_path' => 'stoelen-luxe.png'
            ]
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}