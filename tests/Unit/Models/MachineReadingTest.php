<?php

use App\Models\MachineReading;
use App\Models\Machine;

describe('MachineReading Model', function () {
    it('can create machine reading', function () {
        $machine = Machine::factory()->create();

        $reading = MachineReading::factory()->create([
            'machine_id' => $machine->id,
            'sensor_key' => 'temperature',
            'value' => 35.5,
            'unit' => '°C',
        ]);

        expect($reading)->toBeInstanceOf(MachineReading::class)
            ->sensor_key->toBe('temperature');
    });

    it('belongs to machine', function () {
        $machine = Machine::factory()->create();
        $reading = MachineReading::factory()->create(['machine_id' => $machine->id]);

        expect($reading->machine_id)->toBe($machine->id)
            ->and($reading->machine->id)->toBe($machine->id);
    });

    it('casts read_at to datetime', function () {
        $reading = MachineReading::factory()->create();

        expect($reading->read_at)->toBeInstanceOf(\Carbon\Carbon::class);
    });

    it('casts value to decimal', function () {
        $reading = MachineReading::factory()->create(['value' => 42.50]);

        expect($reading->getRawOriginal('value'))->toEqual('42.50');
    });
});
