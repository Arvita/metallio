<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
    return redirect('login');
});

Auth::routes();

Route::group(['middleware' => ['web','auth']], function () {

    //admin
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('manage_user/data', [App\Http\Controllers\UserController::class, 'data']);
	Route::get('manage_user/{kode}/conf', [App\Http\Controllers\UserController::class, 'confirm']);
    Route::post('manage_user/import', function () {
        Excel::import(new UsersImport, request()->file('file'));
        return back();
    });
    Route::resource('manage_user', UserController::class);
    Route::post('category/data', [App\Http\Controllers\CategoryController::class, 'data']);
	Route::get('category/{kode}/conf', [App\Http\Controllers\CategoryController::class, 'confirm']);
    Route::resource('category', CategoryController::class);
});
