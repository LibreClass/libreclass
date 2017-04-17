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
    // Array utilizado para imprimir PDF
    $info = [];

    $user = User::find($this->idUser);
    $offer = Offer::find($unit->idOffer);
    $class = $offer->getClass();
    $discipline = $offer->getDiscipline();
    $period = $discipline->getPeriod();
    $course = $period->getCourse();
    $institution = $course->getInstitution();
    $institution->city = $institution->printLocation();
    $teacher = $offer->getLectures() ? User::find($offer->getLectures()->idUser) : null;

    // Período do dia
    if ($offer->day_period == "M") {
      $offer->day_period = "Matutino";
    } else if ($offer->day_period == "V") {
      $offer->day_period = "Vespertino";
    } else if ($offer->day_period == "N") {
      $offer->day_period = "Noturno";
    }

    //  if ($offer->getLectures()->idUser != $this->idUser) {
    //    return Redirect::to("/lectures")->with("error", "Você não tem acesso a essa página");
    //  }

    $students = DB::select("select Users.id, Users.name "
      . "from Users, Attends, Units "
      . "where Units.idOffer=? and Attends.idUnit=Units.id and Attends.idUser=Users.id "
      . "group by Users.id order by Users.name", [$offer->id]);

    $lessons = $unit->getLessonsToPdf();

    $exams = $unit->getExams();
    $countLessons = $unit->countLessons();

    $street = empty($institution->city) ? $institution->street . ", " . $institution->city : $institution->city;

    // Prepara informações do cabeçalho
    $classInfo = [
      "institution_name" => $institution->name,
      "institution_street" => $street,
      "institution_uee" => $institution->uee,
      "periodo_letivo" => $class->class,
      "tipo_de_ensino" => $course->type,
      "modalidade" => $course->modality,
      "submodalidade" => $course->name,
      "serie" => $period->name,
      "turma" => $class->name,
      "periodo_dia" => $offer->day_period,
      "disciplina" => $discipline->name,
      "professor" => $teacher ? $teacher->name : "",
      "unidade" => $unit->value,
      "maxlessons" => $offer->maxlessons,
      "toughtLessons" => $countLessons,
    ];

    // Formata datas de provas para impressão
    $examsDate = [0 => "", 1 => "", 2 => "", 3 => ""];
    for ($i = 0; $i < 4; $i++) {
      if (isset($exams[$i])) {
        $tmpExamDate = explode("-", $exams[$i]->date);
        $examsDate[$i] = $tmpExamDate[2] . "/" . $tmpExamDate[1] . "/" . $tmpExamDate[0];
      }
    }

    $num = 0;
    // Percorre a lista de todos os alunos
    foreach ($students as $student) {
      $faltas = 0;
      $info[$num]["num"] = $num + 1;
      $info[$num]["name"] = $student->name;

      // Prepara o array com as frequências do aluno
      for ($i = 0; $i < 45; $i++) {
        if (isset($lessons[$i])) {
          $value = Frequency::getValue($student->id, $lessons[$i]->id);
          if ($value == "F") {
            $faltas++;
          }
          $info[$num]["lesson-" . ($i + 1)] = ($value == "P") ? "." : $value;
        } else {
          $info[$num]["lesson-" . ($i + 1)] = ".";
        }
      }

      // Prepara o array com as avaliações do aluno
      for ($i = 0; $i < 4; $i++) {
        if (isset($exams[$i])) {
          $info[$num]["exam-" . ($i + 1)] = ExamsValue::getValue($student->id, $exams[$i]->id);
        } else {
          $info[$num]["exam-" . ($i + 1)] = "-";
        }
      }
      $average = $unit->getAverage($student->id);
      $info[$num]["average"] = empty($average[0]) ? "-" : sprintf("%.2f", $average[0]);
      $info[$num]["final-average"] = empty($average[1]) ? "-" : sprintf("%.2f", $average[1]);

      // Quantidade total de faltas
      $info[$num]["absence"] = (string) $faltas;
      $num++;
    }

    $fpdf = new \reports\cetep\Offer($classInfo, $examsDate);
    $fpdf->AddPage();
    $fpdf->SetMargins(1, 1, 1);
    $fpdf->SetAutoPageBreak(true, 1);
    $fpdf->SetAuthor('LibreClass');
    $fpdf->SetTitle('LibreClass Report - ' . date('Y-m-d'));
    $fpdf->SetFont('Times', '', 8);

    // Imprime no PDF as informações de cada aluno
    foreach ($info as $studentInfo) {
      echo $fpdf->insertStudent($studentInfo);
    }

    // Imprime as notas de aula
    $i = 1;
    foreach ($lessons as $lesson) {
      // if (!empty($lesson->notes)) {
      $fpdf->insertLessonNotes($i, $lesson->title, $lesson->notes);
      // }
      $i++;
    }

    $fpdf->signatureField();
    // echo print_r($studentInfo, true);
    $fpdf->Output('Diário_' . date('Y-m-d') . '.pdf', 'I');

    return;
    //  return View::make("modules.frequency", ["user" => $user, "offer" => $offer, "unit" => $unit, "students" => $students]);
    //  return $offer;
  }

  private function printDescriptiveReport(Unit $unit)
  {
    try {
      $data = [];

      $exams = $unit->getExams();
      $unit->count_lessons = $unit->countLessons();
      $lessons = $unit->getLessons();

      $institution = $unit->offer->classe->period->course->institution()->first();
      $institution->local = $institution->printCityState();

      if (!isset($institution->photo) || empty($institution->photo)) {
        throw new Exception('Erro! Instituição não concluiu o cadastro. Foto/logotipo não identificado.');
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
      return $e->getMessage();
    }
  }

}
