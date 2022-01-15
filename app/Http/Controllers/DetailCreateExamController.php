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
        return view('detail_create_exam.index', compact('detail_create_exam', 'create_exam', 'm_create_exam', 'countQuestion'));
    }

    public function getAllQuestion($id)
    {
        $data = CreateExam::select('detail_create_exams.id as id', 'bank_questions.name as bank', 'detail_bank_questions.question', 'categories.name as category')
        ->where('create_exams.id', $id)
        ->join('detail_create_exams', 'detail_create_exams.id_create_exam', '=', 'create_exams.id')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'bank_questions.id_category', '=', 'categories.id')
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<button type="button" data-url="' . url('detail_bank_question/confirm_delete/deletequestion/'. $mn->id) . '" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
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
        $countQuestion = DetailBankQuestion::where('id_bank_question', $id)->count();
        $exam = CreateExam::find($kode);
        return view('detail_create_exam.create', compact('bank_question', 'countQuestion', 'm_create_exam', 'kode', 'exam'));
    }

    public function generate(Request $request)
    {
        $data[] = DetailBankQuestion::select('detail_bank_questions.id as id', 'detail_bank_questions.id_bank_question', 'detail_bank_questions.question as question')
            ->where('id_bank_question', $request->_id_bank)
            ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
            ->limit($request->total)
            ->inRandomOrder()
            ->get();
        return $data;
    }
    public function getAnswer($id)
    {
        return AnswerQuestionBanks::where('id_question', $id)->get();
    }
}
