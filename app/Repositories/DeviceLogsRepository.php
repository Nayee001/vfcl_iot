<?php

namespace App\Repositories;

use App\Interfaces\DeviceLogsRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use Exception;

class DeviceLogsRepository implements DeviceLogsRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create($message)
    {
        try {
            // Check if the message is not empty
            if ($message) {
                // Retrieving device details based on the API key in the message
                $getDevice = Device::select('id', 'name', 'api_key')->where('api_key', '=', $message['device_api'])->first();

                // Check if the device is found
                if ($getDevice) {
                    // Preparing log data
                    $logs = [
                        'device_id' => $getDevice->id,
                        'json_response' => json_encode($message)
                    ];

                    // Creating a new log entry in the database
                    return $this->model->create($logs);
                } else {
                    // Return false if the device is not found
                    return false;
                }
            } else {
                // Return false if the message is empty
                return false;
            }
        } catch (Exception $e) {
            // Logging the error
            Log::channel('mqttlogs')->error("MQTT - Something Wrong with Device Logs: {$e->getMessage()}");

            // Optionally, you can return false or null here as well to indicate failure
            return false;
        }
    }
}
