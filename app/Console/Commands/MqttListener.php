<?php

namespace App\Console\Commands;
require('vendor/autoload.php');
use Illuminate\Console\Command;
use App\Services\MqttService;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;


class MqttListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-listener';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to MQTT topics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $mqttService = new MqttService();
        // $mqttService->connectAndSubscribe('weather/#'); // Replace with your topic

        $server = "172.20.122.191";
        $port = 1883;
        $clientId = "4dfadf3277db7b6ee784";
        $username = "ubuntu";
        $password = "Mqtt001";
        $clean_session = True;
        $mqtt_version = MqttClient::MQTT_3_1;

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password);

        $mqtt = new MqttClient($server, $port);

        $mqtt->connect($connectionSettings, $clean_session);
        printf("client connected\n");
        $result = [];

        $mqtt->subscribe("weather", function ($topic, $message) {
            printf("Received message on topic [%s]: %s\n", $topic, $message);
            // Save the message to the database
        }, 0);

        $mqtt->loop();


        // $mqtt->subscribe('weather/#', function (string $topic, string $message) use ($mqtt, &$result) {
        //     $result['topic'] = $topic;
        //     $result['message'] = $message;

        //     $mqtt->interrupt();
        // }, 1);
        // $mqtt->loop(true);
        return response()->json($result, 200);

    }
}
