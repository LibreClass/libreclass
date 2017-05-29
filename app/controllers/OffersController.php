<?php

class OffersController extends \BaseController
{

  private $idUser;

  public function OffersController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  public function getUser()
  {
    $user = Crypt::decrypt(Input::get("u"));
    if ($user) {
      $user = User::find($user);
      return $user;
    } else {
      return Redirect::guest("/");
    }
  }

  public function getIndex()
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);
      $classe = Classe::find(Crypt::decrypt(Input::get("t")));
      $period = Period::find($classe->idPeriod);
      $course = Course::find($period->idCourse);
      $offers = Offer::where("idClass", $classe->id)->get();

      foreach ($offers as $offer) {
        $teachers = [];
        $list = Lecture::where("idOffer", $offer->id)->get();
        foreach ($list as $value) {
          $teachers[] = base64_encode($value->idUser);
        }
        $offer->teachers = $teachers;
        if (isset($offer->idOffer) && !empty($offer->idOffer)) {
          $offer->offer = Offer::find($offer->idOffer);
        }
      }

      return View::make("offers.institution", [
        "course" => $course,
        "user" => $user,
        "offers" => $offers,
        "period" => $period,
        "classe" => $classe,
      ]);
    } else {
      return Redirect::guest("/login");
    }
  }

  public function getUnit($offer)
  {
    $offer = Offer::find(Crypt::decrypt($offer));
    if ($this->idUser != $offer->getClass()->getPeriod()->getCourse()->idInstitution) {
      return Redirect::to("/classes/offers?t=" . Crypt::encrypt($offer->idClass))->with("error", "Você não tem permissão para criar unidade");
    }

    $old = Unit::where("idOffer", $offer->id)->orderBy("value", "desc")->first();

    $unit = new Unit;
    $unit->idOffer = $offer->id;
    $unit->value = $old->value + 1;
    $unit->calculation = $old->calculation;
    $unit->save();

    $attends = Attend::where("idUnit", $old->id)->get();

    foreach ($attends as $attend) {
      $new = new Attend;
      $new->idUnit = $unit->id;
      $new->idUser = $attend->idUser;
      $new->save();
    }

    return Redirect::to("/classes/offers?t=" . Crypt::encrypt($offer->idClass))->with("success", "Unidade criada com sucesso!");
  }

  public function postTeacher()
  {
    // return Input::all();

    $offer = Offer::find(Crypt::decrypt(Input::get("offer")));
    $offer->classroom = Input::get("classroom");
    $offer->day_period = Input::get("day_period");
    $offer->maxlessons = Input::get("maxlessons");
    $offer->save();
    $lectures = $offer->getAllLectures();

    $teachers = [];
    if (Input::has("teachers")) {
      $teachers = Input::get("teachers");
      for ($i = 0; $i < count($teachers); $i++) {
        $teachers[$i] = base64_decode($teachers[$i]);
      }

    }
    // return $teachers;
    foreach ($lectures as $lecture) {
      $find = array_search($lecture->idUser, $teachers);
      if ($find === false) {
        Lecture::where('idOffer', $offer->id)->where('idUser', $lecture->idUser)->delete();
      } else {
        unset($teachers[$find]);
      }

    }

    foreach ($teachers as $teacher) {
      $last = Lecture::where("idUser", $teacher)->orderBy("order", "desc")->first();
      $last = $last ? $last->order + 1 : 1;

      $lecture = new Lecture;
      $lecture->idUser = $teacher;
      $lecture->idOffer = $offer->id;
      $lecture->order = $last;
      $lecture->save();
    }

    //   $idTeacher = Crypt::decrypt(Input::get("teacher"));
    //   $last = Lecture::where("idUser", $idTeacher)->orderBy("order", "desc")->first();
    //   $last = $last ? $last->order+1 : 1;
    //
    //   if (!$lecture) {
    //     $lecture = new Lecture;
    //     $lecture->idUser = $idTeacher;
    //     $lecture->idOffer = $offer->id;
    //     $lecture->order = $last;
    //     $lecture->save();
    //   }
    //   else if($lecture->idUser != $idTeacher) {
    //     Lecture::where('idOffer', $offer->id)->where('idUser', $lecture->idUser)->update(["idUser" => $idTeacher, "order" => $last]);
    //   }
    // }
    // else if ($lecture)
    // {
    //   Lecture::where('idOffer', $offer->id)->where('idUser', $lecture->idUser)->delete();
    // }

    return Redirect::guest(Input::get("prev"))->with("success", "Modificado com sucesso!");
  }

  public function postStatus()
  {
    $status = Input::get("status");
    $id = Crypt::decrypt(Input::get("unit"));

    $unit = Unit::find($id);
    if (!strcmp($status, 'true')) {
      $unit->status = 'E';
    } else {
      $unit->status = 'D';
    }
    $unit->save();

    return "Status changed to " . $status . " / " . $id;
  }

  public function getStudents($offer)
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);

      //$students = User::whereType("N")->orderby("name")->get();
      //$list_students = [];
      //foreach( $students as $student )
      //$list_students[Crypt::encrypt($student->id)] = $student->name;
      $info = DB::select("SELECT Courses.name as course, Periods.name as period, Classes.id as idClass, Classes.class as class
                          FROM Courses, Periods, Classes, Offers
                          WHERE Courses.id = Periods.idCourse
                          AND Periods.id = Classes.idPeriod
                          AND Classes.id = Offers.idClass
                          AND Offers.id = " . Crypt::decrypt($offer) . "
                          ");
      $students = DB::select("SELECT Users.name as name, Users.id as id, Attends.status as status
                              FROM Users, Attends, Units
                              WHERE Users.id=Attends.idUser
                              AND Attends.idUnit = Units.id
                              AND Units.idOffer = " . Crypt::decrypt($offer) . " GROUP BY Users.id ORDER BY Users.name");

      return View::make("modules.liststudentsoffers", ["user" => $user, "info" => $info, "students" => $students, "offer" => $offer]);
    } else {
      return Redirect::guest("/");
    }
  }

  public function postStatusStudent()
  {

    //~ return Input::all();
    $offer = Crypt::decrypt(Input::get("offer"));
    $student = Crypt::decrypt(Input::get("student"));
    $units = Unit::where("idOffer", $offer)->get();

    if (Input::get("status") == 'M') {
      foreach ($units as $unit) {
        Attend::where('idUnit', $unit->id)->where('idUser', $student)->update(["status" => 'M']);
      }

    }

    if (Input::get("status") == 'D') {
      foreach ($units as $unit) {
        Attend::where('idUnit', $unit->id)->where('idUser', $student)->update(["status" => 'D']);
      }

    }

    if (Input::get("status") == 'T') {
      foreach ($units as $unit) {
        Attend::where('idUnit', $unit->id)->where('idUser', $student)->update(["status" => 'T']);
      }

    }

    if (Input::get("status") == 'R') {
      foreach ($units as $unit) {
        Attend::where("idUnit", $unit->id)->where("idUser", $student)->delete();
      }

      return Redirect::back()->with("success", "Aluno removido com sucesso");
    }
    return Redirect::back()->with("success", "Status atualizado com sucesso");
  }

  public function anyDeleteLastUnit($offer)
  {
    $offer = Offer::find(Crypt::decrypt($offer));

    $unit = Unit::where('idOffer', $offer->id)->orderBy('value', 'desc')->first();
    $unit->delete();

    return Redirect::to("/classes/offers?t=" . Crypt::encrypt($offer->idClass))->with("success", "Unidade deletada com sucesso!");
  }

}
