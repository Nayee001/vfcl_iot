<?php

namespace App\Repositories;

use App\Interfaces\DeviceDataRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\DeviceAssignment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Yajra\DataTables\Datatables;

class DeviceDataRepository implements DeviceDataRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function update_device_data($deviceData)
    {
        try {
            if ($deviceData) {
                // Find the device using the API key from the payload
                $getDevice = Device::select('id', 'name', 'short_apikey')
                    ->where('short_apikey', $deviceData['device_api'])
                    ->first();
                if (!$getDevice) {
                    Log::channel('mqttlogs')->error("Device not found in Web-Command-Center", [
                        'device_api' => $deviceData['device_api']
                    ]);
                    return false;
                }
                // Prepare the data for insertion/update
                $data = [
                    'device_id' => $getDevice->id,
                    'fault_status' => 'ON',
                    'topic' => $deviceData['topic'],
                    'health_status' => $deviceData['health_status'],
                    'timestamp' => $deviceData['timestamps'],
                    'event_data' => json_encode($deviceData['event_data'] ?? []),  // Encode as JSON string
                ];
                // dd($data);
                // Find the latest record for the device
                $latestRecord = $this->model->where('device_id', $getDevice->id)
                    ->latest('created_at')
                    ->first();
                // Calculate time difference and set threshold
                $timeDifference = $latestRecord ? now()->diffInMinutes($latestRecord->created_at) : PHP_INT_MAX;
                $threshold = interval(1);  // Interval in minutes
                if ($latestRecord && $timeDifference < $threshold) {
                    $latestRecord->update($data);  // Update with JSON-encoded event data
                    return $latestRecord;
                } else {
                    try {
                        // Create a new record with JSON-encoded event data
                        $creation = $this->model->create($data);
                        return $creation;
                    } catch (\Exception $e) {
                        Log::channel('mqttlogs')->error("Error creating new record", [
                            'error' => $e->getMessage(),
                            'deviceData' => $deviceData
                        ]);
                        return false;
                    }
                }
            } else {
                Log::channel('mqttlogs')->error("Invalid Device Data Received", $deviceData);
                return false;
            }
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Something Wrong With Device Logs: {$e->getMessage()}");
            return false;
        }
    }

    public function getDataCounts()
    {
        $count = $this->model::count();
        return response()->json(['count' => $count]);
    }


    private function queryLatestDeviceRecords()
    {
        $latestRecords = $this->model::select('device_data.*')
            ->join(DB::raw('(SELECT device_id, MAX(timestamp) as latest_timestamp FROM device_data GROUP BY device_id) as latest_data'), function ($join) {
                $join->on('device_data.device_id', '=', 'latest_data.device_id')
                    ->on('device_data.timestamp', '=', 'latest_data.latest_timestamp');
            })
            ->join('devices', 'device_data.device_id', '=', 'devices.id')
            ->with('device');

        if (isManager()) {
            $latestRecords->where('devices.created_by', Auth::id());
        }

        if (isCustomer()) {
            // Assuming `device_assignments` table has `device_id` and `customer_id` columns
            // and `Auth::id()` returns the ID of the currently authenticated customer
            $latestRecords->join('device_assignments', 'devices.id', '=', 'device_assignments.device_id')
                ->where('device_assignments.assign_to', Auth::id());
        }

        // Further logic to execute or return the query results

        return $latestRecords;
    }

    public function getDeviceAllMessages()
    {
        try {
            $latestRecords = $this->queryLatestDeviceRecords();

            return response()->json($latestRecords->get());
        } catch (Exception $e) {
            // Return a generic error message to the user
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function dashboarddevicedataTable($request)
    {
        try {
            if ($request->ajax()) {
                $latestRecords = $this->queryLatestDeviceRecords();

                $device =  $latestRecords->get();
                // dd($device);
                return DataTables::of($device)
                    ->addIndexColumn()
                    ->addColumn('deviceName', function ($row) {
                        // Check if the device property exists and has an ID
                        $deviceId = isset($row->device) ? $row->device->id : '';
                        // Generate the HTML link with the onclick event calling showData with the device ID
                        $deviceName = "<a href='#' class='primary' onclick='showData(\"{$deviceId}\")'>" . htmlspecialchars($row->device->name) . "</a>";
                        return $deviceName;
                    })

                    ->addColumn('deviceStatus', function ($row) {
                        switch ($row->device_status) {
                            case Device::STATUS['Active']:
                                $badgeClass = 'primary';
                                break;
                            case Device::STATUS['Inactive']:
                                $badgeClass = 'danger';
                                break;
                            default:
                                $badgeClass = 'warning';
                                break;
                        }
                        $status = '<span class="badge bg-label-' . $badgeClass . ' me-1">' . e($row->device_status) . '</span>';
                        return $status;
                    })->addColumn('healthStatus', function ($row) {
                        $healthStatus = '<a class="primary">' . $row->health_status . ' </a>';
                        return $healthStatus;
                    })->addColumn('faultStatus', function ($row) {
                        switch ($row->fault_status) {
                            case 'ON':
                                $badgeClass = 'success';
                                break;
                            case 'OFF':
                                $badgeClass = 'danger';
                                break;
                            default:
                                $badgeClass = 'warning';
                                break;
                        }
                        $faultStatus = '<span class="badge bg-label-' . $badgeClass . ' me-1">' . e($row->fault_status) . '</span>';
                        return $faultStatus;
                    })->addColumn('TimeStamps', function ($row) {
                        $TimeStamps = Carbon::parse($row->timestamp)->diffForHumans();
                        return $TimeStamps;
                    })->addColumn('actions', function ($row) {
                        $deviceDetails = '<a class="dropdown-item" href="' . route('devices.edit', $row->id) . '" title="Device Details"><i class="bx bx-detail"></i> Device Details</a>';
                        $viewGraph = '<a class="dropdown-item" href="' . route('devices.edit', $row->id) . '" title="View Graph"><i class="bx bx-line-chart"></i> View Graph</a>';
                        $actions = $deviceDetails || $viewGraph
                            ? '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">' .
                            $deviceDetails .
                            $viewGraph .
                            '</div>
                          </div>'
                            : '';

                        return $actions;
                    })
                    ->rawColumns(['deviceName', 'deviceStatus', 'healthStatus', 'faultStatus', 'TimeStamps', 'actions'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get Device Data by ID
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceData($id)
    {
        try {
            // Assuming $id is already provided as a parameter and should not be hardcoded
            $deviceData = $this->model::with('device')->where('device_id', $id)->first();

            return response()->json($deviceData);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function getDeviceLineChartData($id)
    {
        $deviceData = $this->model::with('device')
            ->where('device_id', $id)
            ->latest()
            ->first();

        if (!$deviceData) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        // Decode the event_data JSON field
        $eventData = json_decode($deviceData->event_data, true);
        // dd($eventData);
        return response()->json([
            'deviceName' => $deviceData->device->name ?? 'Unknown Device',
            'eventData' => $eventData,
        ]);
    }
}
