<?php namespace App\Http\Controllers;

class HelpController extends Controller
{
	public function getView($rota)
	{
		$blade_file = "help.$rota";
		return view($blade_file);
	}
}
