<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:menu-list', ['only' => ['index', 'store']]);
        // $this->middleware('permission:menu-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:menu-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('notification.index');
    }
}
