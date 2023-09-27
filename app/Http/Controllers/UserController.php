<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Datatables;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::latest()->paginate(5);

        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function userAjaxDatatable(Request $request)
    {
        if ($request->ajax()) {
            $users = User::orderBy('id', 'DESC')->withTrashed()->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $role = '<span class="badge bg-label-secondary me-1">--</span>';
                    if (!empty($row->getRoleNames())) {
                        foreach ($row->getRoleNames() as $v) {
                            $role = '<span class="badge bg-label-primary me-1">' . $v . '</span>';
                        }
                    }
                    return $role;
                })
                ->addColumn('devices', function ($row) {
                    $devices = '<span class="badge bg-label-secondary me-1">--</span>';
                    return $devices;
                })
                ->addColumn('status', function ($row) {
                    $status = '';
                    if ($row->status == User::USER_STATUS['ACTIVE']) {
                        $status .= '<span class="badge rounded-pill bg-label-success me-1">Active</span>';
                    } elseif ($row->status == User::USER_STATUS['NEWUSER']) {
                        $status .= '<span class="badge rounded-pill bg-label-primary me-1">New User</span>';
                    } elseif ($row->status == User::USER_STATUS['NOACTIVEDEVICE']) {
                        $status .= '<span class="badge rounded-pill bg-label-warning me-1">No Active Devices</span>';
                    } elseif ($row->status == User::USER_STATUS['INACTIVE']) {
                        $status .= '<span class="badge rounded-pill bg-label-danger me-1">In Active</span>';
                    }
                    return $status;
                })->addColumn('actions', function ($row) {
                    $actions = '';
                    $actions .= '<div class="row"><a title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn" href="javascript:void(0);"><i
                    class="bx bx-edit-alt"></i></a>';
                    if ($row->deleted_at == null) {
                        $actions .= '<a class="btn rounded-pill btn-icon btn-outline-danger delete-user"  title="Delete"  href="javascript:void(0);"
                        id="' . $row->id . '"><i class="bx bx-trash-alt "></i></a></div>';
                    }else{
                        $actions .= '<a class="btn rounded-pill btn-icon btn-outline-warning restore-user"  title="Delete"  href="javascript:void(0);"
                        id="' . $row->id . '"><i class="bx bx-undo"></i></a></div>';
                    }
                    return $actions;
                })
                ->rawColumns(['status', 'role', 'devices', 'actions'])
                ->make(true);
        }
    }
}
