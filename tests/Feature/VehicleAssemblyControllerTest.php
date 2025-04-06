<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class VehicleAssemblyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_valid_vehicle_assembly()
    {
        // Maak eerst de benodigde modules aan
        $chassis = Module::factory()->create(['type' => 'chassis']);
        $stuur = Module::factory()->create(['type' => 'stuur']);
        $stoelen = Module::factory()->create(['type' => 'stoelen']);

        $response = $this->post(route('vehicle.assembly.store'), [
            'name' => 'Test Voertuig',
            'chassis' => $chassis->id,
            'stuur' => $stuur->id,
            'stoelen' => $stoelen->id,
        ]);

        // Debugging: toon de response als de test faalt
        if ($response->status() !== 302) {
            dd($response->status(), $response->content());
        }

        $response->assertStatus(302); // Redirect na succes
        $this->assertDatabaseHas('vehicles', ['name' => 'Test Voertuig']);
    }
}