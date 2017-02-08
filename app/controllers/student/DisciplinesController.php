<?php namespace student;

use DB, Session, Exception, User, Crypt, View, Offer, Discipline, Unit;

class DisciplinesController extends \BaseController {

  public function __construct()
  {
    if ( !Session::has("user") )
      throw new Exception("404");
    else
      $this->user = User::find(Crypt::decrypt(Session::get("user")));
  }

  public function getIndex()
	{
    $offers= DB::select("SELECT Offers.id, Disciplines.name as discipline FROM Attends, Units, Offers, Disciplines "
                  . " WHERE Attends.idUser=? AND Attends.idUnit=Units.id AND Units.idOffer=Offers.id AND Offers.idDiscipline=Disciplines.id", [$this->user->id]);

    return View::make("students.disciplines", ["offers" => $offers]);
	}

  public function getUnits($offer)
  {
    $offer = Offer::find(Crypt::decrypt($offer));
    $teachers = DB::select("SELECT Users.id, Users.name, Users.photo FROM Lectures, Users WHERE Lectures.idOffer=? and Lectures.idUser=Users.id", [$offer->id]);
    $discipline = Discipline::find($offer->idDiscipline);
    $units = Unit::where("idOffer", $offer->id)->orderBy("value", "desc")->get();
    $course = $offer->getCourse();

    return View::make("students.units", ["offer" => $offer, "teachers" => $teachers, "discipline" => $discipline, "units" => $units, "course" => $course]);
  }

  public function postResumeUnit($unit)
  {
    $unit = Crypt::decrypt($unit);
    $list = DB::select("SELECT Lessons.id, title, date, value, 'L' as type FROM Lessons, Frequencies, Attends WHERE Lessons.idUnit=? AND Lessons.id=idLesson AND idAttend=Attends.id AND idUser=?"
          . " UNION ALL ( SELECT Exams.id, title, date, value, 'E' as type FROM Exams, ExamsValues, Attends WHERE Exams.idUnit=? AND Exams.id=idExam AND idAttend=Attends.id AND idUser=? ) ORDER BY date DESC",
          [$unit, $this->user->id, $unit, $this->user->id]);

    $now = date("Y-m-d");
    foreach($list as $i)
    {
      $i->id = Crypt::encrypt($i->id);
      if( $now <= $i->date )
        $i->value = "";

      $i->date = date("d/m/Y", strtotime($i->date));
    }

    return $list;
  }
}


