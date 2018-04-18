<?php

class Discipline extends \Eloquent {
  protected $table = 'Disciplines';
  protected $fillable = ['name', 'idPeriod'];

  public function period() {
    return $this->belongsTo('Period', 'idPeriod');
  }

  public function getPeriod() {
    return Period::find($this->idPeriod);
  }
}
