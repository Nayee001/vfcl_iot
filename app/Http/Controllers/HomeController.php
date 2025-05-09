<?php

namespace App\Http\Controllers;

use App\Models\DeviceAssignment;
use App\Models\DeviceData;
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
    protected $dashboardService;
    protected $notificationRepository;

    /**
     * Constructor to inject services and middleware.
     *
     * @param DashboardService $dashboardService
     * @param NotificationRepository $notificationRepository
     */
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
        try {
            // Retrieve common dashboard data
            $managerCount = $this->dashboardService->getManagerCount();
            $userCount = $this->dashboardService->getUserCount();
            $getDeviceTotalCount = $this->dashboardService->getDeviceTotalCount();
            $getTotalActiveDevice = $this->dashboardService->getTotalActiveDevice();
            $locationCount = $this->dashboardService->getLocationNameCount();
            $deviceTypesWithDeviceCount = $this->dashboardService->getDeviceTypeWithDevicesCount();
            $deviceDataCount = DeviceData::count();
            // Customer Dashboard
            $user = auth()->user();
            $showNewUserModel = $user->status === User::USER_STATUS['NEWUSER'];
            $firstPassword = $user->status === User::USER_STATUS['FIRSTTIMEPASSWORDCHANGED'];
            $notifications = $this->notificationRepository->notifictionCount($user->id);
            $unAuthnewDevices = DeviceAssignment::where('assign_to', $user->id)
                ->where('connection_status', 'Not Authorized')
                ->where('status', 'Reject')
                ->get();
            // Role-based view rendering
            if (isSuperAdmin()) {
                $unAuth = DeviceAssignment::where('connection_status', 'Not Authorized')
                    ->where('status', 'Reject')
                    ->count();
                return view('dashboard.admin-dashboard', compact(
                    'managerCount',
                    'deviceDataCount',
                    'getTotalActiveDevice',
                    'userCount',
                    'unAuth',
                    'deviceTypesWithDeviceCount',
                    'getDeviceTotalCount',
                    'locationCount'
                ));
            }


            if (isManager()) {
                $userCount = $this->dashboardService->getCountUsersAddedByManagers(Auth::id());
                return view('dashboard.manager-dashboard', compact(
                    'unAuthnewDevices',
                    'showNewUserModel',
                    'firstPassword',
                    'userCount',
                    'deviceTypesWithDeviceCount',
                    'getDeviceTotalCount',
                    'getTotalActiveDevice',
                    'user'
                ));
            }

            return view('dashboard.customer-dashboard', [
                'unAuthnewDevices' => $unAuthnewDevices,
                'showNewUserModel' => $showNewUserModel,
                'firstPassword' => $firstPassword,
                'locationCount' => $locationCount,
                'user' => $user,
                'notifications' => $notifications,
                'getDeviceTotalCount' => $getDeviceTotalCount,
                'getTotalActiveDevice' => $getTotalActiveDevice
            ]);
        } catch (Exception $e) {
            Log::error("Error loading dashboard: {$e->getMessage()}");
            return view('errors.500');  // Show an error page to the user
        }
    }

    /**
     * Get device data counts for the dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceDataCounts()
    {
        try {
            return response()->json($this->dashboardService->getDeviceDataCount());
        } catch (Exception $e) {
            Log::error("Error fetching device data counts: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve data counts'], 500);
        }
    }

    /**
     * Get device line chart data for a specific device.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceLineChartData($id)
    {
        try {
            return response()->json($this->dashboardService->getDeviceLineChartData($id));
        } catch (Exception $e) {
            Log::error("Error fetching line chart data for device ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve line chart data'], 500);
        }
    }

    public function getFaultData()
    {
        // Get device IDs assigned to the authenticated user
        if(isManager()){
            $deviceIds = DeviceAssignment::where('manager_id', Auth::id())
            ->pluck('device_id');
        }else{
            $deviceIds = DeviceAssignment::where('assign_to', Auth::id())
            ->pluck('device_id');
        }


        // Fetch fault data for the assigned devices along with device names
        $faultData = DeviceData::whereIn('device_data.device_id', $deviceIds)
            ->whereNotNull('device_data.fault_status') // Ensure we only fetch records with a fault status
            ->join('devices', 'device_data.device_id', '=', 'devices.id') // Join with the devices table
            ->selectRaw('device_data.fault_status, COUNT(*) as count, device_data.device_id, devices.name')
            ->groupBy('device_data.fault_status', 'device_data.device_id', 'devices.name')
            ->get();

        return response()->json([
            'assignedDeviceIds' => $deviceIds,
            'faultData' => $faultData
        ]);
    }


    /**
     * Get all device messages asynchronously.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceAllMessages()
    {
        try {
            return response()->json($this->dashboardService->getDeviceAllMessages());
        } catch (Exception $e) {
            Log::error("Error fetching all device messages: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve device messages'], 500);
        }
    }

    /**
     * Get detailed data for a specific device.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceData($id)
    {
        try {
            return response()->json($this->dashboardService->getDeviceData($id));
        } catch (Exception $e) {
            Log::error("Error fetching data for device ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve device data'], 500);
        }
    }

    /**
     * Get device-specific messages.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getdeviceMessage($id)
    {
        try {
            return response()->json($this->dashboardService->getDeviceData($id));
        } catch (Exception $e) {
            Log::error("Error fetching messages for device ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve device messages'], 500);
        }
    }

    /**
     * Fetch devices asynchronously for a datatable via AJAX.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardDevicesAjaxDatatable(Request $request)
    {
        try {
            return $this->dashboardService->dashboarddevicedataTable($request);
        } catch (Exception $e) {
            Log::error("Error fetching devices for datatable: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve devices'], 500);
        }
    }
}
