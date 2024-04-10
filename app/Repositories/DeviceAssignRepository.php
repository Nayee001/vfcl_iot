<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\DeviceAssignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\Mail;

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
    public function assignDeviceToUser(array $inputdata): bool
    {
        $fillableFields = [
            'device_id', 'assign_to', 'location_id'
        ];


        $modifiedData = array_intersect_key($inputdata, array_flip($fillableFields));
        if ($this->model->where('device_id', $modifiedData['device_id'])->exists()) {
            return false;
        }
        $modifiedData['assign_by'] = Auth::id();
        $user = User::find($modifiedData['assign_to']);
        $device = Device::find($modifiedData['device_id']);
        Mail::to($user->email)->send(new \App\Mail\DeviceAssigned($device, $user));
        return (bool) $this->model->create($modifiedData);
    }

    public function destroy($id)
    {
        try {
            $device = $this->model::where('id', $id)->delete();
            if ($device) {
                return successMessage('Device UnAssigned !!');
            } else {
                return errorMessage();
            }
        } catch (ModelNotFoundException $e) {
            return exceptionMessage($e->getMessage());
        }
    }
}
