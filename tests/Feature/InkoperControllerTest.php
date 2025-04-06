<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InkoperControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $inkoper;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Maak rollen aan
        Role::create(['name' => 'inkoper']);
        Role::create(['name' => 'monteur']);
        Role::create(['name' => 'planner']);
        Role::create(['name' => 'klant']);
        
        $this->inkoper = $this->inkoperUser();
        Module::factory()->count(5)->create();
    }

    public function test_it_shows_dashboard_with_module_stats()
    {
        $response = $this->actingAs($this->inkoper)
                        ->get(route('inkoper.dashboard'));

        $response->assertOk()
                ->assertViewHasAll([
                    'modulesCount',
                    'latestModule',
                    'moduleTypesCount',
                    'recentModules'
                ]);
    }

    public function test_unauthorized_users_cannot_access_dashboard()
    {
        $unauthorizedRoles = ['monteur', 'planner', 'klant'];
        
        foreach ($unauthorizedRoles as $role) {
            $user = User::factory()->create();
            $user->roles()->attach(Role::where('name', $role)->first());
            
            $response = $this->actingAs($user)
                            ->get(route('inkoper.dashboard'));
            
            $response->assertForbidden();
        }
    }

    public function test_dashboard_shows_correct_module_count()
    {
        $moduleCount = Module::count();
        
        $response = $this->actingAs($this->inkoper)
                        ->get(route('inkoper.dashboard'));
        
        $this->assertEquals($moduleCount, $response->viewData('modulesCount'));
    }
}