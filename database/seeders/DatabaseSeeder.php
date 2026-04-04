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

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@maintsys.local',
        ]);
        $admin->assignRole('admin');

        $manager = User::factory()->create([
            'name' => 'Gerente',
            'email' => 'gerente@maintsys.local',
        ]);
        $manager->assignRole('gerente');

        $tech = User::factory()->create([
            'name' => 'Técnico',
            'email' => 'tecnico@maintsys.local',
        ]);
        $tech->assignRole('tecnico');

        $operator = User::factory()->create([
            'name' => 'Operador',
            'email' => 'operador@maintsys.local',
        ]);
        $operator->assignRole('operador');
    }
}
