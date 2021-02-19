<?php

	namespace App\Http\Controllers\Student;

	use App\Http\Controllers\Controller;
	use DB, Session, Exception, User, Crypt, View, Offer, Discipline, Unit;

	class DisciplinesController extends Controller
	{
		public function __construct()
		{
			$this->middleware('auth.type:I');
		}

		public function getIndex()
		{
			$offers= DB::select("SELECT Offers.id, Disciplines.name as discipline
				FROM Attends, Units, Offers, Disciplines
				WHERE Attends.user_id=?
					AND Attends.unit_id=Units.id
					AND Units.offer_id=Offers.id
					AND Offers.discipline_id=Disciplines.id", [auth()->id()]);

			return view("students.disciplines", ["offers" => $offers]);
		}

		public function getUnits($offer)
		{
			$offer = Offer::find(decrypt($offer));
			$teachers = DB::select("SELECT users.id, users.name, users.photo from lectures, users where lectures.offer_id=? and lectures.user_id=users.id", [$offer->id]);
			$discipline = Discipline::find($offer->discipline_id);
			$units = Unit::where("offer_id", $offer->id)->orderBy("value", "desc")->get();
			$course = $offer->getCourse();

			return view("students.units", [
				"offer" => $offer,
				"teachers" => $teachers,
				"discipline" => $discipline,
				"units" => $units,
				"course" => $course,
			]);
		}

		public function postResumeUnit($unit)
		{
			$unit = decrypt($unit);
			$list = DB::select("SELECT Lessons.id, title, date, value, 'L' as type
				FROM Lessons, Frequencies, Attends
				WHERE Lessons.unit_id=?
					AND Lessons.id=lesson_id
					AND attend_id=Attends.id
					AND user_id=?
				UNION ALL (
					SELECT Exams.id, title, date, value, 'E' as type
					FROM Exams, ExamsValues, Attends
					WHERE Exams.unit_id=?
						AND Exams.id=exam_id
						AND attend_id=Attends.id
						AND user_id=?
				) ORDER BY date DESC", [
					$unit,
					auth()->id(),
					$unit,
					auth()->id(),
				]);

			$now = date("Y-m-d");
			foreach($list as $i)
			{
				$i->id = encrypt($i->id);
				if( $now <= $i->date )
					$i->value = "";

				$i->date = date("d/m/Y", strtotime($i->date));
			}

			return $list;
		}
	}
	
?>