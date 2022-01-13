<?php

namespace App\Http\Controllers;

use App\Models\BankQuestion;
use App\Models\Category;
use App\Models\DetailBankQuestion;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BankQuestionController extends Controller
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
        $m_bank_question = 'active';
        $bank_question = BankQuestion::get();
        return view('bank_question.index', compact('bank_question', 'm_bank_question'));
    }

    public function create()
    {
        $category = Category::get();
        $bank_question = null;
        return view('bank_question.create', compact('bank_question', 'category'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'category' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['stat' => false, 'msg' => $this->getMessage('insert.failed')]);
            }

            $bank_question = new BankQuestion;
            $bank_question->name = strip_tags($request->input('name'));
            $bank_question->id_category = strip_tags($request->input('category'));
            $bank_question->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('insert.success')]);
        }
        return redirect('/dashboard');
    }

    public function data()
    {
        $data = BankQuestion::select('bank_questions.name', 'categories.name as category', 'bank_questions.updated_at', 'bank_questions.id', 'bank_questions.created_at')
        ->join('categories', 'categories.id', '=', 'bank_questions.id_category')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('action', function ($mn) {
                $detail = DetailBankQuestion::where('id_bank_question', '=', $mn->id)->first();
                if ($detail==null) {
                    return '<div class="form-button-action"><a href="' . url('detail_bank_question/' . $mn->id) . '/detail" data-toggle="tooltip" title="" class=" btn btn-link btn-primary btn-lg" data-original-title="Add"><i class="fa fa-plus"></i></a> ' ;
                } else {
                    return '<div class="form-button-action"><a href="' . url('detail_bank_question/' . $mn->id) . '/detail" data-toggle="tooltip" title="" class=" btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></a> ' ;
                }                
            })
            ->addColumn('detail', function ($mn) {
                return
                    '<div class="form-button-action"><button type="button" data-url="' . url('bank_question/' . $mn->id) . '/edit" data-toggle="tooltip" title="" class="ajax_modal btn btn-link btn-primary btn-lg" data-original-title="Edit"><i class="fa fa-edit"></i></button> ' .
                    '<button type="button" data-url="' . url('bank_question/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail','action'])
            ->make(true);
    }

    public function edit($id)
    {
        $category = Category::get();
        $bank_question = BankQuestion::find($id);
        return ($bank_question) ? view('bank_question.create', compact('bank_question', 'id', 'category')) : $this->showModalError();
    }


    public function update($id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'category' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['stat' => false, 'msg' => $validator->errors()]);
            }

            $bank_question = BankQuestion::find($id);
            $bank_question->name = strip_tags($request->input('name'));
            $bank_question->id_category = strip_tags($request->input('category'));
            $bank_question->save();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('update.success')]);
        }
        return redirect('/dashboard');
    }

    public function confirm($id)
    {
        $bank_question  = BankQuestion::find($id);
        return ($bank_question) ? view('bank_question.confirm', compact('bank_question', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            BankQuestion::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }
}
