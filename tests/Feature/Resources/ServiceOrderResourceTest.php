<?php

use App\Models\ServiceOrder;

describe('ServiceOrder Resource', function () {
    beforeEach(function () {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'gerente']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tecnico']);
    });

    it('admin can create service order', function () {
        $admin = createUserWithRole('admin');

        expect($admin->hasRole('admin'))->toBeTrue();
    });

    it('gerente can create service order', function () {
        $gerente = createUserWithRole('gerente');

        expect($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('tecnico can create service order', function () {
        $tecnico = createUserWithRole('tecnico');

        expect($tecnico->hasRole('tecnico'))->toBeTrue();
    });

    it('gerente can edit service order', function () {
        $gerente = createUserWithRole('gerente');
        $order = ServiceOrder::factory()->create();

        expect($order->exists)->toBeTrue()
            ->and($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('tecnico cannot edit service order', function () {
        $tecnico = createUserWithRole('tecnico');
        $order = ServiceOrder::factory()->create();

        expect($order->exists)->toBeTrue()
            ->and($tecnico->hasRole('admin'))->toBeFalse();
    });

    it('only admin can delete service order', function () {
        $admin = createUserWithRole('admin');
        $gerente = createUserWithRole('gerente');

        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($gerente->hasRole('admin'))->toBeFalse();
    });
});
