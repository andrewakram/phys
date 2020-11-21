<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 18/06/2019
 * Time: 03:52 م
 */

namespace App\Http\Controllers\Eloquent\App;


use App\Http\Controllers\Interfaces\App\AppRepositoryInterface;
use App\Models\AboutUs;
use App\Models\ComplainSuggests;
use App\Models\TermCondition;

class AppRepository implements AppRepositoryInterface
{
    public function complainAndSuggestion($input)
    {
        $array = array(
          'type'=> $input->type,
          'user_id' => $input->user_id,
          'title' => $input->title,
          'description' => $input->description,
        );
        ComplainSuggests::create($array);
        return true;
    }

    public function aboutUs()
    {
        return new AboutUs;
    }

    public function termCondition()
    {
        return new TermCondition();
    }
}
