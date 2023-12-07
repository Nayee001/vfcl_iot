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
            if ($message) {
                $getDevice = Device::select('id', 'name', 'api_key')->where('api_key', '=', $message['device_api'])->first();
                if ($getDevice) {
                    $logs = [
                        'device_id' => $getDevice->id,
                        'json_response' => \json_encode($message)
                    ];
                    return $this->model->create($logs);
                }
            } else {
            }
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Somwthing Wrong with Device Logs: {$e->getMessage()}");
        }
    }
}
