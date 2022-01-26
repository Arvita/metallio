<?php

namespace App\Http\Controllers;

use App\Models\AnswerQuestionBanks;
use App\Models\DetailBankQuestion;
use App\Models\DetailCreateExam;
use App\Models\DetailExam;
use App\Models\Exam;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ExamController extends Controller
{
    private $guard = [1,0];

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
        $m_exam = 'active';
        $schedule = Schedule::select('create_exams.name', 'create_exams.duration_tps', 'create_exams.duration_tpa', 'types.name as type', 'schedules.open', 'schedules.close', 'schedules.status', 'schedules.id_exam', 'schedules.id as id_schedule', 'exams.id')
        ->join('create_exams', 'create_exams.id', 'schedules.id_exam')
        ->leftjoin('exams', 'schedules.id', 'exams.id_schedule')
        ->join('types', 'types.id', '=', 'create_exams.id_type')
        ->where('create_exams.id_type', '=', $auth->user()->id_type)
        ->first();
        $user = $auth->user();
        $exam = Exam::where('exams.id_user', '=', $auth->user()->id)->first();
        if ($exam==null) {
            $complete = 0;
            $complete_tps = 0;
        } else {
            $complete = $exam->complete;
            $complete_tps = $exam->complete_tps;
        }
        return view('exam.index', compact('schedule', 'm_exam', 'user', 'exam', 'complete','complete_tps'));
    }

    public function data()
    {
        $data = Exam::select('users.name', 'create_exams.duration_tps', 'create_exams.duration_tpa', 'exams.start_tps', 'exams.finish_tps', 'exams.start_tpa', 'exams.finish_tpa', 'exams.complete', 'exams.id', 'exams.created_at', 'exams.updated_at', 'types.name as type')
        ->join('schedules', 'schedules.id', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', 'schedules.id_exam')
        ->join('types', 'types.id', 'create_exams.id_type')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->latest()
        ->get();
        return Datatables::of($data)
            ->addColumn('detail', function ($mn) {
                return
                    '<button type="button" data-url="' . url('exam/' . $mn->id) . '/conf" data-toggle="tooltip" title="" class="ajax_modal_popup btn btn-link btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></button></div>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function take_exam_tps(Guard $auth, Request $request)
    {
        $m_exam = 'active open';
        $id_user = $auth->user()->id;
        $is_in_db = Exam::where('id_schedule', $request->id_schedule)
        ->where(function ($query) use ($id_user) {
            $query->where('id_user', $id_user);
        })
        ->count();
        if ($is_in_db < 1) {
            $data = [
          'id_schedule' => $request->id_schedule,
          'id_exam' => $request->id,
          'id_user' => $id_user,
          'start_tps' => $request->start,
          'finish_tps' => $request->finish
        ];
            $exam = Exam::create($data);
            $exam = Exam::select('exams.id', 'create_exams.duration_tpa', 'create_exams.duration_tps', 'exams.complete', 'exams.finish_tps', 'exams.start_tpa')
            ->join('schedules', 'schedules.id', 'exams.id_schedule')->join('create_exams', 'create_exams.id', 'schedules.id_exam')->where('exams.id', $exam->id)->first();
        }
        else{
            
            $exam = Exam::select('exams.id', 'create_exams.duration_tpa', 'create_exams.duration_tps', 'exams.complete', 'exams.finish_tps', 'exams.start_tpa')
            ->join('schedules', 'schedules.id', 'exams.id_schedule')->join('create_exams', 'create_exams.id', 'schedules.id_exam')->where('exams.id', $request->id)->first();
        }
           // dd($exam);
        
        // return $exam_assessment->completed == 1;
        
        $id_exam = $exam->id;

        //pbm
        $pbm = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'PBM')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();
        
        //PPU
        $ppu = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'PPU')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

        //PK
        $pk = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'PK')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

        //PPU
        $ppu = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'PPU')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

        //PU
        $pu = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'PU')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();
  
        $inggris = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'Inggris')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

        $join1 =$pbm->merge($ppu);
        $join2 =$pk->merge($join1);
        $join3 =$pu->merge($join2);
        $id_question =$inggris->merge($join3);

        $answered = DetailExam::where('id_exam', $id_exam)->get();
        $arr_answer = array();
        foreach ($answered as $key => $value) {
            $arr_answer[$value->id_question] =  $value;
        }
        
        return view('exam.take_exam_tps', compact('m_exam', 'id_question', 'exam', 'arr_answer', 'id_exam'));
    }

    public function start_tpa(Guard $auth, Request $request)
    {
        $m_exam = 'active open';
        $id_user = $auth->user()->id;
        $exam = Exam::where('exams.id', $request->id)->first();
        if ($exam->start_tpa==null) {
            $data = [
                'start_tpa' => $request->start,
                'finish_tpa' => $request->finish
                  ];
                //   dd($data);
                  Exam::query()->update($data);
        }
        Exam::where('id', $request->id)
        ->update(['complete_tps' => 1]);
        return FacadesResponse::json($exam);
    }

    public function take_exam_tpa(Guard $auth, $id)
    {
        $m_exam = 'active open';
        $id_user = $auth->user()->id;
        $exam = Exam::where('id', $id)->first();
         
        $id_exam = $exam->id;

        $user = User::select('types.name')->join('types', 'types.id', 'users.id_type')->where('users.id', '=', $auth->user()->id)->first();
        if ($user->name =="TKA Saintek") {
            //mtk
            $mtk = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'MTK Saintek')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

            //fisika
            $fisika = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'Fisika')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

            //kimia
            $kimia = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'Kimia')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();


            //biologi
            $biologi = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'Biologi')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

            $tpa1 =$biologi->merge($mtk);
            $tpa2 =$fisika->merge($tpa1);
            $id_question =$kimia->merge($tpa2);
        } else {
            //sejarah
            $sejarah = Exam::select('detail_bank_questions.id', 'categories.name')
             ->where('exams.id', $id_exam)
             ->where('categories.name', 'Sejarah')
             ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
             ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
             ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
             ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
             ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
             ->join('categories', 'categories.id', 'bank_questions.id_category')
             ->groupBy('detail_bank_questions.id', 'categories.name')
             ->inRandomOrder()->get();
     
            //ekonomi
            $ekonomi = Exam::select('detail_bank_questions.id', 'categories.name')
             ->where('exams.id', $id_exam)
             ->where('categories.name', 'Ekonomi')
             ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
             ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
             ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
             ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
             ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
             ->join('categories', 'categories.id', 'bank_questions.id_category')
             ->groupBy('detail_bank_questions.id', 'categories.name')
             ->inRandomOrder()->get();
     
            //geografi
            $geografi = Exam::select('detail_bank_questions.id', 'categories.name')
             ->where('exams.id', $id_exam)
             ->where('categories.name', 'Geografi')
             ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
             ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
             ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
             ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
             ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
             ->join('categories', 'categories.id', 'bank_questions.id_category')
             ->groupBy('detail_bank_questions.id', 'categories.name')
             ->inRandomOrder()->get();
     
            //sosiologi
            $sosiologi = Exam::select('detail_bank_questions.id', 'categories.name')
             ->where('exams.id', $id_exam)
             ->where('categories.name', 'Sosiologi')
             ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
             ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
             ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
             ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
             ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
             ->join('categories', 'categories.id', 'bank_questions.id_category')
             ->groupBy('detail_bank_questions.id', 'categories.name')
             ->inRandomOrder()->get();
     
            $tpa1 =$sosiologi->merge($sejarah);
            $tpa2 =$ekonomi->merge($tpa1);
            $id_question =$geografi->merge($tpa2);
        }
        $answered = DetailExam::where('id_exam', $id_exam)->get();
        $arr_answer = array();
        foreach ($answered as $key => $value) {
            $arr_answer[$value->id_question] =  $value;
        }
        return view('exam.take_exam_tpa', compact('m_exam', 'id_question', 'exam', 'arr_answer', 'id_exam'));
    }

    public function getquestion(Request $request)
    {
        $question = DetailBankQuestion::select('detail_bank_questions.id', 'detail_bank_questions.question', 'categories.name as category')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
      ->where('detail_bank_questions.id', $request->id)
      ->first();
        $id_question = $question->id;
        $answer = AnswerQuestionBanks::where('id_question', $id_question)->get();
        $answer_assessment = DetailExam::select('id_answer')
        ->where('id_exam', $request->id_exam)
        ->where(function ($query) use ($id_question) {
            $query->where('id_question', $id_question);
        })
        ->first();
        return ['question' => $question, 'answer' => $answer, 'answer_assessment' => $answer_assessment];
    }
    public function submit_answer(Guard $auth, Request $request)
    {
        $id_question = $request->id_question;

        $isExist = DetailExam::where('id_exam', $request->id_exam)
      ->where(function ($query) use ($id_question) {
          $query->where('id_question', $id_question);
      })
      ->first();

        // $getWeight = DetailCreateExam::where('id_detail_bank_question', $request->id_question)->first();
        $answer = AnswerQuestionBanks::where('id', $request->id_answer)->first();
        $weight=0;
        if ($answer->status == 1) {
            $status = 1;
        // $weight = $getWeight->weight + 1;
        } else {
            $status = 0;
            // $weight = $getWeight->weight;
        }
        // $exam = Exam::where('id_user', $auth->user()->id)
        // ->join('detail_exams', 'detail_exams.id_exam', 'exams.id')
        // ->where('exams.id', $request->id_exam)
        // ->first();
        // if ($exam==null) {
        //     DetailCreateExam::where('id_detail_bank_question', $request->id_question)
        // ->update(['weight' => $weight]);
        // } else {
        //     DetailCreateExam::where('id_detail_bank_question', $request->id_question)
        //     ->update(['weight' => $weight-1]);
        // }
        // return $status;
        if ($isExist) {
            return DetailExam::where('id', $isExist->id)
          ->update(['id_answer' => $request->id_answer, 'status' => $status]);
        } else {
            $data = [
          'id_exam' => $request->id_exam,
          'id_question' => $request->id_question,
          'id_answer' => $request->id_answer,
          'status' => $status
        ];

            return DetailExam::create($data);
        }
    }

    public function completed(Request $request)
    {
        $assessment = Exam::where('id', $request->id)->first();
        $question = DetailExam::where('id_exam', $assessment->id)->get();
        // dd($assessment);

        foreach ($question as $key => $value) {
            $id_question = $value->id_question;

            $is_exists = DetailExam::where('id_exam', $request->id)
        ->where(function ($query) use ($id_question) {
            $query->where('id_question', $id_question);
        })
        ->count();
            if ($is_exists < 1) {
                $data = [
          'id_exam' => $request->id,
          'id_question' => $value->id_question,
        ];

                DetailExam::create($data);
            }
            
            $getWeight = DetailCreateExam::where('id_detail_bank_question', $id_question)->first();
            $weight=0;
            if ($value->status == 1) {
                $weight = $getWeight->weight + 1;
            } else {
                $weight = $getWeight->weight;
            }
            DetailCreateExam::where('id_detail_bank_question', $id_question)
        ->update(['weight' => $weight]);
        }
        return Exam::where('id', $request->id)
      ->update(['complete' => 1]);
    }

    public function confirm($id)
    {
        $exam  = Exam::find($id);
        return ($exam) ? view('exam.confirm', compact('exam', 'id')) : $this->showModalError();
    }

    public function destroy(Request $request)
    {
        try {
            $detail=DetailExam::where('id_exam', $request->id)->get();
            foreach ($detail as $key => $value) {
                DB::table('detail_exams')->where('id_exam', $value['id_exam'])->delete();
            }
            Exam::where('id', '=', $request->input('id'))->delete();
            return response()->json(['stat' => true, 'msg' => $this->getMessage('delete.success')]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['stat' => false, 'msg' => $this->getMessage('delete.prevent')]);
        }
    }
}
