<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attend extends Model
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

public function frequencys()
{
  return $this->hasMany('App\Frequency');
}

public function units()
{
  return $this->belongsTo('App\Unit');
}

public function users()
{
  return $this->belongsTo('App\User');
}

}
