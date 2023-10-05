<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserDeactivationRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:users-list|users-create|users-edit|users-delete', ['only' => ['index', 'store']]);
        // $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }
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
    public function store(StoreUserRequest $request)
    {
        try {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['status'] = User::USER_STATUS['NEWUSER'];
            $user = User::create($input);
            $user->assignRole($request->input('role'));

            return successMessage('User Created successfully');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return errorMessage();
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'title' => 'required',
            'roles' => 'required',
            'password' => 'same:confirm-password',
            'email' => 'required|email|unique:users,email,' . $id,
            'phonenumber' => 'required|numeric|digits:10|unique:users,phonenumber,' . $id,
        ]);
        try {

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
            return successMessage('User updated successfully');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return errorMessage();
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
            User::find($id)->forceDelete();
            return successMessage('User deleted successfully');
        } catch (Exception $e) {
            return errorMessage();
        }
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
                    $actions .= '<div class="row"><a href="' . route('users.edit', $row->id) . '" title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn" href="javascript:void(0);"><i
                    class="bx bx-edit-alt"></i></a>';
                    if ($row->deleted_at == null) {
                        $actions .= '<a class="btn rounded-pill btn-icon btn-outline-danger delete-user"  title="Delete"  href="javascript:void(0);"
                        id="' . $row->id . '"><i class="bx bx-trash-alt "></i></a></div>';
                    } else {
                        $actions .= '<a class="btn rounded-pill btn-icon btn-outline-warning restore-user"  title="Delete"  href="javascript:void(0);"
                        id="' . $row->id . '"><i class="bx bx-undo"></i></a></div>';
                    }
                    return $actions;
                })
                ->rawColumns(['status', 'role', 'devices', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for account settings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accountSettings($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('user_settings.account_settings', compact('user', 'roles', 'userRole'));
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(UserDeactivationRequest $request, $id)
    {
        try {
            if ($request->input('accountDeactivation') == 'on') {
                User::where('id', $id)->update(['status' => User::USER_STATUS['INACTIVE']]);
                User::find($id)->delete();
                return successMessage('Loging Out !!');
            }
        } catch (Exception $e) {
            return errorMessage();
        }
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {
            User::withTrashed()->find($id)->restore();
            User::where('id', $id)->update(['status' => User::USER_STATUS['ACTIVE']]);
            return successMessage('User Activated successfully');
        } catch (Exception $e) {
            return errorMessage();
        }
    }

    /**
     * Show the form for account settings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chnagePassword($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('user_settings.change-password', compact('user', 'roles', 'userRole'));
    }


    /**
     * Chnage Password Request.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chnagePasswordRequest(ChangePasswordRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if (Hash::check($request->oldpassword, $user->password)) {
                $input['status'] = User::USER_STATUS['FIRSTTIMEPASSWORDCHANGED'];
                $input['password'] = Hash::make($request->password);
                $user->update($input);
                return successMessage('Password Changed successfully');
            }
        } catch (Exception $e) {
            return errorMessage();
        }
    }

    /**
     * Chnage Password Request.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function termsandconditions(Request $request)
    {
        $this->validate($request, [
            'terms_and_conditions' => 'required',
        ]);
        try {
            if ($request->terms_and_conditions) {
                $user = User::findOrFail(Auth::user()->id);
                $input['status'] = User::USER_STATUS['ACTIVE'];
                $input['terms_and_conditions'] = User::TERMS_AND_CONDITIONS;
                $user->update($input);
                return successMessage('Please Wait ...');
            }
        } catch (Exception $e) {
            return errorMessage();
        }
    }
}
