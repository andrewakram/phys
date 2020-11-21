<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class SessionTest extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "sessions_tests";
    protected $fillable = [
         'test_id', 'session_id'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function test(){
        return $this->belongsTo(Test::class,'test_id');
    }

    public function stage(){
        return $this->belongsTo(Stage::class,'stage_id');
    }

    public function questions(){
        return $this->hasMany(Question::class,'exam_id');
    }

    public function setExamNumAttribute()
    {
        $this->attributes['exam_num'] = rand(100,999) .' - '. Str::random(3) ;
    }


}
