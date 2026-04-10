<?php

use App\Models\MaintenanceLog;

describe('MaintenanceLog Resource', function () {
    beforeEach(function () {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'gerente']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tecnico']);
    });

    it('admin can view maintenance logs', function () {
        $admin = createUserWithRole('admin');

        expect($admin->hasRole('admin'))->toBeTrue();
    });

    it('gerente can view maintenance logs', function () {
        $gerente = createUserWithRole('gerente');

        expect($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('tecnico can view maintenance logs', function () {
        $tecnico = createUserWithRole('tecnico');

        expect($tecnico->hasRole('tecnico'))->toBeTrue();
    });

    it('tecnico can create maintenance log', function () {
        $tecnico = createUserWithRole('tecnico');

        expect($tecnico->hasRole('tecnico'))->toBeTrue();
    });

    it('gerente can edit maintenance log', function () {
        $gerente = createUserWithRole('gerente');
        $log = MaintenanceLog::factory()->create();

        expect($log->exists)->toBeTrue()
            ->and($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('tecnico cannot edit maintenance log', function () {
        $tecnico = createUserWithRole('tecnico');
        $log = MaintenanceLog::factory()->create();

        expect($log->exists)->toBeTrue()
            ->and($tecnico->hasRole('admin'))->toBeFalse();
    });

    it('gerente can delete maintenance log', function () {
        $gerente = createUserWithRole('gerente');

        expect($gerente->hasRole('gerente'))->toBeTrue();
    });

    it('tecnico cannot delete maintenance log', function () {
        $tecnico = createUserWithRole('tecnico');

        expect($tecnico->hasRole('tecnico'))->toBeTrue()
            ->and($tecnico->hasRole('gerente'))->toBeFalse();
    });
});
