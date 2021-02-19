<?php namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Course;
use App\Period;
use Exception;

class CoursesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	public function index()
	{
		
		$courses = Course::query()
			->where("institution_id", auth()->id())
			->whereStatus("E")
			->orderBy("name")
			->get();

		$listcourses = [];

		foreach ($courses as $course) {
			$listcourses[encrypt($course->id)] = $course->name;
			$course->periods = Period::where("course_id", $course->id)->get();
		}

		/*
		//Declaração conforme Laravel 8
		foreach ($courses as $course) {
			dd($course);
			
			$listcourses[Crypt::encryptString($course->id)] = $course->name;
			
			$course->periods = Period::query()->where("course_id", $course->id)->get();
		}
		*/		

		return view("social.courses", [
			"courses" => $courses,
			"user" => auth()->user(),
			"listcourses" => $listcourses,
		]);
	}

	public function allCourses()
	{
		$courses = Course::where("institution_id", auth()->id())
			->whereStatus("E")
			->orderBy("name")
			->get();

		foreach($courses as $course) {
			$course->id = encrypt($course->id);
		}

		return $courses;
	}

	public function save(Request $request)
	{
		$course = null;

		if (strlen($request->get("course"))) {
			$course = Course::find(decrypt($request->get("course")));
		} else if (auth()->user()->type == "I") {
			$course = new Course;
		} else {
			return redirect("/courses")
				->with("error", "Adquira uma conta <i>premium</i> para completar essa operação.");
		}

		$course->institution_id = auth()->id();
		$course->name = $request->get("name");
		$course->type = $request->get("type");
		$course->quant_unit = $request->get("quant_unit");
		$course->modality  = $request->get("modality");
		$course->absent_percent = $request->get("absent_percent");
		$course->average = $request->get("average");
		$course->average_final = $request->get("average_final");
		$course->curricular_profile = "";
		$course->status = 'E';
		$course->save();

		if (
			$request->hasFile("curricularProfile") &&
			$request->file("curricularProfile")->getClientOriginalExtension() === "pdf"
		) {
			$name = md5($course->id) . ".pdf";
			$request->file("curricularProfile")->move(public_path("/uploads/curricularProfile/"), $name);
			$course->curricular_profile = $name;
			$course->save();
		} else if ($request->hasFile("curricularProfile")) {
			return redirect("/courses")
				->with("error", "Problema ao realizar upload de arquivo");
		}

		// Este return é realizado ao inserir novo curso ou editar um curso existente
		return redirect("/courses")
			->with("success", "Curso $course->name salvo com sucesso!");
	}

	public function delete(Request $request)
	{
		$course = Course::find(decrypt($request->get("input-trash")));
		if ($course) {
			$course->status = "D";
			$course->save();
			return redirect("/courses")
				->with("success", "Excluído com sucesso!");
		}
		else {
			return redirect("/courses")
				->with("error", "Não foi possível deletar");
		}
	}

	public function edit(Request $request)
	{
		return Course::find(decrypt($request->get("course")));
	}

	public function period(Request $request)
	{
			$course = Course::find(decrypt($request->get("course")));
			if( $course->institution_id != auth()->id() ) {
				throw new Exception("Esse usuário nao tem acesso ao curso.");
			}

			$periods = Period::where("course_id", $course->id)->get();

			foreach ($periods as $period) {
				$period->id = encrypt($period->id);
				unset($period->course_id);
				unset($period->created_at);
				unset($period->updated_at);
			}

			return $periods;
	}

	public function editperiod(Request $request)
	{
			$course = Course::find(decrypt($request->get("course")));
			if ($course->institution_id != auth()->id()) {
				throw new Exception("Esse usuário nao tem aceso ao curso.");
			}

			if ($request->has("key")) {
				$period = Period::find(decrypt($request->get("key")));
			} else {
				$period = new Period;
			}

			$period->name = $request->get("value");
			$period->course_id = $course->id;
			$period->save();

			return "ok";
	}
}
