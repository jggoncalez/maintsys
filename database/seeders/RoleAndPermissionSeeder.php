<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions (Status Alerts are read-only, auto-generated)
        $permissions = [
            // Machines
            'view_machines',
            'create_machines',
            'update_machines',
            'delete_machines',
            // Service Orders
            'view_service_orders',
            'create_service_orders',
            'update_service_orders',
            'delete_service_orders',
            // Maintenance Logs
            'view_maintenance_logs',
            'create_maintenance_logs',
            'update_maintenance_logs',
            'delete_maintenance_logs',
            // Machine Readings
            'view_machine_readings',
            // Status Alerts (read-only, auto-generated)
            'view_status_alerts',
            // Users
            'view_users',
            'create_users',
            'update_users',
            'delete_users',
        ];

        // Create permissions using Spatie command
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles using Spatie command
        // Admin - Full access
        $admin = Role::findOrCreate('admin');
        $admin->syncPermissions($permissions);

        // Gerente - Can view and update machines, full service orders, view logs and alerts and users
        $gerente = Role::findOrCreate('gerente');
        $gerente->syncPermissions([
            'view_machines', 'update_machines',
            'view_service_orders', 'create_service_orders', 'update_service_orders', 'delete_service_orders',
            'view_maintenance_logs',
            'view_status_alerts',
            'view_users',
        ]);

        // Técnico - Can view machines, service orders (create/update own), create logs, view alerts and users
        $tecnico = Role::findOrCreate('tecnico');
        $tecnico->syncPermissions([
            'view_machines',
            'view_service_orders', 'create_service_orders', 'update_service_orders',
            'view_maintenance_logs', 'create_maintenance_logs',
            'view_status_alerts',
            'view_users',
        ]);

        // Operador - Read-only access
        $operador = Role::findOrCreate('operador');
        $operador->syncPermissions([
            'view_machines',
            'view_service_orders',
            'view_maintenance_logs',
            'view_machine_readings',
            'view_status_alerts',
            'view_users',
        ]);
    }
}
