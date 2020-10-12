<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Interfaces\App\AppRepositoryInterface;
use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    protected $appRepository;
    protected $userAuthRepository;
    public function __construct(AppRepositoryInterface $appRepository,AuthRepositoryInterface $userAuthRepository)
    {
        $this->appRepository = $appRepository;
        $this->userAuthRepository = $userAuthRepository;
    }

    public function complainSuggest(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required|in:complain,suggest',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|max:190',
            'description' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->appRepository->complainAndSuggestion($request);
            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function aboutUs(Request $request)
    {
        if($request->header('lang') == 'ar')
            $about = $this->appRepository->aboutUs()->select('ar_name as name')->first();
        else
            $about = $this->appRepository->aboutUs()->select('en_name as name')->first();
        return response()->json(msgdata($request, success(), 'success',$about));
    }

    public function termCondition(Request $request)
    {
        if($request->header('lang') == 'ar')
            $term = $this->appRepository->termCondition()->select('ar_name as name')->first();
        else
            $term = $this->appRepository->termCondition()->select('en_name as name')->first();
        return response()->json(msgdata($request, success(), 'success',$term));
    }
}
