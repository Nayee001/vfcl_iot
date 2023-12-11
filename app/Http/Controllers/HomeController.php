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

    public function __construct(DeviceService $dashboardService)
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
        $userCount = User::where('created_by', '=', Auth::user()->id)->count();
        $deviceCount = $this->dashboardService->getCount();
        $deviceList = $this->dashboardService->getDevices();
        return view('home', compact('userCount', 'deviceCount', 'deviceList'));
    }
}
