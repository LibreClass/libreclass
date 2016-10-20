<?php

class LessonsController extends \BaseController {
  private $idUser;

  public function LessonsController()
  {
    $id = Session::get("user");
    if ( $id == null || $id == "" )
      $this->idUser = false;
    else
      $this->idUser = Crypt::decrypt($id);
  }
  
  public function getIndex()
  {
    
    if ( $this->idUser ) {
      $user = User::find($this->idUser);

      $lesson = Lesson::find(Crypt::decrypt(Input::get("l")));

      $students = DB::select("SELECT Users.name AS name, Attends.id AS idAttend, Frequencies.value AS value
                                FROM Frequencies, Attends, Users
                                WHERE Frequencies.idAttend=Attends.id AND Attends.idUser=Users.id AND Frequencies.idLesson=?
                                ORDER BY Users.name", [$lesson->id]);
      
      
      return View::make("modules.lessons", ["user" => $user, "lesson" => $lesson, "students" => $students]);
    }
    else {
      return Redirect::guest("/");
    }
  }

  public function getNew()
  {
    $unit = Unit::find(Crypt::decrypt(Input::get("unit")));

    $lesson = new Lesson;
    $lesson->idUnit = $unit->id;
    $lesson->date = date("Y-m-d");
    $lesson->title = "Sem título";
    $lesson->save();

    $attends = Attend::where("idUnit", $unit->id)->get();
    foreach( $attends as $attend ) {
      $frequency = new Frequency;
      $frequency->idAttend = $attend->id;
      $frequency->idLesson = $lesson->id;
      $frequency->value = "P";
      $frequency->save();
    }

    return Redirect::to("/lessons?l=" . Crypt::encrypt($lesson->id))->with("success", "Uma nova aula foi criada com sucesso.");
    return $lesson;
  }

  public function postSave() {
    //~ var_dump(Input::all());

    $lesson = Lesson::find(Crypt::decrypt(Input::get("l")));

    $lesson->date = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
    $lesson->title = Input::get("title");
    $lesson->description = Input::get("description");
    $lesson->goals = Input::get("goals");
    $lesson->content = Input::get("content");
    $lesson->methodology = Input::get("methodology");
    $lesson->valuation = Input::get("valuation");
    $lesson->estimatedTime = Input::get("estimatedTime");
    $lesson->keyworks = Input::get("keyworks");
    $lesson->bibliography = Input::get("bibliography");
    $lesson->notes = Input::get("notes");
    $lesson->save();

    //~ return $lesson;
    $unit = DB::select("SELECT Units.id, Units.status
                          FROM Units, Lessons
                          WHERE Units.id = Lessons.idUnit AND
                            Lessons.id=?", [$lesson->id]);

     return Redirect::guest("/lectures/units?u=".Crypt::encrypt($unit[0]->id))->with("success", "Aula atualizada com sucesso");
  }

  public function postFrequency() {
    $attend = Crypt::decrypt(Input::get("idAttend"));
    $lesson = Crypt::decrypt(Input::get("idLesson"));
    $value  = Input::get("value") == "P" ? "F" : "P";

    $status = Frequency::where("idAttend", $attend)->where("idLesson", $lesson)->update(["value" => $value]);

    return Response::json(["status" => $status, "value" => $value]);
  }
  
  public function postDelete() {
    $lesson = Lesson::find(Crypt::decrypt(Input::get("input-trash")));
    
    $unit = DB::select("SELECT Units.id, Units.status
                          FROM Units, Lessons
                          WHERE Units.id = Lessons.idUnit AND
                            Lessons.id=?", [$lesson->id]);
    
    if($unit[0]->status == 'D') {
      return Redirect::guest("/lectures/units?u=".Crypt::encrypt($unit[0]->id))->with("error", "Não foi possível deletar.<br>Unidade desabilitada.");
    }
    if ($lesson) {
      $lesson->status = "D";
      $lesson->save();
      return Redirect::guest("/lectures/units?u=".Crypt::encrypt($unit[0]->id))->with("success", "Aula excluída com sucesso!");
    }
    else {
      return Redirect::guest("/lectures/units?u=".Crypt::encrypt($unit[0]->id))->with("error", "Não foi possível deletar");
    }
  }
  
  public function getInfo() {
    $lesson = Lesson::find(Crypt::decrypt(Input::get("lesson"))); 
    $lesson->date = date("d/m/Y", strtotime($lesson->date));
    return $lesson;
  }
}
