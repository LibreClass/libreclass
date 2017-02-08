<?php

class Bind extends \Eloquent {
	protected $table = "Binds";
	public $timestamps = false;

	public function discipline() {
    return $this->hasOne('Discipline', 'idDiscipline');
  }
}
