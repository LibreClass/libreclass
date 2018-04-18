<?php

class BindController extends \BaseController {

	public function anyLink()
	{
		$user = Crypt::decrypt(Input::get("user"));
		$discipline = Crypt::decrypt(Input::get("discipline"));
		if ( Input::get("bind") == "true"){
			$bind = new Bind;
			$bind->idUser = $user;
			$bind->idDiscipline = $discipline;
			$bind->save();
			return "new";
		}
		else
			Bind::where("idUser", $user)->where("idDiscipline", $discipline)->delete();

		return "del";
	}

	public function anyList()
	{
		$teacher = Crypt::decrypt(Input::get("teacher"));

		return View::make("modules.addTeacherDisciplineForm",
								["courses" => Course::where("idInstitution", Crypt::decrypt(Session::get("user")))->get(),
								 "teacher" => $teacher]);
	}

}
