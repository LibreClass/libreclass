<?php

class LecturesController extends \BaseController {

  private $idUser;

  public function LecturesController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  public function getIndex()
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);
      $lectures = Lecture::where("idUser", $this->idUser)->get();
      return View::make("offers.teacher", ["user" => $user, "lectures" => $lectures]);
    } else {
      return Redirect::guest("/");
    }
  }

  public function getFinalreport ($offer = "") {
    if ($this->idUser) {
      $user = User::find($this->idUser);
    }
    $offer  = Offer::find(Crypt::decrypt($offer));
    $course = $offer->getDiscipline()->getPeriod()->getCourse();
    $qtdLessons = $offer->qtdLessons();

    $alunos = DB::select("SELECT Users.id, Users.name
                          FROM Attends, Units, Users
                          WHERE Units.idOffer=? AND Units.id=Attends.idUnit AND Attends.idUser=Users.id
                          GROUP BY Attends.idUser
                          ORDER BY Users.name", [$offer->id]);

    $units = Unit::where("idOffer", $offer->id)->get();

    foreach ($alunos as $aluno) {
      $aluno->absence = $offer->qtdAbsences($aluno->id);
      $aluno->averages = [];
      $sum = 0.;
      foreach( $units as $unit ) {
        $exam = $unit->getAverage($aluno->id);
        $aluno->averages[$unit->value] =  $exam[0] < $course->average ? $exam[1] : $exam[0];
        $sum += $aluno->averages[$unit->value];
      }
      $aluno->med = $sum/count($units);

      if ($aluno->med >= $course->average) {
        $aluno->rec = "-";
        $aluno->result = "Ap. por nota";
        $aluno->status = "label-success";
      } else {
        $rec = FinalExam::where("idOffer", $offer->id)->where("idUser", $aluno->id)->first();
        $aluno->rec = $rec ? $rec->value : "0.00";
        if ($aluno->rec >= $course->averageFinal) {
          $aluno->result = "Aprovado";
          $aluno->status = "label-success";
        } else {
          $aluno->result = "Rep. por nota";
          $aluno->status = "label-danger";
        }
      }
      $qtdLessons = $qtdLessons ? $qtdLessons : 1; /* evitar divisão por zero */
      if ($aluno->absence/$qtdLessons*100. > $course->absentPercent ) {
        $aluno->result = "Rep. por falta";
        $aluno->status = "label-danger";
      }
    }

    return View::make("modules.disciplines.finalreport",
                      ["user" => $user,
                       "units" => $units,
                       "students" => $alunos,
                       "offer" => $offer,
                       "qtdLessons" => $qtdLessons,
                       "course" => $course]);
  }

  public function getFrequency($offer)
  {
    $user = User::find($this->idUser);
    $offer = Offer::find(Crypt::decrypt($offer));
    if ($offer->getLectures()->idUser != $this->idUser) {
      return Redirect::to("/lectures")->with("error", "Você não tem acesso a essa página");
    }
    $units = Unit::where("idOffer", $offer->id)->get();
    $students = DB::select("select Users.id, Users.name "
                            . "from Users, Attends, Units "
                            . "where Units.idOffer=? and Attends.idUnit=Units.id and Attends.idUser=Users.id "
                            . "group by Users.id order by Users.name", [$offer->id]);

    return View::make("modules.frequency", ["user" => $user, "offer" => $offer, "units" => $units, "students" => $students]);
    return $offer;
  }
}
