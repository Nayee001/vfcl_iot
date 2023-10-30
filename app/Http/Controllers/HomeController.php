<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\DeviceService;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $deviceService;
    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $userCount = User::where('created_by','=',Auth::user()->id)->count();
        $deviceCount = $this->deviceService->getCount();
        $deviceList = $this->deviceService->getDevices();
        return view('home',compact('userCount','deviceCount','deviceList'));
    }
}
