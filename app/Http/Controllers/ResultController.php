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
        // $inggris = Exam::select('users.name', 'exams.id_user')
        // ->join('users', 'users.id', '=', 'exams.id_user')
        // ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        // ->join('types','types.id','=','users.id_type')
        // ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        // ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        // ->join('categories', 'categories.id', 'bank_questions.id_category')
        // ->where('types.name', 'TKA Saintek')
        // ->where('categories.name', 'Inggris')
        // ->groupby('users.name', 'exams.id_user')
        // ->get();
        // dd($inggris);
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
        $i = 0.0;
        foreach ($val as $key => $value) {            
            $sum = 0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
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
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    // $sum+=divnum($values->score,$values->weight);
                    $sum= $sum + ($values->score / $values->weight);
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
        $bing = collect();
        $pbm = collect();
        $ppu = collect();
        $pk = collect();
        $pu = collect();
        $mtk = collect();
        $fisika = collect();
        $kimia = collect();
        $biologi = collect();
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
        $inggris = collect();
        $inggris = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Inggris')
        ->where('users.id', $value->id_user)
        ->get();
        $pbms = collect();
        $pbms = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PBM')
        ->where('users.id', $value->id_user)
        ->get();
        $ppus = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PPU')
        ->where('users.id', $value->id_user)
        ->get();

        $pks = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PK')
        ->where('users.id', $value->id_user)
        ->get();

        $pus = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PU')
        ->where('users.id', $value->id_user)
        ->get();

        $mtks = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'MTK Saintek')
        ->where('users.id', $value->id_user)
        ->get();

        $fisikas = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Fisika')
        ->where('users.id', $value->id_user)
        ->get();
        $kimias = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Kimia')
        ->where('users.id', $value->id_user)
        ->get();
        $biologis = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Biologi')
        ->where('users.id', $value->id_user)
        ->get();
            $bing->push($inggris);
            $pbm->push($pbms);
            $ppu->push($ppus);
            $pk->push($pks);
            $pu->push($pus);
            $mtk->push($mtks);
            $fisika->push($fisikas);
            $kimia->push($kimias);
            $biologi->push($biologis);
            $val->push($datas);
        }
        $vals = array();
        $i = 0;
        foreach ($val as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            array_push($vals,['score'=> $sum,'name'=> $value[$i]->name]);
            $i++;
        }
        $i = 0;
        foreach ($bing as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['bing'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($pbm as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['pbm'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($ppu as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['ppu'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($pk as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['pk'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($pu as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['pu'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($mtk as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['mtk'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($fisika as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['fisika'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($kimia as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['kimia'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($biologi as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['biologi'=> $sum];
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
        $bing = collect();
        $pbm = collect();
        $ppu = collect();
        $pk = collect();
        $pu = collect();
        $sejarah = collect();
        $ekonomi = collect();
        $geografi = collect();
        $sosiologi = collect();
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
        $inggris = collect();
        $inggris = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Inggris')
        ->where('users.id', $value->id_user)
        ->get();
        $pbms = collect();
        $pbms = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PBM')
        ->where('users.id', $value->id_user)
        ->get();
        $ppus = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PPU')
        ->where('users.id', $value->id_user)
        ->get();

        $pks = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PK')
        ->where('users.id', $value->id_user)
        ->get();

        $pus = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'PU')
        ->where('users.id', $value->id_user)
        ->get();
        $sejarahs = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Sejarah')
        ->where('users.id', $value->id_user)
        ->get();
        $ekonomis = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Ekonomi')
        ->where('users.id', $value->id_user)
        ->get();
        $geografis = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Geografi')
        ->where('users.id', $value->id_user)
        ->get();
        $sosiologis = Exam::select('users.name', 'exams.id_user', 'detail_exams.status', 'detail_create_exams.score','detail_create_exams.weight')
        ->join('users', 'users.id', '=', 'exams.id_user')
        ->join('detail_exams', 'detail_exams.id_exam', '=', 'exams.id')
        ->join('types','types.id','=','users.id_type')
        ->join('detail_bank_questions', 'detail_exams.id_question', '=', 'detail_bank_questions.id')
        ->join('detail_create_exams', 'detail_create_exams.id_detail_bank_question', '=', 'detail_bank_questions.id')
        ->join('bank_questions', 'detail_bank_questions.id_bank_question', '=', 'bank_questions.id')
        ->join('categories', 'categories.id', 'bank_questions.id_category')
        ->where('types.name', 'TKA Saintek')
        ->where('categories.name', 'Sosiologi')
        ->where('users.id', $value->id_user)
        ->get();
            $val->push($datas);
            $bing->push($inggris);
            $pbm->push($pbms);
            $ppu->push($ppus);
            $pk->push($pks);
            $pu->push($pus);
            $sejarah->push($sejarahs);
            $ekonomi->push($ekonomis);
            $geografi->push($geografis);
            $sosiologi->push($sosiologis);
        }
        $vals = array();
        $i = 0;
        foreach ($val as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            array_push($vals,['score'=> $sum,'name'=> $value[$i]->name]);
            $i++;
        }
        $i = 0;
        foreach ($bing as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['bing'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($pbm as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['pbm'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($ppu as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['ppu'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($pk as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['pk'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($pu as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['pu'=> $sum];
            $i++;
        }

        $i = 0;
        foreach ($sejarah as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['sejarah'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($ekonomi as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['ekonomi'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($geografi as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['geografi'=> $sum];
            $i++;
        }
        $i = 0;
        foreach ($sosiologi as $key => $value) {            
            $sum = 0.0;
            foreach ($value as $keys => $values) {
                if ($values->status==1) {
                    $sum= $sum + ($values->score / $values->weight);
                }
            }
            $vals[$i]= $vals[$i]+['sosiologi'=> $sum];
            $i++;
        }

 
    	$pdf = PDF::loadview('result.soshum',['vals'=>$vals]);
    	return $pdf->download('soshum.pdf');
    }
}
