<?php

class UnitsController extends \BaseController {

  private $idUser;
  private $unit;

  public function __construct()
  {
    if ( Input::has("u") )
      $this->unit = Unit::find(Crypt::decrypt(Input::get("u")));

    $id = Session::get("user");
    if ( $id == null || $id == "" )
      $this->idUser = false;
    else
      $this->idUser = Crypt::decrypt($id);
  }

  public function getIndex()
  {
    if ( $this->idUser && Input::has("u") )
    {
      $user = User::find($this->idUser);
      $unit_current = Unit::find(Crypt::decrypt(Input::get("u")));

      if ($unit_current->status == "D")
        return Redirect::to("lectures")->with("error", "Esta unidade está desativada");

      $units = Unit::where("idOffer", $unit_current->idOffer )->get();
      $list_units = [];
      foreach( $units as $unit )
        $list_units[] = $unit->value;

      $lessons  = Lesson::where("idUnit", $unit_current->id)->whereStatus('E')->orderBy("id", "desc")->orderBy("date", "desc")->get();
      $recovery = Exam::where("idUnit", $unit_current->id)->whereAval("R")->first();
      $exams    = Exam::where("idUnit", $unit_current->id)->whereStatus('E')->whereAval("A")->orderBy("id", "desc")->get();
      return View::make("modules.panel", ["user"          => $user,
                                          "list_units"    => $list_units,
                                          "unit_current"  => $unit_current,
                                          "lessons"       => $lessons,
                                          "recovery"      => $recovery,
                                          "exams"         => $exams ]);
    }
    else
      return Redirect::guest("/");
  }

  /*
    edita a unidade. O único atributo editável é a forma de calcular a média
  */
  public function postEdit()
  {
    try
    {
      $this->unit->calculation = Input::get("calculation");
      $this->unit->save();
      return Response::json(true);
    }
    catch(Exception $e)
    {
      return Response::json(false);
    }
  }


  public function getNew()
  {
    if ( Input::has("offer") ) {
      $offer = Crypt::decrypt(Input::get("offer"));
// return $offer;
//    select * from Units where idOffer=1 order by value desc limmit 1
      $old = Unit::where("idOffer", $offer)->orderBy("value", "desc")->first();

      $unit = new Unit;
      $unit->idOffer = $old->idOffer;
      $unit->value = $old->value+1;
      $unit->calculation = $old->calculation;
      $unit->save();

      $attends = Attend::where("idUnit", $old->id)->get();

      foreach( $attends as $attend ) {
        $new = new Attend;
        $new->idUnit = $unit->id;
        $new->idUser = $attend->idUser;
        $new->save();
      }

      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit->id));
    }
  }

  public function getStudent()
  {
    if ( $this->idUser ) {
      $user = User::find($this->idUser);

      $students = User::whereType("S")->orderby("name")->get();
      $list_students = [];
      foreach( $students as $student )
        $list_students[Crypt::encrypt($student->id)] = $student->name;
      $students = DB::select("SELECT Users.name as name, Users.id as id FROM Users, Attends WHERE Users.id=Attends.idUser AND Attends.idUnit = ".$this->unit->id . " ORDER BY Users.name");

      return View::make("modules.units", ["user" => $user, "list_students" => $list_students, "students" => $students]);
    }
    else {
      return Redirect::guest("/");
    }
  }

  public function postRmstudent()
  {
    //~ return Input::all();
    $unit = Crypt::decrypt(Input::get("unit"));
    $student = Crypt::decrypt(Input::get("student"));

    Attend::where("idUnit", $unit)->where("idUser", $student)->delete();

    return Redirect::to("lectures/units/student?u=".Input::get("unit"))
                      ->with("success", "Aluno removido com sucesso");
  }

  public function postAddstudent()
  {
    $unit = Crypt::decrypt(Input::get("unit"));
    $student = Crypt::decrypt(Input::get("student"));

    $attend = Attend::where("idUnit", $unit)->where("idUser", $student)->first();
    if ( $attend ){
      return Redirect::to("lectures/units/student?u=".Input::get("unit"))
                      ->with("error", "Aluno já cadastrado");
    }
    else {
      $attend = new Attend;
      $attend->idUnit = $unit;
      $attend->idUser = $student;
      $attend->save();
      return Redirect::to("lectures/units/student?u=".Input::get("unit"))
                      ->with("success", "Aluno cadastrado com sucesso");
    }
  }

  public function getNewunit() {
    return "nova unidade";
  }
}
