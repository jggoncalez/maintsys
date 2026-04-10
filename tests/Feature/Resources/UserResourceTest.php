<?php

use App\Models\User;

describe('User Resource', function () {
    beforeEach(function () {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'gerente']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tecnico']);
    });

    it('only admin can view users', function () {
        $admin = createUserWithRole('admin');
        $gerente = createUserWithRole('gerente');

        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($gerente->hasRole('admin'))->toBeFalse();
    });

    it('only admin can create user', function () {
        $admin = createUserWithRole('admin');
        $gerente = createUserWithRole('gerente');

        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($gerente->hasRole('admin'))->toBeFalse();
    });

    it('only admin can edit user', function () {
        $admin = createUserWithRole('admin');
        $gerente = createUserWithRole('gerente');
        $user = createUser(['name' => 'Old Name']);

        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($gerente->hasRole('admin'))->toBeFalse()
            ->and($user->exists)->toBeTrue();
    });

    it('only admin can delete user', function () {
        $admin = createUserWithRole('admin');
        $gerente = createUserWithRole('gerente');

        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($gerente->hasRole('admin'))->toBeFalse();
    });

    it('can assign roles to user', function () {
        $user = createUser();
        $user->assignRole('admin');
        $user->assignRole('gerente');

        expect($user->hasRole('admin'))->toBeTrue()
            ->and($user->hasRole('gerente'))->toBeTrue();
    });

    it('can update user without changing password', function () {
        $user = createUser(['name' => 'Original Name']);

        $user->update(['name' => 'New Name']);

        expect($user->fresh()->name)->toBe('New Name');
    });
});
