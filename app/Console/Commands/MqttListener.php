<?php

namespace App\Console\Commands;
require('vendor/autoload.php');
use Illuminate\Console\Command;
use App\Services\MqttService;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Repositories\DeviceDataRepository;
use App\Repositories\DeviceLogsRepository;

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

    protected $deviceDataRepository;
    protected $deviceLogsRepository;

    public function __construct(DeviceDataRepository $deviceDataRepository, DeviceLogsRepository $deviceLogsRepository) {
        parent::__construct();
        $this->deviceDataRepository = $deviceDataRepository;
        $this->deviceLogsRepository = $deviceLogsRepository;
    }
    public function handle()
    {
        $mqttService = new MqttService($this->deviceDataRepository, $this->deviceLogsRepository);
        $mqttService->connectAndSubscribe('weather/#');
    }
}
