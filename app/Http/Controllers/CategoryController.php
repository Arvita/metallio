<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
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
        $m_category = 'active';
        $category = Category::get();
        return view('category.index', compact('category', 'm_category'));
    }

    public function create()
    {
        $category = null;
        return view('category.create', compact('category'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
            ]);

            if ($validator->fails()) return response()->json(['stat' => false, 'msg' => $this->getMessage('insert.failed')]);

            $category = new Category;
            $category->name = strip_tags($request->input('name'));
            $category->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('insert.success')]);
        }
        return redirect('/home');
    }

    public function data()
    {
        $data = Category::select('categories.name','categories.id','categories.updated_at')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<div class="form-button-action"><button type="button" data-url="' . url('category/' . $mn->id) . '/edit" data-toggle="tooltip" title="" class="ajax_modal btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button type="button" data-url="' . url('category/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return ($category) ? view('category.create', compact('category', 'id')) : $this->showModalError();
    }


    public function update($id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
            ]);

            if ($validator->fails()) return response()->json(['stat' => false, 'msg' => $validator->errors()]);

            $category = Category::find($id);
            $category->name = strip_tags($request->input('name'));
            $category->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('update.success')]);
        }
        return redirect('/home');
    }

    public function confirm($id)
    {
        $category  = Category::find($id);
        return ($category) ? view('category.confirm', compact('category', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            Category::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }
}
