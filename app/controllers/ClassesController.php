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
        CONCAT('[', Classes.class, '] ', Classes.name) AS classe,
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

  public function postListdisciplines()
  {
		if(Input::has("flag")) {
			$disciplines = Discipline::where("idPeriod", Crypt::decrypt(Input::get("period")))->whereStatus('E')->get();
		}
		else {
			$disciplines = Discipline::where("idPeriod", Crypt::decrypt(Input::get("period")))->whereStatus('E')->get();
		}
    //~ return $disciplines;
    return View::make("modules.disciplines.listOffer", [ "disciplines" => $disciplines ]);
  }

  public function postNew()
  {
    $class = new Classe;
    $class->idPeriod = Crypt::decrypt(Input::get("period"));
    $class->name = Input::get("name");
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

  public function getInfo()
  {
    $class = Classe::find(Crypt::decrypt(Input::get("classe")));
    $class->idPeriodCrypt = Crypt::encrypt($class->idPeriod);
    $class->course = Course::find(Period::find($class->idPeriod)->idCourse);

    return $class;
  }

  public function postEdit()
  {
    $class = Classe::find(Crypt::decrypt(Input::get("classId")));
    if($class) {
      $class->name = Input::get("class");
      $class->save();
      return Redirect::guest("/classes")->with("success", "Classe editada com sucesso!");
    }
    return Redirect::guest("/classes")->with("error", "Não foi possível editar!");

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

  public function anyListOffers()
  {
    $offers = Offer::where("idClass", Crypt::decrypt(Input::get("class")))->get();
    $idStudent = Crypt::decrypt(Input::get("student"));

    foreach ($offers as $offer) {
      $offer->status = DB::select("SELECT count(*) as qtd FROM Units, Attends ".
                                    "WHERE Units.idOffer=? AND Units.id=Attends.idUnit AND Attends.idUser=?",
                                    [$offer->id, $idStudent])[0]->qtd;

      $offer->name = Discipline::find($offer->idDiscipline)->name;
      $offer->id = Crypt::encrypt($offer->id);
    }

    return $offers;
  }

  /**
   * Faz uma busca por todos os cursos da instituição e suas unidades ativas
   *
   *
   * @return json com cursos e unidades
   */
  public function postListUnits($status = 1)
  {
    $status = ((int) $status ? "E" : "D");

    $courses = Course::where("idInstitution", $this->idUser)->whereStatus("E")->get();
    foreach ($courses as $course) {
      $course->units = DB::select("SELECT Units.value
                                     FROM Periods, Classes, Offers, Units
                                    WHERE Periods.idCourse=?
                                          AND Periods.id=Classes.idPeriod
                                          AND Classes.id=Offers.idCLass
                                          AND Classes.status='E'
                                          AND Offers.id=Units.idOffer
                                          AND Units.status=?
                                 GROUP BY Units.value", [$course->id, $status]);

      $course->id = Crypt::encrypt($course->id);
    }

    return $courses;
  }

  public function postBlockUnit()
  {
    $course = Course::find(Crypt::decrypt(Input::get("course")));
    if ( $course->idInstitution != $this->idUser )
      throw new Exception('Usuário inválido');

    $periods = Period::where("idCourse", $course->id)->get();
    foreach ($periods as $period)
    {
      $classes = Classe::where("idPeriod", $period->id)->get();
      foreach ($classes as $class)
      {
        $offers = Offer::where("idClass", $class->id)->get();
        foreach ($offers as $offer)
          Unit::where("idOffer", $offer->id)->whereValue(Input::get("unit"))->whereStatus("E")->update(array('status' => "D"));
      }
    }
  }

  public function postUnblockUnit()
  {
    $course = Course::find(Crypt::decrypt(Input::get("course")));
    if ( $course->idInstitution != $this->idUser )
      throw new Exception('Usuário inválido');

    $periods = Period::where("idCourse", $course->id)->get();
    foreach ($periods as $period)
    {
      $classes = Classe::where("idPeriod", $period->id)->get();
      foreach ($classes as $class)
      {
        $offers = Offer::where("idClass", $class->id)->get();
        foreach ($offers as $offer)
          Unit::where("idOffer", $offer->id)->whereValue(Input::get("unit"))->whereStatus("D")->update(array('status' => "E"));
      }
    }
  }

  public function anyCreateUnits()
  {
    $s_attends = false;
    $course = Course::find(Crypt::decrypt(Input::get("course")));
    if ( $course->idInstitution != $this->idUser )
      throw new Exception("Você não tem permissão para realizar essa operação");

    $offers = DB::select("SELECT Offers.id FROM Periods, Classes, Offers "
            . "WHERE Periods.idCourse=? AND Periods.id=Classes.idPeriod AND Classes.id=Offers.idClass", [$course->id]);

    if(!count($offers))
      throw new Exception("Não possui ofertas nesse curso.");

    foreach($offers as $offer)
    {
      $old = Unit::where("idOffer", $offer->id)->orderBy("value", "desc")->first();

      $unit = new Unit;
      $unit->idOffer = $old->idOffer;
      $unit->value = $old->value+1;
      $unit->calculation = $old->calculation;
      $unit->save();

      $attends = Attend::where("idUnit", $old->id)->get();

      $s_attends = false;
      foreach( $attends as $attend )
      {
        if( !$s_attends )
          $s_attends = "INSERT IGNORE INTO Attends (idUnit, idUser) VALUES ($unit->id, $attend->idUser)";
        else
          $s_attends .= ", ($unit->id, $attend->idUser)";
      //  $new = new Attend;
      //  $new->idUnit = $unit->id;
      //  $new->idUser = $attend->idUser;
      //  $new->save();
      }
      if($s_attends)   DB::insert($s_attends);
    }
  }
}
