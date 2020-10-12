<?php

namespace App\Http\Controllers\Interfaces\User;

interface AuthRepositoryInterface{

    public function create($attributes);
    public function beAShop($request,$email,$jwt,$lang);
    public function sendSMS($type,$phone);
    public function codeCheck($code);
    public function activeUser($phone);
    public function checkIfEmailExist($email);
    public function checkIfEmailExist2($email,$id);
    public function checkIfPhoneExist($phone);
    public function checkIfPhoneExist2($phone,$id);
    public function updateEmail($id,$is_shop,$request,$lang);
    public function updatePhone($id,$is_shop,$request,$lang);
    public function editeProfile($id,$request,$lang);
    //public function checkJWT($jwt);
    public function checkId($id);
    public function userData($jwt,$is_shop,$lang);
}
