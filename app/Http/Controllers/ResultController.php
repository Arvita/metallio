<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;
use PDF;
use Yajra\DataTables\DataTables;

class ResultController extends Controller
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
        $m_result = 'active';
        return view('result.index', compact('m_result'));
    }

    public function data_saintek()
    {
        $data = Exam::select('users.name', 'exams.id_user')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Saintek')
        ->groupby('users.name', 'exams.id_user')
        ->get();
        $val = collect();
        foreach ($data as $key => $value) {
            $datas = collect();
            $datas = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('detail_bank_questions', 'detail_bank_questions.id', '=', 'detail_exams.id_question')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Saintek')
        ->where('users.id', $value->id_user)
        ->get();
            $val->push($datas);
        }
        $vals = collect();
        $i = 0;
        foreach ($val as $key => $value) {            
            $sum = 0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum+=($values->score / $values->weight);
                }
            }
            $vals->push(['score'=> $sum,'name'=> $value[$i]->name]);
            $i++;
        }
        return Datatables::of($vals->sortByDesc('score'))
            ->make(true);
    }

    public function data_soshum()
    {
        $data = Exam::select('users.name', 'exams.id_user')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->where('types.name', 'TKA Soshum')
        ->join('types','types.id','=','users.id_type')
        ->groupby('users.name', 'exams.id_user')
        ->get();
        $val = collect();
        foreach ($data as $key => $value) {
            $datas = collect();
            $datas = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('detail_bank_questions', 'detail_bank_questions.id', '=', 'detail_exams.id_question')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Soshum')
        ->where('users.id', $value->id_user)
        ->get();
            $val->push($datas);
        }
        $vals = collect();
        $i = 0;
        foreach ($val as $key => $value) {            
            $sum = 0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum+=($values->score / $values->weight);
                }
            }
            $vals->push(['score'=> $sum,'name'=> $value[$i]->name]);
            $i++;
        }
        return Datatables::of($vals->sortByDesc('score'))
            ->make(true);
    }

    public function saintek_pdf()
    {
    	$data = Exam::select('users.name', 'exams.id_user')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Saintek')
        ->groupby('users.name', 'exams.id_user')
        ->get();
        $val = collect();
        foreach ($data as $key => $value) {
            $datas = collect();
            $datas = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('detail_bank_questions', 'detail_bank_questions.id', '=', 'detail_exams.id_question')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Saintek')
        ->where('users.id', $value->id_user)
        ->get();
            $val->push($datas);
        }
        $vals = array();
        $i = 0;
        foreach ($val as $key => $value) {            
            $sum = 0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum+=($values->score / $values->weight);
                }
            }
            array_push($vals,['score'=> $sum,'name'=> $value[$i]->name]);
            $i++;
        }

 
    	$pdf = PDF::loadview('result.saintek',['vals'=>$vals]);
    	return $pdf->download('saintek.pdf');
    }
    public function soshum_pdf()
    {
    	$data = Exam::select('users.name', 'exams.id_user')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Soshum')
        ->groupby('users.name', 'exams.id_user')
        ->get();
        $val = collect();
        foreach ($data as $key => $value) {
            $datas = collect();
            $datas = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('detail_bank_questions', 'detail_bank_questions.id', '=', 'detail_exams.id_question')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Soshum')
        ->where('users.id', $value->id_user)
        ->get();
            $val->push($datas);
        }
        $vals = array();
        $i = 0;
        foreach ($val as $key => $value) {            
            $sum = 0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum+=($values->score / $values->weight);
                }
            }
            array_push($vals,['score'=> $sum,'name'=> $value[$i]->name]);
            $i++;
        }

 
    	$pdf = PDF::loadview('result.soshum',['vals'=>$vals]);
    	return $pdf->download('soshum.pdf');
    }
}
