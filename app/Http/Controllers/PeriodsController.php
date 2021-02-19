<?php namespace App\Http\Controllers;

use App\Course;
use App\Period;

class PeriodsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	public function index()
	{
		$user = auth()->user();
		$courses = Course::where('institution_id', auth()->id())
			->whereStatus('E')
			->orderBy('name')
			->get();

		$listCourses = [];
		foreach ($courses as $course) {
			$listCourses[$course->id] = $course->name;
		}

		return view('social.periods', [
			'listCourses' => $listCourses,
			'user' => $user,
		]);
	}

	public function list()
	{
		$periods = Period::where('course_id', request()->get('course_id'))
			->where('status', 'E')->get();
		return view("social.periods.list", [ "periods" => $periods ]);
	}

	public function save()
	{
		$period = new Period;
		if (request()->get('period_id')) {
			$period = Period::find(request()->get('period_id'));
		}
		$period->name = request()->get('name');
		$period->course_id = request()->get('course_id');
		$period->save();

		return redirect()->back()->with('success', 'Período salvo com sucesso!');
	}

	public function read()
	{
		$period = Period::find(request()->get('period_id'));

		return [
			'period' => $period,
		];
	}

}
