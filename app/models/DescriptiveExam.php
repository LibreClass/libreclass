<?php

class DescriptiveExam extends \Eloquent
{
  protected $table = "DescriptiveExams";

  public function student()
  {
    return $this->belongsTo('Attend', 'idAttend')->first()->getUser();
  }

}
