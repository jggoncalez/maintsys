<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles and permissions
        $this->call(RoleAndPermissionSeeder::class);

        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $manager = User::factory()->create([
            'name' => 'Gerente',
            'email' => 'gerente@example.com',
        ]);
        $manager->assignRole('gerente');

        $tech = User::factory()->create([
            'name' => 'Técnico',
            'email' => 'tecnico@example.com',
        ]);
        $tech->assignRole('tecnico');

        $operator = User::factory()->create([
            'name' => 'Operador',
            'email' => 'operador@example.com',
        ]);
        $operator->assignRole('operador');
    }
}
