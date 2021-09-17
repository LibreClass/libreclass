<?php namespace App\Http\Controllers;

use App\UserConfig;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		if (auth()->guest()) {
			return view('home');
		} else {
			$config = UserConfig::getConfig();
			session(['period' => $config->period]);
			return view('social.home');
		}

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
