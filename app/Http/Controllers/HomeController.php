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
			$this::updatePeriodSession();
			return view('social.home');
		}

	}

	/**
	 * Atualiza session com nomenclatura de períodos
	 */
	public static function updatePeriodSession() {
		$config = UserConfig::getConfig();
		session(['period' => $config->period]);
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
