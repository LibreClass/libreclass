<?php

class ClassesGroupController extends \BaseController
{

  private $idUser;

  public function __construct()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  /**
   * Carrega view para agrupar ofertas.
   * @param  String $idClass Id da turma.
   * @return View
   */
  public function loadClassGroup($idClass)
  {
    $classe = Classe::find(Crypt::decrypt($idClass));
    $classe->id = Crypt::encrypt($classe->id);
    $classe->idPeriodCrypt = Crypt::encrypt($classe->idPeriod);
    $classe->course = Course::find(Period::find($classe->idPeriod)->idCourse);

    $disciplines = [];
    foreach ($classe->period->disciplines as $discipline) {
      $disciplines[] = (object) [
        'name' => $discipline->name,
        'id' => Crypt::encrypt($discipline->id),
      ];
    }
    $classe->disciplines = $disciplines;

    $user = User::find($this->idUser);
    return View::make("modules.classesGroup", ['user' => $user, 'classe' => $classe]);
  }
}
