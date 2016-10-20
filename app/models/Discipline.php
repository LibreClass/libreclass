<?php

class Discipline extends \Eloquent {
  protected $table = 'Disciplines';

  public function getPeriod() {
    return Period::find($this->idPeriod);
  }
}
