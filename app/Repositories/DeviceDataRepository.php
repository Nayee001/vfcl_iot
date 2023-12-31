<?php

namespace App\Repositories;

use App\Interfaces\DeviceDataRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use Exception;
use Illuminate\Support\Facades\DB;
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
            if ($deviceData) {
                $getDevice = Device::select('id', 'name', 'api_key')->where('api_key', '=', $deviceData['device_api'])->first();

                if ($getDevice) {
                    $data = [
                        'device_id' => $getDevice->id,
                        'fault_status' => $deviceData['fault_status'],
                        'topic' => $deviceData['topic'],
                        'device_status' => $deviceData['device_status'],
                        'health_status' => $deviceData['health_status'],
                        'timestamp' => $deviceData['timestamps'],
                    ];

                    // Find the latest record for the device
                    $latestRecord = $this->model->where('device_id', $getDevice->id)->latest('created_at')->first();

                    // Determine the time difference based on the interval
                    $timeDifference = $latestRecord ? now()->diffInMinutes($latestRecord->created_at) : PHP_INT_MAX;

                    // Set the threshold based on the interval
                    $threshold = interval(1);

                    // Check if the latest record is older than the specified threshold
                    if ($latestRecord && $timeDifference < $threshold) {
                        // Update the latest record
                        $latestRecord->update($data);
                        return $latestRecord;
                    } else {
                        // Create a new record
                        return $this->model->create($data);
                    }
                } else {
                    Log::channel('mqttlogs')->error("Device  - Something Wrong with DEVICE in web-command-center", $getDevice);
                    return false;
                }
            } else {
                Log::channel('mqttlogs')->error("Device Data - Something Wrong with Device Data: ", $deviceData);
                return false;
            }
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Something Wrong with Device Logs: {$e->getMessage()}");
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
                ->with('device')
                ->get();
            return response()->json($latestRecords);
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

            // Check if device data is found
            if (!$deviceData) {
                return response()->json(['message' => 'Device not found'], 404);
            }

            return response()->json($deviceData);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
