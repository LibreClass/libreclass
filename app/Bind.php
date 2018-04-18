<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bind extends Model
{
    //

public function users()
{
  return $this->belongsTo('App\User');
}

public function disciplines()
{
  return $this->belongsTo('App\Discipline');
}

}
