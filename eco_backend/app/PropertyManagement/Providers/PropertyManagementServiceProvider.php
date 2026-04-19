<?php

namespace App\PropertyManagement\Providers;

use App\PropertyManagement\Repositories\Buildings\BuildingRepository;
use App\PropertyManagement\Repositories\Buildings\Interfaces\BuildingRepositoryInterface;
use App\PropertyManagement\Repositories\Contracts\ContractRepository;
use App\PropertyManagement\Repositories\Contracts\Interfaces\ContractRepositoryInterface;
use App\PropertyManagement\Repositories\Payments\Interfaces\PaymentRepositoryInterface;
use App\PropertyManagement\Repositories\Payments\PaymentRepository;
use App\PropertyManagement\Repositories\Tenants\Interfaces\TenantRepositoryInterface;
use App\PropertyManagement\Repositories\Tenants\TenantRepository;
use App\PropertyManagement\Services\Buildings\BuildingService;
use App\PropertyManagement\Services\Contracts\ContractService;
use App\PropertyManagement\Services\Notifications\NotificationService;
use App\PropertyManagement\Services\Payments\PaymentService;
use App\PropertyManagement\Services\Tenants\TenantService;
use Illuminate\Support\ServiceProvider;

class PropertyManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Repositories
        $this->app->bind(ContractRepositoryInterface::class, ContractRepository::class);
        $this->app->bind(TenantRepositoryInterface::class, TenantRepository::class);
        $this->app->bind(BuildingRepositoryInterface::class, BuildingRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        // Register Services
        $this->app->singleton(ContractService::class, function ($app) {
            return new ContractService(
                $app->make(ContractRepositoryInterface::class),
                $app->make(PaymentService::class)
            );
        });

        $this->app->singleton(TenantService::class, function ($app) {
            return new TenantService(
                $app->make(TenantRepositoryInterface::class)
            );
        });

        $this->app->singleton(BuildingService::class, function ($app) {
            return new BuildingService(
                $app->make(BuildingRepositoryInterface::class)
            );
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService(
                $app->make(PaymentRepositoryInterface::class),
                $app->make(NotificationService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

