<?php

class CSVController extends \BaseController {

  /**
   * Armazena o ID do usuário
   * @var type num
   */
  private $user;

  public function CSVController() 
  {
    $id = Session::get("user");
    $this->user = User::find(Crypt::decrypt($id));
  }

  /**
   * mostra view de importação de CSV
   * 
   * @return type
   */
  public function getIndex() 
  {
    Session::forget("structure");
    return View::make("modules.import",["user" => $this->user]);
//  Form::open(["enctype" => "multipart/form-data"]) . Form::file("csv") . Form::submit("Enviar") . Form::close();
  }

  /**
   * Recebe CSV com lista de alunos, valida, salva na session e mostra para confirmação do usuário
   * 
   * @return html com dados para confirmação
   * @throws caso não esteja no padrão
   */
  public function postIndex() 
  {
    try 
    {
      $csv = utf8_encode(file_get_contents(Input::file("csv")));
      if (!strpos($csv, "RM;RA;Nome Aluno;")) 
      {
        throw new Exception("Arquivo inválido");
      }

      $course = null;
      $period = null;
      $year   = null;

      $csv = explode("\n", $csv);

      $result = [];
      foreach ($csv as $line) 
      {
        $cols = explode(";", $line);
        if ($cols[0] == "Período Letivo:") 
        {
          $year = $cols[1];
        }
        else if ($cols[0] == "SubModalidade:") 
        {
//          return $cols[1];
          $course = Course::where("idInstitution", $this->user->id)->whereName($cols[1])->first();
          if (!$course) {
            throw new Exception("Não possui o curso: " . $cols[1]);
          }
        }
        else if ($cols[0] == "Série:") 
        {
          $period = Period::where("idCourse", $course->id)->whereName(explode(" - ", $cols[1])[0])->first();
          if (!$period) {
            throw new Exception("Não possui o periodo/série: " . explode(" - ", $cols[1])[0]);
          }
          $cols[3] = "[". date("Y") . "] " . $cols[3];
          $class = Classe::where("idPeriod", $period->id)->whereClass($cols[3])->first();
          if (!$class) {
            throw new Exception("Não possui a turma: " . $cols[3]);
          }
//        $units = DB::select("SELECT * FROM Units, Offers
//                             WHERE Offers.idClass=? AND Units.idOffer=Offers.id ORDER BY Units.value DESC", [$class->id]);
        }
        elseif (is_numeric($cols[0])) 
        {
          $cols[3] = $this->firstUpper($cols[3]);
          $cols[4] = substr($cols[4], 0, 1);
          $cols[5] = explode("/", $cols[5]);
          $cols[5] = $cols[5][2] . "-" . $cols[5][1] . "-" . $cols[5][0];
          $student = DB::select("SELECT Users.id FROM Relationships, Users
                                 WHERE Relationships.idUser=? AND
                                 Relationships.idFriend=Users.id AND
                                 Users.enrollment=?", [ $this->user->id, $cols[1] ]);
          $result[] = [
            $cols[1], // matricula
            $cols[3], // nome
            $cols[4], // sexo
            $cols[5], // data nascimento
            count($student) ? $student[0]->id : 0, // se o estudante existe no sistema
            $class->name, // turma
            $class->id
          ]; // id turma
        }
      }
      Session::put("attends", $result);
      return View::make("modules.import/students",["user" => $this->user, "students" => $result]);
    }
    catch(Exception $e) 
    {
      return Redirect::to("/import")->with("error", $e->getMessage());
    }
  }

  /**
   * Pega lista de alunos da session e faz a matricula na turma, tambem faz o cadastro caso precise
   * 
   * @return type página com erro ou confirmação
   */
  public function getConfirmattends() 
  {
    $units = null;
    if (Session::has("attends")) 
    {
      $attends = Session::get("attends");
//    print_r($attends); return;
      foreach ($attends as $attend) 
      {
        if (!$attend[4])
        {
          $student = DB::select("SELECT Users.id FROM Relationships, Users
                                 WHERE Relationships.idUser=? AND
                                 Relationships.idFriend=Users.id AND
                                 Users.enrollment=?", [ $this->user->id, $attend[0] ]);
          if (count($student))
          {
            $attend[4] = $student[0]->id;
          }
          else
          {
            $student = new User;
            $student->type = "N";
            $student->enrollment = $attend[0];
            $student->name = $attend[1];
            $student->gender = $attend[2];
            $student->birthdate = $attend[3];
            $student->save();
            $attend[4] = $student->id;
            $relationship = new Relationship;
            $relationship->idUser = $this->user->id;
            $relationship->idFriend = $student->id;
            $relationship->type = "1";
            $relationship->save();
          }
        }
        if (!($units and $units[0]->idClass == $attend[6])) 
        {
          $units = DB::select("SELECT Units.id as id, Offers.idClass as idClass FROM Units, Offers
                               WHERE Offers.idClass=? AND Units.idOffer=Offers.id ORDER BY Units.value DESC", [$attend[6]]);
        }
        foreach ($units as $unit) {
          if (!Attend::where("idUnit", $unit->id)->where("idUser", $attend[4])->first()) {
            $novo = new Attend;
            $novo->idUnit = $unit->id;
            $novo->idUser = $attend[4];
            $novo->save();
          }
        }
      }
      return Redirect::to("/import")->with("success", "Alunos matriculados com sucesso.");
    }
    else
      return Redirect::to("/import")->with("error", "Algum erro aconteceu, tente novamente.");
  }

  /**
   * Recebe csv de importção de professores, disciplinas e ofertas, organiza, salva na session e mostra lista de disciplinas
   * 
   * @return view com lista de disciplinas
   * @throws caso o arquivo seja invalido
   */
  public function postClasswithteacher() 
  {
    try {
      $csv = utf8_encode(file_get_contents(Input::file("csv")));
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
                                                           WHERE Courses.idInstitution=? AND Courses.name=? AND
                                                           Courses.id=Periods.idCourse AND Periods.name=? AND
                                                           Periods.id=Disciplines.idPeriod AND Disciplines.name=?",
                                                          [$this->user->id, $cod[2], $cod[1], $disc])[0]->qtd;
  //      print_r($out); return;
          $offer[] = $out;
        }
        $structure[] = [$cod, $offer];
      }
      Session::put("structure", $structure);
      return View::make("modules.import.structure",["user" => $this->user, "school" => $school]);
    }
    catch (Exception $e) {
      return Redirect::to("/import")->with("error", "Arquivo inválido!");
    }
  }

