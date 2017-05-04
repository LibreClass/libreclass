<?php

class UnitsController extends \BaseController
{

  private $idUser;
  private $unit;

  public function __construct()
  {
    if (Input::has("u")) {
      $this->unit = Unit::find(Crypt::decrypt(Input::get("u")));
    }

    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }

  }

  public function getIndex()
  {
    if ($this->idUser && Input::has("u")) {
      $user = User::find($this->idUser);
      $unit_current = Unit::find(Crypt::decrypt(Input::get("u")));

      if ($unit_current->status == "D") {
        return Redirect::to("lectures")->with("error", "Esta unidade está desativada");
      }

      $units = Unit::where("idOffer", $unit_current->idOffer)->get();
      $list_units = [];
      foreach ($units as $unit) {
        $list_units[] = $unit->value;
      }

      $lessons = Lesson::where("idUnit", $unit_current->id)->whereStatus('E')->orderBy("date", "desc")->orderBy("id", "desc")->get();
      $recovery = Exam::where("idUnit", $unit_current->id)->whereAval("R")->first();
      $exams = Exam::where("idUnit", $unit_current->id)->whereStatus('E')->whereAval("A")->orderBy("id", "desc")->get();
      return View::make("modules.panel", ["user" => $user,
        "list_units" => $list_units,
        "unit_current" => $unit_current,
        "lessons" => $lessons,
        "recovery" => $recovery,
        "exams" => $exams]);
    } else {
      return Redirect::guest("/");
    }

  }

  /*
  edita a unidade. O único atributo editável é a forma de calcular a média
   */
  public function postEdit()
  {
    try
    {
      $this->unit->calculation = Input::get("calculation");
      $this->unit->save();
      return Response::json(true);
    } catch (Exception $e) {
      return Response::json(false);
    }
  }

  public function getNew()
  {
    if (Input::has("offer")) {
      $offer = Crypt::decrypt(Input::get("offer"));
      $old = Unit::where("idOffer", $offer)->orderBy("value", "desc")->first();

      $unit = new Unit;
      $unit->idOffer = $old->idOffer;
      $unit->value = $old->value + 1;
      $unit->calculation = $old->calculation;
      $unit->save();

      $attends = Attend::where("idUnit", $old->id)->get();

      foreach ($attends as $attend) {
        $new = new Attend;
        $new->idUnit = $unit->id;
        $new->idUser = $attend->idUser;
        $new->save();
      }

      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit->id));
    }
  }

  public function getStudent()
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);

      $students = User::whereType("N")->orderby("name")->get();
      $list_students = [];
      foreach ($students as $student) {
        $list_students[Crypt::encrypt($student->id)] = $student->name;
      }

      $students = DB::select("SELECT Users.name as name, Users.id as id FROM Users, Attends WHERE Users.id=Attends.idUser AND Attends.idUnit = " . $this->unit->id . " ORDER BY Users.name");

      return View::make("modules.units", ["user" => $user, "list_students" => $list_students, "students" => $students]);
    } else {
      return Redirect::guest("/");
    }
  }

  public function postRmstudent()
  {
    //~ return Input::all();
    $unit = Crypt::decrypt(Input::get("unit"));
    $student = Crypt::decrypt(Input::get("student"));

    Attend::where("idUnit", $unit)->where("idUser", $student)->delete();

    return Redirect::to("lectures/units/student?u=" . Input::get("unit"))
      ->with("success", "Aluno removido com sucesso");
  }

  public function postAddstudent()
  {
    $unit = Crypt::decrypt(Input::get("unit"));
    $student = Crypt::decrypt(Input::get("student"));

    $attend = Attend::where("idUnit", $unit)->where("idUser", $student)->first();
    if ($attend) {
      return Redirect::to("lectures/units/student?u=" . Input::get("unit"))
        ->with("error", "Aluno já cadastrado");
    } else {
      $attend = new Attend;
      $attend->idUnit = $unit;
      $attend->idUser = $student;
      $attend->save();
      return Redirect::to("lectures/units/student?u=" . Input::get("unit"))
        ->with("success", "Aluno cadastrado com sucesso");
    }
  }

  public function getNewunit()
  {
    return "nova unidade";
  }

  public function getReportunitz()
  {
    if ($this->idUser) {
      $user = User::find($this->idUser);

      $students = DB::select("select Users.id, Users.name "
        . "from Users, Attends, Units "
        . "where Units.id=? and Attends.idUnit=Units.id and Attends.idUser=Users.id "
        . "group by Users.id order by Users.name", [Crypt::decrypt(Input::get("u"))]);

      return $students;

    }
  }

  /**
   * Retorna um PDF com o relatório de frequência e notas
   * @param int $idUnit - Id da unidade (unidade da disciplina ofertada)
   * @return file
   */
  public function getReportUnit($idUnit)
  {
    try {
      $unit = Unit::find(Crypt::decrypt($idUnit));
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
    } catch (Exception $e) {
      return $e->getMessage();
    }
  } //--- Imprimir PDF

  private function printDefaultReport(Unit $unit)
  {
    try {
      $data = [];
      $institution = $unit->offer->classe->period->course->institution()->first();
      $institution->local = $institution->printCityState();
      $data['institution'] = $institution;
      $data['classe'] = $unit->offer->getClass();
      $data['period'] = $unit->offer->classe->getPeriod();
      $data['course'] = $unit->offer->classe->period->getCourse();

      $offer = Offer::find($unit->idOffer);

      $students = DB::select(""
        . " SELECT Users.id, Users.name "
        . " FROM Users, Attends, Units "
        . " WHERE Units.idOffer=? AND Attends.idUnit=Units.id AND Attends.idUser=Users.id "
        . " GROUP BY Users.id "
        . " ORDER BY Users.name ASC", [$offer->id]
      );
      $data['students'] = [];
      foreach ($students as $student) {
        $data['students'][] = $student;
      }

      $lessons = $unit->getLessonsToPdf();

      // Prepara o nome das aulas com a data de realização das mesmas
      $data['lessons'] = [];
      foreach ($lessons as $key => $lesson) {
        $date = explode('-', $lesson->date)[2] . '/' . explode('-', $lesson->date)[1] . '/' . explode('-', $lesson->date)[0];
        $data['lessons'][$key] = 'Aula ' . (string) ($key + 1) . ' - ' . $date;
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

        $exams = $unit->getExams();
        $data['exams'] = [];
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
        // dd($data['students'][$key]);

        // Registra a média e a média final após prova de recuperação
        $average = $unit->getAverage($student->id);
        $data['students'][$key]->average = empty($average[0]) ? "-" : sprintf("%.2f", $average[0]);
        $data['students'][$key]->finalAverage = empty($average[1]) ? "-" : sprintf("%.2f", $average[1]);

        // Quantidade total de faltas
        $data['students'][$key]->countAbsences = (string) $absences;
      }
      // dd($data['students']);

      $pdf = PDF::loadView('reports.arroio_dos_ratos-rs.class_diary', ['data' => $data])
        ->setPaper('a4')
        ->setOrientation('landscape')
        ->setOption('margin-top', 5)
        ->setOption('margin-right', 5)
        ->setOption('margin-bottom', 5)
        ->setOption('margin-left', 5);
      return $pdf->stream();

      // return View::make('reports.arroio_dos_ratos-rs.class_diary', ['data' => $data]);

    } catch (Exception $e) {
      return View::make("reports.report_error", [
        "message" => $e->getMessage() . ' ' . $e->getLine(),
      ]);
    }
  }

  private function printDescriptiveReport(Unit $unit)
  {
    try {
      $data = [];

      $exams = $unit->getExams();
      if (count($exams) == 0) {
        throw new Exception('É necessário criar pelo menos uma <b>avaliação</b> para gerar o relatório de parecer descritivo.');
      }
      $unit->count_lessons = $unit->countLessons();
      $lessons = $unit->getLessons();

      $institution = $unit->offer->classe->period->course->institution()->first();
      $institution->local = $institution->printCityState();

      if (!isset($institution->photo) || empty($institution->photo)) {
        throw new Exception('A Instituição não concluiu o cadastro, pois não identificamos a <b>foto de perfil</b> que é utilizada para construir o relatório.');
      }

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

      $data['institution'] = $institution;
      $data['unit'] = $unit;
      $data['discipline'] = $unit->offer->discipline->name;
      $data['teachers'] = $unit->offer->getTeachers();

      $pdf = PDF::loadView('reports.arroio_dos_ratos-rs.descriptive_exam', ['data' => $data]);
      return $pdf->stream();
    } catch (Exception $e) {
      return View::make("reports.report_error", [
        "message" => $e->getMessage(),
      ]);
    }
  }

}
