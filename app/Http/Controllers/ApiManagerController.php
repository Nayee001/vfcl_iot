<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ApiManagerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:api-connections', ['only' => ['index', 'store']]);
        $this->middleware('permission:api-connections-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:api-connections-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:api-connections-delete', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('user_settings.api_connections');
    }
}
