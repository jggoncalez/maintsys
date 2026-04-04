<?php

namespace Database\Factories;

use App\Models\Machine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusAlertFactory extends Factory
{
    public function definition(): array
    {
        return [
            'machine_id' => Machine::factory(),
            'triggered_by' => User::factory(),
            'previous_status' => $this->faker->randomElement(['operational', 'maintenance', 'critical', 'offline']),
            'new_status' => $this->faker->randomElement(['operational', 'maintenance', 'critical', 'offline']),
            'message' => $this->faker->sentence(),
            'is_read' => $this->faker->boolean(),
            'triggered_at' => $this->faker->dateTime(),
        ];
    }
}
