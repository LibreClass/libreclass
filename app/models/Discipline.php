<?php

class Discipline extends \Eloquent {
  protected $table = 'Disciplines';

  public function period() {
    return $this->belongsTo('Period', 'idPeriod');
  }

  public function getPeriod() {
    return Period::find($this->idPeriod);
  }
}
