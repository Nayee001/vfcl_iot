<?php

namespace App\Interfaces;
use Illuminate\Database\Eloquent\Model;

interface DeviceAssignRepositoryInterface
{
    public function assignDeviceToUser(array $inputData): Model;
}
