<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Symfony\Component\Console\Question\Question;

class Answer extends Model
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'answer', 'description', 'image', 'is_true', 'question_id','deleted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function question(){
        return $this->belongsTo(Question::class,'question_id');
    }


}
