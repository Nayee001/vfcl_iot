<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MqttService;


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
        $mqttService = new MqttService();
        $mqttService->connectAndSubscribe('weather'); // Replace with your topic
    }
}
