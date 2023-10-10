<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Datatables;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:role-list', ['only' => ['index', 'store']]);
        // $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {

        $permission = Permission::get();
        $roles = Role::with('permissions')->orderBy('id', 'DESC')->paginate(5);
        return view('roles.index', compact('roles', 'permission'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        try {
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
            return successMessage('Role Created !!');
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        try {
            $role = Role::find($id);
            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();
            return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
        } catch (Exception $e) {
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'permission' => 'required',
            ]);

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            $role->syncPermissions($request->input('permission'));
            return successMessage('Role Updated !!');
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Role::where('id', $id)->forceDelete();
            return successMessage('Role Deleted !!');
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    public function roleAjaxDatatable(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('permissions')->orderBy('id', 'DESC')->get();
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('guard', function ($row) {
                    $btn = '<span class="badge bg-label-primary me-1">' . $row->guard_name . '</span>';
                    return $btn;
                })->addColumn('actions', function ($row) {
                    $actions = '';
                    if ($row->deleted_at == null) {
                        if (Gate::allows('role-edit', $row)) {
                            $actions .= '<div class="row"><a href="' . route('roles.edit', $row->id) . '" title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn" href="javascript:void(0);"><i
                            class="bx bx-edit-alt"></i></a>';
                        }
                        if (Gate::allows('role-delete', $row)) {
                            $actions .= '<a class="btn rounded-pill btn-icon btn-outline-danger delete-role"  title="Delete"  href="javascript:void(0);"
                            id="' . $row->id . '"><i class="bx bx-trash-alt "></i></a></div>';
                        }
                    }
                    return $actions;
                })
                ->rawColumns(['guard', 'permissions', 'actions'])
                ->make(true);
        }
    }
}
