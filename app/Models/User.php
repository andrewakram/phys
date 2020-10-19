<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'jwt', 'active', 'name', 'phone', 'password','image','group_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function group(){
        return $this->belongsTo(Group::class,'group_id');
    }

    public function exams_passed(){
        return $this->hasMany(UserExamResult::class,'user_id');
    }


//    protected  $primaryKey = 'jwt';
//    public $incrementing = false;

//    public function scopeActivated($query)
//    {
//        return $query->where('active', 1);
//    }
//
//    public function scopeSuspended($query)
//    {
//        return $query->where('active', 0);
//    }

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/users/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/users/'.$value);
        }else{
            return asset('/default.png');
        }
    }


}
