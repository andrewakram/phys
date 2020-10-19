<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:53 م
 */

namespace App\Http\Controllers\Eloquent;


use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;

class IndexRepository implements IndexRepositoryInterface
{
    //public $indexRepository;

    public function __construct()
    {
        //$this->$indexRepository = $indexRepository;
    }
//    public function object($model){
//        return "App\\Models\\".$model::query();
//    }

    public function index($model){
        $class="\\App\\Models\\".$model;
        return $class::query();
    }

    public function create($model,$request){
        $class="\\App\\Models\\".$model;
        return $class::create($request);
    }

    public function update($model,$request,$id){
        $class="\\App\\Models\\".$model;
        $obj = $class::whereId($id)->first();
        $obj->update($request);
    }

    public function delete($model,$id){
        $class="\\App\\Models\\".$model;
        $class::destroy($id);
    }

    public function checkIfExists($model,$colom,$value){
        $class="\\App\\Models\\".$model;
        $check = $class::where($colom,$value)->first();
        if($check)
            return true;
        return false;
    }

    public function changStatus($model,$id)
    {
        $class="\\App\\Models\\".$model;
        $obj = $class::whereId($id)->select('id','active')->first();
        if($obj->active == 1)
            $obj->active=0;
        else
            $obj->active=1;
        $obj->save();
    }

}
