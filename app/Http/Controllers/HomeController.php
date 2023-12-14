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
        if(isSuperAdmin()){
            // $userCount = User::where('created_by', '=', Auth::user()->id)->count();
            $managerCount = $this->dashboardService->getManagerCount();
            $userCount = $this->dashboardService->getUserCount();
            $deviceCount = $this->dashboardService->getDeviceCount();

            $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
            return view('dashboard.admin-dashboard', compact('managerCount','userCount','deviceTypesWithDeviceCount','deviceCount'));
        }elseif(isManager()){
        }else{
            // $userCount = User::where('created_by', '=', Auth::user()->id)->count();
            // $deviceList = $this->dashboardService->getDevices();
            // return view('customer-home', compact('userCount', 'deviceCount', 'deviceList'));
        }

    }
    public function getDeviceDataCounts()
    {
        return $this->dashboardService->getDeviceDataCount();
    }

    public function getDeviceData(){
        return $this->dashboardService->getDeviceData();
    }
}
