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
     * Retrieves all device records from the database.
     *
     * This function uses the underlying model to fetch all the device entries.
     *
     * @return Collection Returns a collection of all device records.
     */
    public function getDevices()
    {
        if (isSuperAdmin()) {
            return $this->model::with('deviceType', 'deviceOwner', 'createdBy')->get();
        } else {
            return $this->model::with('deviceType', 'deviceOwner', 'createdBy')->where('created_by', Auth::id())->get();
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
            'name', 'device_type', 'owner', 'health', 'status', 'description'
        ];
        $modifiedData = array_intersect_key($inputdata, array_flip($fillableFields));
        $modifiedData['created_by'] = Auth::id();
        return $this->model->create($modifiedData);
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
                $deviceType = $this->getDevices();
                if (!isSuperAdmin()) {
                    $deviceType = $this->getDevices();
                }
                return DataTables::of($deviceType)
                    ->addIndexColumn()
                    ->addColumn('deviceStatus', function ($row) {
                        if ($row->status == $this->model::STATUS['Active']) {
                            $status = '<span class="badge bg-label-primary me-1">' . $row->status . '</span>';
                            return $status;
                        } elseif ($row->status == $this->model::STATUS['Inactive']) {
                            $status = '<span class="badge bg-label-danger me-1">' . $row->status . '</span>';
                            return $status;
                        } else {
                            $status = '<span class="badge bg-label-warning me-1">' . $row->status . '</span>';
                            return $status;
                        }
                    })->addColumn('ownedBy', function ($row) {
                        $ownedBy = '<a class="primary" href="' . route('users.show', $row->deviceOwner->id) . '">' . $row->deviceOwner->fname . ' ' . $row->deviceOwner->lname . '</a>';
                        return $ownedBy;
                    })->addColumn('createdBy', function ($row) {
                        $createdBy = '<a class="secondary" href="' . route('users.show', $row->createdBy->id) . '">' . $row->createdBy->fname . ' ' . $row->createdBy->lname . '</a>';
                        return $createdBy;
                    })
                    ->addColumn('createdtime', function ($row) {
                        $createdtime = $row->created_at->format('m-d-Y');
                        return $createdtime;
                    })->addColumn('actions', function ($row) {
                        $actions = '';
                        if ($row) {
                            $actions .= '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="">';
                            if (Gate::allows('device-edit', $row)) {
                                $actions .= '<a class="dropdown-item" href="' . route('devices.edit', $row->id) . '" title="Edit"><i class="bx bx-edit-alt me-1"></i> Edit</a>';
                            }
                            if (Gate::allows('device-delete', $row)) {
                                $actions .= '<a class="dropdown-item delete-device"  title="Delete"  href="javascript:void(0);"
                                id="' . $row->id . '"><i class="bx bx-trash-alt "></i> Delete</a>';
                            }
                            $actions .= '</div>
                          </div>';
                            // if (Gate::allows('device-edit', $row)) {
                            //     $actions .= '<a href="' . route('devices.edit', $row->id) . '" title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn"><i class="bx bx-edit-alt"></i></a>';
                            // }
                            // if (Gate::allows('device-delete', $row)) {
                            //     $actions .= '<a class="btn rounded-pill btn-icon btn-outline-danger delete-device"  title="Delete"  href="javascript:void(0);"
                            // id="' . $row->id . '"><i class="bx bx-trash-alt "></i></a></div>';
                            // }
                        }

                        return $actions;
                    })
                    ->rawColumns(['deviceStatus', 'ownedBy', 'createdBy', 'createdtime', 'actions'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
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
            return $this->model::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function updateDevice($request, $id)
    {
        $fillableFields = [
            'name', 'device_type', 'owner', 'health', 'status', 'description'
        ];

        $modifiedData = array_intersect_key($request, array_flip($fillableFields));
        $modifiedData['updated_by'] = Auth::id();  // Assuming you intended to track who updated the record

        $device = $this->model->findOrFail($id);  // Find the specific device by ID or fail
        return $device->update($modifiedData);
    }
}
