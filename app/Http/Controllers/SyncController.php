<?php namespace App\Http\Controllers;

class SyncController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	public function getIndex()
	{
		$user = auth()->user();
		$keyExams = [];
		$keyLessons = [];

		if (request()->has("lb"))
			session("lb", request()->get("lb"));


		if ($user) {
			Session::forget("redirect");
			$data = auth()->user();
			$data->id = encrypt($data->id); //crypt user
			$data->download = date("H:i:s - d/m/Y");
			unset($data->password);
			if ($user->type == "P")
			{
				/*Getting professor's offers*/
				$lectures = Lecture::where('user_id', decrypt($user->id))->orderBy("order")->get();

				$keyAttend = []; /* array para salvar chave, para
				 * que seja igual em Attends e em ExamsValues.
				 * Cada vez que criptografa sai uma chave diferente. */

				foreach ( $lectures as $lecture ) {
					$offer = Offer::find($lecture->offer_id);
					$units = Unit::where('offer_id', $offer->id)->get();
					$offer->id = encrypt($offer->id); //crypt offer
					$lecture->offer_id = $offer->id;
					$lecture->user_id = $data->id;
					foreach ( $units as $unit ) {
						$exams = Exam::where('unit_id', $unit->id)->get();
						$lessons = Lesson::where('unit_id', $unit->id)->get();
						$attends = Attend::where('unit_id', $unit->id)->get();
						$unit->id = encrypt($unit->id); //crypt unit
						$unit->offer_id = $offer->id;

						foreach ( $attends as $attend ) {
							$keyAttend[$attend->id] = encrypt($attend->id);
							$attend->id = $keyAttend[$attend->id]; //crypt attend
							$attend->unit_id = $unit->id;
							$attend->user = User::find($attend->user_id);
							$attend->user->id = encrypt($attend->user->id);
							$attend->user_id = $attend->user->id;
							unset($attend->user->password);
							unset($attend->user->email);
							unset($attend->user->photo);
							unset($attend->user->city_id);
							unset($attend->user->cadastre);
							unset($attend->user->formation);
							unset($attend->user->course);
							unset($attend->user->institution);
							unset($attend->user->updated_at);
							unset($attend->user->created_at);
						}
						$unit->attends = $attends;

						foreach ( $exams as $exam ) {
							$examsValues = ExamsValue::where('exam_id', $exam->id)->get();
							$exam->id = encrypt($exam->id);
							$exam->unit_id = $unit->id;
							foreach ( $examsValues as $examValue) {
								$examValue->attend_id = $keyAttend[$examValue->attend_id];
								$examValue->exam_id = $exam->id;
							}
							$exam->examsValues = $examsValues;
						}
						$unit->exams = $exams;

						foreach ( $lessons as $lesson ) {
							$frequencies = Frequency::where('lesson_id', $lesson->id)->get();
							$lesson->id = encrypt($lesson->id);
							$lesson->unit_id = $unit->id;
							foreach ( $frequencies as $frequency ) {
								$frequency->attend_id = $keyAttend[$frequency->attend_id];
								$frequency->lesson_id = $lesson->id;
							}
							$lesson->frequencies = $frequencies;
						}
						$unit->lessons = $lessons;
					}

					$offer->discipline = Discipline::find($offer->discipline_id);
					$offer->discipline->id = encrypt($offer->discipline->id); //crypt discipline
					$offer->class = Classe::find($offer->class_id);
					$offer->class->id = encrypt($offer->class->id); //crypt class
					$offer->period = Period::find($offer->discipline->period_id);
					$offer->period->id = encrypt($offer->period->id); //crypt period
					$offer->discipline->period_id = $offer->period->id;
					$offer->class->period_id = $offer->period->id;
					$offer->period->course = Course::find($offer->period->course_id);
					$offer->period->course->id = encrypt($offer->period->course->id); //crypt course
					$offer->period->course_id = $offer->period->course->id;
					$offer->period->course->institution = User::find($offer->period->course->institution_id);
					$offer->period->course->institution->id = encrypt($offer->period->course->institution->id); //crypt institution
					$offer->period->course->institution_id =  $offer->period->course->institution->id;
					/*crypt offer left*/
					$offer->units = $units;
					$offer->discipline_id = $offer->discipline->id;
					$offer->class_id = $offer->class->id;
					$lecture->offer = $offer;
				}
				$data->lectures = $lectures;

				return view("modules.sync.login", ["data" => $data]);
			}

			return redirect("/sync/error")
				->with("error", "Erro ao syncronizar. Usuário deve ser professor.");
		} else {
			session("redirect", "sync");
			return redirect("/login");
		}
	}

	public function postReceive()
	{
		Session::put("data", request()->get("data"));
		Session::put("lb", request()->get("lb"));
		if (auth()->user())
		{
			return redirect("/sync/receive");
		}
		else
		{
			Session::put("redirect", "sync/receive");
			return redirect("/login");
		}
	}

	public function getReceive()
	{
		if( !request()->has("confirm") )
		{
			return view("modules.sync.send", ["data" => auth()->user() ]);
		}

		Session::forget("redirect");
		$data = json_decode(session("data"));

		if(decrypt($data->id) != auth()->id())
					return redirect("/sync/error")
								 ->with("erro", "Erro ao syncronizar. Incompatiblilidade de usuários [" . auth()->user()->email . " != $data->email]");

		foreach( $data->lectures as $lecture )
		{
			foreach( $lecture->offer->units as $unit )
			{
				$unit->id = decrypt($unit->id);
				$valid = DB::select("SELECT COUNT(*) as valid FROM Lectures, Offers, Units "
														. "WHERE Lectures.user_id=? AND Lectures.offer_id=Units.offer_id AND Units.id=?",
														[auth()->id(), $unit->id])[0]->valid;
				if($valid == 0)
					return redirect("/sync/error")
												 ->with("error", "Erro ao syncronizar. Arquivo foi modificado de forma maliciosa. [units]")
												 ->with("email", auth()->user()->email);

				foreach($unit->lessons as $json_lesson)
				{
					$lesson = null;
					if(is_numeric($json_lesson->id))
					{
						$lesson = new Lesson;
						$lesson->unit_id = $unit->id;
					}
					else
						$lesson = Lesson::find(decrypt($json_lesson->id));

					if ( $lesson->unit_id != $unit->id )
						return redirect("/sync/error")
													 ->with("error", "Erro ao syncronizar. Arquivo foi modificado de forma maliciosa. [lesson]")
													 ->with("email", auth()->user()->email);

					$lesson->date = $json_lesson->date;
					$lesson->title = $json_lesson->title;
					$lesson->description = $json_lesson->description;
					$lesson->goals = $json_lesson->goals;
					$lesson->content = $json_lesson->content;
					$lesson->methodology = $json_lesson->methodology;
					$lesson->keyworks = $json_lesson->keyworks;
					$lesson->estimatedTime = $json_lesson->estimatedTime;
					$lesson->bibliography = $json_lesson->bibliography;
					$lesson->valuation = $json_lesson->valuation;
					$lesson->notes = $json_lesson->notes;
					$lesson->save();
					foreach ($json_lesson->frequencies as $json_frequency)
						if ( !Frequency::where('attend_id', decrypt($json_frequency->attend_id))
														->where('lesson_id', $lesson->id)
														->update(["value" => $json_frequency->value]) )
						{
							$frequency = new Frequency;
							$frequency->attend_id = decrypt($json_frequency->attend_id);
							$frequency->lesson_id = $lesson->id;
							$frequency->value = $json_frequency->value;
							$frequency->save();
						}
				}

				foreach($unit->exams as $json_exam)
				{
					$exam = null;
					if(is_numeric($json_exam->id))
					{
						$exam = new Exam;
						$exam->unit_id = $unit->id;
					}
					else
						$exam = Exam::find(decrypt($json_exam->id));

					if ( $exam->unit_id != $unit->id )
						return redirect("/sync/error")
													 ->with("error", "Erro ao syncronizar. Arquivo foi modificado de forma maliciosa. [exam]")
													 ->with("email", auth()->user()->email);

					$exam->date = $json_exam->date;
					$exam->title = $json_exam->title;
					$exam->type = $json_exam->type;
					$exam->aval = $json_exam->aval;
					$exam->weight = $json_exam->weight;
					$exam->comments = $json_exam->comments;
					$exam->save();

					foreach ($json_exam->examsValues as $json_value )
						if ( !ExamsValue::where('attend_id', decrypt($json_value->attend_id))
														->where('exam_id', $exam->id)
														->update(array('value' => $json_value->value)) )
						{
							$value = new ExamsValue;
							$value->attend_id = decrypt($json_value->attend_id);
							$value->exam_id = $exam->id;
							$value->value = $json_value->value;
							$value->save();
						}
				}
			}
		}
		return redirect("/sync");
	}

	public function getError()
	{
		if( session("email") )
			Mail::send('email.alert', ["msg" => session("error"), "email" => session("email") ], function($message)
			{
				$message->to(config('app.mail_suporte'), 'Suporte LibreClass')
								->subject("Tentativa de burlar o sistema");
			});

		return view("modules.sync.error", ["data" => auth()->user(), "error" => session("error")]);
	}
}
