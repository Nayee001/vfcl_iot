<?php

namespace App\Services;

require('vendor/autoload.php');

use App\Interfaces\MqttServiceInterface;

use App\Models\DeviceLogs;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Repositories\DeviceRepository;
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
    protected $deviceRepository;


    /**
     * Constructor for the class.
     * Initializes the device data and device logs repositories.
     *
     * @param DeviceDataRepository $deviceDataRepository
     * @param DeviceLogsRepository $deviceLogsRepository
     * @param DeviceRepository $deviceRepository
     *
     *
     *
     */
    public function __construct(
        DeviceDataRepository $deviceDataRepository,
        DeviceLogsRepository $deviceLogsRepository,
        DeviceRepository $deviceRepository
    ) {
        $this->deviceDataRepository = $deviceDataRepository;
        $this->deviceLogsRepository = $deviceLogsRepository;
        $this->deviceRepository = $deviceRepository;
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
                // $associativeArray = json_decode($message, true);
                // dd($associativeArray);
                $associativeArray =  [
                    "encryption_key" => "3tZIilSrXpG3nzNHQNw3xBvwA3HcaCvpQIlDQYjWE5Q=",
                    "api_key" => "46269a"
                ];
                // Check if 'encryption_key' is present in the array
                if (isset($associativeArray['encryption_key'])) {
                    $this->deviceRepository->deviceVerifications($associativeArray);
                }

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
