<?php

namespace App\Providers;

use App\Models\Device;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\DeviceTypeRepository;
use App\Models\User;
use App\Models\DeviceType;
use App\Models\DeviceAssignment;
use App\Repositories\DeviceAssignRepository;
use App\Repositories\DeviceRepository;
use App\Models\Location;
use App\Repositories\LocationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register Repositories to Models (Binding dynamically).
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make(User::class));
        });

        $this->app->bind(DeviceTypeRepository::class, function ($app) {
            return new DeviceTypeRepository($app->make(DeviceType::class));
        });

        $this->app->bind(DeviceRepository::class, function ($app) {
            return new DeviceRepository($app->make(Device::class));
        });

        $this->app->bind(DeviceAssignRepository::class, function ($app) {
            return new DeviceAssignRepository($app->make(DeviceAssignment::class));
        });

        $this->app->bind(LocationRepository::class, function ($app) {
            return new LocationRepository($app->make(Location::class));
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
