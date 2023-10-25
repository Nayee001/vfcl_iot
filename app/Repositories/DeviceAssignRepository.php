<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\DeviceAssignRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DeviceAssignRepository implements DeviceAssignRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create A New Device
     *
     * @param array $inputdata
     * @return Bool
     */
    public function assignDeviceToUser(array $inputdata): Model
    {
        $fillableFields = [
            'device_id', 'assign_to', 'location'
        ];
        $modifiedData = array_intersect_key($inputdata, array_flip($fillableFields));
        $modifiedData['assign_by'] = Auth::id();
        return $this->model->create($modifiedData);
    }
}
