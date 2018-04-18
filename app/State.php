<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //

public function countries()
{
  return $this->belongsTo('App\Country');
}

public function cities()
{
  return $this->hasMany('App\City');
}

}
