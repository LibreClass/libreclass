<?php namespace App\Http\Controllers;

use Exception;
use DB;
use PDF;
use App\Unit;
use App\Lesson;
use App\Exam;
use App\Offer;
use App\Frequency;
use App\Attest;
use App\ExamsValue;
use App\Attend;
use App\User;

class UnitsController extends Controller
{
	private $unit;

	public function __construct()
	{
		if (request()->get("u")) {
			$this->unit = Unit::find(decrypt(request()->get("u")));
		}
		$this->middleware('auth.type:IP');
	}

	public function getIndex()
	{
		if (request()->has("u")) {
			$user = auth()->user();
			$unit_current = Unit::find(decrypt(request()->get("u")));

			if ($unit_current->status == "D") {
				return redirect("lectures")->with("error", "Esta unidade está desativada");
			}

			if($unit_current->offer->classe->status == 'F') {
				return redirect("lectures")->with("error", "Não é possível acessar unidade de turmas encerradas");
			}

			$units = Unit::where("offer_id", $unit_current->offer_id)->get();
			$list_units = [];
			foreach ($units as $unit) {
				$list_units[] = $unit->value;
			}

			$lessons = Lesson::where("unit_id", $unit_current->id)->whereStatus('E')->orderBy("date", "desc")->orderBy("id", "desc")->get();
			$recovery = Exam::where("unit_id", $unit_current->id)->whereAval("R")->first();
			$exams = Exam::where("unit_id", $unit_current->id)->whereStatus('E')->whereAval("A")->orderBy("id", "desc")->get();
			return view("modules.panel", ["user" => $user,
				"list_units" => $list_units,
				"unit_current" => $unit_current,
				"lessons" => $lessons,
				"recovery" => $recovery,
				"exams" => $exams]);
		} else {
			return redirect("/");
		}
	}

	/*
	 * edita a unidade. O único atributo editável é a forma de calcular a média
	 */
	public function postEdit()
	{
			$this->unit->calculation = request()->get("calculation");
			$this->unit->save();
			return response()->json(true);
	}

	public function getNew()
	{
		if (request()->has("offer")) {
			$offer = decrypt(request()->get("offer"));
			$old = Unit::where("offer_id", $offer)->orderBy("value", "desc")->first();

			$unit = new Unit;
			$unit->offer_id = $old->offer_id;
			$unit->value = $old->value + 1;
			$unit->calculation = $old->calculation;
			$unit->save();

			$attends = Attend::where("unit_id", $old->id)->get();

			foreach ($attends as $attend) {
				$new = new Attend;
				$new->unit_id = $unit->id;
				$new->user_id = $attend->user_id;
				$new->save();
			}

			return redirect("/lectures/units?u=" . encrypt($unit->id));
		}
	}

	public function getStudent()
	{
		if (auth()->id()) {
			$user = auth()->user();

			$students = User::whereType("N")->orderby("name")->get();
			$list_students = [];
			foreach ($students as $student) {
				$list_students[encrypt($student->id)] = $student->name;
			}

			$students = DB::select("SELECT users.name as name, users.id as id FROM users, attends WHERE users.id=attends.user_id AND attends.unit_id = " . $this->unit->id . " ORDER BY users.name");

			return view("modules.units", ["user" => $user, "list_students" => $list_students, "students" => $students]);
		} else {
			return redirect("/");
		}
	}

	public function postRmstudent()
	{
		//~ return request()->all();
		$unit = decrypt(request()->get("unit"));
		$student = decrypt(request()->get("student"));

		Attend::where("unit_id", $unit)->where("user_id", $student)->delete();

		return redirect("lectures/units/student?u=" . request()->get("unit"))
			->with("success", "Aluno removido com sucesso");
	}

	public function postAddstudent()
	{
		$unit = decrypt(request()->get("unit"));
		$student = decrypt(request()->get("student"));

		$attend = Attend::where("unit_id", $unit)->where("user_id", $student)->first();
		if ($attend) {
			return redirect("lectures/units/student?u=" . request()->get("unit"))
				->with("error", "Aluno já cadastrado");
		} else {
			$attend = new Attend;
			$attend->unit_id = $unit;
			$attend->user_id = $student;
			$attend->save();
			return redirect("lectures/units/student?u=" . request()->get("unit"))
				->with("success", "Aluno cadastrado com sucesso");
		}
	}

	public function getNewunit()
	{
		return "nova unidade";
	}

	public function getReportunitz()
	{
		if (auth()->id()) {
			$user = auth()->user();

			$students = DB::select("SELECT DISTINCT users.id, users.name "
				. "from users, attends, units "
				. "where units.id=? and attends.unit_id=units.id and attends.user_id=users.id "
				. "order by users.name", [decrypt(request()->get("u"))]);

			return $students;

		}
	}

