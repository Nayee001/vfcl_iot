<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceTypeStoreRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\DeviceType;

class DeviceTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:device-type-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:device-type-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:device-type-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:device-type-delete', ['only' => ['destroy']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        return view('device-type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('device-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeviceTypeStoreRequest $request)
    {
        try {
            $input = $request->all();
            $data = DeviceType::create($input);
            if ($data) {
                return successMessage('Device Type Created !!');
            }
        } catch (Exception $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
