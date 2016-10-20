<?php

class ClassesController extends \BaseController {

  private $idUser;

  public function ClassesController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    }
    else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  public function getIndex()
  {
    if ($this->idUser ) {
      $user = User::find($this->idUser);
      $courses = Course::where("idInstitution", $this->idUser)->where("status", "E")->orderBy("name")->get();
      $listPeriod = [];
      foreach ($courses as $course) {
        $periods = Period::where("idCourse", $course->id)->orderBy("name")->get();
        //~ $listPeriod[$course->name] = [];
        foreach ($periods as $period) {
          $listPeriod[$course->name][Crypt::encrypt($period->id)] = $period->name;
        }
      }

      $classes = DB::select("SELECT Classes.id AS id, Periods.name AS period,
                              Classes.class AS classe,
                              Courses.name AS name, Classes.status AS status
                             FROM Courses, Periods, Classes
                             WHERE Courses.idInstitution=? AND
                              Courses.status = 'E' AND
                              Classes.status <> 'D' AND
                              Periods.idCourse=Courses.id AND
                              Classes.idPeriod=Periods.id", [$user->id]);
//~ return $classes;
      return View::make("modules.classes", ["listPeriod" => $listPeriod, "user" => $user, "classes" => $classes]);
    }
    else {
      return Redirect::guest("/");
    }
  }

  public function getPanel()
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);
      $courses = Course::where("idInstitution", $this->idUser)->where("status", "E")->orderBy("name")->get();
      $listCourses = [];
      foreach ($courses as $course) {
        $listCourses[Crypt::encrypt($course->id)] = $course->name;
      }
      return View::make("modules.panel", ["listCourses" => $listCourses, "user" => $user]);
    }
    else {
      return Redirect::guest("/");
    }
  }

  public function postListdisciplines() {
    $disciplines = Discipline::where("idPeriod", Crypt::decrypt(Input::get("period")))->whereStatus('E')->get();
    //~ return $disciplines;
    return View::make("modules.disciplines.listOffer", [ "disciplines" => $disciplines ]);
  }

  public function postNew() {
    $class = new Classe;
    $class->idPeriod = Crypt::decrypt(Input::get("period"));
    $class->class = Input::get("class");
    $class->status = 'E';
    $class->save();
    foreach (Input::all() as $key => $value) {
      if (strstr($key, "discipline_") != false)
      {
        $offer = new Offer;
        $offer->idClass  = $class->id;
        $offer->idDiscipline = Crypt::decrypt($value);
        $offer->save();
        $unit = new Unit;
        $unit->IdOffer = $offer->id;
        $unit->value = "1";
        $unit->calculation = "A";
        $unit->save();
      }
    }
    return Redirect::guest("classes")->with("success", "Turma criada com sucesso!");
  }

  public function getEdit()
  {
    $class = Classe::find(Crypt::decrypt(Input::get("classe")));
    $class->course = Course::find(Period::find($class->idPeriod)->idCourse);

    return $class;
  }

  public function postDelete()
  {
    $class = Classe::find(Crypt::decrypt(Input::get("input-trash")));
    if ($class) {
      $class->status = "D";
      $class->save();
      return Redirect::guest("/classes")->with("success", "Excluído com sucesso!");
    }
    else {
      return Redirect::guest("/classes")->with("error", "Não foi possível excluir!");
    }
  }

  public function postChangeStatus()
  {
    $id = Crypt::decrypt(Input::get("key"));

    $class = Classe::find($id);
    if ($class)
    {
      $class->status = Input::get("status");
      $class->save();
      if ( $class->status == "E")
        return Redirect::guest("/classes")->with("success", "Turma ativada com sucesso!");
      else
        return Redirect::guest("/classes")->with("success", "Turma bloqueada com sucesso!<br/>Turmas bloqueadas são movidas para o final.");
    }
    else {
      return Redirect::guest("/classes")->with("error", "Não foi possível realizar essa operação!");
    }

  }
}
