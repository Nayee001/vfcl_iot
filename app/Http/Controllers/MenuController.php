<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use Illuminate\Http\Request;
use App\Models\Menus;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    private $defaultRepository;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:menu-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:menu-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $menus = Menus::get();
        $permission = Permission::pluck('name', 'id');
        return view('menus.manage_menus', compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuRequest $request)
    {
        $data = $request->all();
        $data['status'] = Menus::STATUS['ISNOTPUBLISHED'];
        if($request->status){
            $data['status'] = Menus::STATUS['ISPUBLISHED'];
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
