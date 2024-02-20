<?php

namespace App\Services;

use App\Repositories\DeviceRepository;
use App\Interfaces\DeviceRepositoryInterface;
use App\Repositories\DeviceTypeRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use App\Repositories\DeviceDataRepository;
use App\Repositories\LocationRepository;
use App\Repositories\DeviceAssignRepository;
use Exception;

class DeviceService
{

    protected $deviceRepository, $deviceTypeRepository, $deviceAssignRepository, $locationRepository, $deviceDataRepository;

    /**
     * Constructor for the class.
     * Initializes the device and device type repositories.
     *
     * @param DeviceRepository $deviceRepository
     * @param DeviceTypeRepository $deviceTypeRepository
     * @param DeviceAssignRepository $deviceAssignRepository
     * @param LocationRepository $locationRepository
     * @param DeviceDataRepository $deviceDataRepository
     *
     *
     *
     */
    public function __construct(
        DeviceRepository $deviceRepository,
        DeviceTypeRepository $deviceTypeRepository,
        DeviceAssignRepository $deviceAssignRepository,
        LocationRepository $locationRepository,
        DeviceDataRepository $deviceDataRepository

    ) {
        $this->deviceRepository = $deviceRepository;
        $this->deviceTypeRepository = $deviceTypeRepository;
        $this->deviceAssignRepository = $deviceAssignRepository;
        $this->locationRepository = $locationRepository;
        $this->deviceDataRepository = $deviceDataRepository;
    }

    /**
     * Get Device Data
     */
    public function getDeviceAllMessages()
    {
        return $this->deviceDataRepository->getDeviceAllMessages();
    }
    /**
     * Get Device Data by ID
     */
    public function getDeviceData($id){
        return $this->deviceDataRepository->getDeviceData($id);
    }
    public function getDeviceLineChartData($id)
    {
        return $this->deviceDataRepository->getDeviceLineChartData($id);
    }
    /**
     *Get Device Data Counts
     */
    public function getDeviceDataCount()
    {
        return $this->deviceDataRepository->getDataCounts();
    }
    public function getCount()
    {
        return $this->deviceRepository->getCount();
    }
    /**
     * Get Device Statuses
     *
     * @return Array
     */
    public function getAllStatus()
    {
        return $this->deviceRepository->getAllStatus();
    }
    /**
     * Get All Device Health Array
     *
     * @return Array
     */
    public function getAllHealth()
    {
        return $this->deviceRepository->getAllHealth();
    }
    /**
     * Get Device Types
     *
     * @return Array
     */
    public function getAllDeviceTypes()
    {
        return $this->deviceTypeRepository->getAllDeviceType();
    }

    /**
     * Create New Device
     *
     * @return collection
     */
    public function createNewDevice(array $input)
    {
        return $this->deviceRepository->createDevice($input);
    }

    /**
     * Fetches Data from database for all devices
     *
     * @return collection
     */
    public function getDevices()
    {
        return $this->deviceRepository->getDevices();
    }

    /**
     * Puck Data from database for all devices
     *
     * @return collection
     */
    public function getPluckedDevices()
    {
        return $this->deviceRepository->getPluckedDevices();
    }

    /**
     * Fetches data for a datatable from the device repository.
     *
     * @param mixed $request The request containing data fetch parameters.
     * @return mixed Data for the datatable or null if an error occurs.
     */
    public function dataTable($request)
    {
        try {
            return $this->deviceRepository->datatable($request);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }


    public function destroy($id)
    {
        try {
            return $this->deviceRepository->destroy($id);
        } catch (Exception $e) {
        }
    }

    public function unAssign($id)
    {
        try {
            return $this->deviceAssignRepository->destroy($id);
        } catch (Exception $e) {
        }
    }

    public function findorfail($id)
    {
        try {
            return $this->deviceRepository->findorfail($id);
        } catch (Exception $e) {
        }
    }

    public function updateDevice($request, $id)
    {
        return $this->deviceRepository->updateDevice($request, $id);
    }

    public function assignDeviceToUser($request)
    {
        return $this->deviceAssignRepository->assignDeviceToUser($request);
    }

    public function deviceLocations($user, $input)
    {
        return $this->locationRepository->create($user, $input);
    }
    public function getAllDeviceTypeWithCounts()
    {
        return $this->deviceTypeRepository->getAllDeviceTypeWithCounts();
    }

}
