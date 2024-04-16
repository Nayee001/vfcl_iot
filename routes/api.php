<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController as AuthApi;
use Spatie\Permission\Contracts\Role;
use App\Http\Controllers\DeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Akshay Nayee:)
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthApi::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApi::class, 'logout']);
    Route::post('/verifyDevice', [DeviceController::class, 'verifyDeviceApi'])->name('verifyDeviceApi');
});
