<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    //

public function attends()
{
  return $this->belongsTo('App\Attend');
}

public function lessions()
{
  return $this->belongsTo('App\Lesson');
}

}
