<?php

namespace App\Interfaces;

interface MqttServiceInterface
{
    public function connectAndSubscribe($topic);
}
