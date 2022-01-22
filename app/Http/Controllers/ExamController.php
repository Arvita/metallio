<?php

namespace App\Http\Controllers;

use App\Models\AnswerQuestionBanks;
use App\Models\DetailBankQuestion;
use App\Models\DetailCreateExam;
use App\Models\DetailExam;
use App\Models\Exam;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $schedule = Schedule::select('create_exams.name', 'create_exams.duration', 'types.name as type', 'schedules.open', 'schedules.close', 'schedules.status', 'schedules.id_exam', 'schedules.id as id_schedule', 'exams.id')
        ->join('create_exams', 'create_exams.id', 'schedules.id_exam')
        ->leftjoin('exams', 'schedules.id', 'exams.id_schedule')
        ->join('types', 'types.id', '=', 'create_exams.id_type')
        ->where('create_exams.id_type', '=', $auth->user()->id_type)
        ->first();
        $user = $auth->user();
        $exam = Exam::where('exams.id_user', '=', $auth->user()->id)->first();
        if ($exam==null) {
            $complete = 0;
        } else {
            $complete = $exam->complete;
        }
        return view('exam.index', compact('schedule', 'm_exam', 'user', 'exam', 'complete'));
    }

    public function data()
    {
        $data = Exam::select('users.name', 'create_exams.duration', 'exams.start', 'exams.finish', 'exams.complete', 'exams.id', 'exams.created_at', 'exams.updated_at', 'types.name as type')
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

    public function take_exam(Guard $auth, Request $request)
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
          'start' => $request->start,
          'finish' => $request->finish
        ];
            $exam = Exam::create($data);
        } else {
            $exam = Exam::where('id', $request->id)->first();
        }
        // return $exam_assessment->completed == 1;
        if ($exam->completed == 1) {
            return redirect(url('exam'));
        }
        $id_exam = $exam->id;
  
        $bahasa_inggris = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'Bahasa Inggris')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();

        $tps = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'TPS')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();
        $main =$bahasa_inggris->merge($tps);
        $user = User::select('types.name')->join('types', 'types.id', 'users.id_type')->where('users.id', '=', $auth->user()->id)->first();
        if ($user->name =="TKA Saintek") {
            $tpa = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'TKA Saintek')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();
            $id_question =$main->merge($tpa);
        } else {
            $tpa = Exam::select('detail_bank_questions.id', 'categories.name')
        ->where('exams.id', $id_exam)
        ->where('categories.name', 'TKA Soshum')
        ->join('schedules', 'schedules.id', '=', 'exams.id_schedule')
        ->join('create_exams', 'create_exams.id', '=', 'schedules.id_exam')
        ->join('detail_create_exams', 'create_exams.id', '=', 'detail_create_exams.id_create_exam')
        ->join('detail_bank_questions', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->groupBy('detail_bank_questions.id', 'categories.name')
        ->inRandomOrder()->get();
            $id_question =$main->merge($tpa);
        }
        $answered = DetailExam::where('id_exam', $id_exam)->get();
        $arr_answer = array();
        foreach ($answered as $key => $value) {
            $arr_answer[$value->id_question] =  $value;
        }
        return view('exam.take_exam', compact('m_exam', 'id_question', 'exam', 'arr_answer', 'id_exam'));
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
        // dd($question);

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
