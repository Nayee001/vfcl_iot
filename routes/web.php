<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiManagerController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\LocationManagerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;


// use App\Mail\UserCreated;
// use Illuminate\Support\Facades\Mail;
// use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Example route to send a test email
// Route::get('/send-test-mail', function () {
//     // Retrieve or create a user instance to pass to the mailable
//     // For testing, you can either create a new user or use a static one
//     $user = User::first(); // Assuming there's at least one user in your database

//     // Check if a user exists
//     if ($user) {
//         // Send the email
//         Mail::to($user->email)->send(new UserCreated($user));
//         return response()->json(['message' => 'Test email sent successfully to ' . $user->email]);
//     } else {
//         return response()->json(['error' => 'No user found in the database.'], 404);
//     }
// });

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::any('/account-settings/{id}', [UserController::class, 'accountSettings'])->name('account-settings');
    Route::post('/user-deactivate/{id}', [UserController::class, 'deactivate'])->name('user-deactivate');
    Route::get('/users-restore/{id}', [UserController::class, 'restore'])->name('user-restore');
    Route::get('/change-password/{id}', [UserController::class, 'changepassword'])->name('change-password');
    Route::post('/change-password/{id}', [UserController::class, 'changePasswordRequest'])->name('users.password-change');
    Route::post('/terms-and-conditions', [UserController::class, 'termsandconditions'])->name('users.terms-and-conditions');
    Route::get('/users-ajax-datatable', [UserController::class, 'userAjaxDatatable'])->name('users-ajax-datatables');
    Route::get('/users-show-hierarchy-ajax-datatables/{id}', [UserController::class, 'userShowHierarchyAjaxDatatable'])->name('users-show-hierarchy-ajax-datatables');
    Route::get('/api-connections', [ApiManagerController::class, 'index'])->name('api-connections');
    Route::get('/roles-ajax-datatable', [RoleController::class, 'roleAjaxDatatable'])->name('roles-ajax-datatables');

    //Dashboard Code
    Route::get('/get-dashboard-devices-ajax-datatable', [HomeController::class, 'getDashboardDevicesAjaxDatatable'])->name('get-dashboard-devices-ajax-datatable');

    // End Dashboard Code

    // Device Type Datatable
    Route::get('/device-type-ajax-datatable', [DeviceTypeController::class, 'deviceTypeAjaxDatatable'])->name('device-type-ajax-datatables');
    // Devices Datatable
    Route::get('/devices-ajax-datatable', [DeviceController::class, 'deviceAjaxDatatable'])->name('devices-ajax-datatable');
    Route::get('/device/{id}/assign', [DeviceController::class, 'showAssignDeviceForm'])->name('device.assign');
    Route::get('/device/dashboard', [DeviceController::class, 'dashboard'])->name('devices.dashboard');
    // Customer Device Data
    Route::get('/customer/devices/data', [DeviceController::class, 'fetchDevices'])->name('devices.data');

    Route::post('/assign-device', [DeviceController::class, 'assignDevice'])->name('assign.device');

    Route::get('/device/{id}/api-key', [DeviceController::class, 'getApiKey'])->name('device.getApiKey');
    Route::delete('/unassign-device/{id}', [DeviceController::class, 'unAssign'])->name('device.unAssign');
    Route::delete('/location-name/{id}/delete', [LocationManagerController::class, 'deleteLocationName'])->name('location-name-delete');
    Route::get('/get-customer-locations/{customer_id}', [LocationManagerController::class, 'getCustomerLocations'])->name('get-customer-locations');
    Route::get('/device-data/count', [HomeController::class, 'getDeviceDataCounts']);
    Route::get('/device-data/messages', [HomeController::class, 'getDeviceAllMessages']);
    Route::get('/device-data/{id}', [HomeController::class, 'getdeviceMessage']);

    Route::get('/get-device-line-chart-data/{id}', [HomeController::class, 'getDeviceLineChartData']);



    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('devices', DeviceController::class);
    Route::resource('devices-type', DeviceTypeController::class);
});
