<?php

namespace App\Http\Controllers;

use App\Models\DeviceAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\DeviceService;
use App\Services\DashboardService;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Repositories\NotificationRepository;

class HomeController extends Controller
{
    /**
     * vFCL IOT Main Dashboard.
     *
     * @return void
     */

    protected $dashboardService;
    protected $notificationRepository;


    public function __construct(DashboardService $dashboardService, NotificationRepository $notificationRepository)
    {
        $this->dashboardService = $dashboardService;
        $this->notificationRepository = $notificationRepository;
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $managerCount = $this->dashboardService->getManagerCount();
        $userCount = $this->dashboardService->getUserCount();
        $deviceCount = $this->dashboardService->getDeviceCount();
        $locationCount = $this->dashboardService->getLocationNameCount();

        $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
        if (isSuperAdmin()) {
            return view('dashboard.admin-dashboard', compact('managerCount', 'userCount', 'deviceTypesWithDeviceCount', 'deviceCount', 'locationCount'));
        } elseif (isManager()) {
            $userCount = $this->dashboardService->getCountUsersAddedByManagers(Auth::id());
            return view('dashboard.manager-dashboard', compact('userCount', 'deviceTypesWithDeviceCount', 'deviceCount'));
        } else {
            $user = auth()->user();
            // dd($user);
            $showNewUserModel = $user->status == User::USER_STATUS['NEWUSER'];
            $firstPassword = $user->status == User::USER_STATUS['FIRSTTIMEPASSWORDCHANGED'];
            $notifications = $this->notificationRepository->notifictionCount($user->id);
            $unAuthnewDevices = DeviceAssignment::where('assign_to', $user->id)->where('connection_status', 'Authorized')->where('status', 'Accept')->get();
            // dd($unAuthnewDevices);
            return view('dashboard.customer-dashboard', [
                'unAuthnewDevices' => $unAuthnewDevices,
                'showNewUserModel' => $showNewUserModel,
                'firstPassword' => $firstPassword,
                'locationCount' => $locationCount,
                'user' => $user,
                'notifications' => $notifications
            ]);
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
