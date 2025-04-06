<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function inkoperUser(): User
    {
        $role = Role::firstOrCreate(['name' => 'inkoper']);
        $user = User::factory()->create();
        $user->roles()->attach($role);
        return $user;
    }

    protected function monteurUser(): User
    {
        $role = Role::firstOrCreate(['name' => 'monteur']);
        $user = User::factory()->create();
        $user->roles()->attach($role);
        return $user;
    }

    protected function plannerUser(): User
    {
        $role = Role::firstOrCreate(['name' => 'planner']);
        $user = User::factory()->create();
        $user->roles()->attach($role);
        return $user;
    }

    protected function klantUser(): User
    {
        $role = Role::firstOrCreate(['name' => 'klant']);
        $user = User::factory()->create();
        $user->roles()->attach($role);
        return $user;
    }
}