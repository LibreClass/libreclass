<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    //

public function binds()
{
  return $this->hasMany('App\Bind');
}

public function offers()
{
  return $this->belongsTo('App\Offer');
}

public function periods()
{
  return $this->belongsTp('App\Period');
}

}
