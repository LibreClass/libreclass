<?php namespace App\Http\Controllers;

class ClassroomController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	public function getIndex()
	{
		if (session("redirect")) {
			return redirect(session("redirect"));
		}

		$user = auth()->user();
		return view("classrooms.home", ["user" => $user]);
	}

	public function getCampus()
	{
		$user = auth()->user();
		return view("classrooms.campus", ["user" => $user]);
	}
}