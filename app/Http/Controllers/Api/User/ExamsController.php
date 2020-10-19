<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use App\Models\GroupExam;
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
            $exams=  GroupExam::where('group_id', $group_id)->with('exam')->get();
            foreach($exams as $exam){ 
                if($exam->start <= Carbon::now() && $exam->end >= Carbon::now()){
                    $exam['status'] = true;
                }else{
                    $exam['status'] = false;
                }
            }
            return response()->json(msgdata($request, success(), 'success', $exams));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }


    public function examQuestions(Request $request)
    {
        $input = $request->all();
        $users = checkJWT($request->header('jwt'));
        $id = $request->header('exam_id');
        if ($users != null) {
            $group_id = $users->group_id;
            $exams =  GroupExam::where('id', $id)->first();
            $questions = Question::where('exam_id', $exams->exam_id)->with('answers')->get();
            return response()->json(msgdata($request, success(), 'success', $questions));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
