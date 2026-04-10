<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

describe('Role Resource', function () {
    beforeEach(function () {
        // Create roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'gerente']);
        Role::firstOrCreate(['name' => 'tecnico']);
        Role::firstOrCreate(['name' => 'operador']);

        // Create permissions
        Permission::firstOrCreate(['name' => 'view_machines']);
        Permission::firstOrCreate(['name' => 'create_machines']);
        Permission::firstOrCreate(['name' => 'update_machines']);
        Permission::firstOrCreate(['name' => 'delete_machines']);
        Permission::firstOrCreate(['name' => 'view_service_orders']);
    });

    it('only admin can view roles', function () {
        $admin = createUserWithRole('admin');
        $gerente = createUserWithRole('gerente');

        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('can create a new role', function () {
        $role = Role::create(['name' => 'supervisor']);

        expect($role->exists)->toBeTrue()
            ->and($role->name)->toBe('supervisor');
    });

    it('can assign permissions to role', function () {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::firstOrCreate(['name' => 'view_machines']);

        $role->givePermissionTo($permission);

        expect($role->hasPermissionTo('view_machines'))->toBeTrue();
    });

    it('can sync multiple permissions to role', function () {
        $role = Role::create(['name' => 'manager']);
        $permissions = [
            'view_machines',
            'create_machines',
            'update_machines',
        ];

        $role->syncPermissions($permissions);

        expect($role->hasPermissionTo('view_machines'))->toBeTrue()
            ->and($role->hasPermissionTo('create_machines'))->toBeTrue()
            ->and($role->hasPermissionTo('update_machines'))->toBeTrue();
    });

    it('can delete a role', function () {
        $role = Role::create(['name' => 'deletable']);

        expect($role->exists)->toBeTrue();

        $role->delete();

        expect(Role::where('name', 'deletable')->exists())->toBeFalse();
    });

    it('users inherit permissions from role', function () {
        $role = Role::firstOrCreate(['name' => 'viewer']);
        $role->syncPermissions(['view_machines']);

        $user = createUser();
        $user->assignRole('viewer');

        expect($user->hasPermissionTo('view_machines'))->toBeTrue();
    });
});
