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

    // protected $server = '172.20.120.102';
    protected $server = "broker.hivemq.com";
    protected $port = 1883;
    protected $username = '';
    protected $password = '';
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

        try {
            // Initialize the MQTT client and connection settings
            $this->mqttClient = new MqttClient($this->server, $this->port, uniqid());
            // $this->connectionSettings = (new ConnectionSettings())
            //     ->setUsername($this->username)
            //     ->setPassword($this->password);
            $this->connectionSettings = new ConnectionSettings();

            // Only apply if username is not empty or whitespace
            if (trim($this->username) !== '') {
                $this->connectionSettings->setUsername($this->username);
            }
            if (trim($this->password) !== '') {
                $this->connectionSettings->setPassword($this->password);
            }


            // Attempt to connect to the MQTT broker
            $this->mqttClient->connect($this->connectionSettings, $this->cleanSession);
            Log::info('MQTT - Successfully connected to broker');
        } catch (Exception $e) {
            // Log the connection error
            Log::channel('mqttlogs')->error("MQTT Connection Error: {$e->getMessage()}");

            // Set $mqttClient to null to indicate the connection failed
            $this->mqttClient = null;
        }
    }
    public function connectAndSubscribe($topic)
    {
        if (!$this->mqttClient) {
            Log::channel('mqttlogs')->error("MQTT - Unable to subscribe as client is not connected.");
            return response()->view('errors.500', [], 500);
        }

        try {
            $this->mqttClient->subscribe($topic, function ($topic, $message) {
                // dd($message);
                $associativeArray = json_decode($message, true);
                if (isset($associativeArray['encryption_key'])) {
                    $this->deviceRepository->deviceVerifications($associativeArray);
                }
                dump($associativeArray);
                $this->deviceDataRepository->update_device_data($associativeArray);
                $this->deviceLogsRepository->create($associativeArray);
            }, 0);

            $this->mqttClient->loop();
        } catch (Exception $e) {
            Log::channel('mqttlogs')->error("MQTT - Something went wrong: {$e->getMessage()}");
            return response()->view('errors.500', [], 500);
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
