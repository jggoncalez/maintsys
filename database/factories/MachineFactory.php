<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MachineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'serial_number' => 'MACH-' . $this->faker->unique()->numerify('######'),
            'name' => $this->faker->word() . ' Machine',
            'model' => $this->faker->word() . '-' . $this->faker->numerify('###'),
            'location' => $this->faker->word(),
            'status' => $this->faker->randomElement(['operational', 'maintenance', 'critical', 'offline']),
            'installed_at' => $this->faker->date(),
            'description' => $this->faker->text(),
            'image' => null,
            'last_reading_at' => $this->faker->dateTime(),
        ];
    }
}
