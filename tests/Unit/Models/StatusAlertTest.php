<?php

use App\Models\StatusAlert;
use App\Models\Machine;
use App\Models\User;

describe('StatusAlert Model', function () {
    it('can create status alert', function () {
        $machine = Machine::factory()->create();
        $user = User::factory()->create();

        $alert = StatusAlert::factory()->create([
            'machine_id' => $machine->id,
            'triggered_by' => $user->id,
            'previous_status' => 'operational',
            'new_status' => 'critical',
        ]);

        expect($alert)->toBeInstanceOf(StatusAlert::class)
            ->previous_status->toBe('operational')
            ->new_status->toBe('critical');
    });

    it('belongs to machine', function () {
        $machine = Machine::factory()->create();
        $alert = StatusAlert::factory()->create(['machine_id' => $machine->id]);

        expect($alert->machine_id)->toBe($machine->id)
            ->and($alert->machine->id)->toBe($machine->id);
    });

    it('belongs to triggered by user', function () {
        $user = User::factory()->create();
        $alert = StatusAlert::factory()->create(['triggered_by' => $user->id]);

        expect($alert->triggered_by)->toBe($user->id)
            ->and($alert->triggeredBy->id)->toBe($user->id);
    });

    it('casts is_read to boolean', function () {
        $alertRead = StatusAlert::factory()->create(['is_read' => true]);
        $alertUnread = StatusAlert::factory()->create(['is_read' => false]);

        expect($alertRead->is_read)->toBeTrue()
            ->and($alertUnread->is_read)->toBeFalse();
    });

    it('casts triggered_at to datetime', function () {
        $alert = StatusAlert::factory()->create();

        expect($alert->triggered_at)->toBeInstanceOf(\Carbon\Carbon::class);
    });

    it('can have null triggered_by', function () {
        $alert = StatusAlert::factory()->create(['triggered_by' => null]);

        expect($alert->triggered_by)->toBeNull();
    });
});
