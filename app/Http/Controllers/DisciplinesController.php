<?php namespace App\Http\Controllers;

use App\Classe;
use App\Course;
use App\Discipline;
use App\Period;
use Illuminate\Http\Request;

class DisciplinesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:IP');
	}

	/**
	 * Construção da página inicial
	 * @return type redirect
	 */
	public function index(Request $request)
	{
		$user = auth()->user();
		$courses = Course::where('institution_id', $user->id)
			->whereStatus('E')->orderBy('name')->get();

		$listCourses = [];
		foreach ($courses as $course) {
			$listCourses[$course->id] = $course->name;
		}

		return view('social.disciplines', [
			'listCourses' => $listCourses,
			'user' => $user,
			'course_id' => $request->session()->has('course_id') ? 
				$request->session()->get('course_id') : null
		]);
	}

	public function save()
	{
		$course = Course::find(request()->get("course"));
		$period = Period::where("course_id", $course->id )
			->whereId(decrypt(request()->get("period")))
			->first();

		$discipline = new Discipline;
		if (strlen(request()->get("discipline"))) {
			$discipline = Discipline::find(request()->get("discipline"));
		}

		$discipline->period_id = $period->id;
		$discipline->name = request()->get("name");
		$discipline->ementa = request()->get("ementa");
		$discipline->save();

		return redirect("/disciplines")
			->with("success", "Disciplina inserida com sucesso.");
	}

	public function postDelete()
	{
		$discipline = Discipline::find(decrypt(request()->get("input-trash")));
		if (!$discipline) {
			return redirect("/disciplines")
				->with("error", "Não foi possível excluir a disciplina.");
		}

		$class_ids = $discipline->offers()->get(['class_id'])->pluck('class_id')->all();

		$classe = Classe::whereIn('id', $class_ids)->whereStatus('E')->first();
		if ($classe) {
			return redirect('/disciplines')
				->with('error', "Não foi possível excluir. <br>Disciplina vinculada à turma <b>$classe->name</b>");
		}

		$discipline->status = "D";
		$discipline->save();

		return redirect('/disciplines')
			->with('success', 'Excluído com sucesso.');
	}

	public function getDiscipline()
	{
		$discipline = Discipline::find(decrypt(request()->get("discipline")));
		return $discipline;
	}

	public function postEdit()
	{
		$discipline = Discipline::find(decrypt(request()->get("discipline")));
		if (!isset($discipline) || empty($discipline)) {
			return redirect()->back()->with("error", "Não foi possível editar a disciplina");
		} else {
			$discipline->name = request()->get("name");
			$discipline->ementa = request()->get("ementa");
			$discipline->save();
			return redirect()->back()->with("success", "Disciplina editada com sucesso!");
		}
	}

	/**
	 * Lista os periodos para mostrar em um select
	 *
	 * @return array [id=>value]
	 */
	public function listPeriods()
	{
		$periods = Period::where("course_id", request()->get("course"))->whereStatus("E")->get();
		foreach( $periods as $period )
			$period->id = encrypt($period->id);

		return $periods;
	}

	public function list()
	{
		$disciplines = collect();

		$periods = Period::whereCourseId(request()->course)
			->with(['disciplines' => function($query) {
				$query->whereStatus('E');
			}])
			->get();

		foreach ($periods as $period) {
			foreach ($period->disciplines as $discipline) {
				$disciplines->push(optional((object) [
					'id' => $discipline->id,
					'name' => $discipline->name,
					'period' => $period->name,
				]));
			}
		}

		return view("social.disciplines.list", [ "disciplines" => $disciplines ]);
	}

	public function getEmenta() {
		$discipline = Discipline::find(decrypt(request()->get("offer")));

		return $discipline;
	}

}
