<?php

class Exam extends \Eloquent {
	protected $table = "Exams";
  
  public function unit() {
    return $this->belongsTo("Unit", "idUnit");
  }
}