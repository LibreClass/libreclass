<?php namespace App\Http\Controllers;

use DB;
use Log;
use App\Lesson;
use App\Unit;
use App\Attest;
use App\Offer;
use App\Attend;
use App\Frequency;

class LessonsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:IP');
	}

	public function getIndex()
	{
		$user = auth()->user();
		$lesson = Lesson::find(decrypt(request()->get("l")));

		$frequencies = Frequency::whereHas('attend', function ($query){
			$query->with(['user:id,name', 'unit:id,offer_id'])
				->where('status', '<>', 'T');
		})->where('lesson_id', $lesson->id)->get();

		$students = $frequencies->map(function ($item){
			return (object)[
				'name' => $item->attend->user->name,
				'attend_id' => $item->attend->id,
				'value' => $item->value,
				'offer_id' => $item->attend->unit->offer_id,
				'user_id' => $item->attend->user->id,
				'unit_id' => $item->attend->unit_id,
			];
		})->sortBy('name');

		//Obtém todas a aulas da oferta da aula para calcular atestados;

		foreach ($students as $student) {
			//Obtém os atestados
			$allLessons = Unit::find($student->unit_id)->getOffer()->lessons();
			$attests = Attest::where('student_id',$student->user_id)->get();
			$qtdAttests = 0;
			foreach($attests as $attest) {
				$attest->dateFinish = date('Y-m-d', strtotime($attest->date. '+ '. ($attest->days - 1) .' days'));

				//If true, aluno possui um atestado para o dia da aula.
				if (($lesson->date >= $attest->date) && ($lesson->date <= $attest->dateFinish))
				{
					$student->attest = true;
				}

				foreach($allLessons as $tmpLesson) {
					if (($tmpLesson->date >= $attest->date) && ($tmpLesson->date <= $attest->dateFinish)) {
						$qtdAttests++;
					}
				}
			}

			$offer = Offer::find($student->offer_id);

			$accumulated_faults = Unit::where('offer_id', $student->offer_id)
				->with(['attend' => function ($query) use ($student){
					$query->where('user_id', $student->user_id)
						->with(['frequencies' => function ($query){
							$query->where('value', 'F');
						}]);
				}])->get();

			$frequency = $accumulated_faults[0]->attend->frequencies->count();

			$student->maxlessons = $offer->maxlessons;
			$student->qtd = $frequency - $qtdAttests;
		}
		return view("modules.lessons", ["user" => $user, "lesson" => $lesson, "students" => $students]);
	}

	public function anyNew()
	{
		$unit = Unit::find(decrypt(request()->get("unit")));

		$date = date("Y-m-d");
		if(request()->has("date-year") && request()->has("date-month") && request()->has("date-day")) {
			$date = request()->get("date-year") . "-" . request()->get("date-month") . "-" . request()->get("date-day");
		}

		$lesson = new Lesson;
		$lesson->unit_id = $unit->id;
		$lesson->date = $date;
		$lesson->title = "Sem título";
		$lesson->save();
		$lessons_count = count(Lesson::where('unit_id', $unit->id)->get());

		$attends = Attend::where("unit_id", $unit->id)->get();
		foreach ($attends as $attend) {
			$frequency = new Frequency;
			$frequency->attend_id = $attend->id;
			$frequency->lesson_id = $lesson->id;
			$frequency->value = "P";
			$frequency->save();
		}

		if ($unit->offer->grouping == 'M') {
			if(request()->has('slaves')) {
				$slaves = request()->get('slaves', []);
				$offers = Offer::whereIn('id', $slaves)->get();
			}
			else {
				$offers = $unit->offer->slaves;
			}

			foreach ($offers as $offer) {
				$unit_slave = $offer->units()->where('value', $unit->value)->first();
				$lessons_count_slave = count(Lesson::where('unit_id', $unit_slave->id)->get());
				if ($lessons_count_slave >= $lessons_count) {
					continue;
				}
				$lesson_slave = new Lesson;
				$lesson_slave->unit_id = $unit_slave->id;
				$lesson_slave->date = $date;
				$lesson_slave->title = $lesson->title;
				Log::info('Lesson Slave', [$lesson_slave]);
				$lesson_slave->save();

				$attends_slaves = Attend::where("unit_id", $unit_slave->id)->get();
				foreach ($attends_slaves as $attend_slave) {
					$frequency_slave = new Frequency;
					$frequency_slave->attend_id = $attend_slave->id;
					$frequency_slave->lesson_id = $lesson_slave->id;
					$frequency_slave->value = "P";
					$frequency_slave->save();
				}
			}
		}

		$lesson = (object) $lesson->toArray();
		$lesson->id = encrypt($lesson->id);

		if(request()->isMethod('post')) {
			return ['status'=> 1, 'lesson'=> $lesson];
		}

		return redirect("/lessons?l=" . $lesson->id)
			->with("success", "Uma nova aula foi criada com sucesso.");
	}

	public function postSave()
	{
		$lesson = Lesson::find(decrypt(request()->get("l")));

		$lesson->date = sprintf(
			'%04d-%02d-%02d',
			request()->get("date-year"),
			request()->get("date-month"),
			request()->get("date-day")
		);
		$lesson->title = request()->get("title");
		$lesson->description = request()->get("description");
		$lesson->goals = request()->get("goals");
		$lesson->content = request()->get("content");
		$lesson->methodology = request()->get("methodology");
		$lesson->resources = request()->get("resources");
		$lesson->valuation = request()->get("valuation");
		$lesson->estimatedTime = request()->get("estimatedTime");
		$lesson->keyworks = request()->get("keyworks");
		$lesson->bibliography = request()->get("bibliography");
		$lesson->notes = request()->get("notes");
		$lesson->save();

		$unit = DB::select("SELECT units.id, units.status
			FROM units, lessons
			WHERE units.id = lessons.unit_id AND
				lessons.id=?", [$lesson->id]);

		return redirect("/lectures/units?u=" . encrypt($unit[0]->id))->with("success", "Aula atualizada com sucesso");
	}

	public function anyFrequency()
	{
		$attend = Attend::find(decrypt(request()->get("attend_id")));
		$lesson_id = decrypt(request()->get("lesson_id"));
		$value = request()->get("value") == "P" ? "F" : "P";

		$offer_id = DB::select("SELECT units.offer_id
			FROM lessons, units
			WHERE
				lessons.id = ? AND
				lessons.unit_id = units.id",
			[$lesson_id]
		)[0]->offer_id;

		$status = Frequency::where("attend_id", $attend->id)
			->where("lesson_id", $lesson_id)
			->update(["value" => $value]);

		$offer = Offer::find($offer_id);

		$frequency = collect(DB::select("SELECT count(*) as qtd
			from
				units,
				attends,
				frequencies
			where
				units.offer_id = ? and
				units.id = attends.unit_id and
				attends.user_id = ? and
				attends.id = frequencies.attend_id and
				frequencies.value = 'F'",
			[$offer_id, $attend->user_id]
		))->first();

		$this->slavesFrequency($attend->id, $lesson_id, $value);

		return [
			'status' => $status,
			'value' => $value,
			'frequency' => sprintf("%d (%.1f %%)", $frequency->qtd, 100. * $frequency->qtd / $offer->maxlessons),
		];
	}

	/**
	 * Replica a frequência para as ofertas slaves. A frequência é replicada por
	 * aluno por aula em oferta. É verificado se o aluno existe nas ofertas slaves.
	 *
	 * @param  [type] $attend_id [description]
	 * @param  [type] $lesson_id [description]
	 * @param  [type] $value    [description]
	 * @return [type]           [description]
	 */
	private function slavesFrequency($attend_id, $lesson_id, $value)
	{
		if (Attend::find($attend_id)->getUnit()->offer->grouping != 'M') {
			return;
		}
		$unit = Attend::find($attend_id)->getUnit();
		$slaveOffers = Offer::where('offer_id', $unit->offer->id)->get();
		$groupLesson = Lesson::find($lesson_id);
		$student = Attend::find($attend_id)->getUser();


		foreach($slaveOffers as $o) {
			$o_unit = Unit::where('offer_id', $o->id)
				->where('value', $unit->value)
				->first();
			$lessons = Lesson::where("unit_id", $o_unit->id)
				->where('date', $groupLesson->date)
				->whereStatus('E')
				->orderBy("date", "desc")
				->orderBy("id", "desc")
				->get();

			foreach ($lessons as $key => $lesson) {
				$o_attend = Attend::where('user_id', $student->id)
					->where('unit_id', $o_unit->id)
					->first();

				Frequency::where("attend_id", $o_attend->id)
					->where("lesson_id", $lesson->id)
					->update(["value" => $value]);
			}
		}
	}

	public function postDelete()
	{
		$lesson = Lesson::find(decrypt(request()->get("input-trash")));

		$unit = DB::select("SELECT units.id, units.status
			FROM units, lessons
			WHERE units.id = lessons.unit_id AND
				lessons.id=?", [$lesson->id]);

		if ($unit[0]->status == 'D') {
			return redirect("/lectures/units?u=" . encrypt($unit[0]->id))->with("error", "Não foi possível deletar.<br>Unidade desabilitada.");
		}
		if ($lesson) {
			$lesson->status = "D";
			$lesson->save();
			return redirect("/lectures/units?u=" . encrypt($unit[0]->id))->with("success", "Aula excluída com sucesso!");
		} else {
			return redirect("/lectures/units?u=" . encrypt($unit[0]->id))->with("error", "Não foi possível deletar");
		}
	}

	public function getInfo()
	{
		$lesson = Lesson::find(decrypt(request()->get("lesson")));
		$lesson->date = date("d/m/Y", strtotime($lesson->date));
		return $lesson;
	}

	/**
	 * Faz uma cópia de uma aula com ou sem frequecia
	 *    1 - cópia para a mesma unidade sem frequencia
	 *    2 - cópia para a mesma unidade com frequencia
	 *    3 - cópia para uma outra unidade sem frequencia
	 *
	 * @return type
	 */
	public function anyCopy()
	{
		$lesson = Lesson::find(decrypt(request()->get("lesson")));
		$auth = DB::select("SELECT count(*) as qtd
			from units, lectures
			where
				units.id=? and
				units.offer_id=lectures.offer_id and
				lectures.user_id=?", [$lesson->unit_id, auth()->id()])[0]->qtd;
		if (!$auth) {
			return Response::JSON(false);
		}

		$copy = $lesson->replicate();
		if (request()->get("type") == 3) {
			$unit = Unit::where("offer_id", decrypt(request()->get("offer")))->whereStatus("E")->orderBy("value", "desc")->first();
			$copy->unit_id = $unit->id;
			$copy->save();

			$attends = Attend::where("unit_id", $unit->id)->get();
			foreach ($attends as $attend) {
				$frequency = new Frequency;
				$frequency->attend_id = $attend->id;
				$frequency->lesson_id = $copy->id;
				$frequency->value = "P";
				$frequency->save();
			}
		} else {
			$copy->save();
			$frequencies = Frequency::where("lesson_id", $lesson->id)->get();
			foreach ($frequencies as $frequency) {
				$frequency = $frequency->replicate();
				$frequency->lesson_id = $copy->id;
				if (request()->get("type") == 1) {
					$frequency->value = "P";
				}

				$frequency->save();

			}
			$copy->id = encrypt($copy->id);
			$copy->date = date("d/m/Y", strtotime($copy->date));
			return $copy;
		}
	}

	/**
	 * seleciona as ofertas ministradas pelo professor que está logado
	 *
	 * @return lista das ofertas
	 */
	public function postListOffers()
	{
		$offers = DB::select("SELECT
				offers.id,
				disciplines.name,
				classes.class,
				periods.name as `periodName`,
				courses.name as `courseName`
			FROM lectures, offers, classes, disciplines, periods, courses
			WHERE
				lectures.user_id=? AND
				lectures.offer_id=offers.id AND
				offers.class_id=classes.id AND
				offers.discipline_id=disciplines.id AND
				disciplines.period_id=periods.id AND
				periods.course_id=courses.id",
			[auth()->id()]);

		foreach ($offers as $offer) {
			$offer->id = encrypt($offer->id);
		}

		return $offers;
	}

	public function anyDelete()
	{
		Lesson::find(decrypt(request()->get("input-trash")))->delete();
		return redirect()->back()->with("alert", "Aula excluída!");
	}
}
