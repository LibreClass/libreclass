<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    //

public function courses()
{
  return $this->belongsTo('App\Course');
}

public function classes()
{
  return $this->hasMany('App\Classe');
}

public function disciplines()
{
  return $this->hasMany('App\Discipline');
}

}
