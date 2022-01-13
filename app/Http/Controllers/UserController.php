<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Category;
use App\Models\User;
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

    public function index(Guard $auth)
    {
        $m_user = 'active';
        $manage_user = User::get();
        $user = $auth->user();
        return view('manage_user.index', compact('manage_user', 'user', 'm_user'));
    }

    public function create()
    {
        $category = Category::get();
        $manage_user = null;
        return view('manage_user.create', compact('manage_user','category'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'email' => 'required|max:255|email|unique:users',
                'password' => 'min:8|required_with:password_again|same:password_again',
                'password_again' => 'min:8',
                'role' => 'required',
                'category' => 'required'
            ]);

            if ($validator->fails()) return response()->json(['stat' => false, 'msg' => $this->getMessage('insert.failed')]);

            $user = new User;
            $user->name = strip_tags($request->input('name'));
            $user->email = strip_tags($request->input('email'));
            $user->role = strip_tags($request->input('role'));
            $user->id_category = strip_tags($request->input('category'));
            $user->password = bcrypt($request->input('password'));
            $user->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('insert.success')]);
        }
        return redirect('/dashboard');
    }

    public function data()
    {
        $data = User::select('users.name','users.email','users.created_at','users.updated_at','categories.name as category','users.role','users.id')
        ->join('categories','categories.id','=','users.id_category')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<div class="form-button-action"><button type="button" data-url="' . url('manage_user/' . $mn->id) . '/edit" data-toggle="tooltip" title="" class="ajax_modal btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button type="button" data-url="' . url('manage_user/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function edit($id)
    {
        $category = Category::get();
        $manage_user = User::find($id);
        return ($manage_user) ? view('manage_user.create', compact('manage_user', 'id','category')) : $this->showModalError();
    }


    public function update($id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'email' => 'required|max:255|email|',
                'role' => 'required',
                'category' => 'required',
            ]);

            if ($validator->fails()) return response()->json(['stat' => false, 'msg' => $validator->errors()]);

            $user = User::find($id);
            if (!$user) return $this->showModalError();
            if ($request->input('passwordupdate') != NULL) {
                $user->password = bcrypt($request->input('passwordupdate'));
            }
            $user->name = strip_tags($request->input('name'));
            $user->email = strip_tags($request->input('email'));
            $user->role = strip_tags($request->input('role'));
            $user->id_category = strip_tags($request->input('category'));
            $user->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('update.success')]);
        }
        return redirect('/dashboard');
    }

    public function confirm($id)
    {
        $manage_user  = User::find($id);
        return ($manage_user) ? view('manage_user.confirm', compact('manage_user', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            User::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }

    public function fileImport(Request $request) 
    {
        Excel::import(new UsersImport, $request->file('file')->store('temp'));
        return back();
    }
}
