<?php namespace App\Http\Controllers;

use DB;
use App\Lecture;
use App\Unit;
use App\Offer;
use App\Attest;
use App\FinalExam;
use App\Attend;
use App\User;

class LecturesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:P');
	}

	public function getIndex()
	{
		$user = auth()->user();
		$lectures = Lecture::where("user_id", auth()->id())
			->orderBy("order")
			->get()
			->filter(function($value, $key) {
				return $value->offer->classe->status == 'E';
			})
			->values();

		return view("offers.teacher", ["user" => $user, "lectures" => $lectures]);
	}

	public function getFinalreport($offer = "")
	{
		if (auth()->id()) {
			$user = auth()->user();
		}
		$offer = Offer::find(decrypt($offer));
		$course = $offer->getCourse();
		$qtdLessons = $offer->qtdLessons();

		$lessons = $offer->lessons();

		$unit_ids = Unit::where('offer_id', $offer->id)->get(['id'])->pluck('id')->all();
		$user_ids = Attend::whereIn('unit_id', $unit_ids)->whereStatus('M')->get(['user_id'])->pluck('user_id')->all();
		$alunos = User::whereIn('id', $user_ids)->orderBy('name')->get(['id', 'name']);

		$units = Unit::where("offer_id", $offer->id)->get();

		foreach ($alunos as $aluno) {
			$aluno->absence = $offer->qtdAbsences($aluno->id);

			//Obtém os atestados e quantidade
			$attests = Attest::where('student_id', $aluno->id)->get();
			$qtdAttests = 0;
			foreach($lessons as $lesson) {
				foreach($attests as $attest) {
					$attest->dateFinish = date('Y-m-d', strtotime($attest->date. '+ '. ($attest->days - 1) .' days'));
					//If true, aluno possui um atestado para o dia da aula.
					if (($lesson->date >= $attest->date) && ($lesson->date <= $attest->dateFinish))
					{
						$qtdAttests++;
					}
				}
			}
			$aluno->absence -= $qtdAttests;


			$aluno->averages = [];
			$sum = 0.;
			foreach ($units as $unit) {
				$exam = $unit->getAverage($aluno->id);

				$arr_tmp = $aluno->averages;
				if ($exam[1] !== null) {
					$arr_tmp[$unit->value] = $exam[0] < $course->average ? $exam[1] : $exam[0];
				} else {
					$arr_tmp[$unit->value] = $exam[0];
				}
				$aluno->averages = $arr_tmp;

				$sum += $aluno->averages[$unit->value];
			}
			$aluno->med = $sum / count($units);

			if ($aluno->med >= $course->average) {
				$aluno->rec = "-";
				$aluno->result = "Ap. por nota";
				$aluno->status = "label-success";
			} else {
				$rec = FinalExam::where("offer_id", $offer->id)->where("user_id", $aluno->id)->first();
				$aluno->rec = $rec ? $rec->value : "0.00";
				if ($aluno->rec >= $course->average_final) {
					$aluno->result = "Aprovado";
					$aluno->status = "label-success";
				} else {
					$aluno->result = "Rep. por nota";
					$aluno->status = "label-danger";
				}
			}
			$qtdLessons = $qtdLessons ? $qtdLessons : 1; /* evitar divisão por zero */
			if ($aluno->absence / $qtdLessons * 100. > $course->absent_percent) {
				$aluno->result = "Rep. por falta";
				$aluno->status = "label-danger";
			}
		}

		return view("modules.disciplines.finalreport", [
			"user" => $user,
			"units" => $units,
			"students" => $alunos,
			"offer" => $offer,
			"qtdLessons" => $qtdLessons,
			"course" => $course,
		]);
	}

	public function getFrequency($offer)
	{
		$user = auth()->user();
		$offer = Offer::find(decrypt($offer));
		if ($offer->getLectures()->user_id != auth()->id()) {
			return redirect("/lectures")->with("error", "Você não tem acesso a essa página");
		}
		$units = Unit::where("offer_id", $offer->id)->get();
		$students = DB::select("SELECT distinct
				users.id,
				users.name
			from
				users,
				attends,
				units
			where
				units.offer_id=? and
				attends.unit_id=units.id and
				attends.user_id=users.id and
				attends.status = 'm'
			-- group by users.id
			order by users.name", [$offer->id]);

		return view('modules.frequency', [
			'user' => $user,
			'offer' => $offer,
			'units' => $units,
			'students' => $students,
		]);
	}

	public function postSort()
	{
		foreach (request()->get("order") as $key => $value) {
			Lecture::where('offer_id', decrypt($value))->where('user_id', auth()->id())->update(["order" => $key + 1]);
		}
	}
}
