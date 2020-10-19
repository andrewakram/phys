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

class ExamsController extends Controller
{
    protected $authRepository;

    public function __construct(Request $request, AuthRepositoryInterface $authRepository)
    {
        App::setLocale($request->header('lang'));
        $this->authRepository = $authRepository;
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
