<?php

class ClassroomController extends \BaseController {
  
  private $idUser;

  public function __construct() {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  public function getIndex() {
    if (Session::has("redirect")) {
      return Redirect::to(Session::get("redirect"));
    }
    $user = User::find($this->idUser);
    Session::put("type", $user->type);
    return View::make("classrooms.home", ["user" => $user]);
  }
  
  public function getCampus() {
    $user = User::find($this->idUser);
    return View::make("classrooms.campus", ["user" => $user]);
  }
  
}