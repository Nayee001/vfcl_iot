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
use App\Models\LocationName;
use App\Models\DeviceData;
use App\Models\DeviceLogs;
use App\Repositories\LocationNameRepository;
use App\Repositories\LocationRepository;
use App\Repositories\DeviceDataRepository;
use App\Repositories\DeviceLogsRepository;

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
        $this->app->bind(LocationNameRepository::class, function ($app) {
            return new LocationNameRepository($app->make(LocationName::class));
        });
        $this->app->bind(DeviceDataRepository::class, function ($app) {
            return new DeviceDataRepository($app->make(DeviceData::class));
        });
        $this->app->bind(DeviceLogsRepository::class, function ($app) {
            return new DeviceLogsRepository($app->make(DeviceLogs::class));
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
