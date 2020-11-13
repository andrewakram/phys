<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Group extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'group_num', 'name', 'stage_id','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function stage(){
        return $this->belongsTo(Stage::class,'stage_id');
    }

    public function users(){
        return $this->hasMany(User::class,'group_id');
    }

    public function setGroupNumAttribute()
    {
        $this->attributes['group_num'] = rand(100,999) .' - '. Str::random(3) ;
    }


}
