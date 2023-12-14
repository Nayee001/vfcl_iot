<?php

namespace App\Services;

use App\Interfaces\DashboardServiceInterface;
use App\Services\DeviceService;
use App\Services\UserService;

class DashboardService implements DashboardServiceInterface
{
    protected $deviceService, $userService;

    /**
     * Constructor for the class.
     * Initializes the device and device type repositories.
     *
     * @param DeviceService $deviceService
     * @param UserService $userService
     *
     *
     */
    public function __construct(
        DeviceService $deviceService,
        UserService $userService
    ) {
        $this->deviceService = $deviceService;
        $this->userService = $userService;
    }

    public function getManagerCount()
    {
        return $this->userService->getManagerCount();
    }
    public function getUserCount()
    {
        return $this->userService->getUserCount();
    }

    public function getDeviceTypeWithDevicesCount()
    {
        return $this->deviceService->getAllDeviceTypeWithCounts();
    }
    public function getDeviceCount()
    {
        return $this->deviceService->getCount();
    }
    public function getDeviceDataCount()
    {
        return $this->deviceService->getDeviceDataCount();
    }
    public function getDeviceData()
    {
        $this->deviceService->getDeviceData();
    }
}
