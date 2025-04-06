<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Module;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MonteurControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $compatibleModules;

    protected function setUp(): void
    {
        parent::setUp();

        // Eerst basis user aanmaken zonder rol
        $this->user = User::factory()->create();

        // Test modules aanmaken die bij elkaar passen
        $this->compatibleModules = [
            'chassis' => Module::create([
                'name' => 'Test Chassis',
                'type' => 'chassis',
                'specifications' => ['wielen' => 4]
            ]),
            'wheels' => Module::create([
                'name' => 'Test Wheels',
                'type' => 'wielen',
                'specifications' => ['aantal' => 4]
            ]),
            'drivetrain' => Module::create([
                'name' => 'Test Drivetrain',
                'type' => 'aandrijving'
            ]),
            'steering' => Module::create([
                'name' => 'Test Steering',
                'type' => 'stuur'
            ]),
            'seats' => Module::create([
                'name' => 'Test Seats',
                'type' => 'stoelen'
            ])
        ];
    }

    public function test_can_assemble_vehicle_with_valid_modules()
    {
        $response = $this->actingAs($this->user)
            ->post('/vehicles', [
                'name' => 'Test Vehicle',
                'chassis_id' => $this->compatibleModules['chassis']->id,
                'wheels_id' => $this->compatibleModules['wheels']->id,
                'drivetrain_id' => $this->compatibleModules['drivetrain']->id,
                'steering_id' => $this->compatibleModules['steering']->id,
                'seats_id' => $this->compatibleModules['seats']->id
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vehicles', ['name' => 'Test Vehicle']);
    }

    public function test_cannot_assemble_with_missing_required_modules()
    {
        $response = $this->actingAs($this->user)
            ->post('/vehicles', [
                'name' => 'Incomplete Vehicle',
                'chassis_id' => $this->compatibleModules['chassis']->id
                // Ontbrekende modules
            ]);

        $response->assertSessionHasErrors(['wheels_id']);
    }

    public function test_can_view_assembled_vehicle()
    {
        $vehicle = Vehicle::create([
            'name' => 'Sample Vehicle',
            'user_id' => $this->user->id,
            'chassis_id' => $this->compatibleModules['chassis']->id,
            'wheels_id' => $this->compatibleModules['wheels']->id,
            'drivetrain_id' => $this->compatibleModules['drivetrain']->id,
            'steering_id' => $this->compatibleModules['steering']->id,
            'seats_id' => $this->compatibleModules['seats']->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/vehicles/' . $vehicle->id);

        $response->assertStatus(200);
        $response->assertSee('Sample Vehicle');
    }
}