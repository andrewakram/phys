<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


function admin()
{
    return Auth::guard('admin')->user();
}

function success()
{
    return 200;
}

function failed()
{
    return 401;
}

function not_authoize()
{
    return 403;
}

function not_found()
{
    return 404;
}

function not_active()
{
    return 405;
}

function msg($request,$status,$key)
{
    $msg['status'] = $status;
    $msg['msg'] = Config::get('response.'.$key.'.'.$request->header('lang'));

    return $msg;
}

function msgdata($request,$status,$key,$data)
{
    $msg['status'] = $status;
    $msg['msg'] = Config::get('response.'.$key.'.'.$request->header('lang'));
    $msg['data'] = $data;

    return $msg;
}

function image($path,$url)
{
    return 'http://'.$_SERVER['SERVER_NAME'].$path.$url;
}

function unique_file($fileName)
{
    $fileName = str_replace(' ','-',$fileName);
    return time() . uniqid().'-'.$fileName;
}

function generateJWT()
{
    return Str::random(25).time();
}

function generateActivationCode()
{
    return rand(1000,9999);
}

function checkJWT($jwt)
{
    return
        User::where("jwt",$jwt)->select('id','email','password',
            'is_captin','country_id','lat','lng')
            ->first();
}

function checkLang()
{
    if(!isset(getallheaders()['lang'])){
        return response()->json(['status' => 401, 'msg' => 'The language is Required']);
    }
}

function getDistanceLatLng($lat1,$lng1,$lat2,$lng2)
{
    $trip = new \App\Models\Trip();

    return $trip->distance($lat1, $lng1, $lat2, $lng2, "K");
}
