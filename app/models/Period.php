<?php

class Period extends \Eloquent {
  protected $table = 'Periods';
  protected $fillable = ['name', 'idCourse'];

  public function course() {
    return $this->belongsTo('Course', 'idCourse');
  }

  public function disciplines()
  {
    return $this->hasMany('Discipline', 'idPeriod');
  }


  public function getCourse() {
    return Course::find($this->idCourse);
  }

}
