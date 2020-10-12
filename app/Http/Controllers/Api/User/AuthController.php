<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use App\Http\Requests\BeAShopRequest;
use App\Http\Requests\RegisterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Notification;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(Request $request,AuthRepositoryInterface $authRepository)
    {
        App::setLocale($request->header('lang'));
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {


        $email = $request->email;

        if($this->authRepository->checkIfEmailExist($email))
        {
            return response()->json(msg($request, failed(), 'email_exist'));
        }

        $phone = $request->phone;

        if($this->authRepository->checkIfPhoneExist($phone))
        {
            return response()->json(msg($request, failed(), 'phone_exist'));
        }

        $user = $this->authRepository->create($request);

        if($user)
        {
            return response()->json(msg($request, success(), 'registered'));
        }
    }

    public function beAShop(BeAShopRequest $request)
    {

        if( $data=checkJWT($request->header('jwt')) ){
            if($data->is_shop == 0){
                $user = $this->authRepository->beAShop($request,$data->email,$request->header('jwt'),$request->header('lang'));

                if($user)
                {
                    $shop=$this->authRepository->userData($request->header('jwt'),1,$request->header('lang'));
                    global $title;
                    global $message;
                    if($request->header('lang') == 'en'){
                        $title='Welcome to E3ln App';
                        $message='Welcome to E3ln App & Admin will accept your requests in few hours.';
                    }else{
                        $title='مرحبا بكم في تطبيق اعلن';
                        $message='مرحبا بكم في تطبيق اعلن و سوف يقوم مدير النظام بالموافقة علي طلبكم في غضون ساعات قليلة.';
                    }
                    Notification::send("$shop->token", $title , $message , 2 );
                    return response()->json(msgdata($request,success(),'wait_admin_accept',$shop));
                }
                return response()->json(msg($request,failed(),'failed'));
            }
            return response()->json(msg($request,failed(),'failed'));
        }

        return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function codeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /*'type' => 'required|in:activate,reset',*/
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $this->authRepository->sendSMS("activate",$request->phone);

        return response()->json(msg($request, success(), 'code_sent'));
    }

    public function codeCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $check = $this->authRepository->codeCheck($request->code);
        if($check)
        {
            if(Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H'))
            {
                return response()->json(msg($request, failed(), 'code_expire'));
            }else{
                if($check->type == 'activate')
                {
                    $this->authRepository->activeUser($check->phone);
                    $user2 = $this->authRepository->checkIfPhoneExist($check->phone);
                    $usersss = $this->authRepository->userData($user2->jwt,0,$request->header('lang'));
                    return response()->json(msgdata($request, success(), 'activated',$usersss));
                }
                $user2 = $this->authRepository->checkIfPhoneExist($check->phone);
                $user = $this->authRepository->userData($user2->jwt,$user2->is_shop,$request->header('lang'));
                return response()->json(msgdata($request, success(), 'activated',$user));
            }
        }else{
            return response()->json(msg($request, failed(), 'invalid_code'));
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|',
            'password' => 'required|min:6',
            'token' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $user = $this->authRepository->checkIfPhoneExist($request->phone);
        if($user)
        {
            if(Hash::check($request->password,$user->password))
            {
                if($user->active == 0){
                    //$this->authRepository->sendSMS("activate",$request->phone);
                    return response()->json(msg($request, not_active(), 'not_active'));
                }

                $jwt=Str::random(25);
                $user->token = $request->token;
                $user->jwt = $jwt;
                $user->save();


                $user = $this->authRepository->userData($jwt,$user->is_shop,$request->header('lang'));
                global $title;
                global $message;
                if($request->header('lang') == 'ar'){
                    $title = 'مرحبا بكم في تطبيق اعلن';
                    $message = 'مرحبا بكم في تطبيق اعلن';
                }else{
                    $title = 'Welcome back to E3ln App';
                    $message = 'Welcome back to E3ln App';
                }
                Notification::send("$user->token", $title , $message , 2 );
                return response()->json(msgdata($request, success(), 'logged_in', $user));
            }
            else return response()->json(msg($request, failed(), 'invalid_data'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'password' => 'required|min:6',
            /*'user_id' => 'required|exists:users,id'*/
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        if($d=checkJWT($request->header('jwt'))) {
            $user = $this->authRepository->checkId($d->id);
            if($user)
            {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(msg($request, success(), 'password_changed'));
            }
            return response()->json(msg($request, failed(), 'invalid_data'));
        }


    }

    public function updateProfile(Request $request){
        if($d=checkJWT($request->header('jwt'))) {

            /////
            $user = $this->authRepository->editeProfile($d->id,$request,$request->header('lang'));
            if($user){
                //return response()->json(msgdata($request, success(), 'success', $user));
            }
            /////
            if($request->email){
                if($this->authRepository->checkIfEmailExist2($request->email,$d->id))
                {
                    return response()->json(msg($request, failed(), 'email_exist'));
                }else{
                    $this->authRepository->updateEmail($d->id,$d->is_shop,$request,$request->header('lang'));
                }
            }
            /////
            if($request->phone){
                if($this->authRepository->checkIfPhoneExist2($request->phone,$d->id))
                {
                    return response()->json(msg($request, failed(), 'phone_exist'));
                }else{
                    $this->authRepository->updatePhone($d->id,$d->is_shop,$request,$request->header('lang'));
                }
            }
            $user = $this->authRepository->userData($request->header('jwt'),$request->is_shop,$request->header('lang'));
            return response()->json(msgdata($request, success(), 'success', $user));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));

    }
}
