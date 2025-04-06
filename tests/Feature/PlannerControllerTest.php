<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use App\Models\Robot;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlannerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Zorg dat alle benodigde database tabellen bestaan
        $this->artisan('migrate:fresh');
        
        // Maak test data aan
        $this->customer = Customer::factory()->create();
        
        // Robots aanmaken zonder factory (voor het geval de factory nog niet werkt)
        Robot::create(['name' => 'TwoWheels']);
        Robot::create(['name' => 'HeavyD']);
        Robot::create(['name' => 'HydroBoy']);
    }

    /** @test */
    public function dashboard_displays_correct_vehicles()
    {
        // Arrange
        $conceptVehicle = Vehicle::create([
            'status' => 'concept',
            'customer_id' => $this->customer->id
        ]);
        
        $productionVehicle = Vehicle::create([
            'status' => 'in_productie',
            'customer_id' => $this->customer->id
        ]);
        
        $completedVehicle = Vehicle::create([
            'status' => 'voltooid',
            'customer_id' => $this->customer->id
        ]);

        // Act - gebruik een concrete route of de volledige URL
        $response = $this->get('/planner/dashboard'); // Pas dit aan naar jouw werkelijke route

        // Assert
        $response->assertStatus(200);
        
        // Controleer of de pagina de juiste voertuigen toont
        $response->assertSee($conceptVehicle->id);
        $response->assertSee($productionVehicle->id);
        $response->assertDontSee($completedVehicle->id);
    }
}