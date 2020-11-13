<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use App\Models\GroupExam;
use App\Models\Exam;
use App\Models\UserExamResult;
use App\Models\Question;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ExamsController extends Controller
{
    protected $authRepository;

    public function __construct(Request $request, AuthRepositoryInterface $authRepository)
    {
        App::setLocale($request->header('lang'));
        $this->authRepository = $authRepository;
    }


    public function examData(Request $request)
    {
        $input = $request->all();
        $users = checkJWT($request->header('jwt'));
        if ($users != null) {
            $group_id = $users->group_id;
            $exam [] = null;
            $doneExams = UserExamResult::where('user_id',$users->id)
                ->where('deleted',0)
                ->pluck('group_exam_id');
            $exams=  GroupExam::where('group_id', $group_id)
                ->where('deleted',0)
                ->whereNotIn('id',$doneExams)
                ->with('exam')
                ->get();
            foreach($exams as $exam){ 
                $examData = Exam::whereId($exam->exam_id)->first();
                
                $exam['name'] = $examData->name;
                $exam['duration'] = $examData->duration;
                $exam['degree'] = $examData->degree;
                if($exam->start <= Carbon::now() || $exam->end >= Carbon::now()){
                    $exam['status'] = true;
                }else{
                    $exam['status'] = false;
                }
            }
            return response()->json(msgdata($request, success(), 'success', $exams));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }


    public function examQuestions(Request $request)
    {
        $input = $request->all();
        $users = checkJWT($request->header('jwt'));
        $id = $request->exam_id;
        if ($users != null) {
            $group_id = $users->group_id;
            $exams =  GroupExam::where('exam_id', $id)->first();
            $questions = Question::where('exam_id', $id)->with('answers')->get();   
                $data=[];
                $examData = Exam::whereId($exams->exam_id)->first();
                $data['name'] = $examData->name;
                $data['duration'] = $examData->duration;
                $data['degree'] = $examData->degree;
                    $result = UserExamResult::where('user_id',$users->id)
                        ->where('exam_id',$id)
                        ->where('group_exam_id',$exams->id)
                        ->select('result')->first();
                $data['answer_degree'] = isset($result) ? $result->result : "";
                $data['questions'] = $questions;
                
            return response()->json(msgdata($request, success(), 'success', $data));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }
    
    public function myExams(Request $request)
    {
        $input = $request->all();
        $users = checkJWT($request->header('jwt'));
        if ($users != null) {
            $doneExams = UserExamResult::where('user_id',$users->id)->where('deleted',0)->pluck('group_exam_id');
            // $exams = Exam::whereIn('id',$doneExams)->with(["questions" => function ($query) {
            //     $query->with('answers');
            // }])->get();
            $exams=  GroupExam::where('group_id', $users->group_id)
                ->whereIn('id',$doneExams)
                ->with('exam')
                ->get();
            foreach($exams as $exam){ 
                $examData = Exam::whereId($exam->exam_id)->first();
                
                $exam['name'] = $examData->name;
                $exam['duration'] = $examData->duration;
                $exam['degree'] = $examData->degree;
                if($exam->start <= Carbon::now() && $exam->end >= Carbon::now()){
                    $exam['status'] = true;
                }else{
                    $exam['status'] = false;
                }
            }
            
            return response()->json(msgdata($request, success(), 'success', $exams));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }
    
    public function finishExam(Request $request)
    {
        $input = $request->all();
        $users = checkJWT($request->header('jwt'));
        if ($users != null) {
            $exam = GroupExam::where('exam_id',$request->exam_id)
                ->where('group_id',$users->group_id)->first();
        if($exam){
            
           UserExamResult::create([
               'exam_id' => $request->exam_id ,
               'user_id' => $users->id ,
               'group_exam_id' => $exam->id ,
               'result' =>  $request->result ,
               ]);
        
            return response()->json(msg($request, success(), 'success'));
                
            }
            return response()->json(msg($request, failed(), 'no_group_exams'));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }
}
