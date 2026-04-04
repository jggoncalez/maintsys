<?php

use App\Models\Machine;

describe('Machine Resource', function () {
    beforeEach(function () {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'gerente']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tecnico']);
    });

    it('admin can create machine', function () {
        $admin = createUserWithRole('admin');

        expect($admin->hasRole('admin'))->toBeTrue();
    });

    it('gerente can create machine', function () {
        $gerente = createUserWithRole('gerente');

        expect($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('tecnico cannot create machine', function () {
        $tecnico = createUserWithRole('tecnico');

        expect($tecnico->hasRole('tecnico'))->toBeTrue()
            ->and($tecnico->hasRole('admin'))->toBeFalse();
    });

    it('admin can delete machine', function () {
        $admin = createUserWithRole('admin');
        $machine = Machine::factory()->create();

        expect($machine->exists)->toBeTrue()
            ->and($admin->hasRole('admin'))->toBeTrue();
    });
});

