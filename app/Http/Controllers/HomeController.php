<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\DeviceService;
use App\Services\DashboardService;
use Exception;
use Illuminate\Support\Facades\Log;
class HomeController extends Controller
{
    /**
     * vFCL IOT Main Dashboard.
     *
     * @return void
     */

    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        if (isSuperAdmin()) {
            $managerCount = $this->dashboardService->getManagerCount();
            $userCount = $this->dashboardService->getUserCount();
            $deviceCount = $this->dashboardService->getDeviceCount();

            $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
            return view('dashboard.admin-dashboard', compact('managerCount', 'userCount', 'deviceTypesWithDeviceCount', 'deviceCount'));
        } elseif (isManager()) {
            $userCount = $this->dashboardService->getUserCount();
            $deviceCount = $this->dashboardService->getDeviceCount();

            $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
            return view('dashboard.manager-dashboard', compact('userCount', 'deviceTypesWithDeviceCount', 'deviceCount'));
        } else {
            $deviceCount = $this->dashboardService->getDeviceCount();

            $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
            return view('dashboard.admin-dashboard', compact('deviceTypesWithDeviceCount', 'deviceCount'));
        }
    }
    public function getDeviceDataCounts()
    {
        return $this->dashboardService->getDeviceDataCount();
    }
    public function getDeviceLineChartData($id)
    {
        return $this->dashboardService->getDeviceLineChartData($id);
    }

    public function getDeviceAllMessages()
    {
        return $this->dashboardService->getDeviceAllMessages();
    }
    public function getDeviceData($id)
    {
        return $this->dashboardService->getDeviceData($id);
    }

    public function getdeviceMessage($id)
    {
        return $this->dashboardService->getDeviceData($id);
    }

    /**
     * Retrieves devices for datatable asynchronously using AJAX.
     *
     * This function receives a request and delegates the task of data retrieval
     * to the deviceTypeRepository's dataTable method. In case of any exception,
     * it returns an exception message.
     *
     * @param Request $request The incoming request object, usually containing query parameters.
     * @return mixed Returns the datatable data for devices or an exception message.
     */
    public function getDashboardDevicesAjaxDatatable(Request $request)
    {
        try {
            return $this->dashboardService->dashboarddevicedataTable($request);
        } catch (Exception $e) {
            Log::error("Error fetching devices: {$e->getMessage()}");
            return exceptionMessage($e->getMessage());
        }
    }
}
