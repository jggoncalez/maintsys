<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)
    ->in('Unit', 'Feature');

// Setup roles for feature tests
beforeEach(function () {
    if (! Role::where('name', 'admin')->exists()) {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'gerente']);
        Role::create(['name' => 'tecnico']);
    }
})->in('Feature');

// Helpers
function createUser(array $attributes = []): User
{
    return User::factory()->create($attributes);
}

function createUserWithRole(string $role, array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    $user->assignRole($role);
    return $user;
}

