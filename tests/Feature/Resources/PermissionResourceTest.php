<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

describe('Permission Resource', function () {
    it('can create a new permission', function () {
        $permission = Permission::create(['name' => 'view_reports']);

        expect($permission->exists)->toBeTrue()
            ->and($permission->name)->toBe('view_reports');
    });

    it('can assign permission to role', function () {
        Permission::firstOrCreate(['name' => 'export_data']);
        $role = Role::firstOrCreate(['name' => 'exporter']);

        $role->givePermissionTo('export_data');

        expect($role->hasPermissionTo('export_data'))->toBeTrue();
    });

    it('can check if user has permission through role', function () {
        Permission::firstOrCreate(['name' => 'publish_posts']);
        $role = Role::firstOrCreate(['name' => 'author']);
        $role->givePermissionTo('publish_posts');

        $user = createUser();
        $user->assignRole('author');

        expect($user->hasPermissionTo('publish_posts'))->toBeTrue();
    });

    it('can remove permission from role', function () {
        Permission::firstOrCreate(['name' => 'delete_content']);
        $role = Role::firstOrCreate(['name' => 'moderator']);
        $role->givePermissionTo('delete_content');

        expect($role->hasPermissionTo('delete_content'))->toBeTrue();

        $role->revokePermissionTo('delete_content');

        expect($role->hasPermissionTo('delete_content'))->toBeFalse();
    });

    it('can delete a permission', function () {
        $permission = Permission::create(['name' => 'temporary_perm']);

        expect($permission->exists)->toBeTrue();

        $permission->delete();

        expect(Permission::where('name', 'temporary_perm')->exists())->toBeFalse();
    });

    it('permission names should be unique', function () {
        Permission::create(['name' => 'unique_perm']);

        expect(fn () => Permission::create(['name' => 'unique_perm']))
            ->toThrow(Exception::class);
    });
});
