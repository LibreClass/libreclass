<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    //
public function offers()
{
  return $this->hasMany('App\Offer');
}

public function periods()
{
  return $this->belongsTo('App\Period');
}

}
