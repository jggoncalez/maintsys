<?php

namespace App\Providers;

use App\Models\Machine;
use App\Models\MaintenanceLog;
use App\Models\MachineReading;
use App\Models\ServiceOrder;
use App\Models\StatusAlert;
use App\Models\User;
use App\Policies\MachinePolicy;
use App\Policies\MaintenanceLogPolicy;
use App\Policies\MachineReadingPolicy;
use App\Policies\ServiceOrderPolicy;
use App\Policies\StatusAlertPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Machine::class => MachinePolicy::class,
        ServiceOrder::class => ServiceOrderPolicy::class,
        MaintenanceLog::class => MaintenanceLogPolicy::class,
        MachineReading::class => MachineReadingPolicy::class,
        StatusAlert::class => StatusAlertPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
