<?php

class Period extends \Eloquent {
  protected $table = 'Periods';

  public function getCourse() {
    return Course::find($this->idCourse);
  }

}
