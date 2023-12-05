<?php

namespace App\Services;

use App\Interfaces\MqttServiceInterface;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService implements MqttServiceInterface
{
    private $mqttClient;

    public function __construct()
    {
        $this->mqttClient = new MqttClient("172.20.122.191", 1883, 'Web Command Center');
    }

    public function connectAndSubscribe($topic)
    {
        $connectionSettings = (new ConnectionSettings())
            ->setUsername('ubuntu') // optional
            ->setPassword('Mqtt001'); // optional

        $this->mqttClient->connect($connectionSettings, true);

        $this->mqttClient->subscribe($topic, function ($topic, $message) {
            $this->handleMessage($message);
        }, 0);

        $this->mqttClient->loopForever();
    }

    // private function handleMessage($message)
    // {
    //     // Decode the JSON message
    //     $data = json_decode($message, true);

    //     // Save to database
    //     SensorData::create($data);
    // }
}
