<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attest extends Model
{
    //
    
public function users()
{
  return $this->belongsTo('App\User', 'institution_id');
}


}
