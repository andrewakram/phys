<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class SessionGroup extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "sessions_groups";
    protected $fillable = [
         'group_id', 'session_id','from','to','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function group(){
        return $this->belongsTo(Group::class,'group_id');
    }

    public function session(){
        return $this->belongsTo(Session::class,'session_id');
    }

    public function questions(){
        return $this->hasMany(Question::class,'exam_id');
    }

    public function setExamNumAttribute()
    {
        $this->attributes['exam_num'] = rand(100,999) .' - '. Str::random(3) ;
    }


}
