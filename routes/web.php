<?php

use App\Http\Controllers\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\InvestigatedCaseController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::delete('attachments/{id}', [File::class, 'destroy']);
    Route::delete('party/{id}', [ComplaintController::class, 'deleteParty']);
    Route::delete('violation/{id}', [ComplaintController::class, 'deleteViolation']);
    Route::delete('deleteComplaint/{id}', [ComplaintController::class, 'deleteComplaint']);
    Route::get('search', [ComplaintController::class, 'autosearch']);
    Route::get('notifications', [NotificationController::class, 'getNewMessages']);
    Route::get('complaint_id', [ComplaintController::class, 'getComplaint_id']);
    Route::put('read/{id}', [NotificationController::class, 'updateMarkMsg']);
    Route::post('caseSaved', [InvestigatedCaseController::class, 'save']);
    Route::put('readAdmin/{id}', [InvestigatedCaseController::class, 'updateNotif']);
    Route::get('openNotification', [NotificationController::class, 'openNotif']);

    Route::get('exportpdf', [ComplaintController::class, 'exportpdf'])->name('exportpdf');
});
