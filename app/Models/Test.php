<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Test extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'test_num', 'name', 'duration', 'degree',
        'pass_degree','points_degree', 'points', 'stage_id','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    protected $casts = [
        'stage_id' => 'integer',
        'degree' => 'float',
        'pass_degree' => 'float',
    ];

    public function stage(){
        return $this->belongsTo(Stage::class,'stage_id');
    }

    public function querries(){
        return $this->hasMany(Querry::class,'test_id');
    }

    public function Session()
    {
        return $this->belongsToMany('App\Models\Session');
    }


    public function setExamNumAttribute()
    {
        $this->attributes['exam_num'] = rand(100,999) .' - '. Str::random(3) ;
    }


}
