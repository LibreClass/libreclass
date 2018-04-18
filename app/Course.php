<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    
public function users()
{
  return $this->belongsTo('App\User', 'course_id');
}

public function periods()
{
  return $this->hasMany('App\Period');
}

}
