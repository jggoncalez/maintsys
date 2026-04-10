<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateTestUsers extends Command
{
    protected $signature = 'app:create-test-users';
    protected $description = 'Create test users with different roles';

    public function handle()
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@test.com', 'role' => 'admin'],
            ['name' => 'Gerente', 'email' => 'gerente@test.com', 'role' => 'gerente'],
            ['name' => 'Técnico', 'email' => 'tecnico@test.com', 'role' => 'tecnico'],
            ['name' => 'Operador', 'email' => 'operador@test.com', 'role' => 'operador'],
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => bcrypt('password')
                ]);
                $user->assignRole($userData['role']);
                $this->info("✅ {$userData['email']} criado com role '{$userData['role']}'");
            } else {
                $this->line("ℹ️  {$userData['email']} já existe");
            }
        }
    }
}
