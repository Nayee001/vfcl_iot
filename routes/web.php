<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiManagerController;
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

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/account-settings', [HomeController::class, 'accountSettings'])->name('account-settings');
    Route::get('/api-connections', [ApiManagerController::class, 'index'])->name('api-connections');
    Route::get('/roles-ajax-datatable', [RoleController::class, 'roleAjaxDatatable'])->name('roles-ajax-datatables');
    Route::get('/users-ajax-datatable', [UserController::class, 'userAjaxDatatable'])->name('users-ajax-datatables');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
