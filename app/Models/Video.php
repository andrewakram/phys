<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Video extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'name', 'description', 'link'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function session_videos()
    {
        return $this->belongsTo(SessionVideo::class,'session_id');
    }

    public function Session()
    {
        return $this->belongsToMany('App\Models\Session');
    }


}
