<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class UserTestResult extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "users_tests_results";
    protected $fillable = [
         'result', 'user_id', 'test_id', 'group_test_id','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function exam($exam_id){
        return Exam::whereId($exam_id)->first();
    }

//    public function exam(){
//        return $this->belongsTo(Exam::class,'exam_id');
//    }
//
//    public function group(){
//        return $this->belongsTo(Group::class,'group_id');
//    }

//    public function setGroupNumAttribute()
//    {
//        $this->attributes['group_num'] = rand(100,999) .' - '. Str::random(3) ;
//    }


}
