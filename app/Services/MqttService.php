<?php

namespace App\Services;

require('vendor/autoload.php');

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
        // $connectionSettings = (new ConnectionSettings())
        //     ->setUsername('ubuntu') // optional
        //     ->setPassword('Mqtt001'); // optional

        // $this->mqttClient->connect($connectionSettings, true);

        // // $this->mqttClient->subscribe($topic, function ($topic, $message) {
        // //     $this->handleMessage($message);
        // // }, 0);

        // // $this->mqttClient->loopForever();

        // $this->mqttClient->subscribe($topic, function ($topic, $message) {
        //     echo $message; // for testing
        //     echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
        // }, 0);
        // $this->mqttClient->loop(true);
        // $this->mqttClient->disconnect();
        $server = '172.20.122.191';
        $port = 1883;
        $clientId = "4dfadf3277db7b6ee784";
        $username = 'ubuntu';
        $password = 'Mqtt001';
        $clean_session = True;
        $mqtt_version = MqttClient::MQTT_3_1_1; // Assuming your library supports this constant for MQTT protocol version 3.1.1

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60)
            ->setLastWillTopic('weather/#')
            ->setLastWillMessage('client disconnect')
            ->setLastWillQualityOfService(1);
        $mqtt = new MqttClient($server, $port);

        $mqtt->connect($connectionSettings, $clean_session);
        printf("client connected\n");
        $mqtt->subscribe('weather', function ($topic, $message) {
            printf("Received message on topic [%s]: %s\n", $topic, $message);
        }, 0);
        $mqtt->loop(true);

    }

    // private function handleMessage($message)
    // {
    //     // Decode the JSON message
    //     $data = json_decode($message, true);

    //     // Save to database
    //     SensorData::create($data);
    // }
}
