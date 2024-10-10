<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\DeviceService;
use App\Services\UserService;
use App\Http\Requests\StoreDeviceRequest;
use App\Exceptions\DeviceCreationException;
use App\Http\Requests\StoreAssignDevice;
use App\Http\Requests\UpdateDeviceRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\MqttService;
use App\Models\Device;

use App\Http\Requests\VerifyDeviceViaApi;
use App\Models\DeviceAssignment;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $deviceService;
    protected $userService;
    protected $mqttService;

    function __construct(DeviceService $deviceService, UserService $userService, MqttService $mqttService)
    {
        $this->deviceService = $deviceService;
        $this->userService = $userService;
        $this->mqttService = $mqttService;
        $this->middleware('permission:device-list', ['only' => ['index', 'store', 'deviceAjaxDatatable']]);
        $this->middleware('permission:device-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:device-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:device-delete', ['only' => ['destroy']]);
    }

    public function dashboard($device_id): view
    {
        return view('devices.dashboard', compact('device_id'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): view
    {
        if (isSuperAdmin()) {
            return view('devices.index');
        } elseif (isManager()) {
            return view('devices.index');
        } else {
            return view('devices.customer-device-index');
        }
    }

    public function verifyDeviceModel($id)
    {
        $content = $this->deviceService->deviceVerify($id);
        // dd($content);
        return response()->json($content);
    }

    public function sendDeviceModel($id)
    {
        $device = DeviceAssignment::with('device', 'deviceLocation', 'assignee')->where('device_id', $id)->first();
        $sendApproval = $this->mqttService->sendToDevice($device);
        $content = $this->deviceService->sendDeviceModel($id);
        return response()->json($content);
    }

    public function getDeviceById($id)
    {
        $device = Device::with('deviceAssigned.assignee')->find($id); // Adjust as necessary
        if ($device) {
            return response()->json($device);
        } else {
            return response()->json(['error' => 'Device not found'], 404);
        }
    }

    public function getDevicesForSelect2()
    {
        $device = null;

        if (isSuperAdmin()) {
            $device = Device::with('deviceAssigned.assignee')->get(); // Fetch all devices with assigned assignees
        } elseif (isManager()) {
            $device = Device::with(['deviceAssigned' => function ($query) {
                $query->where('manager_id', Auth::id()); // Only fetch active device assignments
            }, 'deviceAssigned.assignee'])
                ->get();
        } else {
            $device = Device::with(['deviceAssigned' => function ($query) {
                $query->where('assing_to', Auth::id()); // Only fetch active device assignments
            }, 'deviceAssigned.assignee'])
                ->get();
        }

        // Check if devices were found and return the result as JSON
        if ($device && $device->isNotEmpty()) {
            return response()->json($device);
        } else {
            return response()->json(['error' => 'Device not found'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $status = $this->deviceService->getAllStatus();
        $health = $this->deviceService->getAllHealth();
        $device_type = $this->deviceService->getAllDeviceTypes();
        $managers = $this->userService->getOwners();

        return view('devices.create', compact('status', 'health', 'device_type', 'managers'));
    }

    /**
     * Create New Devices
     *
     * @param StoreDeviceRequest $request
     * @return void
     */
    public function store(StoreDeviceRequest $request)
    {
        try {
            $data = $this->deviceService->createNewDevice($request->all());
            if (!$data) {
                return errorMessage('Failed to create a new device.');
            }
            return successMessage('New Device Created !!');
        } catch (DeviceCreationException $e) {
            return exceptionMessage($e->getMessage());
        } catch (Exception $e) {
            Log::error("Error creating device: {$e->getMessage()}");
            return exceptionMessage('An unexpected error occurred. Please try again later.');
        }
    }

    //Customer Device Dashboard
    public function deviceDashboard()
    {
        $devices = $this->deviceService->deviceDashboard();

        return response()->json($devices);
    }

    public function deviceData($id)
    {
        $devices = $this->deviceService->deviceData($id);
        return response()->json($devices);
    }
    public function assingedDevice($id)
    {
        $devices = $this->deviceService->assingedDevice($id);
        return response()->json($devices);
    }
    public function quickStart()
    {
        return view('devices.quickStart');
    }
    public function authorizeManual()
    {
        return view('devices.authorizeManual');
    }

    public function deviceStep2(Request $request)
    {
        $deviceAssignments = DeviceAssignment::with('device', 'deviceLocation', 'assignee')
            ->where('assign_to', Auth::id())
            ->where('login_to_device', 0)
            ->where('connection_status', 'Not Authorized')
            ->get();

        if ($deviceAssignments->isEmpty()) {
            return response()->json(['error' => 'No device assignments found.'], 404);
        }

        $devices = [];
        foreach ($deviceAssignments as $assignment) {
            $devices[] = [
                'mac_address' => $assignment->device->mac_address,
                'api_key' => $assignment->device->short_apikey,
                'device_name' => $assignment->device->name
            ];
        }

        $data = [
            'title' => 'Device Step 2 : Login and Api Key',
            'devices' => $devices
        ];

        return response()->json($data);
    }

    public function resetDevice($id)
    {
        return $this->deviceService->resetDevice($id);
    }

    public function getAssignedDevices($userId)
    {
        try {
            // Fetch devices created by or assigned to the user
            $devices = Device::with(['deviceType', 'deviceOwner', 'createdBy', 'deviceAssigned', 'deviceAssigned.assignee', 'deviceAssigned.deviceLocation', 'deviceAssigned.assignee.locations'])
                ->orWhereHas('deviceAssigned', function ($query) use ($userId) {
                    $query->where('assign_to', $userId);
                })
                ->get();

            return response()->json($devices);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
    public function deviceAjaxDatatable(Request $request)
    {
        try {
            return $this->deviceService->dataTable($request);
        } catch (Exception $e) {
            Log::error("Error fetching device: {$e->getMessage()}");
            return exceptionMessage($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deviceData = $this->deviceService->findorfail($id);
        return view('devices.show', compact('deviceData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): view
    {
        $deviceData = $this->deviceService->findorfail($id);
        $status = $this->deviceService->getAllStatus();
        $health = $this->deviceService->getAllHealth();
        $device_type = $this->deviceService->getAllDeviceTypes();
        $managers = $this->userService->getOwners();
        return view('devices.edit', compact('status', 'health', 'device_type', 'managers', 'deviceData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, int $id)
    {
        try {
            $device_type = $this->deviceService->updateDevice($request->all(), $id);
            if (!$device_type) {
                return errorMessage('Failed to Update device.');
            }
            return successMessage('Device Updates !!');
        } catch (DeviceCreationException $e) {
            Log::error("Device Exception: {$e->getMessage()}");
            return exceptionMessage($e->getMessage());
        } catch (Exception $e) {
            Log::error("Error creating device: {$e->getMessage()}");
            return exceptionMessage($e->getMessage());
        }
    }

    /**
     * Destroy Device
     *
     * @param string $id
     * @return void
     */
    public function destroy(string $id)
    {
        try {
            return $this->deviceService->destroy($id);
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    /**
     * Destroy Device
     *
     * @param string $id
     * @return void
     */
    public function unAssign(string $id)
    {
        try {
            return $this->deviceService->unAssign($id);
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }


    /**
     *
     */
    public function showAssignDeviceForm($id): view
    {
        $deviceData = $this->deviceService->findorfail($id);
        $allDevices = $this->deviceService->getPluckedDevices();
        $customers = $this->userService->getManagerAddedUsers(Auth::id());
        return view('devices.assign-device', compact('customers', 'deviceData', 'allDevices'));
    }
    public function getApiKey($id): view
    {
        $deviceData = $this->deviceService->findorfail($id);
        return view('devices.getApiKey', compact('deviceData'));
    }

    /**
     *
     */
    public function assignDevice(StoreAssignDevice $request)
    {
        try {
            if (!$this->deviceService->assignDeviceToUser($request->all())) {
                return exceptionMessage('Device is already assigned!');
            }
            return successMessage('Assigned Device!');
        } catch (DeviceCreationException $e) {
            return exceptionMessage($e->getMessage());
        } catch (Exception $e) {
            Log::error("Error in Assign Device: {$e->getMessage()}");
            return exceptionMessage('An unexpected error occurred. Please try again later.');
        }
    }

    public function verifyDeviceApi(VerifyDeviceViaApi $request)
    {
        Log::info('Received verifyDeviceApi request', $request->all());
        try {
            $user = $request->user();
            $token = $user->currentAccessToken();
            Log::info('Current access token details', ['token' => $token]);

            $result = $this->deviceService->verifyDeviceApi($request->all(), $user);
            return $result;
        } catch (Exception $e) {
            return $this->handleExceptions($e);
        }
    }

    private function handleExceptions(Exception $e)
    {
        if ($e instanceof DeviceCreationException) {
            Log::error("Device Creation Error: {$e->getMessage()}");
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        Log::error("General Error in verifyDeviceApi: {$e->getMessage()}");
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'], 500);
    }
}
