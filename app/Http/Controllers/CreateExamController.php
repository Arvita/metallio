<?php

namespace App\Http\Controllers;

use App\Models\CreateExam;
use App\Models\DetailCreateExam;
use App\Models\Type;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CreateExamController extends Controller
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
        $m_create_exam = 'active';
        $create_exam = CreateExam::get();
        return view('create_exam.index', compact('create_exam', 'm_create_exam'));
    }

    public function create()
    {
        $create_exam = null;
        $type = Type::get();
        return view('create_exam.create', compact('create_exam','type'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'duration_tps' => 'required',
                'duration_tpa' => 'required',
                'type' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['stat' => false, 'msg' => $this->getMessage('insert.failed')]);
            }

            $create_exam = new CreateExam;
            $create_exam->name = strip_tags($request->input('name'));
            $create_exam->duration_tps = strip_tags($request->input('duration_tps'));
            $create_exam->duration_tpa = strip_tags($request->input('duration_tpa'));
            $create_exam->id_type = strip_tags($request->input('type'));
            $create_exam->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('insert.success')]);
        }
        return redirect('/home');
    }

    public function data()
    {
        $data = CreateExam::select('create_exams.name', 'create_exams.updated_at','create_exams.duration_tps','create_exams.duration_tpa', 'create_exams.id', 'create_exams.created_at','types.name as type')
        ->join('types','types.id','=','create_exams.id_type')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('action', function ($mn) {
                $detail = DetailCreateExam::where('id_create_exam', '=', $mn->id)->first();
                if ($detail==null) {
                    return '<div class="form-button-action"><a href="' . url('detail_create_exam/' . $mn->id) . '/detail" data-toggle="tooltip" title="" class=" btn btn-link btn-primary btn-lg" data-original-title="Add"><i class="fa fa-plus"></i></a> ' ;
                } else {
                    return '<div class="form-button-action"><a href="' . url('detail_create_exam/' . $mn->id) . '/detail" data-toggle="tooltip" title="" class=" btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></a> ' ;
                }                
            })
            ->addColumn('detail', function ($mn) {
                return
                    '<div class="form-button-action"><button type="button" data-url="' . url('create_exam/' . $mn->id) . '/edit" data-toggle="tooltip" title="" class="ajax_modal btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button type="button" data-url="' . url('create_exam/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail','action'])
            ->make(true);
    }

    public function edit($id)
    {
        $create_exam = CreateExam::find($id);
        $type = Type::get();
        return ($create_exam) ? view('create_exam.create', compact('create_exam', 'id','type')) : $this->showModalError();
    }


    public function update($id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'duration_tps' => 'required',
                'duration_tpa' => 'required',
                'type' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['stat' => false, 'msg' => $validator->errors()]);
            }

            $create_exam = CreateExam::find($id);
            $create_exam->name = strip_tags($request->input('name'));
            $create_exam->duration_tps = strip_tags($request->input('duration_tps'));
            $create_exam->duration_tpa = strip_tags($request->input('duration_tpa'));
            $create_exam->id_type = strip_tags($request->input('type'));
            $create_exam->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('update.success')]);
        }
        return redirect('/home');
    }

    public function confirm($id)
    {
        $create_exam  = CreateExam::find($id);
        return ($create_exam) ? view('create_exam.confirm', compact('create_exam', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            CreateExam::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }
}
