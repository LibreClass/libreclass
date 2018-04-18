<?php

class CensoController extends \BaseController {

	
	public function __construct()
	{
		$id = Session::get("user");
		if ($id == null || $id == "") {
			$this->idUser = false;
		}
		else {
			$this->idUser = Crypt::decrypt($id);
		}
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /censo
	 *
	 * @return Response
	 */
	public function getStudent()
	{
		return View::make("censo.formStudent", ['user' => User::find($this->idUser)]);//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /censo/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /censo
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /censo/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /censo/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /censo/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /censo/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}