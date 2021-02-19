<?php namespace App\Http\Controllers;

use App\Attend;
use App\Classe;
use App\Course;
use App\Discipline;
use App\Offer;
use App\Period;
use App\Unit;
use DB;
use Exception;
use Illuminate\Support\Arr;

class ClassesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:IP');
	}

	public function index()
	{
		$user = auth()->user();
		$courses = Course::where('institution_id', $user->id)
			->where('status', 'E')
			->orderBy('name')->get();
		$listPeriod = [];
		$listPeriodLetivo = [];
		$year = (int) request()->get('year', date('Y'));

		$list_classes = [];
		foreach ($courses as $course) {
			$periods = Period::where("course_id", $course->id)->orderBy("name")->get();
			foreach ($periods as $period) {
				$listPeriod[$course->name][encrypt($period->id)] = $period->name;

				$classes = Classe::where('period_id', $period->id)
					->whereIn('school_year', [$year, $year-1])
					->where('status', '!=', 'D')
					->get();
				foreach ($classes as $classe) {
					$list_classes[] = (object) [
						'id' => $classe->id,
						'period' => $period->name,
						'classe_name' => $classe->name,
						'school_year' => $classe->school_year,
						'classe' => $classe->class,
						'name' => $course->name,
						'status' => $classe->status,
					];
				}
			}
		}

		$atual_classes = Arr::where($list_classes, function($classe, $key) use ($year) {
			return $classe->school_year == $year;
		});

		$previous_classes = Arr::where($list_classes, function($classe, $key) use ($year) {
			return $classe->school_year == $year-1;
		});

		return view('modules.classes', [
			'listPeriod' => $listPeriod,
			'user' => $user,
			'classes' => $list_classes,
			'atual_classes' => $atual_classes,
			'previous_classes' => $previous_classes,
			'school_year' => $year,
		]);
	}

	public function classesByYear()
	{
		$user = auth()->user();
		$year = request()->has('year') ? (int) request()->get('year') : (int) date('Y');
		// $year_previous = $year - 1;

		$classes = DB::select(
			"SELECT classes.id as id, periods.name as period,
			concat('[', classes.class, '] ', classes.name) as classe, classes.status as status
			from courses, periods, classes
			where courses.institution_id=? and
			courses.status = 'e' and
			classes.status <> 'd' and
			classes.school_year=$year and
			periods.course_id=courses.id and
			classes.period_id=periods.id", [$user->id]
		);

		return [
			'classes' => $classes,
		];
	}

	public function getPanel()
	{
		if (auth()->id()) {
			$user = auth()->user();
			$courses = Course::where('institution_id', auth()->id())
				->where('status', 'E')
				->orderBy('name')
				->get();
			$listCourses = [];
			foreach ($courses as $course) {
				$listCourses[encrypt($course->id)] = $course->name;
			}
			return view('modules.panel', [
				'listCourses' => $listCourses,
				'user' => $user,
			]);
		} else {
			return redirect('/');
		}
	}

	public function listdisciplines()
	{
		if (request()->has("flag")) {
			$offers = Offer::where("class_id", decrypt(request()->get("classe_id")))->get();
			$registered_disciplines_ids = [];

			foreach ($offers as $offer) {
				$registered_disciplines_ids[] = $offer->discipline_id;
			}

			$disciplines = Discipline::where("period_id", decrypt(request()->get("period_id")))
				->whereStatus('E')
				->whereNotIn('id', $registered_disciplines_ids)
				->get();
		} else {
			$disciplines = Discipline::where("period_id", decrypt(request()->get("period")))
				->whereStatus('E')
				->get();
		}
		return view("modules.disciplines.listOffer", ["disciplines" => $disciplines]);
	}

	public function postNew()
	{
		$class = new Classe;
		$class->period_id = decrypt(request()->get("period"));
		$class->name = request()->get("name");
		$class->school_year = request()->get("school_year");
		$class->class = request()->get("class");
		$class->status = 'E';
		$class->save();
		foreach (request()->all() as $key => $value) {
			if (strstr($key, "discipline_") != false) {
				$offer = new Offer;
				$offer->class_id = $class->id;
				$offer->discipline_id = decrypt($value);
				$offer->save();

				$unit = new Unit;
				$unit->offer_id = $offer->id;
				$unit->value = "1";
				$unit->calculation = "A";
				$unit->save();
			}
		}
		return redirect("classes")->with("success", "Turma criada com sucesso!");
	}

	public function getInfo()
	{
		$class = Classe::find(decrypt(request()->get("classe")));
		$class->period_idCrypt = encrypt($class->period_id);
		$class->course = Course::find(Period::find($class->period_id)->course_id);

		return $class;
	}

	public function postEdit()
	{
		$class = Classe::find(decrypt(request()->get("classId")));
		if ($class) {
			$class->name = request()->get("class");
			$class->save();
			foreach (request()->all() as $key => $value) {
				if (strstr($key, "discipline_") != false) {
					$offer = new Offer;
					$offer->class_id = $class->id;
					$offer->discipline_id = decrypt($value);
					$offer->save();

					$unit = new Unit;
					$unit->offer_id = $offer->id;
					$unit->value = "1";
					$unit->calculation = "A";
					$unit->save();
				}
			}
			return redirect("/classes")->with("success", "Classe editada com sucesso!");
		}
		return redirect("/classes")->with("error", "Não foi possível editar!");
	}

	public function postDelete()
	{
		$class = Classe::find(decrypt(request()->get("input-trash")));
		if ($class) {
			$class->status = "D";
			$class->save();
			return redirect("/classes")->with("success", "Excluído com sucesso!");
		} else {
			return redirect("/classes")->with("error", "Não foi possível excluir!");
		}
	}

	public function postChangeStatus()
	{
		$id = decrypt(request()->get("key"));

		$class = Classe::find($id);
		if ($class) {
			$class->status = request()->get("status");
			$class->save();
			if ($class->status == "E") {
				return redirect()->back()->with("success", "Turma ativada com sucesso!");
			}
			else if ($class->status == "F") {
				return redirect()->back()->with("success", "Turma encerrada com sucesso!");
			} else {
				return redirect()->back()
					->with("success", "Turma bloqueada com sucesso!<br/>Turmas bloqueadas são movidas para o final.");
			}
		} else {
			return redirect()->back()->with("error", "Não foi possível realizar essa operação!");
		}
	}

	public function anyListOffers()
	{
		$offers = Offer::where("class_id", decrypt(request()->get("class")))->get()->toArray();
		$student_id = decrypt(request()->get("student"));

		foreach ($offers as &$offer) {
			$unit_ids = Unit::whereOfferId($offer['id'])->get(['id'])->pluck('id')->toArray();
			$offer['status'] = Attend::whereIn('unit_id', $unit_ids)->whereUserId($student_id)->count();

			$offer['name'] = Discipline::find($offer['discipline_id'])->name;
			$offer['id'] = encrypt($offer['id']);
		}

		return $offers;
	}

	/**
	 * Faz uma busca por todos os cursos da instituição e suas unidades ativas
	 *
	 *
	 * @return json com cursos e unidades
	 */
	public function postListUnits($status = 1)
	{
		$status = ((int) $status ? "E" : "D");

		$courses = Course::where("institution_id", auth()->id())->whereStatus("E")->get();
		foreach ($courses as $course) {
			$course->units = DB::select("SELECT units.value
				FROM periods, classes, offers, units
				WHERE periods.course_id=?
					AND periods.id=classes.period_id
					AND classes.id=offers.class_id
					AND classes.status='E'
					AND offers.id=units.offer_id
					AND units.status=?
				GROUP BY units.value", [$course->id, $status]);

			$course->id = encrypt($course->id);
		}

		return $courses;
	}

	public function postBlockUnit()
	{
		$course = Course::find(decrypt(request()->get('course')));
		if ($course->institution_id != auth()->id()) {
			throw new Exception('Usuário inválido');
		}

		$periods = Period::where('course_id', $course->id)->get();
		foreach ($periods as $period) {
			$classes = Classe::where('period_id', $period->id)->get();
			foreach ($classes as $class) {
				$offers = Offer::where('class_id', $class->id)->get();
				foreach ($offers as $offer) {
					Unit::where('offer_id', $offer->id)
						->whereValue(request()->get('unit'))
						->whereStatus('E')
						->update(['status' => 'D']);
				}

			}
		}
	}

	public function postUnblockUnit()
	{
		$course = Course::find(decrypt(request()->get('course')));
		if ($course->institution_id != auth()->id()) {
			throw new Exception('Usuário inválido');
		}

		$periods = Period::where('course_id', $course->id)->get();
		foreach ($periods as $period) {
			$classes = Classe::where('period_id', $period->id)->get();
			foreach ($classes as $class) {
				$offers = Offer::where('class_id', $class->id)->get();
				foreach ($offers as $offer) {
					Unit::where('offer_id', $offer->id)
						->whereValue(request()->get('unit'))
						->whereStatus('D')
						->update(array('status' => 'E'));
				}

			}
		}
	}

	public function anyCreateUnits()
	{
		$s_attends = false;
		$course = Course::find(decrypt(request()->get("course")));
		if ($course->institution_id != auth()->id()) {
			throw new Exception("Você não tem permissão para realizar essa operação");
		}

		$offers = DB::select("SELECT Offers.id FROM Periods, Classes, Offers "
			. "WHERE Periods.course_id=? AND Periods.id=Classes.period_id AND Classes.id=Offers.class_id", [$course->id]);

		if (!count($offers)) {
			throw new Exception("Não possui ofertas nesse curso.");
		}

		foreach ($offers as $offer) {
			$old = Unit::where("offer_id", $offer->id)->orderBy("value", "desc")->first();

			$unit = new Unit;
			$unit->offer_id = $old->offer_id;
			$unit->value = $old->value + 1;
			$unit->calculation = $old->calculation;
			$unit->save();

			$attends = Attend::where("unit_id", $old->id)->get();

			$s_attends = false;
			foreach ($attends as $attend) {
				if (!$s_attends) {
					$s_attends = "INSERT IGNORE INTO Attends (unit_id, user_id) VALUES ($unit->id, $attend->user_id)";
				} else {
					$s_attends .= ", ($unit->id, $attend->user_id)";
				}
			}

			if ($s_attends) {
				DB::insert($s_attends);
			}
		}
	}

	public function postCopyToYear()
	{
		if(!request()->has('classes')) {
			return [
				'status' => 0,
				'message' => 'Não foi possível copiar as classes',
			];
		}

		$tmp_groups = [];
		foreach(request()->get('classes') as $in) {
			$classe = Classe::find($in['classe_id']);

			$new_classe = new Classe();
			$new_classe->period_id = $classe->period_id;
			$new_classe->name = $classe->name;
			$new_classe->school_year = $classe->school_year + 1;
			$new_classe->class = '';
			$new_classe->status = 'E';

			$new_classe->save();

			if ($in['with_offers'] == 'false') {
				continue;
			}

			$offers = Offer::where('class_id', $classe->id)->get();
			$tmp_groups = [];
			foreach($offers as $offer) {
				if ($offer->grouping != 'M') { // Diferente de master, porque a master é criada quando há slaves.
					if ($offer->grouping == 'S') { // Criar grupo de ofertas / Oferta Slave
						if (isset($tmp_groups[$offer->offer_id])) { // Se o grupo já foi criado
							$this->createOffer($offer, $new_classe, $tmp_groups[$offer->offer_id]); //Cria oferta apontado para o novo grupo existente.
						} else {
							$group = Offer::find($offer->offer_id);
							$tmp_groups[$offer->offer_id] = $this->createOffer($group, $new_classe, null); //Cria grupo de oferta e guarda o id;
							$this->createOffer($offer, $new_classe, $tmp_groups[$offer->offer_id]);
						}
					} else {
						$this->createOffer($offer, $new_classe, null); //Duplica oferta para o novo ano letivo
					}
				}
			}
		}

		return ['status' => 1, 'tmp' => $tmp_groups];
	}

	private function createOffer($offer, $classe, $group)
	{
		$new_offer = new Offer();
		$new_offer->class_id = $classe->id;
		$new_offer->discipline_id = $offer->discipline_id;
		$new_offer->classroom = $offer->classroom;
		$new_offer->day_period = $offer->day_period;
		$new_offer->maxlessons = $offer->maxlessons;
		$new_offer->type_final = '';
		$new_offer->comments = $offer->comments;
		$new_offer->status = 'E';
		$new_offer->grouping = $offer->grouping;

		if ($offer->grouping == "S") {
			$new_offer->offer_id = $group;
		}

		$new_offer->save();
		//Cria uma unidade
		$unit = new Unit();
		$unit->offer_id = $new_offer->id;
		$unit->value = 1;
		$unit->save();

		return $new_offer->id;
	}
}
