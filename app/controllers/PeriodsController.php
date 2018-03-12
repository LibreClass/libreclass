<?php

class PeriodsController extends \BaseController {
	private $idUser;

	public function __construct()
	{
		$id = Session::get("user");
		if ($id == null || $id == "" ) {
			$this->idUser = false;
		}
		else {
			$this->idUser = Crypt::decrypt($id);
		}
	}

	public function getIndex()
	{
		if ($this->idUser) {
			$user = User::find($this->idUser);
			$courses = Course::where("idInstitution", $this->idUser)->whereStatus("E")->orderBy("name")->get();
			$listCourses = [];
			foreach ($courses as $course) {
				$listCourses[$course->id] = $course->name;
			}
			return View::make("social.periods", ["listCourses" => $listCourses, "user" => $user]);
		}
		else {
//      return Redirect::guest("/");
		}
	}

	public function anyList() {
		if ($this->idUser) {
			$periods = Period::where('idCourse', Input::get('course_id'))->where('status', 'E')->get();
			if($periods) {
	      return View::make("social.periods.list", [ "periods" => $periods ]);
	    }
		}
	}

	public function anySave() {
		if ($this->idUser) {

			$period = new Period;
			if(Input::has('period_id')) {
				$period = Period::find(Input::get('period_id'));
			}
			$period->name = Input::get('name');
			$period->idCourse = Input::get('course_id');
			// $period->progression_value = Input::get('progression_value');
			// dd(Input::all());
			$period->save();

			return Redirect::back()->with("success", "Período salvo com sucesso!");
		}
	}

	public function anyRead() {
		if ($this->idUser) {
			$period = Period::find(Input::get('period_id'));
			if($period) {
	      return ['period' => $period];
	    }
		}
	}

}
