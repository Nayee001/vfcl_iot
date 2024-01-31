<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\DeviceService;
use App\Services\DashboardService;

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
}
