<?php

class AvaliableController extends \BaseController
{

  /**
   * Armazena o ID do usuário
   * @var type num
   */
  private $idUser;

  public function AvaliableController()
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
    $user = User::find($this->idUser);
    $exam = Exam::find(Crypt::decrypt(Input::get("e")));
    $students = null;
    if ($exam->aval == "A") {
      $students = Attend::where("idUnit", $exam->idUnit)->get();
    }
//  elseif (  ) /* gerar recuperação */
    //    $students = Attend::where("idUnit", $exam->idUnit)->where->get();
    //    $students = DB::select("SELECT Users.name AS name, Attends.id AS idAttend, Frequencies.value AS value
    //                            FROM Frequencies, Attends, Users
    //                            WHERE Frequencies.idAttend=Attends.id AND Attends.idUser=Users.id AND Frequencies.idLesson=?
    //                            ORDER BY Users.name", [$lesson->id]);
    return View::make("modules.avaliable", ["user" => $user, "exam" => $exam, "students" => $students, "unit" => Unit::find($exam->idUnit)]);
//  return View::make("modules.avaliable", ["user" => $user, "students" => $students]);
  }

  public function postSave()
  {
    if (Input::has("exam")) {
      $exam = Exam::find(Crypt::decrypt(Input::get("exam")));
    } else {
      $exam = new Exam;
      $exam->idUnit = Crypt::decrypt(Input::get("unit"));
      $exam->aval = "A";
    }
    $exam->date = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
    $exam->title = Input::get("title");
    //  $exam->weight = Input::get("weight");
    if (Input::get("weight") != "") {
      $exam->weight = Input::get("weight");
    }

    $exam->type = Input::get("type");
    $exam->comments = Input::get("comment");
    $exam->save();

    if (!Input::has("exam")) {
      $attends = Attend::where("idUnit", $exam->idUnit)->get();
      foreach ($attends as $attend) {
        $value = new ExamsValue;
        $value->idAttend = $attend->id;
        $value->idExam = $exam->id;
        $value->value = "";
        $value->save();
      }
    }

    return Redirect::to("/lectures/units?u=" . Crypt::encrypt($exam->idUnit))->with("success", "Avaliação atualizada com sucesso.");
  }

  public function getNew()
  {
    $user = User::find($this->idUser);
    $unit = Unit::find(Crypt::decrypt(Input::get("u")));
    $exam = new Exam;
    $exam->idUnit = $unit->id;
    $exam->date = date("Y-m-d");
    $exam->title = "Sem título";
    $exam->aval = "A";
    $exam->weight = "1";
    $exam->type = 2;
    //  $exam->save();
    //
    //  $attends = Attend::where("idUnit", $unit->id)->get();
    //  foreach( $attends as $attend ) {
    //    $frequency = new Frequency;
    //    $frequency->idAttend = $attend->id;
    //    $frequency->idLesson = $lesson->id;
    //    $frequency->value = "P";
    //    $frequency->save();
    //  }
    return View::make("modules.avaliable", ["user" => $user, "exam" => $exam, "students" => [], "unit" => $unit]);
//  return Redirect::to("/avaliable?e=" . Crypt::encrypt($exam->id))->with("message", "Click em salvar para criar .");;
  }

  public function postExam()
  {
    try {
      $exam = Crypt::decrypt(Input::get("exam"));
      $attend = Crypt::decrypt(Input::get("student"));
      $value = (float) str_replace(",", ".", Input::get("value"));

      $a = Attend::find($attend);
      $average = $a->getUnit()->getOffer()->getClass()->getPeriod()->getCourse()->average;

      if (($average > 10 && ($value > 100 || $value < 0)) || ($average <= 10 && ($value > 10 || $value < 0))) {
        throw new Exception('Invalid value.');
      } else {
        $value = sprintf("%.2f", $value);
      }
      if (ExamsValue::where("idAttend", $attend)->where("idExam", $exam)->first()) {
        ExamsValue::where("idAttend", $attend)->where("idExam", $exam)->update(["value" => $value]);
      } else {
        $examsvalue = new ExamsValue;
        $examsvalue->idAttend = $attend;
        $examsvalue->idExam = $exam;
        $examsvalue->value = $value;
        $examsvalue->save();
      }
      return $value;
    } catch (Exception $e) {
      return "error";
    }
  }

  public function getFinalunit($unit = "")
  {
    try
    {
      $user = User::find($this->idUser);
      $unit = Crypt::decrypt($unit);
      $final = Exam::whereAval("R")->where("idUnit", $unit)->first();
      if (!$final) {
        $final = new Exam;
        $final->aval = "R";
        $final->title = "Recuperação da Unidade";
        $final->type = 2;
        $final->idUnit = $unit;
        $final->date = date("Y-m-d");
      }
      $course = Unit::find($unit)->getOffer()->getDiscipline()->getPeriod()->getCourse();
      $attends = Attend::where("idUnit", $unit)->get();
      return View::make("modules.units.retrieval", ["exam" => $final, "user" => $user, "attends" => $attends, "average" => $course->average]);
    } catch (Exception $e) {
      return "$e";
    }
  }

  public function postFinalunit($unit = "")
  {
    $cUnit = Unit::find(Crypt::decrypt($unit));
    $exam = Exam::where("idUnit", $cUnit->id)->whereAval("R")->first();
    if (!$exam) {
      $exam = new Exam;
      $exam->aval = "R";
      $exam->idUnit = $cUnit->id;
    }
    $exam->title = "Recuperação da Unidade $cUnit->value";
    $exam->date = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
    $exam->type = Input::get("type");
    $exam->comments = Input::get("comment");
    $exam->save();
    return Redirect::to("/lectures/units?u=$unit")->with("success", "Avaliação atualizada com sucesso.");
//  return Redirect::to("avaliable/finalunit/$unit")->with("message", "Avaliação atualizada com sucesso.");
  }

  public function postFinaldiscipline($id = "")
  {
    $offer = Offer::find(Crypt::decrypt($id));
    $offer->dateFinal = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
    $offer->typeFinal = Input::get("type");
    $offer->comments = Input::get("comment");
    $offer->save();
    return Redirect::to("avaliable/finaldiscipline/$id")->with("success", "Recuperação Final atualizada com sucesso");
  }

  public function postOffer()
  {
    try {
      $offer = Crypt::decrypt(Input::get("offer"));
      $student = Crypt::decrypt(Input::get("student"));
      $value = (float) str_replace(",", ".", Input::get("value"));
      if ($value > 10 || $value < 0) {
        throw new Exception('Division by zero.');
      } else {
        $value = sprintf("%.2f", $value);
      }
      if (FinalExam::where("idUser", $student)->where("idOffer", $offer)->first()) {
        FinalExam::where("idUser", $student)->where("idOffer", $offer)->update(["value" => $value]);
      } else {
        $offervalue = new FinalExam;
        $offervalue->idUser = $student;
        $offervalue->idOffer = $offer;
        $offervalue->value = $value;
        $offervalue->save();
      }
      return $value;
    } catch (Exception $e) {
      return "error";
    }
  }

  public function getFinaldiscipline($offer = "")
  {
    $user = User::find($this->idUser);
    $offer = Offer::find(Crypt::decrypt($offer));

    /* caso não tenha data marcada, coloque a data de hoje */
    if (strtotime($offer->dateFinal) < 0) {
      $offer->dateFinal = date("Y-m-d");
    }

    if (!Lecture::where("idUser", $user->id)->where("idOffer", $offer->id)->first()) {
      return Redirect::to("/logout");
    }
    $units = Unit::where("idOffer", $offer->id)->get();
    $course = Offer::find($offer->id)->getDiscipline()->getPeriod()->getCourse();
    $alunos = DB::select("select Users.id, Users.name
                          from Attends, Units, Users
                          where Units.idOffer=? AND Units.id=Attends.idUnit AND Attends.idUser=Users.id
                          group by Attends.idUser
                          order by Users.name", [$offer->id]);
    foreach ($alunos as $aluno) {
      $aluno->absence = $offer->qtdAbsences($aluno->id);
      $aluno->averages = [];
      $sum = 0.;
      foreach ($units as $unit) {
        $exam = $unit->getAverage($aluno->id);
        $aluno->averages[$unit->value] = $exam[0] < $course->average ? $exam[1] : $exam[0];
        $sum += $aluno->averages[$unit->value];
      }
      $aluno->med = $sum / count($units);
      $final = FinalExam::where("idUser", $aluno->id)->where("idOffer", $offer->id)->first();
      $aluno->final = $final ? $final->value : "";
    }
    return View::make("modules.disciplines.retrieval", ["user" => $user, "alunos" => $alunos, "course" => $course, "offer" => $offer]);
  }

  public function getAverageUnit($unit)
  {
    $final = Exam::whereAval("R")->where("idUnit", $unit->id)->first();
    $qtdExam = Exam::whereAval("A")->where("idUnit", $unit->id)->count();
    $sumWeight = Exam::whereAval("A")->where("idUnit", $unit->id)->sum("weight");
    $sumWeight = $sumWeight ? $sumWeight : 1;
    $attends = Attend::where("idUnit", $unit->id)->get();
    foreach ($attends as $attend) {
      if ($final and ($examfinal = ExamsValue::where("idAttend", $attend->id)->where("idExam", $final->id)->first())) {
        $attend->final = $examfinal->value;
      } else {
        $attend->final = "F";
      }
      $values = ExamsValue::where("idAttend", $attend->id)->get();
      $sum = 0.;
      foreach ($values as $value) {
        $sum += $value->value * Exam::find($value->idExam)->weight;
      }
      $attend->media = $sum / $sumWeight;
      $attend->name = User::find($attend->idUser)->name;
//    $result = $media < $course->average ? "FINAL" : "APROVADO";
      //    echo User::find($attend->idUser)->name . " | $sumWeight | $sum | $media | $result<br>";
    }
//  echo "Total de avaliações: $qtdExam<br>";
    //  echo "Peso: $sumWeight<br>";
    //  echo "Média do curso: $course->average<br><br>";
    return $attends;
  }

  public function getListstudentsexam($exam = "")
  {
    $user = User::find($this->idUser);
    $exam = Exam::find(Crypt::decrypt($exam));
    $students = null;
    if ($exam->aval == "A") {
      $students = Attend::where("idUnit", $exam->idUnit)->get();
    }
    return View::make("modules.liststudentsexam", ["user" => $user, "exam" => $exam, "students" => $students]);
    return Crypt::decrypt($exam);
  }

  public function postDelete()
  {
    $exam = Exam::find(Crypt::decrypt(Input::get("input-trash")));

    $unit = DB::select("SELECT Units.id, Units.status
                          FROM Units, Exams
                          WHERE Units.id = Exams.idUnit AND
                            Exams.id=?", [$exam->id]);

    if ($unit[0]->status == 'D') {
      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("error", "Não foi possível deletar.<br>Unidade desabilitada.");
    }
    if ($exam) {
      $exam->status = "D";
      $exam->save();
      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("success", "Avaliação excluída com sucesso!");
    } else {
      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("error", "Não foi possível deletar");
    }
  }

}
