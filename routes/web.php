<?php

use App\Http\Controllers\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ComplaintController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::delete('attachments/{id}', [File::class, 'destroy']);
    Route::delete('party/{id}', [ComplaintController::class, 'deleteParty']);
    Route::delete('violation/{id}', [ComplaintController::class, 'deleteViolation']);
    Route::delete('deleteComplaint/{id}', [ComplaintController::class, 'deleteComplaint']);
});
