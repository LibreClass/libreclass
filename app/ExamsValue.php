<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamsValue extends Model
{
    //

public function attends()
{
  return $this->belongsTo('App\Attend');
}

public function exams()
{
  return $this->belongsTo('App\Exam');
}

}
