<?php

namespace App\Services;

require('vendor/autoload.php');

use App\Interfaces\MqttServiceInterface;

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

    protected $server = '172.20.122.191';
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
    }


    public function connectAndSubscribe($topic)
    {
        try {
            $connection  = $this->mqttClient->connect($this->connectionSettings, true);

            $this->mqttClient->subscribe("weather", function ($topic, $message) {
                // printf("Received message on topic [%s]: %s\n", $topic, $message);
                // Save the message to the database

                //storing Device Logs
                // Log::channel('mqttlogs')->error("MQTT - Somthing went wrong:");
                $associativeArray = json_decode($message, true);
                $deviceLogs = $this->deviceLogsRepository->create($associativeArray);
            }, 0);

            $this->mqttClient->loop();
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Somthing went wrong: {$e->getMessage()}");
        }
    }
}
