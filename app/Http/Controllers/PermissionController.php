<?php namespace App\Http\Controllers;

class PermissionController extends Controller
{
	public function __construct()
  {
		$this->middleware('auth.type:I');
  }

	public function getIndex()
	{
		if ( session("redirect") )
			return redirect(session("redirect"));

		$friends = DB::select("SELECT users.id, users.name, users.enrollment from relationships, users where relationships.user_id=? and relationships.friend_id=users.id", [auth()->id()]);

		$listfriends = [];
		$keys = [];
		foreach($friends as $friend) {
			$keys[$friend->id] = encrypt($friend->id);
			$listfriends[$keys[$friend->id]] = $friend->name;
		}

		$ctrls = Ctrl::where("user_id", auth()->id())->get();
		$listmodules = [];
		foreach($ctrls as $ctrl)
			$listmodules[$ctrl->idModule] = Module::find($ctrl->idModule)->name;

		$user = auth()->user();
		Session::put("type", $user->type);

		$adminers = DB::select("SELECT users.id, users.name, users.email, users.enrollment "
									."from controllers, adminers, users "
									."where controllers.user_id=? and controllers.id=adminers.idcontroller and adminers.user_id=users.id "
									."group by users.id", [auth()->id()]);

		foreach($adminers as $adminer )
		{
			if( !isset($keys[$adminer->id]) )
				$keys[$adminer->id] = encrypt($adminer->id);

			$adminer->modules = DB::select("SELECT Modules.id, Modules.name "
											."FROM Controllers, Adminers, Modules "
											."WHERE Controllers.user_id=? AND Controllers.idModule=Modules.id AND Controllers.id=Adminers.idController AND Adminers.user_id=? ", [auth()->id(), $adminer->id]);
			$adminer->id = $keys[$adminer->id];
		}


		return view("institution.permissions", ["user" => $user, "listfriends" => $listfriends, "listmodules" => $listmodules, "adminers" => $adminers]);
	}

	public function postIndex()
	{
		// return request()->all();
		$user = decrypt(request()->get("id"));

		$ctrls = Ctrl::where("user_id", auth()->id())->get();
		foreach( $ctrls as $ctrl )
			Adminer::where("user_id", $user)->where("idController", $ctrl->id)->delete();

		if (request()->has("ctrl"))
			foreach( request()->get("ctrl") as $ctrl) {
				$adminer = new Adminer;
				$adminer->user_id = $user;
				$adminer->idController = $ctrl;
				$adminer->save();
			}

		return redirect("/permissions")->with("success", "Modificado com sucesso!");
	}

	public function postFind()
	{
		$user = User::find(decrypt(request()->get("id")));

		$modules = DB::select("SELECT Modules.id, Modules.name "
										."FROM Controllers, Adminers, Modules "
										."WHERE Controllers.user_id=? AND Controllers.idModule=Modules.id AND Controllers.id=Adminers.idController AND Adminers.user_id=? ", [auth()->id(), $user->id]);
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
		unset($user->city_id);
		unset($user->formation);

		return $user;
	}
}
