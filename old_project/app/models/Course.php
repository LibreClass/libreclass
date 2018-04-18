<?php

class Course extends \Eloquent {
  protected $table = 'Courses';
  protected $fillable = ['name', 'idInstitution'];

  public function institution() {
    return $this->belongsTo('User', 'idInstitution');
  }

  public function periods()
  {
    return $this->hasMany('Period', 'idCourse');
  }


  public function getInstitution() {
    return User::find($this->idInstitution);
  }

}
