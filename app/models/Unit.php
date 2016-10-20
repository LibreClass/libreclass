<?php

class Unit extends \Eloquent {
  protected $table = "Units";

  public function getOffer()
  {
    return Offer::find($this->idOffer);
  }

  public function getAverage($student)
  {
    $exams  = Exam::where("idUnit", $this->id)->whereAval("A")->get();
    $attend = Attend::where("idUnit", $this->id)->where("idUser", $student)->first();
    if ( !$attend ) return null;
    $out    = [null, null];
    $sum    = 0.;
    $weight = 0.;
    foreach( $exams as $exam ) {
      $value = ExamsValue::where("idExam", $exam->id)->where("idAttend", $attend->id)->first();
      if ( $value )
        $sum += $value->value * ($this->calculation == "W" ? $exam->weight : 1); /* so multiplica pelo peso quando for média ponderada */
      $weight += $exam->weight;
    }

    /* tipo de calculo da média */
    if ( $this->calculation == "A" and count($exams) )
      $out[0] = $sum/count($exams);
    elseif ( $this->calculation == "W" and $weight > 0 )
      $out[0] = $sum/$weight;
    elseif ( $this->calculation == "S" )
      $out[0] = $sum;

    $final = Exam::where("idUnit", $this->id)->whereAval("R")->first();
    if ( $final ) {
      $value = ExamsValue::where("idExam", $final->id)->where("idAttend", $attend->id)->first();
      if ( $value )
        $out[1] = $value->value;
    }

    return $out;
  }

  public function getLessons()
  {
    return Lesson::where("idUnit", $this->id)->get();
  }


}
