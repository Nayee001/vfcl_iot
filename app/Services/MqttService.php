<?php

namespace App\Services;

use App\Interfaces\MqttServiceInterface;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Repositories\DeviceRepository;
use App\Repositories\DeviceLogsRepository;
use App\Repositories\DeviceDataRepository;
use Illuminate\Support\Facades\Log;
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
                $associativeArray = json_decode($message, true);
                // dd($associativeArray);
                if (isset($associativeArray['encryption_key'])) {
                    $this->deviceRepository->deviceVerifications($associativeArray);
                }
                $this->deviceDataRepository->update_device_data($associativeArray);
                $this->deviceLogsRepository->create($associativeArray);
            }, 0);

            $this->mqttClient->loop();
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Something went wrong: {$e->getMessage()}");
        }
    }

    public function sendToDevice($deviceData)
    {
        try {
            $topic = "mqttdevice/{$deviceData['device']['short_apikey']}";
            $message = json_encode([
                'device' => $deviceData['device']['name'],
                'location' => $deviceData['deviceLocation']['location_name'],
                'status' => 'Verified',
                'timestamp' => now()
            ]);

            $endTime = time() + 10;
            while (time() < $endTime) {
                try {
                    Log::info("Sending message to [{$topic}]: {$message}");
                    $this->mqttClient->publish($topic, $message, 0);
                } catch (Exception $e) {
                    Log::channel('mqttlogs')->error("MQTT sendToDevice - Failed to send message: {$e->getMessage()}");
                }
                sleep(5);
            }
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT sendToDevice - Something went wrong: {$e->getMessage()}");
            throw $e;
        }
    }

    public function resetDevice($device)
    {
        try {
            $topic = "mqttdevice/{$device['short_apikey']}";
            $message = json_encode([
                'device' => $device['name'],
                'message' => 'reset',
                'timestamp' => now()
            ]);
            Log::info("Sending reset message to [{$topic}]: {$message}");
            $this->mqttClient->publish($topic, $message, 0);

            Log::info("Reset message sent successfully to [{$topic}]");
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT resetDevice - Failed to send reset message: {$e->getMessage()}");
            throw $e;
        }
    }
}
