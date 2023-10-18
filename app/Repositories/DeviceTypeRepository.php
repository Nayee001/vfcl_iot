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

    public function getAllUsers()
    {
    }

    public function getUserById($id)
    {
    }

    public function store($input)
    {
        try {
            $user = $this->model::create($input);
            return $user;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function destroy($id)
    {
        try {
            $device_type = $this->model::where('id', $id)->forceDelete();
            return $device_type;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function dataTable($request)
    {
        try {
            if ($request->ajax()) {
                $deviceType = $this->model::orderBy('id', 'DESC')->get();
                return DataTables::of($deviceType)
                    ->addIndexColumn()
                    ->addColumn('active_or_not', function ($row) {
                        $active_or_not = '';
                        if ($row->status == DeviceType::STATUS['ACTIVE']) {
                            $active_or_not .= '<span class="badge rounded-pill bg-label-success me-1">Active</span>';
                        } elseif ($row->status == DeviceType::STATUS['INACTIVE']) {
                            $active_or_not .= '<span class="badge rounded-pill bg-label-primary me-1">Not Active</span>';
                        }
                        return $active_or_not;
                    })
                    ->addColumn('actions', function ($row) {
                        $actions = '';
                        if (Gate::allows('device-type-edit', $row)) {
                            $actions .= '<a href="' . route('users.edit', $row->id) . '" title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn" href="javascript:void(0);"><i
                        class="bx bx-edit-alt"></i></a>';
                        }
                        if (Gate::allows('device-type-delete', $row)) {
                            $actions .= '<a class="btn rounded-pill btn-icon btn-outline-danger delete-device-type"  title="Delete"  href="javascript:void(0);"
                            id="' . $row->id . '"><i class="bx bx-trash-alt "></i></a></div>';
                        }
                        return $actions;
                    })
                    ->rawColumns(['active_or_not', 'actions'])
                    ->make(true);
            }
        } catch (Exception $e) {
        }
    }
}
