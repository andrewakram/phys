<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Promocode extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'code', 'name','value', 'user_id','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }




}
