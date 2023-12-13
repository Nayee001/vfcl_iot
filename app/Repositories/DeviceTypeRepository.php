<?php

namespace App\Repositories;

use App\Interfaces\DeviceTypeRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Datatables;
use App\Models\DeviceType;

class DeviceTypeRepository implements DeviceTypeRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAllDeviceType()
    {
        $get = $this->model::pluck('device_type','id');
        return $get;
    }

    public function getAllDeviceTypeWithCounts()
{
    $deviceTypesWithCounts = $this->model::select('device_types.id', 'device_types.device_type', \DB::raw('COUNT(devices.id) as device_count'))
        ->leftJoin('devices', 'device_types.id', '=', 'devices.device_type')
        ->groupBy('device_types.id', 'device_types.device_type')
        ->pluck('device_count', 'device_types.device_type');

    return $deviceTypesWithCounts;
}
    public function getUserById($id)
    {
        try {
            $find = $this->model::find($id);
            return $find;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function update($request, $id){
        try {
            $input = ['device_type' => $request->device_type,'description' => $request->description];
            $device_type = $this->model::where('id', $id)->update($input);
            if ($device_type) {
                return successMessage('Device Type Updated Successfully !!');
            } else {
                return errorMessage();
            }
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    public function store($input)
    {
        try {
            $data = $this->model::create($input);
            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }
    /**
     * Undocumented function
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $device_type = $this->model::where('id', $id)->delete();
            if ($device_type) {
                return successMessage('Device Type Deleted !!');
            } else {
                return errorMessage();
            }
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    public function dataTable($request)
    {
        try {
            if ($request->ajax()) {
                $deviceType = $this->model::orderBy('id', 'DESC')->get();
                return DataTables::of($deviceType)
                    ->addIndexColumn()
                    ->addColumn('actions', function ($row) {
                        $actions = '';
                        if (Gate::allows('device-type-edit', $row)) {
                            $actions .= '<a href="javascript:void(0);" title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn" onClick="getDeviceTypeEditForm(\'' . route('devices-type.edit', $row->id) . '\')" ><i class="bx bx-edit-alt"></i></a>';
                        }
                        if (Gate::allows('device-type-delete', $row)) {
                            $actions .= '<a class="btn rounded-pill btn-icon btn-outline-danger delete-device-type"  title="Delete"  href="javascript:void(0);"
                            id="' . $row->id . '"><i class="bx bx-trash-alt "></i></a></div>';
                        }
                        return $actions;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
        } catch (Exception $e) {
        }
    }
}
