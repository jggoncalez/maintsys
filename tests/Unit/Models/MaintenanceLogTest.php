<?php

use App\Models\MaintenanceLog;
use App\Models\Machine;
use App\Models\ServiceOrder;
use App\Models\User;

describe('MaintenanceLog Model', function () {
    it('can create maintenance log', function () {
        $machine = Machine::factory()->create();
        $user = User::factory()->create();

        $log = MaintenanceLog::factory()->create([
            'machine_id' => $machine->id,
            'user_id' => $user->id,
            'action' => 'Inspeção',
            'description' => 'Inspeção visual realizada',
        ]);

        expect($log)->toBeInstanceOf(MaintenanceLog::class)
            ->action->toBe('Inspeção')
            ->machine_id->toBe($machine->id);
    });

    it('belongs to machine', function () {
        $machine = Machine::factory()->create();
        $log = MaintenanceLog::factory()->create(['machine_id' => $machine->id]);

        expect($log->machine_id)->toBe($machine->id)
            ->and($log->machine->id)->toBe($machine->id);
    });

    it('belongs to user', function () {
        $user = User::factory()->create();
        $log = MaintenanceLog::factory()->create(['user_id' => $user->id]);

        expect($log->user_id)->toBe($user->id)
            ->and($log->user->id)->toBe($user->id);
    });

    it('belongs to service order', function () {
        $order = ServiceOrder::factory()->create();
        $log = MaintenanceLog::factory()->create(['service_order_id' => $order->id]);

        expect($log->service_order_id)->toBe($order->id)
            ->and($log->serviceOrder->id)->toBe($order->id);
    });

    it('can have null service order', function () {
        $log = MaintenanceLog::factory()->create(['service_order_id' => null]);

        expect($log->service_order_id)->toBeNull();
    });
});
