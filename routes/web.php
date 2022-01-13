<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BankQuestionController;
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
    
    Route::post('bank_question/data', [App\Http\Controllers\BankQuestionController::class, 'data']);
	Route::get('bank_question/{kode}/conf', [App\Http\Controllers\BankQuestionController::class, 'confirm']);
    Route::resource('bank_question', BankQuestionController::class);

    Route::get('detail_bank_question/{kode}/detail', [App\Http\Controllers\DetailBankQuestionController::class, 'index']);    
    Route::get('detail_bank_question/getallquestion/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'getAllQuestion']);
    Route::get('/detail_bank_question/create/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'create']);
    Route::get('/detail_bank_question/edit_question/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'edit_question']);
    Route::get('/detail_bank_question/confirm_delete/{type}/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'delete_confirm']);
    Route::resource('detail_bank_question', DetailBankQuestionController::class);

    Route::post('/file/upload', [App\Http\Controllers\FileController::class, 'upload'])->name('file.upload');
});
