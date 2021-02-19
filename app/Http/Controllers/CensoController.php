<?php namespace App\Http\Controllers;

class CensoController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	/**
	 * Display a listing of the resource.
	 * GET /censo
	 *
	 * @return Response
	 */
	public function student()
	{
		return view("censo.formStudent", ['user' => auth()->user()]);//
	}
}