  /**
   * salva diciplinas, periodos e cursos e mostra view de professores
   * 
   * @return view com professores que serão adicionados
   */
  public function getTeacher() 
  {
    $structure = Session::get("structure");
    $teachers = [];
    foreach ($structure as $class) {
      $course = Course::where("idInstitution", $this->user->id)->whereName($class[0][2])->first();
      if (!$course) {
        $course = new Course;
        $course->name = $class[0][2];
        $course->idInstitution = $this->user->id;
        $course->absentPercent = 25;
        $course->average = 7;
        $course->averageFinal = 5;
        $course->save();
      }
      $period = Period::where("idCourse", $course->id)->where("name", $class[0][1])->first();
      if (!$period) {
        $period = new Period;
        $period->idCourse = $course->id;
        $period->name = $class[0][1];
        $period->save();
      }
      foreach ($class[1] as $offer) {
        foreach ($offer[8] as $disc) {
          $discipline = Discipline::where("idPeriod", $period->id)->where("name", $disc)->first();
          if (!$discipline) {
            $discipline = new Discipline;
            $discipline->idPeriod = $period->id;
            $discipline->name = $disc;
            $discipline->save();
          }
        }
        $teachers[$offer[0]] = [
          $offer[1],
          DB::select("SELECT count(*) as 'qtd' FROM Relationships, Users
                      WHERE Relationships.idUser=? AND
                      Relationships.idFriend=Users.id AND
                      Relationships.type='2' AND
                      Users.enrollment=?", [$this->user->id, $offer[0]])[0]->qtd
          ];
      }
    }
    return View::make("modules.import.teacher", ["user" => $this->user, "teachers" => $teachers]);
  }

  /**
   * Salva professores que ainda não estão cadastrados e mostra view de ofertas
   * 
   * @return view com o que vai ser ofertado
   */
  public function getOffer() 
  {
    $structure = Session::get("structure");
    foreach ($structure as $class) {
//    $course = Course::where("idInstitution", $this->user->id)->whereName($class[0][2])->first();
//    $period = Period::where("idCourse", $course->id)->where("name", $class[0][1])->first();
      foreach ($class[1] as $offer) {
//      foreach ( $offer[8] as $disc ) {
//        $discipline = Discipline::where("idPeriod", $period->id)->where("name", $disc)->first();
//      }
        $status = DB::select("SELECT count(*) as 'qtd' FROM Relationships, Users
                              WHERE Relationships.idUser=? AND
                              Relationships.idFriend=Users.id AND
                              Relationships.type='2' AND
                              Users.enrollment=?",[$this->user->id, $offer[0]])[0]->qtd;
        if (!$status) {
          $user = new User;
          $user->name = $offer[1];
          $user->type = 'M';
          $user->cadastre = 'N';
          $user->enrollment = $offer[0];
          $user->save();
          $relationship = new Relationship;
          $relationship->idUser = $this->user->id;
          $relationship->idFriend = $user->id;
          $relationship->type = '2';
          $relationship->save();
        }
      }
    }
    return View::make("modules.import.offers", ["user" => $this->user, "structure" => $structure]);
  }

  public function getConfirmoffer()
  {
    $structure = Session::get("structure");
    foreach ($structure as $class) {
      $course = Course::where("idInstitution", $this->user->id)->whereName($class[0][2])->first();
      $period = Period::where("idCourse", $course->id)->where("name", $class[0][1])->first();
      $classe = Classe::where("idPeriod", $period->id)->whereClass($class[0][0])->first();
      if ( !$classe )
      {
        $classe = new Classe;
        $classe->idPeriod = $period->id;
        $classe->class = $class[0][0];
        $classe->save();
      }
      foreach ($class[1] as $offer_aux) 
      {
        $teacher = DB::select("SELECT Users.id FROM Relationships, Users
                               WHERE Relationships.idUser=? AND
                               Relationships.idFriend=Users.id AND
                               Relationships.type='2' AND
                               Users.enrollment=?",[$this->user->id, $offer_aux[0]])[0];
        foreach ($offer_aux[8] as $disc)
        {
          $discipline = Discipline::where("idPeriod", $period->id)->where("name", $disc)->first();
          $offer = new Offer;
          $offer->idDiscipline = $discipline->id;
          $offer->classroom = $offer_aux[6];
          $offer->idClass = $classe->id;
          $offer->save();
          $lecture = new Lecture;
          $lecture->idUser = $teacher->id;
          $lecture->idOffer = $offer->id;
          $lecture->save();
          $unit = new Unit;
          $unit->idOffer = $offer->id;
          $unit->save();
        }
      }
    }
    return Redirect::to("/import")->with("success", "Turmas inseridas com sucesso");
  }

  /**
   * Padroniza nomes próprios deixando a primeira letra maiúscula
   * 
   * @param type $str string
   * @return type string
   */
  public function firstUpper($str)
  {
    $str = ucwords(mb_strtolower($str, "UTF-8"));
    $str = str_replace(" Da ", " da ", $str);
    $str = str_replace(" Das ", " das ", $str);
    $str = str_replace(" De ", " de ", $str);
    $str = str_replace(" Do ", " do ", $str);
    $str = str_replace(" Dos ", " dos ", $str);
    $str = str_replace(" E ", " e ", $str);
    $str = str_replace(" O ", " o ", $str);
    return $str;
  }
}
