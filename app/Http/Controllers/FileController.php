<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    private $guard = [0];

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {

            if (!in_array(\Auth::user()->role, $this->guard)) {
                return redirect('/login');
            }
            return $next($request);
        });
    }

    public function upload(Request $request)

	{

		if($request->hasFile('upload')) {

			$originName = $request->file('upload')->getClientOriginalName();

			$fileName = pathinfo($originName, PATHINFO_FILENAME);

			$extension = $request->file('upload')->getClientOriginalExtension();

			$fileName = $fileName.'_'.time().'.'.$extension;



			$request->file('upload')->move(public_path().'upload/',$fileName);



			$CKEditorFuncNum = $request->input('CKEditorFuncNum');

			$url = asset('upload/'.$fileName);

			$msg = 'Image uploaded successfully';

			$response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";



			@header('Content-type: text/html; charset=utf-8');

			echo $response;

		}
}
