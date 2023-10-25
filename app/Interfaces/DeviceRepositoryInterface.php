<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface DeviceRepositoryInterface
{
    public function getDevices();
    public function getAllStatus();
    public function getAllHealth();
    public function destroy($id);
    public function dataTable($request);
    public function findorfail($id);
    public function updateDevice($request,$id);
    public function createDevice(array $inputData): Model;
    public function getPluckedDevices();
}
