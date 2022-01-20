<?php

namespace App\Http\Controllers;

use App\Models\CreateExam;
use App\Models\Schedule;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
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
        $m_schedule = 'active';
        $schedule = Schedule::get();
        return view('schedule.index', compact('schedule', 'm_schedule'));
    }

    public function create(){
        $type = DB::table('types')->get();
        return view('schedule.create_schedule', compact('type'));
    }

    public function get_exam($id){
        return CreateExam::where('id_type', $id)->get();
    }

    public function store(request $request){
        $data_question = [
            'id' => null,
            'id_exam' => $request->exam,
            'status' => '0'
        ];

        Schedule::create($data_question);
        return redirect('/schedule');
    }

    public function data()
    {
        $data = Schedule::select('schedules.open','schedules.close','schedules.id','schedules.updated_at','schedules.created_at','types.name as type','create_exams.name')
        ->join('create_exams','create_exams.id','=','schedules.id_exam')
        ->join('types','types.id','=','create_exams.id_type')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<div class="form-button-action"><button schedule="button" data-url="' . url('schedule/schedule/' . $mn->id) . '" data-toggle="tooltip" title="" class="ajax_modal btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button schedule="button" data-url="' . url('schedule/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function schedule($id)
	{
		$data = Schedule::select('schedules.*', 'create_exams.id as id_exam', 'create_exams.id_type','create_exams.name','create_exams.duration', 'types.name as type')
        ->join('create_exams','create_exams.id','=','schedules.id_exam')
        ->join('types','types.id','=','create_exams.id_type')
        ->where('schedules.id', $id)
        ->first();
		return view('schedule.edit_schedule', compact('data'));
    }


    public function update(Request $request)
	{
        $data_schedule = [
            'id' => $request->id,
			'id_exam' => $request->id_exam,
			'status' => $request->status,
			'open' => $request->open,
			'close' => $request->close
        ];   
		return Schedule::where('id', $request->id)->update($data_schedule);
    }

    public function confirm($id)
    {
        $schedule = Schedule::select('schedules.*', 'create_exams.id as id_exam', 'create_exams.id_type','create_exams.name','create_exams.duration', 'types.name as type')
        ->join('create_exams','create_exams.id','=','schedules.id_exam')
        ->join('types','types.id','=','create_exams.id_type')
        ->where('schedules.id', $id)
        ->first();
        return ($schedule) ? view('schedule.confirm', compact('schedule', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            Schedule::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }
}
