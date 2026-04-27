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

        $users = [
            ['name' => 'Admin',    'email' => 'admin@maintsys.local',    'role' => 'admin'],
            ['name' => 'Gerente',  'email' => 'gerente@maintsys.local',  'role' => 'gerente'],
            ['name' => 'Técnico',  'email' => 'tecnico@maintsys.local',  'role' => 'tecnico'],
            ['name' => 'Operador', 'email' => 'operador@maintsys.local', 'role' => 'operador'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name'     => $userData['name'],
                'email'    => $userData['email'],
                'password' => bcrypt('password'),
            ]);
            $user->assignRole($userData['role']);
        }
    }
}
