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
        $object = $class::query();
        $object->create($request);
    }

    public function edit($model,$id){
        $class="\\App\\Models\\".$model;
        return $class::whereId($id)->first();
    }

    public function update($model,$request,$id){
        $class="\\App\\Models\\".$model;
        $object = $class::find($id);
        $object->update($request);
    }

    public function delete($model,$id){
        $class="\\App\\Models\\".$model;
        $class::destroy($id);
    }

}
