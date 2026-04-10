<?php

use App\Models\ServiceOrder;
use App\Models\Machine;
use App\Models\User;

describe('ServiceOrder Model', function () {
    it('can create a service order', function () {
        $user = User::factory()->create();
        $machine = Machine::factory()->create();

        $order = ServiceOrder::factory()->create([
            'machine_id' => $machine->id,
            'technician_id' => $user->id,
            'created_by' => $user->id,
            'title' => 'Manutenção Preventiva',
            'status' => 'open',
        ]);

        expect($order)->toBeInstanceOf(ServiceOrder::class)
            ->title->toBe('Manutenção Preventiva')
            ->status->toBe('open');
    });

    it('can check if order is open', function () {
        $order = ServiceOrder::factory()->create(['status' => 'open']);
        $closedOrder = ServiceOrder::factory()->create(['status' => 'completed']);

        expect($order->isOpen())->toBeTrue()
            ->and($closedOrder->isOpen())->toBeFalse();
    });

    it('can check if order is critical', function () {
        $criticalOrder = ServiceOrder::factory()->create(['priority' => 'critical']);
        $normalOrder = ServiceOrder::factory()->create(['priority' => 'low']);

        expect($criticalOrder->isCritical())->toBeTrue()
            ->and($normalOrder->isCritical())->toBeFalse();
    });

    it('can start service order', function () {
        $order = ServiceOrder::factory()->create(['status' => 'open', 'started_at' => null]);

        $order->start();

        expect($order->status)->toBe('in_progress')
            ->and($order->started_at)->not->toBeNull();
    });

    it('can complete service order with notes', function () {
        $order = ServiceOrder::factory()->create([
            'status' => 'in_progress',
            'resolution_notes' => null,
        ]);

        $order->complete('Manutenção concluída com sucesso');

        expect($order->status)->toBe('completed')
            ->and($order->resolution_notes)->toBe('Manutenção concluída com sucesso')
            ->and($order->completed_at)->not->toBeNull();
    });

    it('belongs to machine', function () {
        $machine = Machine::factory()->create();
        $order = ServiceOrder::factory()->create(['machine_id' => $machine->id]);

        expect($order->machine_id)->toBe($machine->id)
            ->and($order->machine->id)->toBe($machine->id);
    });

    it('belongs to technician', function () {
        $user = User::factory()->create();
        $order = ServiceOrder::factory()->create(['technician_id' => $user->id]);

        expect($order->technician_id)->toBe($user->id)
            ->and($order->technician->id)->toBe($user->id);
    });

    it('belongs to creator', function () {
        $user = User::factory()->create();
        $order = ServiceOrder::factory()->create(['created_by' => $user->id]);

        expect($order->created_by)->toBe($user->id)
            ->and($order->creator->id)->toBe($user->id);
    });

    it('has many maintenance logs', function () {
        $order = ServiceOrder::factory()->create();
        \App\Models\MaintenanceLog::factory()->count(3)->create(['service_order_id' => $order->id]);

        expect($order->maintenanceLogs)->toHaveCount(3)
            ->and($order->maintenanceLogs->first()->service_order_id)->toBe($order->id);
    });
});
