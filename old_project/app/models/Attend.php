<?php

class Attend extends \Eloquent
{
  protected $table = "Attends";
  protected $fillable = ['idUser', 'idUnit'];

  public function getUser()
  {
    return User::find($this->idUser);
  }

  public function getExamsValue($exam)
  {
    $examValue = ExamsValue::where("idExam", $exam)->where("idAttend", $this->id)->first();
    if ($examValue) {
      return $examValue->value;
    } else {
      return null;
    }
  }

  public function getDescriptiveExam($exam)
  {
    $examDescriptive = DescriptiveExam::where("idExam", $exam)->where("idAttend", $this->id)->first();
    if ($examDescriptive) {
      return ["description" => $examDescriptive->description, "approved" => $examDescriptive->approved];
    } else {
      return null;
    }
  }

  public function getUnit()
  {
    return Unit::find($this->idUnit);
  }
}
