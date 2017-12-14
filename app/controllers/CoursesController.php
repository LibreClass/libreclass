<?php

class CoursesController extends \BaseController {

  private $idUser;

  public function CoursesController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    }
    else {
      $this->idUser = Crypt::decrypt($id);
      $this->user = User::find($this->idUser);
    }
  }

  public function getIndex()
  {
    if ($this->idUser) {
      $courses = Course::where("idInstitution", $this->idUser)->whereStatus("E")->orderBy("name")->get();
      $listcourses = [];
      foreach($courses as $course )
      {
        $listcourses[Crypt::encrypt($course->id)] = $course->name;
        $course->periods = Period::where("idCourse", $course->id)->get();
      }
      return View::make("social.courses", ["courses" => $courses, "user" => $this->user, "listcourses" => $listcourses]);
    }
    else {
      return Redirect::guest("/");
    }
  }

  public function postAllCourses() {
    if ($this->idUser) {
      $courses = Course::where("idInstitution", $this->idUser)->whereStatus("E")->orderBy("name")->get();

      foreach($courses as $course)
      {
        $course->id = Crypt::encrypt($course->id);
      }
      return $courses;                                                                                                              ;
    }
  }

  public function postSave()
  {
    $course = null;

    if (strlen(Input::get("course"))) {
      $course = Course::find(Crypt::decrypt(Input::get("course")));
    }
    else {
      if ($this->user->type == "I") {
        $course = new Course;
      }
      else {
        return Redirect::to("/courses")->with("error", "Adquira uma conta <i>premium</i> para completar essa operação.");
      }
    }

    $course->idInstitution = $this->idUser;
    $course->name          = Input::get("name");
    $course->type          = Input::get("type");
    $course->quantUnit    = Input::get("quantUnit");
    $course->modality      = Input::get("modality");
    $course->absentPercent = Input::get("absentPercent");
    $course->average       = Input::get("average");
    $course->averageFinal  = Input::get("averageFinal");

    $course->save();

    if (Input::hasFile("curricularProfile") &&
        Input::file("curricularProfile")->getClientOriginalExtension() === "pdf") {
      $name = md5($course->id) . ".pdf";
      Input::file("curricularProfile")->move(public_path() . "/uploads/curricularProfile/", $name);
      $course->curricularProfile = $name;
      $course->save();
    } else if (Input::hasFile("curricularProfile")) {
      return Redirect::guest("/courses")->with("error", "Problema ao realizar upload de arquivo");
    } else {
      $course->curricularProfile = "";
      $course->save();
    }

    // Este return é realizado ao inserir novo curso ou editar um curso existente
    return Redirect::guest("/courses")->with("success", "Curso $course->name salvo com sucesso!");

    //~ return Input::all();
    return $course;
  }

  public function postDelete()
  {
    $course = Course::find(Crypt::decrypt(Input::get("input-trash")));
    if ($course) {
      $course->status = "D";
      $course->save();
      return Redirect::guest("/courses")->with("success", "Excluído com sucesso!");
    }
    else {
      return Redirect::guest("/courses")->with("error", "Não foi possível deletar");
    }
  }

  public function getEdit()
  {
    return Course::find(Crypt::decrypt(Input::get("course")));
  }

  public function postEdit()
  {
    var_dump(Input::all());
  }

  public function postPeriod()
  {
    try
    {
      $course = Course::find(Crypt::decrypt(Input::get("course")));
      if( $course->idInstitution != $this->user->id )
        throw new Exception("Esse usuário nao tem acesso ao curso.");

      $periods = Period::where("idCourse", $course->id)->get();
      foreach($periods as $period)
      {
        $period->id = Crypt::encrypt($period->id);
        unset($period->idCourse);
        unset($period->created_at);
        unset($period->updated_at);
      }
      return $periods;
    }
    catch (Exception $e)
    {
      return $e->getMessage();
    }
  }

  public function postEditperiod()
  {
    try
    {
      $course = Course::find(Crypt::decrypt(Input::get("course")));
      if( $course->idInstitution != $this->user->id )
        throw new Exception("Esse usuário nao tem aceso ao curso.");

      if ( Input::has("key") )
        $period = Period::find(Crypt::decrypt(Input::get("key")));
      else
        $period = new Period;
      $period->name = Input::get("value");
      $period->idCourse = $course->id;
      $period->save();

      return "ok";
    }
    catch (Exception $e)
    {
      return $e->getMessage();
    }
  }
}
