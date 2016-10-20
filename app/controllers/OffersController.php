<?php

class OffersController extends \BaseController {

  private $idUser;

  public function OffersController()
  {
    $id = Session::get("user");
    if ( $id == null || $id == "" )
      $this->idUser = false;
    else
      $this->idUser = Crypt::decrypt($id);
  }

  public function getUser(){
    $user = Crypt::decrypt(Input::get("u"));
    if ( $user ) {
      $user = User::find($user);
      return $user;
    }else {
      return Redirect::guest("/");
    }
  }

  public function getIndex()
  {
    if ( $this->idUser ) {
      $user = User::find($this->idUser);

      $teachers = DB::select("SELECT Users.id, Users.name
                              FROM Relationships, Users 
                              WHERE Relationships.idUser = ?
                                AND Relationships.idFriend = Users.id
                                AND Relationships.status = 'E'
                                AND Relationships.type = 2
                              ORDER BY Users.name", [$user->id]);
      $listTeachers = [];
      foreach( $teachers as $teacher )
        $listTeachers[Crypt::encrypt($teacher->id)] = $teacher->name;

      $classe = Classe::find(Crypt::decrypt(Input::get("t")));
      $period = Period::find($classe->idPeriod);
      $course = Course::find($period->idCourse);
      $offers = Offer::where("idClass", $classe->id)->get();
      return View::make("offers.institution",
                          ["course" => $course, "user" => $user, "teachers" => $listTeachers, "offers" => $offers, "period" => $period, "classe" => $classe ]);
    }
    else {
      return Redirect::guest("/login");
    }
  }

  public function getUnit($offer)
  {
    $offer = Offer::find(Crypt::decrypt($offer));
    if ( $this->idUser != $offer->getClass()->getPeriod()->getCourse()->idInstitution )
      return Redirect::to("/classes/offers?t=" . Crypt::encrypt($offer->idClass))->with("error", "Você não tem permissão para criar unidade");

    $old = Unit::where("idOffer", $offer->id)->orderBy("value", "desc")->first();

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

    return Redirect::to("/classes/offers?t=" . Crypt::encrypt($offer->idClass))->with("success", "Unidade criada com sucesso!");
  }

  public function postTeacher() {
    $offer = Offer::find(Crypt::decrypt(Input::get("offer")));
    $offer->classroom = Input::get("classroom");
    $offer->save();
    $lecture = $offer->getLectures();
    if (!$lecture) {
      $lecture = new Lecture;
      $lecture->idUser = Crypt::decrypt(Input::get("teacher"));
      $lecture->idOffer = $offer->id;
      $lecture->save();
    }
    else {
      Lecture::where('idOffer', $offer->id)->update(["idUser" => Crypt::decrypt(Input::get("teacher"))]);
    }
    return Redirect::guest(Input::get("prev"))->with("success", "Professor vinculado com sucesso!");
  }

  public function postStatus() {
    $status = Input::get("status");
    $id = Crypt::decrypt(Input::get("unit"));

    $unit = Unit::find($id);
    if(!strcmp($status, 'true')) {
      $unit->status = 'E';
    }else {
      $unit->status = 'D';
    }
    $unit->save();

    return "Status changed to ".$status." / ".$id;
  }

}
