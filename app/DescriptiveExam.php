<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescriptiveExam extends Model
{
    //

public function exams()
{
  return $this->belongsTo('App\Exam');
}

public function attends()
{
  return $this->belongsTo('App\Attend');
}

}
