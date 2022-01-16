<?php

namespace App\Http\Controllers;

use App\Models\AnswerQuestionBanks;
use App\Models\BankQuestion;
use App\Models\CreateExam;
use App\Models\DetailBankQuestion;
use App\Models\DetailCreateExam;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DetailCreateExamController extends Controller
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

    public function index($id, Guard $auth)
    {
        $m_create_exam = 'active';
        $detail_create_exam = DetailCreateExam::get();
        $create_exam = CreateExam::find($id);
        $countQuestion = DetailCreateExam::where('id_create_exam', $id)->count();
        $sumScore =DetailCreateExam::select(
            \DB::raw('sum(score) as score')
        )
        ->first();
        return view('detail_create_exam.index', compact('detail_create_exam', 'create_exam', 'm_create_exam', 'countQuestion', 'sumScore'));
    }

    public function getAllQuestion($id)
    {
        $data = CreateExam::select('detail_create_exams.id as id', 'bank_questions.name as bank', 'detail_bank_questions.question', 'categories.name as category', 'detail_create_exams.score')
        ->where('create_exams.id', $id)
        ->join('detail_create_exams', 'detail_create_exams.id_create_exam', '=', 'create_exams.id')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'bank_questions.id_category', '=', 'categories.id')
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<button type="button" data-url="' . url('detail_create_exam/confirm_delete/deletequestion/'. $mn->id) . '" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->addColumn('score', function ($mn) {
                return $mn->score;
            })
            ->rawColumns(['detail','score'])
            ->make(true);
    }

    public function list_bank($id)
    {
        return view('detail_create_exam.list_bank', compact('id'));
    }

    public function list_question(Request $request, Guard $auth)
    {
        $id = $request->id;
        $question = BankQuestion::select('bank_questions.name', 'categories.name as category', 'bank_questions.id')
        ->join('categories', 'categories.id', '=', 'bank_questions.id_category')
        ->get();
        return Datatables::of($question)
            ->addColumn('action', function ($mn) use ($id) {
                return
                '<a href="'.url('detail_create_exam/create/'.$mn->id.'/'.$id).'" class="btn btn-xs btn-round btn-primary tooltips" data-placement="top" data-original-title="List Exam">Choose<i class="clip-clipboard"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create($id, $kode, Guard $auth)
    {
        $m_create_exam = 'active';
        $bank_question = BankQuestion::select('bank_questions.name', 'categories.name as category', 'bank_questions.id')
        ->join('categories', 'categories.id', '=', 'bank_questions.id_category')
        ->where('bank_questions.id', $id)->first();
        $countQuestion = DetailBankQuestion::where('id_bank_question', $id)
        ->leftjoin('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->leftjoin('create_exams', 'detail_create_exams.id_create_exam', '=', 'create_exams.id')
        ->whereNotIn('detail_bank_questions.id', DB::table('detail_create_exams')->pluck('id_detail_bank_question'))
        ->count();
        $exam = CreateExam::find($kode);
        return view('detail_create_exam.create', compact('bank_question', 'countQuestion', 'm_create_exam', 'kode', 'exam'));
    }

    public function generate(Request $request)
    {
        $data[] = DetailBankQuestion::select('detail_bank_questions.id as id', 'detail_bank_questions.id_bank_question', 'detail_bank_questions.question as question')
            ->where('id_bank_question', $request->_id_bank)
            ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
            ->whereNotIn('detail_bank_questions.id', DB::table('detail_create_exams')->pluck('id_detail_bank_question'))
            ->limit($request->total)
            ->inRandomOrder()
            ->get();
        
        // dd($data);
        return $data;
    }
    public function getAnswer($id)
    {
        $data = AnswerQuestionBanks::select('answer_question_banks.*')
        ->join('detail_bank_questions', 'detail_bank_questions.id', '=', 'answer_question_banks.id_question')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->where('detail_create_exams.id', $id)->get();
        return $data;
    }

    public function submit(Request $request)
    {
        $data = $request->data_question;

        // foreach ($data as $key => $value) {
        //     foreach ($value as $values) {
        //         $values['score'] = 0;
        //         // $i++;
        //     }
        // }
        $i =0;
        
        // dd($data);
        foreach ($data as $key => $value) {
            if (!(array_key_exists('score',$value))) {
                $data[$i] = array_merge($value, array("score"=>0));
            }
            $i++;
        }       
        dd($data);
        foreach ($data as $key => $value) {
            $field = [
        'id_detail_bank_question' => $value['id'],
        'score' => $value['score'],
        'id_create_exam' => $request->id_exam,
    ];

            DetailCreateExam::create($field);
        }
        if (!$data) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('insert.failed')]);
        }
        return response()->json(['stat' => true, 'msg' => $this->getMessage('insert.success')]);
    }

    public function delete_confirm($type, $id)
    {
        return view('detail_create_exam.confirm', compact('type', 'id'));
    }

    public function getdetailquestionbank($id)
    {
        $data = DetailCreateExam::select('detail_create_exams.id', 'detail_bank_questions.question')->where('detail_create_exams.id', $id)->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')->first();
        return $data;
    }

    public function delete(Request $request)
    {
        if ($request->type == 'delete') {
            try {
                DetailCreateExam::destroy('id', $request->id);
                return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
            }
        }
        if ($request->type == 'deleteall') {
            try {
                DetailCreateExam::where('id_create_exam', $request->id)->delete();
                return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
            }
        }
    }
    public function confirm($type, $id)
    {
        return view('detail_create_exam.confirm', compact('type', 'id'));
    }
}
