<?php

class AttendsController extends \BaseController
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

	public function postByClasse()
	{
		if(!Input::has('classe_id')) {
			return ['status' => 0];
		}
		$classe_id = Crypt::decrypt(Input::get('classe_id'));
		$attends = DB::select("
			SELECT Users.name as user_name, Attends.id as attend_id, Users.id as user_id
			FROM Classes, Offers, Units, Attends, Users
			WHERE Classes.id = Offers.idClass
				AND Offers.id = Units.idOffer
				AND Units.id = Attends.idUnit
				AND Users.id = Attends.idUser
				AND Classes.id = $classe_id
				AND Attends.status = 'M'
			GROUP BY Users.id
		");

		foreach($attends as $attend) {
			$attend->user_id = Crypt::encrypt($attend->user_id);
			$attend->attend_id = Crypt::encrypt($attend->attend_id);
		}

		return ['attends' => $attends];
	}
}
