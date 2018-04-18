<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    //

public function units()
{
  return $this->hasMany('App\Unit');
}

public function lectures()
{
  return $this->hasMany('App\Lecture');
}

public function finalExams()
{
  return $this->hasMany('App\FinalExam');
}

public function classes()
{
  return $this->belongsTo('App\Classe');
}

public function disciplines()
{
  return $this->belongsTo('App\Discipline');
}

}
