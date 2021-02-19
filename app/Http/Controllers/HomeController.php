<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		if (auth()->guest()) {
			return view('home');
		}

		return view('social.home');
	}

	public function ie()
	{
		return view('ie');
	}

	public function student()
	{
		return view('students.disciplines');
	}
}
