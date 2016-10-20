<?php

class Course extends \Eloquent {
  protected $table = 'Courses';

  public function getInstitution() {
    return User::find($this->idInstitution);
  }

}
