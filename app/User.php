<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


public function suggestions()
{
  return $this->hasMany('App\Suggestion');
}

public function attends()
{
  return $this->hasMany('App\Attend');
}

public function attests()
{
  return $this->hasMany('App\Attest');
}

public function binds()
{
  return $this->hasMany('App\Bind');
}

public function lectures()
{
  return $this->hasMany('App\Lecture');
}

public function finalExams()
{
  return $this->hasMany('App\FinalExam');
}

public function cities()
{
  return $this->belongsTo('App\City');
}

public function courses()
{
  return $this->hasMany('App\Course');
}

public function relationships()
{
  return $this->hasMany('App\Relationship');
}

}
