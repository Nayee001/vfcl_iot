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
            $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
            return view('dashboard.admin-dashboard', compact('managerCount','userCount','deviceTypesWithDeviceCount'));
        }elseif(isManager()){

            // return view('manager-home', compact('userCount', 'deviceCount', 'deviceList'));
        }else{
            // $userCount = User::where('created_by', '=', Auth::user()->id)->count();
            // $deviceCount = $this->dashboardService->getCount();
            // $deviceList = $this->dashboardService->getDevices();
            // return view('customer-home', compact('userCount', 'deviceCount', 'deviceList'));
        }

    }
}