	/**
	 * Retorna um PDF com o relatório de frequência e notas
	 * @param int $unit_id - Id da unidade (unidade da disciplina ofertada)
	 * @return file
	 */
	public function getReportUnit($unit_id)
	{
			$unit = Unit::find(decrypt($unit_id));
			switch ($unit->calculation) {
				case 'S':
				case 'A':
				case 'W':
					return $this->printDefaultReport($unit);
					break;
				case 'P':
					return $this->printDescriptiveReport($unit);
					break;
				default:
					throw new Exception('Error: Unknown report type');
					break;
			}
	} //--- Imprimir PDF

	/**
	 * Imprime o relatório da oferta, acessível pelo perfil de professor e instituição, quando o método de
	 * avaliação for somatório, média aritmética ou média ponderada.
	 *
	 * @param  Unit   $unit [Unidade a gerar relatório]
	 * @return [File]       [PDF com o relatório preparado para impressão]
	 */
	private function printDefaultReport(Unit $unit)
	{
			$data = [];
			$institution = $unit->offer->classe->period->course->institution()->first();
			$institution->local = $institution->printCityState();
			$data['institution'] = $institution;
			$data['classe'] = $unit->offer->getClass();
			$data['period'] = $unit->offer->classe->period;
			$data['course'] = $unit->offer->classe->period->course;
			$data['exams'] = [];


			$offer = Offer::find($unit->offer_id);

			$students = DB::select(""
				. " SELECT DISTINCT users.id, users.name "
				. " FROM users, attends, units "
				. " WHERE units.offer_id=? AND attends.unit_id=units.id AND attends.user_id=users.id AND attends.status = 'M' "
				. " ORDER BY users.name ASC", [$offer->id]
			);
			$data['students'] = [];
			foreach ($students as $student) {
				$data['students'][] = $student;
			}

			$lessons = $unit->getLessonsToPdf();
			// Prepara o nome das aulas com a data de realização das mesmas
			$data['lessons'] = [];
			$data['lessons_notes'] = [];
			foreach ($lessons as $key => $lesson) {
				$date = explode('-', $lesson->date)[2] . '/' . explode('-', $lesson->date)[1] . '/' . explode('-', $lesson->date)[0];
				$data['lessons'][$key] = 'Aula ' . (string) ($key + 1) . ' - ' . $date;
				$data['lessons_notes'][$key] = [
					'description' => 'Aula ' . (string) ($key + 1) . ' - ' . $date,
					'title' => isset($lesson->title) && !empty($lesson->title) ? $lesson->title : 'Sem título',
					'note' => isset($lesson->notes) && !empty($lesson->notes) ? $lesson->notes : 'Sem nota de aula',
				];
				// dd($data['lessons'][$key]);
			}

			// Percorre a lista de todos os alunos
			foreach ($data['students'] as $key => $student) {

				$absences = 0;
				$data['students'][$key]->number = $key + 1;

				// Obtém frequência escolar do aluno
				$data['students'][$key]->absences = [];
				for ($i = 0; $i < count($lessons); $i++) {
					if (isset($lessons[$i])) {
						$value = Frequency::getValue($student->id, $lessons[$i]->id);
						if ($value == "F") {
							$absences++;
						}
						$data['students'][$key]->absences[$i] = ($value == "P") ? "." : $value;
					} else {
						$data['students'][$key]->absences[$i] = ".";
					}

					$attests = Attest::where('student_id',$student->id)->get();
					foreach($attests as $attest) {
						$attest->dateFinish = date('Y-m-d', strtotime($attest->date. '+ '. ($attest->days - 1) .' days'));

						//If true, aluno possui um atestado para o dia da aula.
						if (($lessons[$i]->date >= $attest->date) && ($lessons[$i]->date <= $attest->dateFinish))
						{
							$data['students'][$key]->absences[$i] = "A";
							$absences--;
						}
					}

				}

				// Quantidade total de faltas
				$data['students'][$key]->countAbsences = (string) $absences;

				$exams = $unit->getExams();
				foreach ($exams as $_key => $exam) {
					$data['exams'][$_key] = $exam;
					$data['exams'][$_key]['number'] = $_key + 1;
					$date = explode('-', $exam->date)[2] . '/' . explode('-', $exam->date)[1] . '/' . explode('-', $exam->date)[0];
					$data['exams'][$_key]['date'] = $date;
				}

				$data['students'][$key]->exams = [];

				// Inclui as avaliações realizadas pelo anulo
				foreach ($exams as $exam) {
					$data['students'][$key]->exams[] = ExamsValue::getValue($student->id, $exam->id) ? ExamsValue::getValue($student->id, $exam->id) : '-';
				}

				// Registra a média e a média final após prova de recuperação
				$average = $unit->getAverage($student->id);
				$data['students'][$key]->average = empty($average[0]) ? "-" : sprintf("%.2f", $average[0]);
				$data['students'][$key]->finalAverage = empty($average[1]) ? "-" : sprintf("%.2f", $average[1]);
			}

			$pdf = PDF::loadView('reports.arroio_dos_ratos-rs.class_diary', ['data' => $data])
				->setPaper('a4')
				->setOrientation('landscape')
				->setOption('margin-top', 5)
				->setOption('margin-right', 5)
				->setOption('margin-bottom', 5)
				->setOption('margin-left', 5);
			return $pdf->inline();
	}

