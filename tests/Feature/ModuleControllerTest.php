<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $inkoper;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Maak inkoper rol aan
        $inkoperRole = Role::create(['name' => 'inkoper']);
        
        // Maak testgebruiker aan en wijs rol toe
        $this->inkoper = User::factory()->create();
        $this->inkoper->roles()->attach($inkoperRole);
    }

    /** @test */
    public function it_can_list_modules_with_pagination()
    {
        Module::factory()->count(15)->create();

        $response = $this->actingAs($this->inkoper)
                        ->get(route('inkoper.modules.index'));

        $response->assertOk()
                ->assertViewHas('modules')
                ->assertSee('Next');
    }

    /** @test */
    public function it_validates_module_creation()
    {
        $response = $this->actingAs($this->inkoper)
                        ->post(route('inkoper.modules.store'), [
                            'name' => '',
                            'type' => 'invalid_type',
                            'cost' => 'not_a_number',
                            'assembly_time' => -1,
                            'specifications' => []
                        ]);
    
        $response->assertSessionHasErrors([
            'name', 
            'type', 
            'cost', 
            'assembly_time',
            'specifications'
        ]);
    }

    /** @test */
    public function it_can_update_a_module()
    {
        $module = Module::factory()->create();
        
        $response = $this->actingAs($this->inkoper)
                        ->put(route('inkoper.modules.update', $module), [
                            'name' => 'Updated Name',
                            'type' => $module->type,
                            'cost' => 1500,
                            'assembly_time' => 3,
                            'specifications' => [
                                'wheel_count' => 4,
                                'vehicle_type' => 'personenauto',
                                'length' => 400,
                                'width' => 186,
                                'height' => 165,
                                'compatible_wheels' => 'nikinella,centio'
                            ]
                        ]);

        $response->assertRedirect(route('inkoper.modules.index'))
                ->assertSessionHas('success');
                
        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'name' => 'Updated Name',
            'cost' => 1500
        ]);
    }

    /** @test */
    public function it_can_soft_delete_a_module()
    {
        $module = Module::factory()->create();

        $response = $this->actingAs($this->inkoper)
                        ->delete(route('inkoper.modules.destroy', $module));

        $response->assertRedirect(route('inkoper.modules.index'))
                ->assertSessionHas('success');
                
        $this->assertSoftDeleted($module);
    }

    /** @test */
    public function it_can_restore_a_module()
    {
        $module = Module::factory()->create(['deleted_at' => now()]);

        $response = $this->actingAs($this->inkoper)
                        ->post(route('inkoper.modules.restore', $module->id));

        $response->assertRedirect(route('inkoper.modules.index'))
                ->assertSessionHas('success');
                
        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function it_can_permanently_delete_a_module()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('module.jpg');
        $module = Module::factory()->create(['image_path' => 'modules/'.$file->hashName()]);

        $response = $this->actingAs($this->inkoper)
                        ->delete(route('inkoper.modules.force-delete', $module->id));

        $response->assertRedirect(route('inkoper.modules.index'))
                ->assertSessionHas('success');
                
        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
        Storage::disk('public')->assertMissing($module->image_path);
    }

    /** @test */
    public function unauthorized_users_cannot_access_module_management()
    {
        $roles = ['monteur', 'planner', 'klant'];
        
        foreach ($roles as $role) {
            $roleModel = Role::create(['name' => $role]);
            $user = User::factory()->create();
            $user->roles()->attach($roleModel);

            // Test index
            $response = $this->actingAs($user)
                            ->get(route('inkoper.modules.index'));
            $response->assertForbidden();

            // Test create
            $response = $this->actingAs($user)
                            ->get(route('inkoper.modules.create'));
            $response->assertForbidden();
        }
    }
}