<?php

namespace App\Http\Controllers;

use App\Models\AnswerQuestionBanks;
use App\Models\BankQuestion;
use App\Models\DetailBankQuestion;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DetailBankQuestionController extends Controller
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
        $m_bank_question = 'active';
        $detail_bank_question = DetailBankQuestion::get();
        $bank_question = BankQuestion::select('bank_questions.*', 'categories.name as category')
        ->where('bank_questions.id', $id)
        ->join('categories', 'bank_questions.id_category', '=', 'categories.id')
        ->first();
        $countQuestion = DetailBankQuestion::where('id_bank_question', $id)->count();
        return view('detail_bank_question.index', compact('detail_bank_question', 'bank_question', 'm_bank_question', 'countQuestion'));
    }

    public function getAllQuestion($id)
    {
        $data = DetailBankQuestion::select('detail_bank_questions.id as id', 'detail_bank_questions.id_bank_question', 'detail_bank_questions.question as question')
        ->where('detail_bank_questions.id_bank_question', $id)
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                '<div class="form-button-action"><a  type="button" href="' . url('detail_bank_question/edit/'.$mn->id) . '" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button type="button" data-url="' . url('detail_bank_question/confirm_delete/deletequestion/'. $mn->id) . '" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function create($id)
    {
        $m_bank_question = 'active';
        return view('detail_bank_question.question', compact('id', 'm_bank_question'));
    }

    public function store(request $request)
    {
        $arrAnswer = explode(',', $request->_answer);
        $data_question = [
                'id_bank_question' => $request->id_bank_question,
                'question' => $request->question,
            ];

        $input_question = DetailBankQuestion::create($data_question);

        foreach ($arrAnswer as $key => $value) {
            $answer = 'answer'.$value;
            if ($value == $request->correct) {
                $data_answer = [
                        'id_question' => $input_question->id,
                        'answer' => $request->$answer,
                        'status' => true
                    ];
                AnswerQuestionBanks::create($data_answer);
            } else {
                $data_answer = [
                        'id_question' => $input_question->id,
                        'answer' => $request->$answer,
                        'status' => false
                    ];

                AnswerQuestionBanks::create($data_answer);
            }
        }

        return redirect('detail_bank_question/'.($request->id_bank_question).'/detail');
    }
    public function getAnswer($id)
    {
        return AnswerQuestionBanks::where('id_question', $id)->get();
    }

    public function edit($id)
    {
        $m_bank_question = 'active';
        $detail_bank_question = DetailBankQuestion::find($id);
        return view('detail_bank_question.edit_question', compact('detail_bank_question', 'id', 'm_bank_question'));
    }

    public function update(request $request)
    {
        $arrAnswer = explode(',', $request->_answer);
        DetailBankQuestion::where('id', $request->id)->update([
                'id_bank_question' => $request->id_bank_question,
                'question' => $request->question,
                
            ]);
            
        foreach ($arrAnswer as $key => $value) {
            $answer = 'answer'.$value;
            if (AnswerQuestionBanks::where('id', $value)->first()) {
                if ($value == $request->correct) {
                    $data_answer = [
                        'id_question' => $request->id,
                        'answer' => $request->$answer,
                        'status' => true
                    ];
                    AnswerQuestionBanks::where('id', $value)->update($data_answer);
                } else {
                    $data_answer = [
                        'id_question' => $request->id,
                        'answer' => $request->$answer,
                        'status' => false
                    ];
                    AnswerQuestionBanks::where('id', $value)->update($data_answer);
                }
            } else {
                if ($value == $request->correct) {
                    $data_answer = [
                                    'id_question' => $request->id,
                                    'answer' => $request->$answer,
                                    'status' => true
                                ];
                    AnswerQuestionBanks::create($data_answer);
                } else {
                    $data_answer = [
                                    'id_question' => $request->id,
                                    'answer' => $request->$answer,
                                    'status' => false
                                ];

                    AnswerQuestionBanks::create($data_answer);
                }
            }
        }
        return redirect('detail_bank_question/'.($request->id_bank_question).'/detail');
    }
    public function deleteanswer(request $request)
    {
        try {
            AnswerQuestionBanks::destroy('id', $request->id);
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }

    public function delete_confirm($type, $id)
    {
        return view('detail_bank_question.confirm', compact('type', 'id'));
    }

    public function getdetailquestionbank($id)
    {
        return DetailBankQuestion::where('id', $id)->first();
    }

    public function delete(Request $request)
    {
        if ($request->type == 'delete') {
            try {
                DB::table('answer_question_banks')->where('id_question', $request->id)->delete();
                DetailBankQuestion::destroy('id', $request->id);
                return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
            }
        }
        if ($request->type == 'deleteall') {
            try {
                $detail=DetailBankQuestion::where('id_bank_question', $request->id)->get();
                foreach ($detail as $key => $value) {
                    DB::table('answer_question_banks')->where('id_question', $value['id'])->delete();
                }
                DetailBankQuestion::where('id_bank_question', $request->id)->delete();
                return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
            }
        }
    }
    public function confirm($type, $id)
    {
        return view('detail_bank_question.confirm', compact('type', 'id'));
    }
}
