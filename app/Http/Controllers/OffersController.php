<?php namespace App\Http\Controllers;

use DB;
use App\Classe;
use App\Period;
use App\Course;
use App\Offer;
use App\User;
use App\Lecture;
use App\Unit;
use App\Attend;

class OffersController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I')->except('postOffersGrouped');
		$this->middleware('auth.type:IP')->only('postOffersGrouped');
	}

	public function getUser()
	{
		$user = decrypt(request()->get("u"));
		if ($user) {
			$user = User::find($user);
			return $user;
		} else {
			return redirect("/");
		}
	}

	public function getIndex()
	{
			$user = auth()->user();
			$classe = Classe::find(decrypt(request()->get("t")));
			$period = Period::find($classe->period_id);
			$course = Course::find($period->course_id);
			$offers = Offer::where("class_id", $classe->id)->get();

			foreach ($offers as $offer) {
				$teachers = [];
				$list = Lecture::where("offer_id", $offer->id)->get();
				foreach ($list as $value) {
					$teachers[] = base64_encode($value->user_id);
				}
				$offer->teachers = $teachers;
				if (isset($offer->offer_id) && !empty($offer->offer_id)) {
					$offer->offer = Offer::find($offer->offer_id);
				}
			}

			return view("offers.institution", [
				"course" => $course,
				"user" => $user,
				"offers" => $offers,
				"period" => $period,
				"classe" => $classe,
			]);
	}

	public function getUnit($offer)
	{
		$offer = Offer::find(decrypt($offer));
		if (auth()->id() != $offer->getClass()->getPeriod()->course->institution_id) {
			return redirect("/classes/offers?t=" . encrypt($offer->class_id))->with("error", "Você não tem permissão para criar unidade");
		}

		$old = Unit::where("offer_id", $offer->id)->orderBy("value", "desc")->first();

		$unit = new Unit;
		$unit->offer_id = $offer->id;

		if(!$old) {
			$unit->value = 1;
			$unit->calculation = 'A';
		}
		else {
			$unit->value = $old->value + 1;
			$unit->calculation = $old->calculation;
		}
		$unit->save();

		if($old) {
			$attends = Attend::where("unit_id", $old->id)->get();

			foreach ($attends as $attend) {
				$new = new Attend;
				$new->unit_id = $unit->id;
				$new->user_id = $attend->user_id;
				$new->save();
			}
		}


		return redirect("/classes/offers?t=" . encrypt($offer->class_id))->with("success", "Unidade criada com sucesso!");
	}

	public function postTeacher()
	{
		$offer = Offer::find(decrypt(request()->get("offer")));
		$offer->classroom = request()->get("classroom");
		$offer->day_period = request()->get("day_period");
		$offer->maxlessons = request()->get("maxlessons");
		$offer->save();
		$lectures = $offer->getAllLectures();

		$teachers = [];
		if (request()->has("teachers")) {
			$teachers = request()->get("teachers");
			for ($i = 0; $i < count($teachers); $i++) {
				$teachers[$i] = base64_decode($teachers[$i]);
			}

		}

		foreach ($lectures as $lecture) {
			$find = array_search($lecture->user_id, $teachers);
			if ($find === false) {
				Lecture::where('offer_id', $offer->id)->where('user_id', $lecture->user_id)->delete();
			} else {
				unset($teachers[$find]);
			}

		}

		foreach ($teachers as $teacher) {
			$last = Lecture::where("user_id", $teacher)->orderBy("order", "desc")->first();
			$last = $last ? $last->order + 1 : 1;

			$lecture = new Lecture;
			$lecture->user_id = $teacher;
			$lecture->offer_id = $offer->id;
			$lecture->order = $last;
			$lecture->save();
		}

		return redirect(request()->get("prev"))->with("success", "Modificado com sucesso!");
	}

	public function postStatus()
	{
		$status = request()->get("status");
		$id = decrypt(request()->get("unit"));

		$unit = Unit::find($id);
		if (!strcmp($status, 'true')) {
			$unit->status = 'E';
		} else {
			$unit->status = 'D';
		}
		$unit->save();

		return "Status changed to " . $status . " / " . $id;
	}

	public function getStudents($offer)
	{
		$user = auth()->user();

		$info = DB::select("SELECT courses.name as course, periods.name as period, classes.id as class_id, classes.class as class
			from courses, periods, classes, offers
			where courses.id = periods.course_id
			and periods.id = classes.period_id
			and classes.id = offers.class_id
			and offers.id = " . decrypt($offer)
		);
		$students = DB::select("SELECT DISTINCT users.id as id, users.name as name, attends.status as status
			from users, attends, units
			where users.id=attends.user_id
			and attends.unit_id = units.id
			and units.offer_id = " . decrypt($offer) . " ORDER BY users.name"
		);

		return view('modules.liststudentsoffers', [
			'user' => $user,
			'info' => $info,
			'students' => $students,
			'offer' => $offer,
		]);
	}

	public function postStatusStudent()
	{
		$offer = decrypt(request()->get("offer"));
		$student = decrypt(request()->get("student"));
		$units = Unit::where("offer_id", $offer)->get();

		if (request()->get("status") == 'M') {
			foreach ($units as $unit) {
				Attend::where('unit_id', $unit->id)->where('user_id', $student)->update(["status" => 'M']);
			}

		}

		if (request()->get("status") == 'D') {
			foreach ($units as $unit) {
				Attend::where('unit_id', $unit->id)->where('user_id', $student)->update(["status" => 'D']);
			}

		}

		if (request()->get("status") == 'T') {
			foreach ($units as $unit) {
				Attend::where('unit_id', $unit->id)->where('user_id', $student)->update(["status" => 'T']);
			}

		}

		if (request()->get("status") == 'R') {
			foreach ($units as $unit) {
				Attend::where("unit_id", $unit->id)->where("user_id", $student)->delete();
			}

			return redirect()->back()->with('success', 'Aluno removido com sucesso');
		}
		return redirect()->back()->with('success', 'Status atualizado com sucesso');
	}

	public function anyDeleteLastUnit($offer)
	{
		$offer = Offer::find(decrypt($offer));

		$unit = Unit::where('offer_id', $offer->id)
			->orderBy('value', 'desc')
			->first();

		$msg = 'Não possui unidade para ser deletada';
		if ($unit) {
			$unit->delete();
			$msg = 'Unidade deletada com sucesso!';
		}

		return redirect('/classes/offers?t=' . encrypt($offer->class_id))
			->with('success', $msg);
	}

	public function postOffersGrouped() {
		if(!request()->has('group_id')) {
			return ['status'=> 0];
		}

		$groupId = request()->get('group_id');
		$offers = Offer::where('offer_id', decrypt($groupId))
			->where('grouping', 'S')
			->with('discipline')
			->get();

		return ['status' => 1, 'offers' => $offers];
	}

}
