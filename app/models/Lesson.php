<?php

class Lesson extends \Eloquent {
	use SoftDeletingTrait;
	protected $table = "Lessons";
  protected $dates = ['deleted_at'];

  public function unit() {
    return $this->belongsTo("Unit", "idUnit");
  }

}
