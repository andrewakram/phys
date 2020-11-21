<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Session extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'stage_id', 'name','price', 'from', 'to','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function session_videos(){
        return $this->hasMany(SessionVideo::class,'session_id');
    }

    public function session_tests(){
        return $this->hasMany(SessionTest::class,'session_id');
    }

    public function stage(){
        return $this->belongsTo(Stage::class,'stage_id');
    }

    public function tests(){
        return $this->belongsToMany('App\Models\Test','sessions_tests');
    }

    public function videos(){
        return $this->belongsToMany('App\Models\Video','sessions_videos');
    }

    public function getFromAttribute()
    {
        return Carbon::parse($this->attributes['from'])->format('Y-m-d g:i A');
    }

    public function getToAttribute()
    {
        return Carbon::parse($this->attributes['to'])->format('Y-m-d g:i A');
    }





}
