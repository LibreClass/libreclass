<?php namespace App\Http\Controllers;

class CSVController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	/**
	 * mostra view de importação de CSV
	 *
	 * @return type
	 */
	public function getIndex()
	{
		Session::forget('structure');
		Session::forget('attends');
		Session::forget('classes');

		return view('modules.import', ['user' => auth()->user()]);
	}

	/**
	 * Recebe CSV com lista de alunos, valida, salva na session e mostra para confirmação do usuário
	 *
	 * @return html com dados para confirmação
	 * @throws caso não esteja no padrão
	 */
	public function postIndex()
	{
			$csv = utf8_encode(file_get_contents(request()->file("csv")));
			if (!strpos($csv, "RM;RA;Nome Aluno;"))
			{
				throw new Exception("Arquivo inválido");
			}

			$course = null;
			$period = null;
			$year   = null;

			$csv = explode("\n", $csv);

			$result = [];
			$classes = [];
			foreach ($csv as $line)
			{
				$cols = explode(";", $line);
				if ($cols[0] == "Período Letivo:")
				{
					$year = $cols[1];
				}
				else if ($cols[0] == "SubModalidade:")
				{
					$course = Course::where("institution_id", auth()->id())->whereName($cols[1])->first();
					if (!$course) {
						throw new Exception("Não possui o curso: " . $cols[1]);
					}
				}
				else if ($cols[0] == "Série:")
				{
					$period = Period::where("course_id", $course->id)->whereName(explode(" - ", $cols[1])[0])->first();
					if (!$period) {
						throw new Exception("Não possui o periodo/série: " . explode(" - ", $cols[1])[0] . " $course->name");
					}
					$class = Classe::where("period_id", $period->id)->whereName($cols[3])->whereClass(date("Y"))->first();
					if (!$class) {
						$class = new Classe;
						$class->id = 0;
						$class->class = date("Y");
						$class->name = $cols[3];
						$class->period_id = $period->id;
						$class->period = $period->name;
						$classes[] = $class;
					}
				}
				elseif (is_numeric($cols[0]))
				{
					$cols[3] = firstUpper($cols[3]);
					$cols[4] = substr($cols[4], 0, 1);
					$cols[5] = explode("/", $cols[5]);
					$cols[5] = $cols[5][2] . "-" . $cols[5][1] . "-" . $cols[5][0];

					$user_ids = Relationship::where('user_id', auth()->id())
						->get(['friend_id'])
						->pluck('friend_id')->all();
					$student = User::whereIn('id', $user_ids)
						->where('enrollment', $cols[1])->first();

					$result[] = [
						$cols[1], // matricula
						$cols[3], // nome
						$cols[4], // sexo
						$cols[5], // data nascimento
						$student ? $student->id : 0, // se o estudante existe no sistema
						$class->name, // turma
						$class->id, // id turma
						$period->id // id do periodo caso precise
					];
				}
			}
			Session::put("attends", $result);
			Session::put("classes", $classes);
			return view("modules.import.classes",["user" => auth()->user(), "classes" => $classes]);
	}

	public function getConfirmClasses()
	{
		if (!session("classes")) {
			return redirect("/import")->with("error", "Algum erro aconteceu, tente novamente.");
		}

		$s_units = false;
		$classes = session("classes");
		foreach( $classes as $c )
		{
			$classe = new Classe;
			$classe->period_id = $c->period_id;
			$classe->class = $c->class;
			$classe->name = $c->name;
			$classe->save();

			$disciplines = Discipline::where("period_id", $c->period_id)->get();
			foreach ( $disciplines as $discipline) {
				$offer = new Offer;
				$offer->discipline_id = $discipline->id;
				$offer->classroom = "";
				$offer->class_id = $classe->id;
				$offer->save();

				if( !$s_units ) {
					$s_units = "INSERT IGNORE INTO Units (offer_id) VALUES ($offer->id)";
				} else {
					$s_units .= ", ($offer->id)";
				}
			}
		}
		if($s_units) DB::insert($s_units);

		return view("modules.import/students",["user" => auth()->user(), "students" => session("attends")]);
	}


	/**
	 * Pega lista de alunos da session e faz a matricula na turma, tambem faz o cadastro caso precise
	 *
	 * @return type página com erro ou confirmação
	 */
	public function getConfirmattends()
	{
		$units = null;
		$s_relations = false;
		$s_attends = false;


		if (session("attends")) {
			$attends = session("attends");
			foreach ($attends as $attend) {
				if (!$attend[4]) {

					$user_ids = Relationship::where('user_id', auth()->id())
						->get(['friend_id'])
						->pluck('friend_id')->all();
					$student = User::whereIn('id', $user_ids)
						->where('enrollment', $cols[1])->first();

					if (count($student)) {
						$attend[4] = $student->id;
					} else {
						$student = new User;
						$student->type = "N";
						$student->enrollment = $attend[0];
						$student->name = $attend[1];
						$student->gender = $attend[2];
						$student->birthdate = $attend[3];
						$student->save();
						$attend[4] = $student->id;

						if( !$s_relations ) {
							$s_relations = "INSERT IGNORE INTO Relationships (user_id, friend_id, type ) VALUES (".auth()->id().", $student->id, '1')";
						} else {
							$s_relations .= ", (".auth()->id().", $student->id, '1')";
						}
					}
				}
				if (!($units and $units[0]->class_id == $attend[6]))
				{
					if ( !$attend[6] )
					{
							$class = Classe::where("period_id", $attend[7])->whereClass($attend[5])->where("status", "!=", "D")->first();
							$attend[6] = $class->id;
					}
					$units = DB::select("SELECT Units.id as id, Offers.class_id as class_id FROM Units, Offers
						WHERE Offers.class_id=? AND Units.offer_id=Offers.id ORDER BY Units.value DESC", [$attend[6]]);
				}
				foreach ($units as $unit) {
					if (!Attend::where("unit_id", $unit->id)->where("user_id", $attend[4])->first()) {
						if( !$s_attends ) {
							$s_attends = "INSERT IGNORE INTO Attends (unit_id, user_id) VALUES ($unit->id, ".$attend[4].")";
						} else {
							$s_attends .= ", ($unit->id, ".$attend[4].")";
						}
					} else {
						break;
					}
				}
			}
			if ($s_relations) {
				DB::insert($s_relations);
			}
			if ($s_attends) {
				DB::insert($s_attends);
			}

			return redirect("/import")->with("success", "Alunos matriculados com sucesso.");
		}
		else
			return redirect("/import")->with("error", "Algum erro aconteceu, tente novamente.");
	}

	/**
	 * Recebe csv de importção de professores, disciplinas e ofertas, organiza, salva na session e mostra lista de disciplinas
	 *
	 * @return view com lista de disciplinas
	 * @throws caso o arquivo seja invalido
	 */
	public function postClasswithteacher()
	{
			$csv = utf8_encode(file_get_contents(request()->file("csv")));
			if (!strpos($csv, "SERVIDORES ATRIBUÍDOS NA CLASSE")) {
				throw new Exception("Arquivo inválido");
			}
			$school = [];
			$structure = [];
			$turmas = explode("Turma:", $csv);
			unset($turmas[0]);
			foreach ($turmas as $turma)
			{
				$cod = explode(";", $turma);
				$cod = explode(" - ", $cod[2]);
				if ( count($cod) == 4 )
				{ /* exceção do cadefas [1ª C - V - 1ª SÉRIE - ENSINO MÉDIO] */
					$cod[0] .= " - " . $cod[1];
					$cod[1] = $cod[2];
					$cod[2] = $cod[3];
				}
				$cod[0] = "[". date("Y") . "] " . $cod[0];

				if (!isset($school[$cod[2]]))
				{
					$school[$cod[2]] = [];
				}
				if (!isset($school[$cod[2]][$cod[1]]))
				{
					$school[$cod[2]][$cod[1]] = [];
				}
				$lines = explode("\n", $turma);
				$offer = [];
				foreach ($lines as $i => $line)
				{
					if (!is_numeric(explode(";", $line)[0]))
					{
						continue;
					}
					$line = $lines[$i] . $lines[$i+1];
					$cols = explode(";", $line);
					$out = [];
					foreach ($cols as $col) {
						if (strlen($col)) {
							$out[] = $col;
						}
					}
					$out[8] = explode(", ", $out[8]);
					foreach ($out[8] as $disc)
						$school[$cod[2]][$cod[1]][$disc] = DB::select("SELECT count(*) as 'qtd' FROM Courses, Periods, Disciplines
							WHERE Courses.institution_id=? AND Courses.name=? AND
							Courses.id=Periods.course_id AND Periods.name=? AND
							Periods.id=Disciplines.period_id AND Disciplines.name=?",
							[auth()->id(), $cod[2], $cod[1], $disc])[0]->qtd;
					$offer[] = $out;
				}
				$structure[] = [$cod, $offer];
			}
			Session::put("structure", $structure);
			return view("modules.import.structure",["user" => auth()->user(), "school" => $school]);
	}

	/**
	 * salva diciplinas, periodos e cursos e mostra view de professores
	 *
	 * @return view com professores que serão adicionados
	 */
	public function getTeacher()
	{
		$structure = session("structure");
		$teachers = [];
		foreach ($structure as $class) {
			$course = Course::where("institution_id", auth()->id())->whereName($class[0][2])->first();
			if (!$course) {
				$course = new Course;
				$course->name = $class[0][2];
				$course->institution_id = auth()->id();
				$course->absent_percent = 25;
				$course->average = 7;
				$course->average_final = 5;
				$course->save();
			}
			$period = Period::where("course_id", $course->id)->where("name", $class[0][1])->first();
			if (!$period) {
				$period = new Period;
				$period->course_id = $course->id;
				$period->name = $class[0][1];
				$period->save();
			}
			foreach ($class[1] as $offer) {
				foreach ($offer[8] as $disc) {
					$discipline = Discipline::where("period_id", $period->id)->where("name", $disc)->first();
					if (!$discipline) {
						$discipline = new Discipline;
						$discipline->period_id = $period->id;
						$discipline->name = $disc;
						$discipline->save();
					}
				}
				$teachers[$offer[0]] = [
					$offer[1],
					DB::select("SELECT count(*) as 'qtd' FROM relationships, users
						where relationships.user_id=? and
						relationships.friend_id=users.id and
						relationships.type='2' and
						users.enrollment=?", [auth()->id(), $offer[0]])[0]->qtd
					];
			}
		}
		return view("modules.import.teacher", ["user" => auth()->user(), "teachers" => $teachers]);
	}

	/**
	 * Salva professores que ainda não estão cadastrados e mostra view de ofertas
	 *
	 * @return view com o que vai ser ofertado
	 */
	public function getOffer()
	{
		$structure = session("structure");
		$s_relations = false;
		foreach ($structure as $class) {
			foreach ($class[1] as $offer) {
				$status = DB::select("SELECT count(*) as 'qtd' FROM relationships, users
					where relationships.user_id=? and
					relationships.friend_id=users.id and
					relationships.type='2' and
					users.enrollment=?",[auth()->id(), $offer[0]])[0]->qtd;
				if (!$status) {
					$user = new User;
					$user->name = $offer[1];
					$user->type = 'M';
					$user->cadastre = 'N';
					$user->enrollment = $offer[0];
					$user->save();
					if( !$s_relations )
						$s_relations = "INSERT IGNORE INTO Relationships (user_id, friend_id, type ) VALUES (".auth()->id().", $user->id, '2')";
					else
						$s_relations .= ", (".auth()->id().", $user->id, '2')";
				}
			}
			if($s_relations) DB::insert($s_relations);
		}
		return view("modules.import.offers", ["user" => auth()->user(), "structure" => $structure]);
	}

	public function getConfirmoffer()
	{
		$structure = session("structure");
		foreach ($structure as $class) {
			$course = Course::where("institution_id", auth()->id())->whereName($class[0][2])->first();
			$period = Period::where("course_id", $course->id)->where("name", $class[0][1])->first();
			$classe = Classe::where("period_id", $period->id)->whereClass($class[0][0])->first();
			if ( !$classe )
			{
				$classe = new Classe;
				$classe->period_id = $period->id;
				$classe->name = $class[0][0];
				$classe->class = date("Y");
				$classe->save();
			}
			foreach ($class[1] as $offer_aux)
			{
				$teacher = DB::select("SELECT users.id from relationships, users
					 where relationships.user_id=? and
					 relationships.friend_id=users.id and
					 relationships.type='2' and
					 users.enrollment=?",[auth()->id(), $offer_aux[0]])[0];
				foreach ($offer_aux[8] as $disc)
				{
					$discipline = Discipline::where("period_id", $period->id)->where("name", $disc)->first();
					$offer = new Offer;
					$offer->discipline_id = $discipline->id;
					$offer->classroom = $offer_aux[6];
					$offer->class_id = $classe->id;
					$offer->save();
					$lecture = new Lecture;
					$lecture->user_id = $teacher->id;
					$lecture->offer_id = $offer->id;
					$lecture->save();
					$unit = new Unit;
					$unit->offer_id = $offer->id;
					$unit->save();
				}
			}
		}
		return redirect("/import")->with("success", "Turmas inseridas com sucesso");
	}
}