	/**
	 * Imprime o relatório da oferta, acessível pelo perfil de professor e instituição, quando o método de
	 * avaliação for Parecer Descritivo.
	 *
	 * @param  Unit   $unit [Unidade a gerar relatório]
	 * @return [File]       [PDF com o relatório preparado para impressão]
	 */
	private function printDescriptiveReport(Unit $unit)
	{
			$data = [];
			$institution = $unit->offer->classe->period->course->institution()->first();
			$institution->local = $institution->printCityState();
			$data['institution'] = $institution;
			$data['classe'] = $unit->offer->getClass();
			$data['period'] = $unit->offer->classe->period;
			$data['course'] = $unit->offer->classe->period->course;

			$offer = Offer::find($unit->offer_id);

			$students = DB::select(""
				. " SELECT DISTINCT users.id, users.name "
				. " FROM users, attends, units "
				. " WHERE units.offer_id=? AND attends.unit_id=units.id AND attends.user_id=users.id AND attends.status = 'M'"
				. " ORDER BY users.name ASC", [$offer->id]
			);
			$data['students'] = [];
			foreach ($students as $student) {
				$data['students'][] = $student;
			}

			$lessons = $unit->getLessonsToPdf();

			// Prepara o nome das aulas com a data de realização das mesmas
			$data['lessons'] = [];
			$data['lessons_notes'] = [];
			foreach ($lessons as $key => $lesson) {
				$date = explode('-', $lesson->date)[2] . '/' . explode('-', $lesson->date)[1] . '/' . explode('-', $lesson->date)[0];
				$data['lessons'][$key] = 'Aula ' . (string) ($key + 1) . ' - ' . $date;
				$data['lessons_notes'][$key] = [
					'description' => 'Aula ' . (string) ($key + 1) . ' - ' . $date,
					'title' => isset($lesson->title) && !empty($lesson->title) ? $lesson->title : 'Sem título',
					'note' => isset($lesson->notes) && !empty($lesson->notes) ? $lesson->notes : 'Sem nota de aula.',
				];
				// dd($data['lessons'][$key]);
			}

			// Percorre a lista de todos os alunos
			foreach ($data['students'] as $key => $student) {
				$absences = 0;
				$data['students'][$key]->number = $key + 1;

				// Obtém frequência escolar do aluno
				$data['students'][$key]->absences = [];
				for ($i = 0; $i < count($lessons); $i++) {
					if (isset($lessons[$i])) {
						$value = Frequency::getValue($student->id, $lessons[$i]->id);
						if ($value == "F") {
							$absences++;
						}
						$data['students'][$key]->absences[$i] = ($value == "P") ? "." : $value;
					} else {
						$data['students'][$key]->absences[$i] = ".";
					}
				}

				// Quantidade total de faltas
				$data['students'][$key]->countAbsences = (string) $absences;
			}

			$unit->count_lessons = $unit->countLessons();
			$lessons = $unit->getLessons();

			$institution = $unit->offer->classe->period->course->institution()->first();
			$institution->local = $institution->printCityState();

			$exams = $unit->getExams();
			if (count($exams) == 0) {
				$data['exams'] = null;
				// throw new Exception('É necessário criar pelo menos uma <b>avaliação</b> para gerar o relatório de parecer descritivo.');
			} else {
				foreach ($exams as $exam) {
					$descriptions = $exam->descriptive_exams();
					foreach ($descriptions as $description) {
						$description->student->absence = 0;
						foreach ($lessons as $lesson) {
							$value = Frequency::getValue($description->student->id, $lesson->id);
							if ($value == 'F') {
								$description->student->absence++;
							}
						}
					}
					$data['exams'][] = ['data' => $exam, 'descriptions' => $descriptions];
				}
			}

			$data['unit'] = $unit;
			$data['discipline'] = $unit->offer->discipline->name;
			$data['teachers'] = $unit->offer->getTeachers();

			$pdf = PDF::loadView('reports.arroio_dos_ratos-rs.descriptive_exam', ['data' => $data])
				->setPaper('a4')
				->setOrientation('landscape')
				->setOption('margin-top', 5)
				->setOption('margin-right', 5)
				->setOption('margin-bottom', 5)
				->setOption('margin-left', 5);
			return $pdf->stream();
	}

}
