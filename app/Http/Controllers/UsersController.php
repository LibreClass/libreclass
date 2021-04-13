<?php

namespace App\Http\Controllers;

use StdClass;
use Mail;
use Hash;
use DB;
use PDF;
use Exception;
use App\User;
use App\Course;
use App\Relationship;
use App\Period;
use App\Classe;
use App\Attest;
use App\Attend;
use App\Unit;
use App\Offer;
use App\Discipline;
use App\Exam;
use App\ExamsValue;
use App\FinalExam;
use App\DescriptiveExam;
use App\Lesson;
use App\Frequency;

class UsersController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I')->except([
			'getStudent',
			'postStudent',
		]);
		$this->middleware('auth.type:IP')->only([
			'getStudent',
			'postStudent',
		]);
	}

	public function postSearchTeacher()
	{
		$teacher = User::where('email', request()->get('str'))->first();

		if ($teacher) {
			$relationship = Relationship::where('user_id', auth()->id())
				->where('friend_id', $teacher->id)
				->whereStatus('E')
				->first();

			if (!$relationship) {
				return response()->json([
					'status' => 1,
					'teacher' => [
						'id' => encrypt($teacher->id),
						'name' => $teacher->name,
						'formation' => $teacher->formation,
					],
					'message' => 'Este professor já está cadastrado no LibreClass e será vinculado à sua instituição.',
				]);
			} else {
				return response()->json([
					'status' => -1,
					'teacher' => [
						'id' => encrypt($teacher->id),
						'name' => $teacher->name,
						'formation' => $teacher->formation,
						'enrollment' => $relationship->enrollment,
					],
					'message' => 'Este professor já está vinculado à instituição!',
				]);
			}
		} else {
			return response()->json([
				'status' => 0,
			]);
		}
	}

	public function anyTeachersFriends()
	{
		$relationships = Relationship::whereuserId(auth()->id())
			->whereType('2')
			->whereStatus('E')
			->get(['friend_id', 'enrollment'])
			->pluck('enrollment', 'friend_id');

		$teachers = User::whereIn('id', $relationships->keys()->all())
			->orderBy('name')
			->get(['id', 'name', 'photo'])
			->toArray();

		foreach ($teachers as &$teacher) {
			$teacher['comment'] = $relationships->get($teacher['id']);
			$teacher['id'] = base64_encode($teacher['id']);
		}

		return $teachers;
	}

	public function getTeacher()
	{
		$block = 30;
		$search = request()->get('search', '');
		$current = (int) request()->get('current', 0);

		$user = auth()->user();
		$courses = Course::where('institution_id', auth()->id())
			->whereStatus('E')
			->orderBy('name')
			->get();
		$listCourses = ['' => ''];
		foreach ($courses as $course) {
			$listCourses[$course->name] = $course->name;
		}

		$relationships = DB::select(
			"SELECT users.id, users.name, relationships.enrollment, users.type
			from users, relationships
			where relationships.user_id=?
				and relationships.type='2'
				and relationships.friend_id=users.id
				and relationships.status='e'
				and (users.name like ? or relationships.enrollment=?)
			order by name limit ? offset ?",
			[auth()->id(), "%$search%", $search, $block, $current * $block]
		);

		// $enrollment = preg_replace('/\D/', '', $search);
		// if ($enrollment) {
		// }

		$length = DB::select("SELECT count(*) as 'length'
			from users, relationships
			where relationships.user_id=?
				and relationships.type='2'
				and relationships.friend_id=users.id
				and (users.name like ? or relationships.enrollment=?) ", [auth()->id(), "%$search%", $search]);

		return view('modules.addTeachers', [
			'courses' => $listCourses,
			'user' => $user,
			'relationships' => $relationships,
			'length' => (int) $length[0]->length,
			'block' => (int) $block,
			'current' => (int) $current,
		]);
	}

	public function postTeacher()
	{
		// Verifica se o número de matrícula já existe

		if (strlen(request()->get("teacher"))) {
			$user = User::find(decrypt(request()->get("teacher")));
			if (strlen(request()->get("registered"))) {
				$relationship = Relationship::where('user_id', auth()->id())
					->where('friend_id', $user->id)
					->first();

				if (!$relationship) {
					$relationship = new Relationship;
					$relationship->user_id = auth()->id();
					$relationship->friend_id = $user->id;
					$relationship->enrollment = request()->get('enrollment');
					$relationship->status = "E";
					$relationship->type = "2";
					$relationship->save();
				} else {
					Relationship::where('user_id', auth()->id())
						->where('friend_id', $user->id)
						->update([
							'status' => 'E',
							'enrollment' => request()->get('enrollment'),
						]);
				}

				return redirect("/user/teacher")
					->with("success", "Professor vinculado com sucesso!");
			}

			// Tipo P é professor com conta liberada. Ele mesmo deve atualizar as suas informações e não a instituição.
			if ($user->type == "P") {
				return redirect("/user/teacher")->with("error", "Professor não pode ser editado!");
			}
			$user->email = request()->get("email");
			// $user->enrollment = request()->get("enrollment");
			$user->name = request()->get("name");
			$user->formation = request()->get("formation");
			$user->gender = request()->get("gender");
			$user->save();
			return redirect("/user/teacher")->with("success", "Professor editado com sucesso!");
		} else {
			$verify = Relationship::whereEnrollment(request()->get("enrollment"))->where('user_id', auth()->id())->first();
			if (isset($verify) || $verify != null) {
				return redirect("/user/teacher")->with("error", "Este número de inscrição já está cadastrado!");
			}
			$user = new User;
			$user->type = "M";
			// $user->email = request()->get("email");
			// $user->enrollment = request()->get("enrollment");
			$user->name = request()->get("name");
			$user->formation = request()->get("formation");
			$user->gender = request()->get("gender");
			if (request()->has("date-year")) {
				$user->birthdate = request()->get("date-year") . "-"
					. request()->get("date-month") . "-"
					. request()->get("date-day");
			}
			$user->save();

			$relationship = new Relationship;
			$relationship->user_id = auth()->id();
			$relationship->friend_id = $user->id;
			$relationship->enrollment = request()->get("enrollment");
			$relationship->status = "E";
			$relationship->type = "2";
			$relationship->save();

			$this->postInvite($user->id);

			return redirect("/user/teacher")->with("success", "Professor cadastrado com sucesso!");
		}
	}

	public function updateEnrollment()
	{
		$user = User::find(decrypt(request()->get("teacher")));
		Relationship::where('user_id', auth()->id())->where('friend_id', $user->id)->update(['enrollment' => request()->get('enrollment')]);
		return redirect("/user/teacher")->with("success", "Matrícula editada com sucesso!");
	}

	public function getProfileStudent()
	{
		$user = auth()->user();
		$profile =  User::find(decrypt(request()->get("u")));

		$course_ids = Course::where('institution_id', $user->id)
			->get(['id'])
			->pluck('id')
			->toArray();

		$period_ids = Period::whereIn('course_id', $course_ids)
			->get(['id'])
			->pluck('id')
			->toArray();

		$classes = Classe::whereIn('period_id', $period_ids)
			->whereStatus('E')
			->get(['id', 'name', 'class']);

		$listclasses = [];
		$listidsclasses = [];
		foreach ($classes as $class) {
			$listclasses[$class->class] = $class->class;
			$listidsclasses[encrypt($class->id)] = "[$class->class] $class->name";
		}

		if ($profile) {
			$attends = Attend::whereUserId($profile->id)->get(['unit_id']);
			$units = Unit::select('offer_id')->find($attends->pluck('unit_id')->all());
			$offers = Offer::select('discipline_id')->find($units->pluck('offer_id')->all());
			$disciplines = Discipline::select('period_id')->find($offers->pluck('discipline_id')->all());
			$periods = Period::select('course_id')->find($disciplines->pluck('period_id')->all());
			$courses = Course::select('id', 'name', 'quant_unit')->find($periods->pluck('course_id')->all());

			$listCourses = [];
			foreach ($courses as $course) {
				$listCourses[encrypt($course->id)] = "$course->name";
			}

			$attests = Attest::where('student_id', $profile->id)
				->where('institution_id', $user->id)
				->orderBy('date', 'desc')->get();

			return view('modules.profilestudent', [
				'user' => $user,
				'profile' => $profile,
				'listclasses' => $listclasses,
				'attests' => $attests,
				'listidsclasses' => $listidsclasses,
				'listCourses' => $listCourses,
				'courses' => $courses,
			]);
		} else {
			return redirect("/");
		}
	}

	public function postGetStudent()
	{
		$student = User::whereId(decrypt(request()->get('student_id')))
			->first(['id', 'name', 'email', 'birthdate', 'enrollment', 'gender', 'course']);

		if (!$student) {
			return ['status' => 0, 'message' => 'Não encontrado'];
		}

		$student = (object) $student->toArray();
		$student->id = encrypt($student->id);
		return [
			'status' => 1,
			'student' => $student,
		];
	}

	public function anyReporterStudentClass()
	{
		$student = decrypt(request()->get("student"));
		$disciplines = DB::select(
			"SELECT
				courses.id as course,
				disciplines.name,
				offers.id as offer,
				attends.id as attend,
				classes.status as statusclasse
			from classes, periods, courses, disciplines, offers, units, attends
			where
				courses.institution_id=? and
				courses.id=periods.course_id and
				periods.id=classes.period_id and
				classes.school_year=? and
				classes.id=offers.class_id and
				offers.discipline_id=disciplines.id and
				offers.id=units.offer_id and
				units.id=attends.unit_id and
				attends.user_id=?
			",
			[auth()->id(), request()->get("class"), $student]
		);

		$disciplines = collect($disciplines)->unique('offer')->values()->all();

		foreach ($disciplines as $discipline) {
			$sum = 0;
			$discipline->units = Unit::where("offer_id", $discipline->offer)->get();
			foreach ($discipline->units as $unit) {
				$unit->exams = Exam::where("unit_id", $unit->id)->orderBy("aval")->get();
				foreach ($unit->exams as $exam) {
					$exam->value = ExamsValue::where("exam_id", $exam->id)->where("attend_id", $discipline->attend)->first();
				}

				$value = $unit->getAverage($student);
				// return $value;
				$sum += isset($value[1]) ? $value[1] : $value[0];
			}
			$discipline->average = sprintf("%.2f", ($sum + .0) / count($discipline->units));
			$discipline->final = FinalExam::where("user_id", $student)->where("offer_id", $discipline->offer)->first();
			$offer = Offer::find($discipline->offer);
			$discipline->absencese = sprintf("%.1f", (100. * ($offer->maxlessons - $offer->qtdAbsences($student))) / $offer->maxlessons);

			$course = Course::find($discipline->course);
			$discipline->course = $course;
			$discipline->aproved = "-";
			if ($discipline->statusclasse == "C") {
				$discipline->aproved = "Aprovado";
				if ($discipline->absencese + $course->absent_percent < 100) {
					$discipline->aproved = "Reprovado";
				}

				if ($discipline->average < $course->average and (!$discipline->final or $discipline->final->value < $course->average_final)) {
					$discipline->aproved = "Reprovado";
				}
			}
		}
		return view("institution.reportStudentDetail", ["disciplines" => $disciplines]);
	}

	public function getReporterStudentOffer()
	{
		return request()->all();
	}

	public function postProfileStudent()
	{
		$user_id = (int) decrypt(request()->get("student"));

		$offers = collect(request()->get("offers"));

		if ($offers->isEmpty()) {
			return redirect()
				->back()
				->with("error", "Nenhuma oferta selecionada!");
		}

		foreach ($offers as $offer) {
			$units = Unit::where("offer_id", decrypt($offer))->get();
			foreach ($units as $unit) {
				$attend = Attend::where("user_id", $user_id)->where("unit_id", $unit->id)->first();
				if ($attend) {
					$disc = Offer::find(decrypt($offer))->getDiscipline();
					return redirect()->back()
						->with("error", "O aluno não pode ser inserido.<br>"
							. "O aluno já está matriculado na oferta da disciplina <b>" . $disc->name . "</b>."); //. " com o status " . $attend->status . ".");
				}
			}
		}

		foreach ($offers as $offer) {
			$units = Unit::where("offer_id", decrypt($offer))->get();
			foreach ($units as $unit) {
				$attend = new Attend;
				$attend->user_id = $user_id;
				$attend->unit_id = $unit->id;
				$attend->save();
				$exams = Exam::where("unit_id", $unit->id)->get();
				foreach ($exams as $exam) {
					$value = new ExamsValue;
					$value->exam_id = $exam->id;
					$value->attend_id = $attend->id;
					$value->save();
				}
				$lessons = Lesson::where("unit_id", $unit->id)->get();
				foreach ($lessons as $lesson) {
					$value = new Frequency;
					$value->lesson_id = $lesson->id;
					$value->attend_id = $attend->id;
					$value->save();
				}
			}
		}
		return redirect()->back()->with("success", "Inserido com sucesso!");
	}

	/**
	 * Cadastra um atestada e retorna para a página anterior
	 */
	public function postAttest()
	{
		$student_id = decrypt(request()->get("student"));
		$relation = Relationship::where("user_id", auth()->id())->where("friend_id", $student_id)->whereType(1)->whereStatus("E")->first();

		if ($relation) {
			$attest = new Attest;
			$attest->institution_id = auth()->id();
			$attest->student_id = $student_id;
			$attest->date = request()->get("date-year") . "-" . request()->get("date-month") . "-" . request()->get("date-day");
			$attest->days = request()->get("days");
			$attest->description = request()->get("description");
			$attest->save();

			return redirect()->back()->with("success", "Operação realizada com sucesso.");
		} else {
			return redirect()->back()->with("error", "Essa operação não pode ser realizado. Consulte o suporte.");
		}
	}

	public function getProfileTeacher()
	{
		$user = auth()->user();
		$profile = decrypt(request()->get("u"));
		if ($profile) {
			$profile = User::find($profile);
			$relationship = Relationship::where('user_id', auth()->id())->where('friend_id', $profile->id)->first();
			$profile->enrollment = $relationship->enrollment;
			$profile->cities = $profile->city()->get();
			switch ($profile->formation) {
				case '0':
					$profile->formation = "Não quero informar";
					break;
				case '1':
					$profile->formation = "Ensino Fundamental";
					break;
				case '2':
					$profile->formation = "Ensino Médio";
					break;
				case '3':
					$profile->formation = "Ensino Superior Incompleto";
					break;
				case '4':
					$profile->formation = "Ensino Superior Completo";
					break;
				case '5':
					$profile->formation = "Pós-Graduado";
					break;
				case '6':
					$profile->formation = "Mestre";
					break;
				case '7':
					$profile->formation = "Doutor";
					break;
			}
			return view("modules.profileteacher", [
				"user" => $user,
				"profile" => $profile,
				"courses" => Course::where('institution_id', auth()->id())->get()
			]);
		} else {
			return redirect("/");
		}
	}

	public function postInvite($id = null)
	{
		$user = auth()->user();
		if ($id) {
			$guest = User::find($id);
		} else {
			$guest = User::find(decrypt(request()->has("teacher") ? request()->get("teacher") : request()->get("guest")));
		}

		if (($guest->type == "M" or $guest->type == "N") and Relationship::where("user_id", auth()->id())->where("friend_id", $guest->id)->first()) {
			if (User::whereEmail(request()->get("email"))->first()) {
				return redirect()->back()->with("error", "O email " . request()->get("email") . " já está cadastrado.");
			}
			$guest->email = request()->get("email");
			$password = substr(md5(microtime()), 1, rand(4, 7));
			$guest->password = Hash::make($password);
			Mail::send('email.invite', [
				"institution" => $user->name,
				"name" => $guest->name,
				"email" => $guest->email,
				"password" => $password,
			], function ($message) use ($guest) {
				$message->to(request()->get("email"), $guest->name)
					->subject("Seja bem-vindo");
			});
			$guest->save();
			return redirect()->back()->with("success", "Operação realizada com sucesso. Os dados de acesso de $guest->name foi enviado para o email $guest->email.");
		} else {
			return redirect()->back()->with("error", "Operação inválida");
		}
	}

	public function getStudent()
	{
		$block = 30;
		$search = request()->get('search', '');
		$current = (int) request()->get('current', 0);
		$user = auth()->user();
		$courses = Course::where('institution_id', auth()->id())
			->whereStatus('E')
			->orderBy('name')
			->get();

		$listCourses = ['' => ''];
		foreach ($courses as $course) {
			$listCourses[$course->id] = $course->name;
		}

		$user_ids = Relationship::whereUserId(auth()->id())
			->whereType('1')
			->get(['friend_id'])
			->pluck('friend_id')
			->toArray();

		$users = User::whereIn('id', $user_ids);
		if ($search) {
			$users = $users->where(function ($query) use ($search) {
				$query->where('name', 'like', "%$search%")->orWhere('enrollment', $search);
			});
		}
		$users = $users->orderBy('name')
			->skip($current * $block)
			->take($block)
			->get(['id', 'name', 'enrollment']);

		$length = User::whereIn('id', $user_ids)->where(function ($query) use ($search) {
			$query->where('name', 'like', "%$search%")->orWhere('enrollment', $search);
		})->count();

		return view('modules.addStudents', [
			'courses' => $listCourses,
			'user' => $user,
			'relationships' => $users,
			'length' => (int) $length,
			'block' => (int) $block,
			'current' => (int) $current,
		]);
	}

	public function anyFindUser($search)
	{
		$users = User::whereIn('type', ['P', 'A'])
			->where(function ($query) use ($search) {
				$query->where('name', 'like', "%$search%")
					->orWhere('email', $search);
			})
			->take(5)->get();

		return view('user.list-search', [
			'users' => $users,
		]);
	}

	public function postSeachEnrollment($user, $type)
	{
		if (strlen(request()->get("registered"))) {
			$relationship = Relationship::where('user_id', auth()->id())
				->where('friend_id', $user->id)
				->first();

			if (!$relationship) {
				$relationship = new Relationship;
				$relationship->user_id = auth()->id();
				$relationship->friend_id = $user->id;
				$relationship->enrollment = request()->get('enrollment');
				$relationship->status = "E";
				$relationship->type = $type;
				$relationship->save();
			} else {
				Relationship::where('user_id', auth()->id())
					->where('friend_id', $user->id)
					->update([
						'status' => 'E',
						'enrollment' => request()->get('enrollment'),
					]);
			}
		}
	}

	public function postStudent()
	{

		if (strlen(request()->get('student_id'))) {
			$user = User::find(decrypt(request()->get('student_id')));

			$this->postSeachEnrollment($user, "1");
			
			$user->enrollment = request()->get("enrollment");
			$user->name = request()->get("name");
			$user->email = strlen(request()->get("email")) ? request()->get("email") : null;
			$user->course = request()->get("course");
			$user->gender = request()->get("gender");
			$user->birthdate = request()->get("date-year") . "-" . request()->get("date-month") . "-" . request()->get("date-day");
			$user->save();

			return redirect("/user/student")->with("success",  "Os dados do aluno foram atualizados com sucesso.");
		} else {
			$verify = Relationship::whereEnrollment(request()->get("enrollment"))->where('user_id', auth()->id())->first();
			if (isset($verify) || $verify != null) {
				return redirect("/user/student")->with("error", "Este número de inscrição já está cadastrado!");
			}
			$user = new User;
			$user->type = "N";

			$user->enrollment = request()->get("enrollment");
			$user->name = request()->get("name");
			$user->email = strlen(request()->get("email")) ? request()->get("email") : null;
			$user->course = request()->get("course");
			$user->gender = request()->get("gender");
			$user->birthdate = request()->get("date-year") . "-" . request()->get("date-month") . "-" . request()->get("date-day");
			$user->save();

			if (!request()->get('student_id')) {
				$relationship = new Relationship;
				$relationship->user_id = auth()->id();
				$relationship->friend_id = $user->id;
				$relationship->enrollment = request()->get("enrollment");
				$relationship->status = "E";
				$relationship->type = "1";
				$relationship->save();
			}

			return redirect("/user/student")->with("success", "Aluno cadastrado com sucesso.");
		}
	}

	public function postUnlink()
	{
		$idTeacher = decrypt(request()->get("input-trash"));

		$offers = DB::select("SELECT courses.name as course, periods.name as period, classes.class as class, disciplines.name as discipline "
			. "from courses, periods, classes, offers, lectures, disciplines "
			. "where courses.institution_id=? and courses.id=periods.course_id and "
			. "periods.id=classes.period_id and classes.id=offers.class_id and "
			. "offers.discipline_id=disciplines.id and "
			. "offers.id=lectures.offer_id and lectures.user_id=?", [auth()->id(), $idTeacher]);

		if (count($offers)) {
			$str = "Erro ao desvincular professor, ele está associado a(s) disciplina(s): <br><br>";
			$str .= "<ul class='text-justify list-group'>";
			foreach ($offers as $offer) {
				$str .= "<li class='list-group-item'>$offer->course/$offer->period/$offer->class/$offer->discipline</li>";
			}
			$str .= "</ul>";

			return redirect()->back()->with("error", $str);
		} else {
			Relationship::where('user_id', auth()->id())
				->where('friend_id', $idTeacher)
				->whereType(2)
				->update(["status" => "D"]);

			return redirect("/user/teacher")->with("success", "Professor excluído dessa Instituição");
		}
	}

	public function getInfouser()
	{
		$user = User::find(decrypt(request()->get("user")));
		$user->enrollment = DB::table('relationships')->where('user_id', auth()->id())->where('friend_id', $user->id)->pluck('enrollment');
		$user->password = null;
		return $user;
	}

	public function anyLink($type, $user)
	{
		switch ($type) {
			case "student":
				$type = 1;
				break;
			default:
				return redirect()->back()->with("error", "Cadastro errado.");
		}
		$user = decrypt($user);

		$r = Relationship::where("user_id", auth()->id())->where("friend_id", $user)->whereType($type)->first();
		if ($r and $r->status == "E") {
			return redirect()->back()->with("error", "Já possui esse relacionamento.");
		} elseif ($r) {
			$r->status = "E";
		} else {
			$r = new Relationship;
			$r->user_id = auth()->id();
			$r->friend_id = $user;
			$r->type = $type;
		}
		$r->save();

		return redirect()->back()->with("success", "Relacionamento criado com sucesso.");
	}

	public function printScholarReport()
	{
		$data = [];
		$data['units'] = [];
		// Obtém dados da instituição
		$data['institution'] = auth()->user();

		// Obtém dados do aluno
		$data['student'] = User::find(decrypt(request()->get('u')));

		// Obtém número de matrícula do aluno na instituição
		$e = Relationship::where('user_id', auth()->id())
			->where('friend_id', $data['student']->id)
			->first();

		$data['student']['enrollment'] = $e['enrollment'];

		$disciplines = DB::select(
			"
			SELECT DISTINCT
				offers.id as offer,
				courses.id as course,
				disciplines.name,
				attends.id as attend,
				classes.status as statusclasse,
				units.value as value
			from
				classes, periods, courses, disciplines, offers, units, attends
			where
				courses.institution_id = ?
				and courses.id = periods.course_id
				and periods.id = classes.period_id
				and classes.id = offers.class_id
				and offers.id = units.offer_id
				and units.id = attends.unit_id
				and offers.discipline_id = disciplines.id
				and classes.school_year = ?
				and attends.user_id = ?
				and units.value in (?)
				and courses.id = ?",
			[
				auth()->id(),
				request()->get('school_year'),
				$data['student']->id,
				collect(request()->get('unit_value'))->implode(','),
				request()->get('course'),
			]
		);

		if (!$disciplines) {
			return "Não há informações para gerar o boletim.";
		}

		//Variável para acumular os pareceres
		$pareceres = new StdClass;
		$pareceres->disciplines = [];
		foreach ($disciplines as $key => $discipline) {

			// Obtém informações da disciplinas
			$data['disciplines'][$key] = (array) $discipline;

			$pareceres->disciplines[] = $discipline;
			$pareceres->disciplines[$key]->units = [];
			$pareceres->disciplines[$key]->hasParecer = false;

			$units = Offer::find($data['disciplines'][$key]['offer'])->units()->whereIn('value', request()->get('unit_value'))->orderBy('created_at')->get();
			foreach ($units as $key2 => $unit) {
				$pareceres->disciplines[$key]->units[] = $unit;
				// Obtém quantidade de aulas realizadas
				$data['disciplines'][$key][$unit->value]['lessons'] = Offer::find($unit->offer_id)->qtdUnitLessons($unit->value);

				// Obtém quantidade de faltas
				$data['disciplines'][$key][$unit->value]['absenceses'] = Offer::find($unit->offer_id)->qtdUnitAbsences($data['student']['id'], $unit->value);

				// Obtém a média do alunos por disciplina por unidade
				$average = number_format($unit->getAverage($data['student']['id'])[0], 0);
				if ($unit->calculation != 'P') {
					$data['disciplines'][$key][$unit->value]['average'] = ($average > 10) ? number_format($average, 0) : number_format($average, 2);
				} else {

					$pareceres->disciplines[$key]->units[$key2]->pareceres = [];
					//Obtém os pareceres
					$attend = Attend::where('unit_id', $unit->id)->where('user_id', $data['student']->id)->first();
					$pareceresTmp = DescriptiveExam::where('attend_id', $attend->id)->get();

					foreach ($pareceresTmp as $parecer) {
						$parecer->exam = Exam::where('id', $parecer->exam_id)->first(['title', 'type', 'date']);
						$parecer->exam->type = $this->typesExams($parecer->exam->type);
					}
					if (!empty($pareceresTmp)) {
						$pareceres->disciplines[$key]->hasParecer = true;
					}

					//Guarda os pareceres para enviar para view
					$pareceres->disciplines[$key]->units[$key2]->pareceres = $pareceresTmp;

					$data['disciplines'][$key][$unit->value]['average'] = 'PD';
				}

				$examRecovery = $unit->getRecovery();

				// Verifica se há prova de recuperação
				if ($examRecovery) {
					$attend = Attend::where('unit_id', $unit->id)->where('user_id', $data['student']['id'])->first();
					$recovery = ExamsValue::where('attend_id', $attend->id)->where('exam_id', $examRecovery->id)->first();
					$data['disciplines'][$key][$unit->value]['recovery'] = isset($recovery) && $recovery->value ? $recovery->value : '--';
				}
			}
			$data['units'] = count($data['units']) < count($units) ? $units : $data['units'];
		}
		//Guarda pareceres
		$data['pareceres'] = $pareceres;

		// Obtém dados do curso
		$data['course'] = Course::find($disciplines[0]->course);

		// Obtém dados da turma
		$data['classe'] = Offer::find($disciplines[0]->offer)->classe;

		$pdf = PDF::loadView('reports.arroio_dos_ratos-rs.final_result', ['data' => $data]);
		return $pdf->stream();
	}

	private function typesExams($type)
	{
		$typesExams = [
			"Prova Dissertativa Individual",
			"Prova Dissertativa em Grupo",
			"Prova Objetiva Individual",
			"Prova Objetiva em Grupo",
			"Trabalho Dissertativo Individual",
			"Trabalho Dissertativo em Grupo",
			"Apresentação de Seminário",
			"Projeto",
			"Produção Visual",
			"Pesquisa de Campo",
			"Texto Dissertativo",
			"Avaliação Prática",
			"Outros"
		];
		return $typesExams[$type];
	}
}