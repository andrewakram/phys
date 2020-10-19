<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:52 م
 */

namespace App\Http\Controllers\Interfaces;


interface IndexRepositoryInterface
{
    public function index($model);
    public function create($model,$request);
    public function update($model,$request,$id);
    public function delete($model,$id);
    public function checkIfExists($model,$colom,$value);
    public function changStatus($model,$id);
}
