<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ApiManagerController extends Controller
{
    public function index(): View
    {
        return view('user_settings.api_connections');
    }
}
