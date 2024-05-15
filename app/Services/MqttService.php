<?php

namespace App\Services;

// require('vendor/autoload.php');

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

    // public $topic = 'mqttdevice/#';

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
                dump("Received message on topic [%s]: %s\n", $topic, $message);
                $associativeArray = json_decode($message, true);
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

    public function sendToDevice($api)
    {
        try {
            $topic = "mqttdevice/{$api}";
            $message = json_encode(['status' => 'Approve', 'timestamp' => now()]);
            $endTime = time() + 10;
            while (time() < $endTime) {
                Log::info("Sending message to [{$topic}]: {$message}");
                $this->mqttClient->publish($topic, $message, 0);
                sleep(5);
            }
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT sendToDevice - Something went wrong: {$e->getMessage()}");
            throw $e;
        }
    }
}
