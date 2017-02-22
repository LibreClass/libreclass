<?php

class Offer extends \Eloquent {
  protected $table = "Offers";
  protected $fillable = ['idClass', 'idDiscipline', 'classroom', 'day_period'];

  public function discipline() {
    return $this->belongsTo('Discipline', 'idDiscipline');
  }

  public function units() {
    return $this->hasMany('Unit', 'idOffer');
  }

  public function getDiscipline() {
    return Discipline::find($this->idDiscipline);
  }

  public function getClass() {
    return Classe::find($this->idClass);
  }

  public function getFirstUnit() {
    return Unit::where("idOffer", $this->id)->first();
  }

  public function getLastUnit() {
    return Unit::where("idOffer", $this->id)->orderBy("value", "desc")->first();
  }

  public function getUnits() {
    return Unit::where("idOffer", $this->id)->get();
  }

  public function getLectures() {
    return Lecture::where("idOffer", $this->id)->first();
  }

  public function getAllLectures() {
    return Lecture::where("idOffer", $this->id)->get();
  }


  public function qtdAbsences( $idStudent ) {
    return DB::select("SELECT COUNT(*) as 'qtd'
                        FROM Units, Attends, Lessons, Frequencies
                        WHERE Units.idOffer=? AND
                              Units.id=Lessons.idUnit AND
                              Lessons.id=Frequencies.idLesson AND
                              Lessons.deleted_at IS NULL AND
                              Frequencies.idAttend=Attends.id AND
                              Frequencies.value='F' AND
                              Attends.idUser=?", [$this->id, $idStudent])[0]->qtd;
  }

  public function qtdLessons() {
    return DB::select("SELECT COUNT(*) as 'qtd'
                        FROM Units, Lessons
                        WHERE Units.idOffer=? AND
                              Units.id=Lessons.idUnit AND
                              Lessons.deleted_at IS NULL", [$this->id])[0]->qtd;
  }

  public function getCourse()
  {
    $course = DB::select("SELECT Periods.idCourse FROM Classes, Periods WHERE ?=Classes.id AND Classes.idPeriod=Periods.id", [$this->idClass])[0]->idCourse;
    return Course::find($course);
  }
}
