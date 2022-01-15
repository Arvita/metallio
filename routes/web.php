<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BankQuestionController;
use App\Http\Controllers\CreateExamController;
use App\Http\Controllers\DetailBankQuestionController;
use App\Http\Controllers\DetailCreateExamController;
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
    Route::get('/detail_bank_question/edit/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'edit']);
    Route::get('/detail_bank_question/confirm_delete/{type}/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'delete_confirm']);
    Route::post('/detail_bank_question/question_submit/', [App\Http\Controllers\DetailBankQuestionController::class, 'store']);
    Route::post('/detail_bank_question/question_update/', [App\Http\Controllers\DetailBankQuestionController::class, 'update']);
    Route::get('/detail_bank_question/getanswer/{id}', [App\Http\Controllers\DetailBankQuestionController::class, 'getAnswer']);
    Route::post('/detail_bank_question/deleteanswer', [App\Http\Controllers\DetailBankQuestionController::class, 'deleteanswer']);
    Route::get('/detail_bank_question/getdetailquestionbank/{id}/', [App\Http\Controllers\DetailBankQuestionController::class, 'getdetailquestionbank']);
    Route::post('/detail_bank_question/delete/', [App\Http\Controllers\DetailBankQuestionController::class, 'delete']);

    Route::post('create_exam/data', [App\Http\Controllers\CreateExamController::class, 'data']);
	Route::get('create_exam/{kode}/conf', [App\Http\Controllers\CreateExamController::class, 'confirm']);
    Route::resource('create_exam', CreateExamController::class);

    Route::get('detail_create_exam/{kode}/detail', [App\Http\Controllers\DetailCreateExamController::class, 'index']); 
    Route::get('detail_create_exam/getallquestion/{id}', [App\Http\Controllers\DetailCreateExamController::class, 'getAllQuestion']);   
    Route::get('/detail_create_exam/list_bank/{id}', [App\Http\Controllers\DetailCreateExamController::class, 'list_bank']);   
    Route::post('/detail_create_exam/list_question', [App\Http\Controllers\DetailCreateExamController::class, 'list_question']);
    Route::get('/detail_create_exam/create/{id}/{kode}', [App\Http\Controllers\DetailCreateExamController::class, 'create']);
    Route::post('/detail_create_exam/generate/',  [App\Http\Controllers\DetailCreateExamController::class, 'generate']);
    Route::get('/detail_create_exam/getanswer/{id}', [App\Http\Controllers\DetailCreateExamController::class, 'getAnswer']);

    Route::post('/file/upload', [App\Http\Controllers\FileController::class, 'upload'])->name('file.upload');
});
