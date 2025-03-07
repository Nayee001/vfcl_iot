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
            'device_id',
            'assign_to',
            'location_id'
        ];

        // Filter input data based on allowed fields
        $modifiedData = array_intersect_key($inputdata, array_flip($fillableFields));

        // Assign the currently authenticated manager
        $modifiedData['assign_by'] = Auth::id(); // Manager assigning the device
        $modifiedData['manager_id'] = $modifiedData['assign_to']; // Store manager_id

        // Find the user and device
        $user = User::find($modifiedData['assign_to']);
        $device = Device::find($modifiedData['device_id']);

        // Check if both user and device exist before proceeding
        if (!$user || !$device) {
            return false;
        }

        // Send email notification (Uncomment if required)
        // Mail::to($user->email)->send(new \App\Mail\DeviceAssigned($device, $user));

        // Store the assignment
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
