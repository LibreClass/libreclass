<?php

class Classe extends \Eloquent {
	protected $table = "Classes";

  public function getPeriod()
  {
    return Period::find($this->idPeriod);
  }
}
