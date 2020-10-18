<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
                return response()->json(msg($request, not_active(), 'not_active'));
            }
            $jwt = Str::random(25);
            $user->jwt = $jwt;
            $user->save();
            $user = $this->authRepository->userData($jwt);
            return response()->json(msgdata($request, success(), 'logged_in', $user));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }


    public function updateProfile(Request $request)
    {
        $input = $request->all();
        $users = checkJWT($request->header('jwt'));

        if ($users != null) {

            $validator = Validator::make($input, [
                'name' => 'unique:users,name,' . $users->id,
                'phone' => 'unique:users,phone,' . $users->id,
                'password' => 'min:6',
            ]);
            if ($request->name != null) {
                $users->name = $request->name;
            }
            if ($request->phone != null) {
                $users->phone = $request->phone;
            }
            if ($request->password != null) {
                $users->password = Hash::make($request->password);
            }
            $users->save();
            return response()->json(msgdata($request, success(), 'success', $users));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
