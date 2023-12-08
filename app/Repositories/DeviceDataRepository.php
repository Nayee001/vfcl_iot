<?php

namespace App\Repositories;

use App\Interfaces\DeviceDataRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use Exception;


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
}
