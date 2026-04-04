<?php

namespace App\Console\Commands;

use App\Models\Machine;
use App\Models\ServiceOrder;
use App\Models\MaintenanceLog;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SeedTestData extends Command
{
    protected $signature = 'seed:test-data {--count=5 : Number of records to create}';

    protected $description = 'Seed test data for development and testing';

    public function handle(): int
    {
        $count = $this->option('count');

        $this->info('🌱 Seeding test data...');

        // Create roles
        $this->info('Creating roles...');
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'gerente']);
        Role::firstOrCreate(['name' => 'tecnico']);

        // Create admin user
        $this->info('Creating admin user...');
        $admin = User::updateOrCreate(
            ['email' => 'admin@maintsys.local'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create gerente user
        $this->info('Creating gerente user...');
        $gerente = User::updateOrCreate(
            ['email' => 'gerente@maintsys.local'],
            [
                'name' => 'Gerente User',
                'password' => bcrypt('password'),
            ]
        );
        $gerente->assignRole('gerente');

        // Create tecnico users
        $this->info('Creating tecnico users...');
        for ($i = 1; $i <= $count; $i++) {
            $tecnico = User::updateOrCreate(
                ['email' => "tecnico{$i}@maintsys.local"],
                [
                    'name' => "Técnico {$i}",
                    'password' => bcrypt('password'),
                ]
            );
            $tecnico->assignRole('tecnico');
        }

        // Create machines
        $this->info("Creating {$count} machines...");
        Machine::factory($count)->create();

        // Create service orders
        $this->info("Creating {$count} service orders...");
        ServiceOrder::factory($count)->create();

        // Create maintenance logs
        $this->info("Creating {$count} maintenance logs...");
        MaintenanceLog::factory($count)->create();

        $this->info('✅ Test data seeded successfully!');
        $this->info('');
        $this->info('Login credentials:');
        $this->info('  Admin: admin@maintsys.local / password');
        $this->info('  Gerente: gerente@maintsys.local / password');
        $this->info("  Tecnico: tecnico{1..{$count}}@maintsys.local / password");

        return 0;
    }
}
