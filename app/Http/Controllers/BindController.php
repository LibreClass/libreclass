<?php namespace App\Http\Controllers;

use App\Bind;
use App\Course;

class BindController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	public function link()
	{
		$user = decrypt(request()->get('user'));
		$discipline = decrypt(request()->get('discipline'));
		if (request()->get('bind') == 'true') {
			$bind = new Bind;
			$bind->user_id = $user;
			$bind->discipline_id = $discipline;
			$bind->save();
			return 'new';
		}
		else {
			Bind::where('user_id', $user)->where('discipline_id', $discipline)->delete();
		}

		return 'del';
	}

	public function list()
	{
		$teacher = decrypt(request()->get('teacher'));

		return view('modules.addTeacherDisciplineForm', [
			'courses' => Course::where('institution_id', auth()->id())->get(),
			'teacher' => $teacher,
		]);
	}
}
