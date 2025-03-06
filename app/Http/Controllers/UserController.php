<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUsersRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserDeactivationRequest;
use App\Repositories\LocationRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Repositories\UserRepository;
use App\Services\DeviceService;
use App\Repositories\LocationNameRepository;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $userRepository, $deviceService, $locationRepository, $locationNameRepository;

    function __construct(
        UserRepository $userRepository,
        DeviceService $deviceService,
        LocationRepository $locationRepository,
        LocationNameRepository $locationNameRepository
    ) {
        $this->userRepository = $userRepository;
        $this->deviceService = $deviceService;
        $this->locationRepository = $locationRepository;
        $this->locationNameRepository = $locationNameRepository;

        $this->middleware('permission:user-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        return view('users.index');
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
        // dd($request->all());
        try {
            $input = $request->all();
            $latestUserId = User::max('user_id');
            $input['user_id'] = $latestUserId ? $latestUserId + 1 : 10000;

            $tempPassword = $input['password'];
            $input['password'] = Hash::make($input['password']);

            $input['status'] = User::USER_STATUS['NEWUSER'];
            $input['created_by'] = Auth::id();
            $user = $this->userRepository->store($input, $request);
            if (!$user) {
                return errorMessage();
            }
            // Mail::to($user->email)->send(new UserCreated($user, $tempPassword));

            $location = $this->locationRepository->create($user, $input);
            if ($location) {
                $this->locationNameRepository->create($location, $input);
            }
            return successMessage('User Created successfully');
        } catch (Exception $e) {
            Log::error('Create User Exception: ' . $e->getMessage());
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
        $user =  $this->userRepository->getUserById($id);
        $userCount = User::where('created_by', '=', (int)$id)->count();
        $deviceCount = $this->deviceService->getTotalCount();
        return view('users.show', compact('user', 'userCount', 'deviceCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user =  $this->userRepository->getUserById($id);
        // dd($user);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }


    public function update(UpdateUsersRequest $request, $id)
    {
        $validatedData = $request->validated();
        // dd($validatedData);
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $validatedData['address_optional'] = $request->input('address_optional', null);

        try {
            $user = $this->userRepository->update($id, $validatedData);
            // dd($user);
            if ($user) {
                $userAddress = $this->locationRepository->update($id, $validatedData);
                $locations = $this->locationNameRepository->update($userAddress, $request->all());
            }

            return response()->json(['Message' => 'User updated successfully', 'code' => 'OK(200)']);
        } catch (\Exception $e) {
            Log::error("Error Updating User: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to update user'], 500);
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

    /**
     * Ajax Call For showing All the users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userAjaxDatatable(Request $request)
    {
        if ($request->ajax()) {
            $users = User::orderBy('id', 'DESC')
                ->with('creater')
                ->withTrashed();
            if (isSuperAdmin() == false) {
                $users->where('created_by', '=', Auth::user()->id);
            }
            $users = $users->get();
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
                ->addColumn('creater', function ($row) {
                    $creater = '';
                    if ($row->creater) {
                        $name = $row->creater->fname;
                        $creater = '<span class="badge bg-label-secondary me-1">' . $name . '</span>';
                    } else {
                        $creater = '<span class="badge bg-label-secondary me-1">--</span>';
                    }

                    return $creater;
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
                    if ($row) {
                        $actions .= '<div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu" style="">';
                        if (Gate::allows('user-history', $row)) {
                            $actions .= '<a class="dropdown-item" href="' . route('users.show', $row->id) . '" title="Edit"><i class="bx bx-history me-1"></i> View Details</a>';
                        }
                        if (Gate::allows('user-edit', $row)) {
                            $actions .= '<a class="dropdown-item" title="Edit" href="' . route('users.edit', $row->id) . '"><i class="bx bx-edit-alt"></i> Edit</a>';
                        }
                        if (Gate::allows('user-delete', $row)) {
                            if ($row->deleted_at == null) {
                                $actions .= '<a class="dropdown-item delete-user"  title="Delete"  href="javascript:void(0);"
                            id="' . $row->id . '"><i class="bx bx-trash-alt "></i>Delete</a></div>';
                            } else {
                                $actions .= '<a class="dropdown-item restore-user"  title="Delete"  href="javascript:void(0);"
                            id="' . $row->id . '"><i class="bx bx-undo"></i>Restore</a></div>';
                            }
                        }
                        $actions .= '</div>
                      </div>';
                    }

                    return $actions;
                })
                ->rawColumns(['status', 'role', 'creater', 'devices', 'actions'])
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
    public function changepassword($id): View
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
    public function changePasswordRequest(ChangePasswordRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if (Hash::check($request->oldpassword, $user->password)) {
                $input['status'] = User::USER_STATUS['ACTIVE'];
                $input['password'] = Hash::make($request->password);
                $user->update($input);
                return successMessage('Password Changed successfully');
            } else {
                return exceptionMessage('Old Password Does Not Match');
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
        // Validate input before proceeding
        $validated = $request->validate([
            'terms_and_conditions' => 'required|in:1', // Ensures only '1' is accepted
        ]);

        try {
            // Update user status
            $user = User::findOrFail(Auth::id());
            $user->update([
                'status' => User::USER_STATUS['FIRSTTIMEPASSWORDCHANGED'],
                'terms_and_conditions' => User::TERMS_AND_CONDITIONS
            ]);

            return response()->json(['code' => 200, 'message' => 'Terms accepted successfully!']);
        } catch (Exception $e) {
            return response()->json(['code' => 500, 'message' => 'Something went wrong!'], 500);
        }
    }



    /**
     * Ajax Call For showing All the users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userShowHierarchyAjaxDatatable(Request $request, $id)
    {
        if ($request->ajax()) {
            $users = User::orderBy('id', 'DESC')
                ->with('creater')
                ->withTrashed()->where('created_by', '=', (int)$id);
            $users = $users->get();
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
                ->addColumn('creater', function ($row) {
                    $creater = '';
                    if ($row->creater) {
                        $role = $row->creater->roles->pluck('name')->first();
                        $name = $row->creater->fname;
                        $creater = '<span class="badge bg-label-secondary me-1">' . $name . '</span>';
                    } else {
                        $creater = '<span class="badge bg-label-secondary me-1">--</span>';
                    }

                    return $creater;
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
                    if (Gate::allows('user-edit', $row)) {
                        $actions .= '<a href="' . route('users.edit', $row->id) . '" title="Edit" class="btn rounded-pill btn-icon btn-outline-primary edit-btn" href="javascript:void(0);"><i
                    class="bx bx-edit-alt"></i></a>';
                    }
                    return $actions;
                })
                ->rawColumns(['status', 'role', 'creater', 'devices', 'actions'])
                ->make(true);
        }
    }
}
