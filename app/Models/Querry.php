<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Querry extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'title', 'description', 'image', 'test_id','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function replies(){
        return $this->hasMany(Reply::class, 'querry_id');
    }

    public function test(){
        return $this->belongsTo(Test::class,'test_id');
    }

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/questions/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/questions/'.$value);
        }else{
            return '';
        }
    }

}
