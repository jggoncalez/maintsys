<?php

namespace Database\Factories;

use App\Models\Machine;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'machine_id' => Machine::factory(),
            'service_order_id' => ServiceOrder::factory(),
            'user_id' => User::factory(),
            'action' => $this->faker->word(),
            'description' => $this->faker->text(),
            'defect_type' => $this->faker->word(),
            'logged_at' => $this->faker->dateTime(),
        ];
    }
}
