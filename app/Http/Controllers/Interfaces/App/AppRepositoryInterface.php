<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 18/06/2019
 * Time: 03:51 م
 */

namespace App\Http\Controllers\Interfaces\App;


interface AppRepositoryInterface
{
    public function complainAndSuggestion($attributes);
    public function aboutUs();
    public function termCondition();
}
