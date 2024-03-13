<?php

namespace App\Services;

use App\Interfaces\DashboardServiceInterface;
use App\Services\DeviceService;
use FFI\Exception;
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
    public function getDeviceAllMessages()
    {
        return $this->deviceService->getDeviceAllMessages();
    }
    public function getDeviceData($id)
    {
        return $this->deviceService->getDeviceData($id);
    }

    public function getDeviceLineChartData($id)
    {
        return $this->deviceService->getDeviceLineChartData($id);
    }
    /**
     * Fetches data for a datatable from the device repository.
     *
     * @param mixed $request The request containing data fetch parameters.
     * @return mixed Data for the datatable or null if an error occurs.
     */
    public function dashboarddevicedataTable($request)
    {
        try {
            return $this->deviceService->dashboarddevicedataTable($request);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
