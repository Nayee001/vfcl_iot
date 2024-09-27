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
            // dd($deviceData);
            // dd($deviceData['data']);
            if ($deviceData) {
                $getDevice = Device::select('id', 'name', 'short_apikey')->where('short_apikey', '=', $deviceData['API_KEY'])->first();
                dd($getDevice);
                if ($getDevice) {
                    // dump('Data Seeding into Database');
                    $data = [
                        'device_id' => $getDevice->id,
                        'fault_status' => $deviceData['fault_status'],
                        'topic' => $deviceData['topic'],
                        'device_status' => $deviceData['device_status'],
                        'health_status' => $deviceData['health_status'],
                        'timestamp' => $deviceData['timestamp'],
                    ];
                    // Find the latest record for the device
                    $latestRecord = $this->model->where('device_id', $getDevice->id)->latest('created_at')->first();
                    // dd($latestRecord);
                    // Determine the time difference based on the interval
                    $timeDifference = $latestRecord ? now()->diffInMinutes($latestRecord->created_at) : PHP_INT_MAX;

                    // Set the threshold based on the interval
                    $threshold = interval(1);


                    if ($latestRecord && $timeDifference < $threshold) {
                        dump('update Function');
                        // Assuming $data needs to be merged with additional data before updating
                        if (!empty($deviceData['data'])) {
                            foreach ($deviceData['data'] as $key => $value) {

                                $updateData = [
                                    "device_timestamps" => $value['device_timestamps'],
                                    "valts" => $value['valts'],
                                ] + $data;

                                $latestRecord->update($updateData);
                            }
                        } else {
                            // If there's no additional data to merge, update directly
                            $latestRecord->update($data);
                        }
                        return $latestRecord;
                    } else {
                        dump('create Function');
                        // Create a new record(s) with optimizations applied from the previous explanation
                        // dd($deviceData);
                        if (!empty($deviceData['data'])) {
                            $creation = null;
                            foreach ($deviceData['data'] as $key => $value) {
                                $recordData = [
                                    "device_timestamps" => $value['device_timestamps'],
                                    "valts" => $value['valts'],
                                ] + $data;

                                $creation = $this->model->create($recordData);
                            }
                            return $creation;
                        } else {
                            Log::channel('mqttlogs')->error("Device Data - Something Wrong With Device Data: ", $deviceData);
                            return false;
                        }
                    }
                } else {
                    Log::channel('mqttlogs')->error("Device  - Something Wrong With DEVICE in Web-Command-Center",);
                    return false;
                }
            } else {
                Log::channel('mqttlogs')->error("Device Data - Something Wrong With Device Data: ", $deviceData);
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
        // Fetch the latest device data
        $deviceData = $this->model::with('device')->where('device_id', $id)->latest()->first();

        // Check if the device data exists
        if (!$deviceData) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        // Get the target timestamp for the nearest data batch
        $targetTimestamp = $deviceData->device_timestamps;

        // Query for the nearest data based on the target timestamp
        $nearestDataBatch = $this->model::select('device_timestamps', 'current_phase1', 'current_phase2', 'current_phase3')
            ->where('device_id', $id)
            // ->orderBy($targetTimestamp,'ASC')
            // ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, timestamp, '{$targetTimestamp}'))")
            ->limit(100) // Limit to 5 nearest data points
            ->get();

        // Map the data to an array with 'x' as device_timestamps and 'y' values as the three phases
        $xyData = $nearestDataBatch->map(function ($item) {
            return [
                'x' => $item->device_timestamps, // Keeping the device_timestamps as-is
                'current_phase1' => $item->current_phase1,
                'current_phase2' => $item->current_phase2,
                'current_phase3' => $item->current_phase3,
            ];
        });

        // Convert to an array and return the device name along with the data
        $xyArray = $xyData->toArray();
        $deviceName = $deviceData->device->name ?? 'Unknown Device';

        // Final array to return the data and device name
        $finalArray = [
            'data' => $xyArray,
            'deviceName' => $deviceName
        ];
        // Ensure data is not empty before broadcasting
        if ($nearestDataBatch->isEmpty()) {
            // $this->info('No data to broadcast.');
            return;
        }

        // $this->info('Data broadcasted successfully for device: ' . $finalArray);
        // dd($finalArray);
        return response()->json($finalArray);
    }
}
