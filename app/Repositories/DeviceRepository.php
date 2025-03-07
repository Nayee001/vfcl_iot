<?php

namespace App\Repositories;

use App\Interfaces\DeviceRepositoryInterface;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\DeviceAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DeviceVerification;
use App\Models\Notifications;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DeviceVerificationNotification;
use Illuminate\Support\Facades\DB;


class DeviceRepository implements DeviceRepositoryInterface
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
     * Verifies device by updating encryption key based on API key.
     *
     * @param array $associativeArray An associative array that must include 'api_key' and 'encryption_key'.
     * @return int|bool Returns user ID on success, false on failure.
     */
    public function deviceVerifications($associativeArray)
    {
        try {
            // Check for required keys
            if (!isset($associativeArray['api_key'], $associativeArray['encryption_key'])) {
                Log::error('Missing required keys in associative array for device verification.');
                throw new \InvalidArgumentException('Missing required keys in associative array for device verification.');
            }

            // Retrieve device using the API key
            $device = $this->model::where('short_apikey', $associativeArray['api_key'])->first();

            if (!$device) {
                Log::error('No device found with the provided API key.', ['api_key' => $associativeArray['api_key']]);
                throw new \RuntimeException('No device found with the provided API key.');
            }

            // Retrieve device assignment and user in a single query
            $assignment = DeviceAssignment::with('assignee')->where('device_id', $device->id)->first();

            if (!$assignment) {
                Log::error('No assignment found for the device.', ['device_id' => $device->id]);
                throw new \RuntimeException('No assignment found for the device.');
            }

            $user = $assignment->assignee;

            if (!$user) {
                Log::error('User not found for the assignment.', ['assignment_id' => $assignment->id]);
                throw new \RuntimeException('User not found.');
            }

            if ($assignment->email_sent == true) {
                Log::info('Email already sent for device assignment.', ['assignment_id' => $assignment->id]);
                return $assignment->assign_to;
            } else {
                // Send the device verification email
                // Mail::to($user->email)->send(new DeviceVerificationNotification($device, $user));
                Log::info('Sending device verification email.', ['user_email' => $user->email, 'device_id' => $device->id]);

                $assignment->update(['email_sent' => 1]);
            }

            // Update the encryption key and login status
            $update = $assignment->update(['encryption_key' => $associativeArray['encryption_key'], 'login_to_device' => 1]);
            if (!$update) {
                Log::error('Failed to update device assignment with encryption key and login status.', ['assignment_id' => $assignment->id]);
                throw new \RuntimeException('Failed to update device assignment.');
            }

            // Create or update the notification
            $notification = Notifications::updateOrCreate(
                ['device_id' => $device->id, 'user_id' => $assignment->assign_to],
                ['notification' => Notifications::DefaultNotiMessages['deviceVerification']]
            );

            if (!$notification) {
                Log::error('Failed to create or update notification entry.', ['device_id' => $device->id, 'user_id' => $assignment->assign_to]);
                throw new \RuntimeException('Failed to create or update notification entry.');
            }

            Log::info('Device verification completed successfully.', ['device_id' => $device->id, 'user_id' => $assignment->assign_to]);
            return $assignment->assign_to;
        } catch (\Exception $e) {
            Log::error("Error during device verification: {$e->getMessage()}", ['exception' => $e]);
            return false;
        }
    }


    public function deviceData(int $id)
    {
        try {
            $device = $this->model::with([
                'deviceType',
                'deviceOwner',
                'createdBy',
                'deviceAssigned',
                'deviceAssigned.assignee',
                'deviceAssigned.deviceLocation',
                'deviceAssigned.assignee.locations',
            ])->findOrFail($id);

            return $device;
        } catch (ModelNotFoundException $e) {
            // Handle the exception, e.g., return a 404 response or custom error message
            return response()->json(['error' => 'Device not found'], 404);
        }
    }

    public function getDeviceTotalCount()
    {
        if (isSuperAdmin()) {
            return $this->model::count();
        } elseif (isManager()) {
            return DeviceAssignment::where('assign_to', Auth::id())
            ->count();
        } else {
            return DeviceAssignment::where('assign_to', Auth::id())
                ->count();
        }
    }

    public function getTotalActiveDevice()
    {
        if (isSuperAdmin()) {
            return $this->model::count();
        } elseif (isManager()) {
            return DeviceAssignment::where('assign_to', Auth::id())->where('connection_status', DeviceAssignment::ConnectionStatus['Authorized'])
                ->count();
        } else {
            return DeviceAssignment::where('assign_to', Auth::id())->where('connection_status', DeviceAssignment::ConnectionStatus['Authorized'])
                ->count();
        }
    }
    /**
     * Retrieves all device records from the database.
     *
     * This function uses the underlying model to fetch all the device entries.
     *
     * @return Collection Returns a collection of all device records.
     */
    public function getDevices()
    {
        if (isSuperAdmin()) {
            $device =  $this->model::with('deviceType', 'deviceOwner', 'createdBy', 'deviceAssigned', 'deviceAssigned.assignee', 'deviceAssigned.deviceLocation', 'deviceAssigned.assignee.locations')->get();
            return $device;
        } elseif (isManager()) {
            return $this->model::with('deviceType', 'deviceOwner', 'createdBy', 'deviceAssigned', 'deviceAssigned.assignee', 'deviceAssigned.deviceLocation', 'deviceAssigned.assignee.locations')->where('created_by', Auth::id())->orWhereHas('deviceAssigned', function ($query) {
                $query->where('assign_to', Auth::id());
            })
                ->get();
        } else {
            $device =  $this->model::with(['deviceType', 'deviceOwner', 'createdBy', 'deviceAssigned', 'deviceAssigned.assignee', 'deviceAssigned.deviceLocation', 'deviceAssigned.assignee.locations'])
                ->whereHas('deviceAssigned', function ($query) {
                    $query->where('assign_to', Auth::id());
                })
                ->get();
            return $device;
        }
    }

    public function assingedDevices($id)
    {
        $query = $this->model::with([
            'deviceType',
            'deviceOwner',
            'createdBy',
            'deviceAssigned',
            'deviceAssigned.assignee',
            'deviceAssigned.deviceLocation',
            'deviceAssigned.assignee.locations'
        ]);

        if (!isSuperAdmin()) {
            return $query->get();
        } else {
            $query->where('created_by', $id)
                ->orWhereHas('deviceAssigned', function ($query) use ($id) {
                    $query->where('assign_to', $id);
                });
            return $query->get();
        }
    }



    public function deviceVerify($id)
    {
        $device =  $this->model::with([
            'deviceType',
            'deviceOwner',
            'createdBy',
            'deviceAssigned',
            'deviceAssigned.assignee',
            'deviceAssigned.deviceLocation',
            'deviceAssigned.assignee.locations'
        ])
            ->whereHas('deviceAssigned', function ($query) {
                $query->where('assign_to', Auth::id());
            })
            ->where('id', $id)  // Adding the condition to filter by id
            ->first();  // Changed from get() to first()
        return $device;
    }


    public function sendDeviceModel($id)
    {
        try {
            $deviceAssignmentUpdated = DeviceAssignment::where('device_id', $id)
                ->where('assign_to', Auth::id())->update(['status' => 'Accept', 'connection_status' => 'Authorized']);
            $notificationsUpdated = Notifications::where('device_id', $id)
                ->where('user_id', Auth::id())->update(['read' => 'Yes']);
            return $deviceAssignmentUpdated;
        } catch (\Exception $e) {
            report($e);
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

    public function deviceDashboard()
    {
        return $this->getDevices();
    }

    public function assingedDevice($id)
    {
        return $this->assingedDevices($id);
    }

    public function resetDevice($id)
    {
        try {
            // Log to check if function is being called
            Log::info("Resetting device with ID: $id");

            // Attempting to reset the specified fields
            $affectedRows = DeviceAssignment::where('device_id', $id)
                ->update([
                    'email_sent' => 0,
                    'last_sync' => null,
                    'send_mac' => 0,
                    'login_to_device' => 0,
                    'connection_status' => 'Not Authorized',
                    'status' => 'Not Responded',
                    'encryption_key' => null,
                    'updated_at' => now()
                ]);

            // Check if any rows were affected
            if ($affectedRows > 0) {
                Log::info("Device reset successfully: $id");
                return response()->json(['success' => true, 'message' => 'Device has been reset successfully.']);
            } else {
                Log::warning("No rows affected while resetting device: $id");
                return response()->json(['success' => false, 'message' => 'No rows were updated.']);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error resetting device with ID: $id", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to reset device.']);
        }
    }
    /**
     * Pluck all device records from the database.
     *
     * This function uses the underlying model to fetch all the device entries.
     *
     * @return Collection Returns a collection of all device records.
     */
    public function getPluckedDevices()
    {
        if (isSuperAdmin()) {
            return $this->model::with('deviceType', 'deviceOwner', 'createdBy')->pluck('name', 'id');
        } else {
            return $this->model::with('deviceType', 'deviceOwner', 'createdBy')->where('created_by', Auth::id())->pluck('name', 'id');
        }
    }

    /**
     * Return array of statuss
     *
     * @return Array
     */
    public function getAllStatus()
    {
        return $this->model::STATUS;
    }

    /**
     * Return array of healths
     *
     * @return Array
     */
    public function getAllHealth()
    {
        return $this->model::HEALTH;
    }

    /**
     * Create A New Device
     *
     * @param array $inputdata
     * @return Bool
     */
    public function createDevice(array $inputdata): Model
    {
        $fillableFields = [
            'name',
            'device_type',
            'owner',
            'health',
            'status',
            'description',
            'ip_address',
            'mac_address'
        ];
        $modifiedData = array_intersect_key($inputdata, array_flip($fillableFields));
        $modifiedData['created_by'] = Auth::id();
        return $this->model->create($modifiedData);
    }

    /**
     * Destroy device records from the database.
     *
     * This function uses the underlying model to Delete the device entries.
     *
     * @param int $id
     * @return Bool
     */
    public function destroy($id)
    {
        try {
            $device = $this->model::where('id', $id)->delete();
            if ($device) {
                return successMessage('Device Deleted !!');
            } else {
                return errorMessage();
            }
        } catch (ModelNotFoundException $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param int $id
     * @return void
     */
    public function findorfail($id)
    {
        try {
            return $this->model::with('deviceType', 'deviceOwner', 'createdBy', 'deviceAssigned', 'deviceAssigned.assignee', 'deviceAssigned.assignee.locations')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update Devices into Database.
     *
     * This function uses the underlying model to Update the device entry.
     *
     * @param int $id
     * @param Request
     * @return Bool
     */
    public function updateDevice($request, $id)
    {
        $fillableFields = [
            'name',
            'device_type',
            'owner',
            'health',
            'status',
            'description',
            'mac_address',
            'ip_address'
        ];
        $modifiedData = array_intersect_key($request, array_flip($fillableFields));
        $modifiedData['updated_by'] = Auth::id();
        $device = $this->model->findOrFail($id);
        if ($request['mac_address'] != $device['mac_address']) {
            $modifiedData['api_key'] = $this->model::generateApiKey($request['mac_address']);
        }
        return $device->update($modifiedData);
    }

    /**
     * Device Datable
     *
     * @param [type] $request
     * @return void
     */
    public function dataTable($request)
    {
        try {
            if ($request->ajax()) {
                $device = $this->getDevices();
                return DataTables::of($device)
                    ->addIndexColumn()
                    ->addColumn('deviceName', function ($row) {
                        $deviceName = '<a href="' . route('devices.dashboard', $row->id) . '" class="text-primary">'
                            . htmlspecialchars($row->name) . '</a>';
                        return $deviceName;
                    })
                    ->addColumn('deviceStatus', function ($row) {
                        switch ($row->status) {
                            case $this->model::STATUS['Active']:
                                $badgeClass = 'primary';
                                break;
                            case $this->model::STATUS['Inactive']:
                                $badgeClass = 'danger';
                                break;
                            default:
                                $badgeClass = 'warning';
                                break;
                        }
                        $status = '<span class="badge bg-label-' . $badgeClass . ' me-1">' . e($row->status) . '</span>';
                        return $status;
                    })->addColumn('ownedBy', function ($row) {
                        // Check if the current user is a manager and if the device owner has the super admin role
                        if (isManager() && $row->deviceOwner->hasRole('Super Admin')) {
                            $ownedBy = "Super Admin";
                        } else {
                            $ownedBy = '<a class="primary" href="' . route('users.show', $row->deviceOwner->id) . '">' . $row->deviceOwner->fname . ' ' . $row->deviceOwner->lname . '</a>';
                        }
                        return $ownedBy;
                    })->addColumn('createdBy', function ($row) {
                        $createdBy = '<a class="secondary" href="' . route('users.show', $row->createdBy->id) . '">' . $row->createdBy->fname . ' ' . $row->createdBy->lname . '</a>';
                        return $createdBy;
                    })->addColumn('assignee', function ($row) {
                        $assigneeLink = $row->deviceAssigned
                            ? '<a class="secondary" href="' . route('users.show', $row->deviceAssigned->assignee->id) . '">'
                            . e($row->deviceAssigned->assignee->fname)
                            . ' '
                            . e($row->deviceAssigned->assignee->lname) . '</a>'
                            : '--';
                        return $assigneeLink;
                    })->addColumn('location', function ($row) {
                        $location = optional(optional($row->deviceAssigned)->deviceLocation, function ($locations) {
                            return $locations->location_name;
                        });

                        return $location ?? '--';
                    })->addColumn('createdtime', function ($row) {
                        $createdtime = $row->created_at->format('m-d-Y');
                        return $createdtime;
                    })->addColumn('apikey', function ($row) {
                        $apikey = $row->api_key
                            ? '<a href="javascript:void(0);" title="Assign Device" class="dropdown-item" onClick="getApiKey(\'' . route('device.getApiKey', $row->id) . '\')"><i class="bx bx-copy-alt"></a'
                            : '--';

                        return $apikey;
                    })->addColumn('actions', function ($row) {
                        $editAction = Gate::allows('device-edit', $row)
                            ? '<a class="dropdown-item" href="' . route('devices.edit', $row->id) . '" title="Edit"><i class="bx bx-edit-alt me-1"></i> Edit</a>'
                            : '';

                        $deleteAction = Gate::allows('device-delete', $row)
                            ? '<a class="dropdown-item delete-device" title="Delete" href="javascript:void(0);" id="' . $row->id . '"><i class="bx bx-trash-alt "></i> Delete</a>'
                            : '';

                        $detailsAction = Gate::allows('device-details', $row)
                            ? '<a class="dropdown-item" title="Delete" href="' . route('devices.show', $row->id) . '"><i class="bx bx-detail"></i> Details</a>'
                            : '';

                        $assignDeviceAction = !$row->deviceAssigned && Gate::allows('device-assign', $row)
                            ? '<a href="javascript:void(0);" title="Assign Device" class="dropdown-item" onClick="assignDeviceToUsers(\'' . route('device.assign', $row->id) . '\')"><i class="bx bx-chip"></i>Assign Device</a>'
                            : '';

                        $unAssignDeviceAction = $row->deviceAssigned
                            ? '<a href="javascript:void(0);" title="Un-assign Device" class="dropdown-item unassign-device" id="' . $row->deviceAssigned->id . '"><i class="bx bx-chip"></i>Un-assign Device</a>'
                            : '';

                        $actions = $editAction || $deleteAction || $detailsAction || $assignDeviceAction || $unAssignDeviceAction
                            ? '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">' .
                            $editAction .
                            $deleteAction .
                            $detailsAction .
                            $assignDeviceAction .
                            $unAssignDeviceAction .
                            '</div>
                          </div>'
                            : '';

                        return $actions;
                    })
                    ->rawColumns(['deviceName', 'deviceStatus', 'ownedBy', 'createdBy', 'createdtime', 'assignee', 'location', 'actions', 'apikey'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * Device Datable
     *
     * @param [type] $request
     * @return void
     */
    public function dashboarddevicedataTable($request)
    {
        try {
            if ($request->ajax()) {
                $device = $this->getDevices();
                return DataTables::of($device)
                    ->addIndexColumn()
                    ->addColumn('deviceStatus', function ($row) {
                        switch ($row->status) {
                            case $this->model::STATUS['Active']:
                                $badgeClass = 'primary';
                                break;
                            case $this->model::STATUS['Inactive']:
                                $badgeClass = 'danger';
                                break;
                            default:
                                $badgeClass = 'warning';
                                break;
                        }
                        $status = '<span class="badge bg-label-' . $badgeClass . ' me-1">' . e($row->status) . '</span>';
                        return $status;
                    })->addColumn('ownedBy', function ($row) {
                        $ownedBy = '<a class="primary" href="' . route('users.show', $row->deviceOwner->id) . '">' . $row->deviceOwner->fname . ' ' . $row->deviceOwner->lname . '</a>';
                        return $ownedBy;
                    })->addColumn('createdBy', function ($row) {
                        $createdBy = '<a class="secondary" href="' . route('users.show', $row->createdBy->id) . '">' . $row->createdBy->fname . ' ' . $row->createdBy->lname . '</a>';
                        return $createdBy;
                    })->addColumn('assignee', function ($row) {
                        $assigneeLink = $row->deviceAssigned
                            ? '<a class="secondary" href="' . route('users.show', $row->deviceAssigned->assignee->id) . '">'
                            . e($row->deviceAssigned->assignee->fname)
                            . ' '
                            . e($row->deviceAssigned->assignee->lname) . '</a>'
                            : '--';
                        return $assigneeLink;
                    })->addColumn('location', function ($row) {
                        $location = optional(optional($row->deviceAssigned)->deviceLocation, function ($locations) {
                            return $locations->location_name;
                        });

                        return $location ?? '--';
                    })->addColumn('createdtime', function ($row) {
                        $createdtime = $row->created_at->format('m-d-Y');
                        return $createdtime;
                    })->addColumn('apikey', function ($row) {
                        $apikey = $row->api_key
                            ? '<a href="javascript:void(0);" title="Assign Device" class="dropdown-item" onClick="getApiKey(\'' . route('device.getApiKey', $row->id) . '\')"><i class="bx bx-copy-alt"></a'
                            : '--';

                        return $apikey;
                    })->addColumn('actions', function ($row) {
                        $editAction = Gate::allows('device-edit', $row)
                            ? '<a class="dropdown-item" href="' . route('devices.edit', $row->id) . '" title="Edit"><i class="bx bx-edit-alt me-1"></i> Edit</a>'
                            : '';

                        $deleteAction = Gate::allows('device-delete', $row)
                            ? '<a class="dropdown-item delete-device" title="Delete" href="javascript:void(0);" id="' . $row->id . '"><i class="bx bx-trash-alt "></i> Delete</a>'
                            : '';

                        $detailsAction = Gate::allows('device-details', $row)
                            ? '<a class="dropdown-item" title="Delete" href="' . route('devices.show', $row->id) . '"><i class="bx bx-detail"></i> Details</a>'
                            : '';

                        $assignDeviceAction = !$row->deviceAssigned && Gate::allows('device-assign', $row)
                            ? '<a href="javascript:void(0);" title="Assign Device" class="dropdown-item" onClick="assignDeviceToUsers(\'' . route('device.assign', $row->id) . '\')"><i class="bx bx-chip"></i>Assign Device</a>'
                            : '';

                        $unAssignDeviceAction = $row->deviceAssigned
                            ? '<a href="javascript:void(0);" title="Un-assign Device" class="dropdown-item unassign-device" id="' . $row->deviceAssigned->id . '"><i class="bx bx-chip"></i>Un-assign Device</a>'
                            : '';

                        $actions = $editAction || $deleteAction || $detailsAction || $assignDeviceAction || $unAssignDeviceAction
                            ? '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">' .
                            $editAction .
                            $deleteAction .
                            $detailsAction .
                            $assignDeviceAction .
                            $unAssignDeviceAction .
                            '</div>
                          </div>'
                            : '';

                        return $actions;
                    })
                    ->rawColumns(['deviceStatus', 'ownedBy', 'createdBy', 'createdtime', 'assignee', 'location', 'actions', 'apikey'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function verifyDeviceApi($request, $user)
    {
        try {
            // Extract MAC address and API key from the request
            $macAddress = $request['mac_address'];
            $apikey = $request['apikey'];

            // Check if the device exists with the given MAC address and API key
            $device = $this->model::where('mac_address', $macAddress)
                ->where('short_apikey', $apikey)
                ->firstOrFail(); // Automatically returns 404 if not found

            // Check if the device is assigned to the user
            abort_unless(
                DeviceAssignment::where('assign_to', $user->id)
                    ->where('device_id', $device->id)
                    ->exists(),
                403,
                'Device not assigned to this user.'
            );

            // If all checks pass, return a success message with a success flag
            return response()->json([
                'success' => true,
                'message' => 'The device has been successfully verified.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found.'
            ], 404);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // This will catch the abort_unless failure
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            // Generic error handling
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }
}
