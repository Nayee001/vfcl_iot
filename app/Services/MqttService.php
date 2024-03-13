<?php

namespace App\Services;

require('vendor/autoload.php');

use App\Interfaces\MqttServiceInterface;

use App\Models\DeviceLogs;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Repositories\DeviceLogsRepository;
use App\Repositories\DeviceDataRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Exception;

class MqttService implements MqttServiceInterface
{
    protected $mqttClient;
    protected $connectionSettings;

    protected $server = '172.20.120.102';
    protected $port = 1883;
    protected $username = 'ubuntu';
    protected $password = 'Mqtt001';
    protected $cleanSession = true;

    protected $deviceDataRepository;
    protected $deviceLogsRepository;


    /**
     * Constructor for the class.
     * Initializes the device data and device logs repositories.
     *
     * @param DeviceDataRepository $deviceDataRepository
     * @param DeviceLogsRepository $deviceLogsRepository
     *
     *
     */
    public function __construct(
        DeviceDataRepository $deviceDataRepository,
        DeviceLogsRepository $deviceLogsRepository
    ) {
        $this->deviceDataRepository = $deviceDataRepository;
        $this->deviceLogsRepository = $deviceLogsRepository;
        $this->mqttClient = new MqttClient($this->server, $this->port, uniqid());
        $this->connectionSettings = (new ConnectionSettings())
            ->setUsername($this->username)
            ->setPassword($this->password);
        $this->mqttClient->connect($this->connectionSettings, true);
    }


    public function connectAndSubscribe($topic)
    {
        try {
            $this->mqttClient->subscribe($topic, function ($topic, $message) {
                // dump("Received message on topic [%s]: %s\n", $topic, $message);
                $associativeArray = json_decode($message, true);
                // dd($associativeArray);
                $this->deviceDataRepository->update_device_data($associativeArray);
                $this->deviceLogsRepository->create($associativeArray);
            }, 0);

            $this->mqttClient->loop();
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Somthing went wrong: {$e->getMessage()}");
        }
    }

    // Simulation Completed
    // public function connectAndSubscribe($topic)
    // {
    //     try {
    //         $deviceLogs = DeviceLogs::orderBy('id', 'desc')->get();

    //         // Loop through each log
    //         foreach ($deviceLogs as $log) {
    //             // Decode the json_response field into an associative array
    //             $associativeArray = json_decode($log->json_response, true);

    //             // Check if decoding was successful
    //             if ($associativeArray !== null) {
    //                 // Update device data with the associative array
    //                 $dummyData = $this->deviceDataRepository->update_device_data($associativeArray);

    //                 // Optionally, output the $dummyData for debugging
    //                 // Note: `dd()` will stop execution, so use `dump()` if iterating over multiple logs
    //                 dump($dummyData);
    //             } else {
    //                 echo "Failed to decode JSON for log ID {$log->id}\n";
    //             }
    //         }
    //         // $this->mqttClient->loop();
    //     } catch (Exception $e) {
    //         Log::channel('mqttlogs')->error("MQTT - Somthing went wrong: {$e->getMessage()}");
    //     }
    // }
}
