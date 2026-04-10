<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\MaintenanceLog;
use App\Models\MachineReading;
use App\Models\ServiceOrder;
use App\Models\StatusAlert;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedTestData extends Seeder
{
    public function run(): void
    {
        // Get test users
        $admin = User::where('email', 'admin@maintsys.local')->first();
        $tecnico = User::where('email', 'tecnico@maintsys.local')->first();

        if (!$admin || !$tecnico) {
            $this->command->error('❌ Usuários de teste não encontrados. Execute php artisan db:seed primeiro.');
            return;
        }

        // Create 10 machines
        $machines = Machine::factory(10)->create();

        foreach ($machines as $machine) {
            // Create 2-5 service orders per machine
            $orders = ServiceOrder::factory(rand(2, 5))
                ->for($machine)
                ->for($tecnico, 'technician')
                ->for($admin, 'creator')
                ->create();

            // Create status alerts
            StatusAlert::factory(rand(1, 3))
                ->for($machine)
                ->for($admin, 'triggeredBy')
                ->create();

            // Create maintenance logs
            MaintenanceLog::factory(rand(3, 8))
                ->for($machine)
                ->for($admin, 'user')
                ->create();

            // Create machine readings (sensor data)
            MachineReading::factory(rand(5, 15))
                ->for($machine)
                ->create();
        }

        $this->command->info('✅ Dados de teste criados com sucesso!');
        $this->command->line('- 10 máquinas');
        $this->command->line('- ~30 ordens de serviço');
        $this->command->line('- ~20 alertas de status');
        $this->command->line('- ~50 logs de manutenção');
        $this->command->line('- ~100 leituras de sensores');
    }
}
