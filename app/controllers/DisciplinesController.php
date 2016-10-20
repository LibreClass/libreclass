<?php

class DisciplinesController extends \BaseController {

  /**
   * Armazena o ID do usuário
   * @var type num
   */
  private $idUser;

  public function DisciplinesController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    }
    else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  /**
   * Construção da página inicial
   * @return type redirect
   */
  public function getIndex()
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);
      $courses = Course::where("idInstitution", $this->idUser)->whereStatus("E")->orderBy("name")->get();
      $listCourses = [];
      foreach ($courses as $course) {
        $listCourses[Crypt::encrypt($course->id)] = $course->name;
      }
      return View::make("social.disciplines", ["listCourses" => $listCourses, "user" => $user]);
    }
    else {
      return Redirect::guest("/");
    }
  }

  public function postSave()
  {
    $course = Course::find(Crypt::decrypt(Input::get("course")));
    if ($this->idUser == $course->idInstitution) {
      $period = Period::where("idCourse", $course->id )->whereId(Crypt::decrypt(Input::get("period")))->first();
      $discipline = null;
      if (strlen(Input::get("discipline"))) {
        $discipline = Discipline::find(Crypt::decrypt(Input::get("discipline")));
      }
      else {
        $discipline = new Discipline;
      }
      $discipline->idPeriod = $period->id;
      $discipline->name = Input::get("name");
      $discipline->ementa = Input::get("ementa");
      $discipline->save();
      return Redirect::to("/disciplines")->with("success", "Disciplina inserida com sucesso.");
    }
    else {
      return Redirect::to("/disciplines")->with("error", "Não foi possível inserir a disciplina.");
    }
    var_dump(Input::all());
  }

  public function postDelete()
  {
    //~ return Input::all();

    $discipline = Discipline::find(Crypt::decrypt(Input::get("input-trash")));
    $offers = DB::select("SELECT Offers.id, Classes.class
                            FROM Offers, Classes
                             WHERE Classes.status = 'E' AND
                             Offers.idDiscipline=? AND
                             Offers.idClass=Classes.id", [$discipline->id]);

    if(count($offers)) {
      return Redirect::to("/disciplines")->with("error", "Não foi possível excluir. <br>Disciplina vinculada à turma <b>". $offers[0]->class . "</b>");
    }

    if ($discipline) {
      $discipline->status = "D";
      $discipline->save();
      return Redirect::to("/disciplines")->with("success", "Excluído com sucesso.");
    }
    else {
      return Redirect::to("/disciplines")->with("error", "Não foi possível excluir a disciplina.");
    }
  }

  public function getEdit()
  {
    $discipline = Discipline::find(Crypt::decrypt(Input::get("discipline")));
    $discipline->course = Crypt::encrypt(Course::find(Period::find($discipline->idPeriod)->id)->id);
    $discipline->period = $discipline->idPeriod;
    return $discipline;
  }

  public function postEdit()
  {
    var_dump(Input::all());
  }

  /**
   * Lista os periodos para mostrar em um select
   *
   * @return array [id=>value]
   */
  public function postListperiods()
  {
    $periods = Period::where("idCourse", Crypt::decrypt(Input::get("course")))->whereStatus("E")->get();
    foreach( $periods as $period )
      $period->id = Crypt::encrypt($period->id);

    return $periods;
  }

  public function anyList()
  {
    if(Input::get("course")) {
      $disciplines = DB::select("SELECT Disciplines.id AS id, Disciplines.name AS name, Periods.name AS period FROM Disciplines, Periods WHERE idPeriod = Periods.id AND idCourse = ? AND Disciplines.status = 'E'", [Crypt::decrypt(Input::get("course"))]);
      return View::make("social.disciplines.list", [ "disciplines" => $disciplines ]);
    }  
  }
  
  public function getEmenta() {
      
      $discipline = Discipline::find(Crypt::decrypt(Input::get("offer")));
      
//      
//      if(!$ementa) {
//        $ementa = "false";
//      }
     
      return $discipline; 
   
  }

}
