<?php

namespace App\Http\Controllers;

use App\Models\BankQuestion;
use App\Models\DetailBankQuestion;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
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
        return view('detail_bank_question.index', compact('detail_bank_question','bank_question', 'm_bank_question','countQuestion'));
    }

    public function getAllQuestion($id)
	{
		$data = DetailBankQuestion::select('detail_bank_questions.id as id', 'detail_bank_questions.id_bank_question','detail_bank_questions.question')
		->where('detail_bank_questions.id_bank_question', $id)
		->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
		->get();
			return Datatables::of($data)
			->addColumn('detail', function ($mn) {
				return
				'<a href="'.url('detail_bank_question/edit_question/'.$mn->id).'" class="btn btn-xs btn-primary tooltips" data-placement="top"><i class="fa fa-edit"></i></a> '.'<button type="button" data-url="'.url('detail_bank_question/confirm_delete/deletequestion/'. $mn->id).'" class="ajax_modal_confirm btn btn-xs btn-danger tooltips"><i class="fa fa-trash-o"></i></button> ';
			})->make(true);
	}    

    public function create($id)
    {
		$m_bank_question = 'active';
        return view('detail_bank_question.question', compact('id','m_bank_question'));
		
    }
}
