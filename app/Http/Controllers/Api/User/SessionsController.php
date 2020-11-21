<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use App\Models\Group;
use App\Models\UserTestResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use App\Models\GroupExam;
use App\Models\Exam;
use App\Models\UserExamResult;
use Illuminate\Support\Facades\App;
use App\Models\Question;
use App\Models\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    protected $authRepository;

    public function __construct(Request $request, AuthRepositoryInterface $authRepository)
    {
        App::setLocale($request->header('lang'));
        $this->authRepository = $authRepository;
    }

    public function Sessions(Request $request)
    {

        $input = $request->all();
        $users = checkJWT($request->header('jwt'));
        if ($users != null) {
            $stage_id = Group::where('id', $users->group_id)->first();
            $stage_id = $stage_id->stage_id;
            $sessions = Session::where('stage_id', $stage_id)->where('deleted',0)->get();
            $session [] = null;
            foreach ($sessions as $session) {
                if ($session->from <= Carbon::now() && $session->to >= Carbon::now()) {
                    $session['status'] = true;
                } else {
                    $session['status'] = false;
                }
            }

            return response()->json(msgdata($request, success(), 'success', $sessions));

        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));


    }

    public function SessionDetails(Request $request, $session_id)
    {
        $user = checkJWT(request()->header('jwt'));
        if ($user) {
            $session_details = Session::where('id', $session_id)->with('tests', 'videos')->first();
            if ($session_details) {

                $test1 = $session_details->tests->first();
                $test2 = $session_details->tests->last();
                $video1 = $session_details->videos->first();
                $video2 = $session_details->videos->last();
                if ($test1 && $test2 && $video1 && $video2) {
                    $test1['status'] = true;

                    $user_result1 = UserTestResult::where('user_id', $user->id)->where('test_id', $test1->id)->latest()->first();

                    if ($user_result1 && ($user_result1->result >= $test1->pass_degree)) {
                        $test2['status'] = true;
                        $video1['status'] = true;
                    } else {
                        $test2['status'] = false;
                        $video1['status'] = false;
                    }

                    $user_result2 = UserTestResult::where('user_id', $user->id)->where('test_id', $test2->id)->latest()->first();
                    if ($user_result2 && ($user_result2->result >= $test2->pass_degree)) {
                        $video2['status'] = true;
                    } else {
                        $video2['status'] = false;
                    }
                } else {
                    return response()->json(msg($request, not_found(), 'not_found'));
                }
                return response()->json(msgdata($request, success(), 'success', $session_details));
            } else {
                return response()->json(msg($request, not_found(), 'session_not_found'));
            }
        } else {
            return response()->json(msg($request, not_authoize(), 'invalid_data'));

        }

    }

}
