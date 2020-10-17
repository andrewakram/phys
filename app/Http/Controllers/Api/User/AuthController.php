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
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(Request $request, AuthRepositoryInterface $authRepository)
    {
        App::setLocale($request->header('lang'));
        $this->authRepository = $authRepository;
    }





    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:6',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $user = $this->authRepository->checkIfPhoneExist($request->phone);

        if (Auth::attempt([
            'phone' => $request->input('phone'),
            'password' => $request->input('password')
        ])) {
            if ($user->active == 0) {
                //$this->authRepository->sendSMS("activate",$request->phone);
                return response()->json(msg($request, not_active(), 'not_active'));
            }

            $jwt = Str::random(25); 
            $user->jwt = $jwt;
            $user->save();


            $user = $this->authRepository->userData($jwt);
           
            return response()->json(msgdata($request, success(), 'logged_in', $user));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            /*'user_id' => 'required|exists:users,id'*/
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        if ($d = checkJWT($request->header('jwt'))) {
            $user = $this->authRepository->checkId($d->id);
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(msg($request, success(), 'password_changed'));
            }
            return response()->json(msg($request, failed(), 'invalid_data'));
        }
    }

    public function updateProfile(Request $request)
    {
        if ($d = checkJWT($request->header('jwt'))) {

            /////
            $user = $this->authRepository->editeProfile($d->id, $request, $request->header('lang'));
            if ($user) {
                //return response()->json(msgdata($request, success(), 'success', $user));
            }
            /////
            if ($request->email) {
                if ($this->authRepository->checkIfEmailExist2($request->email, $d->id)) {
                    return response()->json(msg($request, failed(), 'email_exist'));
                } else {
                    $this->authRepository->updateEmail($d->id, $d->is_shop, $request, $request->header('lang'));
                }
            }
            /////
            if ($request->phone) {
                if ($this->authRepository->checkIfPhoneExist2($request->phone, $d->id)) {
                    return response()->json(msg($request, failed(), 'phone_exist'));
                } else {
                    $this->authRepository->updatePhone($d->id, $d->is_shop, $request, $request->header('lang'));
                }
            }
            $user = $this->authRepository->userData($request->header('jwt'), $request->is_shop, $request->header('lang'));
            return response()->json(msgdata($request, success(), 'success', $user));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
