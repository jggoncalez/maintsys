<?php

use App\Models\Machine;
use App\Models\StatusAlert;
use App\Models\User;

describe('Machine Model', function () {
    it('can create a machine', function () {
        $machine = Machine::factory()->create([
            'serial_number' => 'MACH-001',
            'name' => 'Máquina A',
            'status' => 'operational',
        ]);

        expect($machine)->toBeInstanceOf(Machine::class)
            ->serial_number->toBe('MACH-001')
            ->name->toBe('Máquina A')
            ->status->toBe('operational');
    });

    it('has operational scope', function () {
        Machine::factory()->create(['status' => 'operational']);
        Machine::factory()->create(['status' => 'maintenance']);

        $operationalMachines = Machine::operational()->get();

        expect($operationalMachines)->toHaveCount(1)
            ->first()->status->toBe('operational');
    });

    it('has in maintenance scope', function () {
        Machine::factory()->create(['status' => 'operational']);
        Machine::factory()->create(['status' => 'maintenance']);

        $maintenanceMachines = Machine::inMaintenance()->get();

        expect($maintenanceMachines)->toHaveCount(1)
            ->first()->status->toBe('maintenance');
    });

    it('has critical scope', function () {
        Machine::factory()->create(['status' => 'critical']);
        Machine::factory()->create(['status' => 'operational']);

        $criticalMachines = Machine::critical()->get();

        expect($criticalMachines)->toHaveCount(1)
            ->first()->status->toBe('critical');
    });

    it('has offline scope', function () {
        Machine::factory()->create(['status' => 'offline']);
        Machine::factory()->create(['status' => 'operational']);

        $offlineMachines = Machine::offline()->get();

        expect($offlineMachines)->toHaveCount(1)
            ->first()->status->toBe('offline');
    });

    it('creates status alert when status changes', function () {
        $user = createUser();
        $this->actingAs($user);

        $machine = Machine::factory()->create(['status' => 'operational']);

        $machine->update(['status' => 'critical']);

        $alert = StatusAlert::where('machine_id', $machine->id)->first();

        expect($alert)->not->toBeNull()
            ->previous_status->toBe('operational')
            ->new_status->toBe('critical');
    });

    it('has many service orders', function () {
        $machine = Machine::factory()->create();
        \App\Models\ServiceOrder::factory()->count(3)->create(['machine_id' => $machine->id]);

        expect($machine->serviceOrders)->toHaveCount(3)
            ->and($machine->serviceOrders->first()->machine_id)->toBe($machine->id);
    });

    it('has many maintenance logs', function () {
        $machine = Machine::factory()->create();
        \App\Models\MaintenanceLog::factory()->count(2)->create(['machine_id' => $machine->id]);

        expect($machine->maintenanceLogs)->toHaveCount(2)
            ->and($machine->maintenanceLogs->first()->machine_id)->toBe($machine->id);
    });

    it('has many readings', function () {
        $machine = Machine::factory()->create();
        \App\Models\MachineReading::factory()->count(5)->create(['machine_id' => $machine->id]);

        expect($machine->readings)->toHaveCount(5)
            ->and($machine->readings->first()->machine_id)->toBe($machine->id);
    });

    it('has many status alerts', function () {
        $machine = Machine::factory()->create();
        \App\Models\StatusAlert::factory()->count(4)->create(['machine_id' => $machine->id]);

        expect($machine->statusAlerts)->toHaveCount(4)
            ->and($machine->statusAlerts->first()->machine_id)->toBe($machine->id);
    });
});
