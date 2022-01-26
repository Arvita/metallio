<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $guard = [0,1];

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {

            if (!in_array(\Auth::user()->role, $this->guard)) {
                return redirect('/login');
            }
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $m_dashboard = 'active';
        $saintek = User::where('types.name', 'TKA Saintek')->where('users.role', '1')->join('types','types.id','=','users.id_type')->count();
        $soshum = User::where('types.name', 'TKA Soshum')->where('users.role', '1')->join('types','types.id','=','users.id_type')->count();
        $complete = Exam::where('complete','=','1')->count();
        $not_complete = Exam::where('complete','=','0')->count();

        $datasaintek = Exam::select('users.name', 'exams.id_user')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Saintek')
        ->groupby('users.name', 'exams.id_user')
        ->get();
        $valsaintek = collect();
        foreach ($datasaintek as $key => $value) {
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
            $valsaintek->push($datas);
        }
        $scoresaintek = array();
        $namesaintek = array();
        $i = 0;
        foreach ($valsaintek as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $scoresaintek[$i] = $sum;
            $namesaintek[$i] = $value[$i]->name;
            $i++;
        }

        $datasoshum = Exam::select('users.name', 'exams.id_user')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->where('types.name', 'TKA Soshum')
        ->groupby('users.name', 'exams.id_user')
        ->get();
        $valsoshum = collect();
        foreach ($datasoshum as $key => $value) {
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
            $valsoshum->push($datas);
        }
        $scoresoshum = array();
        $namesoshum = array();
        $i = 0;
        foreach ($valsoshum as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $scoresoshum[$i] = $sum;
            $namesoshum[$i] = $value[$i]->name;
            $i++;
        }


        return view('home',compact('m_dashboard','saintek','soshum','complete','not_complete','scoresaintek','namesaintek','scoresoshum','namesoshum'));
    }
}
