<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    //

public function descriptiveExams()
{
  return $this->hasMany('App\DescriptiveExam');
}

public function examsValues()
{
  return $this->hasMany('App\ExamsValue');
}

public function units()
{
  return $this->belongsTo('App\Unit');
}

}
