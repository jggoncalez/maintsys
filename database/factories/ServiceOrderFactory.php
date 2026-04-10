<?php

namespace Database\Factories;

use App\Models\Machine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'machine_id' => Machine::factory(),
            'technician_id' => User::factory(),
            'created_by' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(),
            'type' => $this->faker->randomElement(['preventive', 'corrective']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'completed', 'cancelled']),
            'started_at' => $this->faker->dateTime(),
            'completed_at' => $this->faker->dateTime(),
            'resolution_notes' => $this->faker->text(),
        ];
    }
}
