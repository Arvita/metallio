<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TypeController extends Controller
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
        $m_type = 'active';
        $type = Type::get();
        return view('type.index', compact('type', 'm_type'));
    }

    public function create()
    {
        $type = null;
        return view('type.create', compact('type'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
            ]);

            if ($validator->fails()) return response()->json(['stat' => false, 'msg' => $this->getMessage('insert.failed')]);

            $type = new Type;
            $type->name = strip_tags($request->input('name'));
            $type->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('insert.success')]);
        }
        return redirect('/home');
    }

    public function data()
    {
        $data = Type::select('types.name','types.id','types.updated_at')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<div class="form-button-action"><button type="button" data-url="' . url('type/' . $mn->id) . '/edit" data-toggle="tooltip" title="" class="ajax_modal btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button type="button" data-url="' . url('type/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function edit($id)
    {
        $type = Type::find($id);
        return ($type) ? view('type.create', compact('type', 'id')) : $this->showModalError();
    }


    public function update($id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
            ]);

            if ($validator->fails()) return response()->json(['stat' => false, 'msg' => $validator->errors()]);

            $type = Type::find($id);
            $type->name = strip_tags($request->input('name'));
            $type->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('update.success')]);
        }
        return redirect('/home');
    }

    public function confirm($id)
    {
        $type  = Type::find($id);
        return ($type) ? view('type.confirm', compact('type', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            Type::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }
}
