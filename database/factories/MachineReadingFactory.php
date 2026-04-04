<?php

namespace Database\Factories;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

class MachineReadingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'machine_id' => Machine::factory(),
            'sensor_key' => $this->faker->unique()->word(),
            'value' => $this->faker->randomFloat(2, 0, 100),
            'unit' => $this->faker->randomElement(['°C', '%', 'psi', 'rpm', 'V']),
            'read_at' => $this->faker->dateTime(),
        ];
    }
}
