<?php

class PermissionController extends \BaseController {

	public function __construct()
  {
    $id = Session::get("user");
    if ( $id == null || $id == "" )
      $this->idUser = false;
    else
      $this->idUser = Crypt::decrypt($id);
  }

	public function getIndex() {
		if ( Session::has("redirect") )
			return Redirect::to(Session::get("redirect"));

		$friends = DB::select("SELECT Users.id, Users.name, Users.enrollment FROM Relationships, Users WHERE Relationships.idUser=? AND Relationships.idFriend=Users.id", [$this->idUser]);

		$listfriends = [];
		$keys = [];
		foreach($friends as $friend) {
			$keys[$friend->id] = Crypt::encrypt($friend->id);
			$listfriends[$keys[$friend->id]] = $friend->name;
		}

		$ctrls = Ctrl::where("idUser", $this->idUser)->get();
		$listmodules = [];
		foreach($ctrls as $ctrl)
			$listmodules[$ctrl->idModule] = Module::find($ctrl->idModule)->name;

		$user = User::find($this->idUser);
		Session::put("type", $user->type);

		$adminers = DB::select("SELECT Users.id, Users.name, Users.email, Users.enrollment "
									."FROM Controllers, Adminers, Users "
									."WHERE Controllers.idUser=? AND Controllers.id=Adminers.idController AND Adminers.idUser=Users.id "
									."GROUP BY Users.id", [$this->idUser]);

		foreach($adminers as $adminer )
		{
			if( !isset($keys[$adminer->id]) )
				$keys[$adminer->id] = Crypt::encrypt($adminer->id);

			$adminer->modules = DB::select("SELECT Modules.id, Modules.name "
											."FROM Controllers, Adminers, Modules "
											."WHERE Controllers.idUser=? AND Controllers.idModule=Modules.id AND Controllers.id=Adminers.idController AND Adminers.idUser=? ", [$this->idUser, $adminer->id]);
			$adminer->id = $keys[$adminer->id];
		}


		return View::make("institution.permissions", ["user" => $user, "listfriends" => $listfriends, "listmodules" => $listmodules, "adminers" => $adminers]);
	}

	public function postIndex() {
		// return Input::all();
		$user = Crypt::decrypt(Input::get("id"));

		$ctrls = Ctrl::where("idUser", $this->idUser)->get();
		foreach( $ctrls as $ctrl )
			Adminer::where("idUser", $user)->where("idController", $ctrl->id)->delete();

		if (Input::has("ctrl"))
			foreach( Input::get("ctrl") as $ctrl) {
				$adminer = new Adminer;
				$adminer->idUser = $user;
				$adminer->idController = $ctrl;
				$adminer->save();
			}

		return Redirect::to("/permissions")->with("success", "Modificado com sucesso!");
	}

	public function postFind()
	{
		$user = User::find(Crypt::decrypt(Input::get("id")));

		$modules = DB::select("SELECT Modules.id, Modules.name "
										."FROM Controllers, Adminers, Modules "
										."WHERE Controllers.idUser=? AND Controllers.idModule=Modules.id AND Controllers.id=Adminers.idController AND Adminers.idUser=? ", [$this->idUser, $user->id]);
		$usermodules = [];
		foreach($modules as $module)
			$usermodules[] = $module->id;

		$user->modules = $usermodules;
		unset($user->id);
		unset($user->password);
		unset($user->created_at);
		unset($user->updated_at);
		unset($user->birthdate);
		unset($user->institution);
		unset($user->course);
		unset($user->type);
		unset($user->gender);
		unset($user->photo);
		unset($user->idCity);
		unset($user->formation);

		return $user;
	}
}
