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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;


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
                $getDevice = Device::select('id', 'name', 'api_key')->where('api_key', '=', $deviceData['device_api'])->first();
                // dd($getDevice);
                if ($getDevice) {
                    dump('Data Seeding into Database');
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
    public function getDeviceAllMessages()
    {
        try {
            $latestRecords = $this->model::select('device_data.*')
                ->join(DB::raw('(SELECT device_id, MAX(timestamp) as latest_timestamp FROM device_data GROUP BY device_id) as latest_data'), function ($join) {
                    $join->on('device_data.device_id', '=', 'latest_data.device_id')
                        ->on('device_data.timestamp', '=', 'latest_data.latest_timestamp');
                })
                ->join('devices', 'device_data.device_id', '=', 'devices.id') // Assuming 'devices' is the table name and 'id' is the primary key
                ->with('device');

            if (isManager()) {
                $latestRecords->where('devices.created_by', Auth::id()); // Apply the filter for managers
            }

            return response()->json($latestRecords->get());
        } catch (Exception $e) {
            // Return a generic error message to the user
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $deviceData = $this->model::with('device')->where('device_id', $id)->latest()->first();

            return response()->json($deviceData);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    // In your Laravel Controller
    public function getDeviceLineChartData($id)
    {
        // dd($id);
        // Simulate data fetching, you should replace this with actual data fetching logic
        $data = [
            // Example data, should be replaced with dynamic data from your database or other source
            ['x' => now()->subMinutes(5)->timestamp * 1000, 'y' => rand(1, 10)],
            ['x' => now()->subMinutes(4)->timestamp * 1000, 'y' => rand(1, 10)],
            ['x' => now()->subMinutes(3)->timestamp * 1000, 'y' => rand(1, 10)],
            ['x' => now()->subMinutes(2)->timestamp * 1000, 'y' => rand(1, 10)],
            ['x' => now()->subMinutes(1)->timestamp * 1000, 'y' => rand(1, 100)],
            // Add more points as needed
        ];

        $deviceData = $this->model::with('device')->where('device_id', $id)->latest()->first();

        if (!$deviceData) {
            return response()->json(['message' => 'Device not found'], 404);
        }
        $targetTimestamp = $deviceData->timestamp;

        $nearestDataBatch = $this->model::select('timestamp', 'device_timestamps', 'valts')
            ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, timestamp, '{$targetTimestamp}'))") // time diff to get nearest data for last data batch
            ->limit(10) // Limit to the nearest 10 entries
            ->get();

        $xyData = $nearestDataBatch->map(function ($item) {
            $timestampInMilliseconds = \Carbon\Carbon::createFromTimestamp($item->device_timestamps)
                ->timestamp * 1000;
            return [
                'x' => $timestampInMilliseconds,
                'y' => $item->valts
            ];
        });
        $xyArray = $xyData->toArray();
        $deviceName = $deviceData->device->name ?? 'Unknown Device';
        $finalArray = [
            'data' => $xyArray,
            'deviceName' => $deviceName
        ];
        return response()->json($finalArray);
    }
}
