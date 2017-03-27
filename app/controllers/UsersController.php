<?php

class UsersController extends \BaseController {

  private $idUser;

  public function UsersController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    }
    else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

  public function postSearchTeacher() {
    $teacher = User::where('email', Input::get('str'))->first();
    \Log::info('post search teacher', [$teacher]);
    if ($teacher) {
      $relationship = Relationship::where('idUser', $this->idUser)->where('idFriend', $teacher->id)->first();
      if (!$relationship) {
        return Response::json([
          'status' => 1,
          'teacher' => [
            'id' => Crypt::encrypt($teacher->id),
            'name' => $teacher->name,
            'formation' => $teacher->formation
          ],
          'message' => 'Este professor já está cadastrado no LibreClass e será vinculado à sua instituição.'
        ]);
      } else {
        return Response::json([
          'status' => -1,
          'teacher' => [
            'id' => Crypt::encrypt($teacher->id),
            'name' => $teacher->name,
            'formation' => $teacher->formation,
            'enrollment' => $relationship->enrollment
          ],
          'message' => 'Este professor já está vinculado à instituição!'
        ]);
      }
    } else {
      return Response::json([
        'status' => 0
      ]);
    }
  }

  public function anyTeachersFriends()
  {
    $teachers = DB::select("SELECT Users.id, Users.name, Users.photo, Relationships.enrollment as 'comment'"
                                . "FROM Users, Relationships "
                                . "WHERE Relationships.idUser=? AND Relationships.type='2' "
                                  . "AND Relationships.idFriend=Users.id "
                                  . "AND Relationships.status='E'"
                                . " ORDER BY name",
                                [$this->idUser]);
    foreach( $teachers as $teacher ){
    //  $teacher->selected = Lecture::where("idUser", $teacher->id)->where("idOffer", $offer)->count();
      $teacher->id = base64_encode($teacher->id);
    }

    return $teachers;
  }

  public function getTeacher()
  {
    if ($this->idUser)
    {
      $block = 30;
      $search = Input::has("search") ? Input::get("search") : "";
      $current = (int) Input::has("current") ? Input::get("current"): 0;
      $user = User::find($this->idUser);
      $courses = Course::where("idInstitution", $this->idUser)
                       ->whereStatus("E")
                       ->orderBy("name")
                       ->get();
      $listCourses = ["" => ""];
      foreach ($courses as $course)
      {
        $listCourses[$course->name] = $course->name;
      }

      $relationships = DB::select("SELECT Users.id, Users.name, Relationships.enrollment, Users.type "
                                  . "FROM Users, Relationships "
                                  . "WHERE Relationships.idUser=? AND Relationships.type='2' AND Relationships.idFriend=Users.id "
                                  . "AND Relationships.status='E' AND (Users.name LIKE ? OR Relationships.enrollment=?) "
                                  . " ORDER BY name LIMIT ? OFFSET ?",
                                  [$this->idUser, "%$search%", $search, $block, $current*$block ]);

      $length = DB::select("SELECT count(*) as 'length' "
                                  . "FROM Users, Relationships "
                                  . "WHERE Relationships.idUser=? AND Relationships.type='2' AND Relationships.idFriend=Users.id "
                                  . "AND (Users.name LIKE ? OR Relationships.enrollment=?) ", [$this->idUser, "%$search%", $search ]);

      return View::make(
        "modules.addTeachers",
        [
          "courses"       => $listCourses,
          "user"          => $user,
          "relationships" => $relationships,
          "length"        => (int) $length[0]->length,
          "block"         => (int) $block,
          "current"       => (int) $current
        ]
      );

    }
    else
    {
      return Redirect::guest("/");
    }
  }

  public function postTeacher()
  {
    // Verifica se o número de matrícula já existe

    if ( strlen(Input::get("teacher")) )
    {
      $user = User::find(Crypt::decrypt(Input::get("teacher")));
      if (strlen(Input::get("registered"))) {
        $relationship = Relationship::where('idUser', $this->idUser)->where('idFriend', $user->id)->first();
        if (!$relationship) {
          $relationship = new Relationship;
          $relationship->idUser   = $this->idUser;
          $relationship->idFriend = $user->id;
          $relationship->enrollment = Input::get('enrollment');
          $relationship->status   = "E";
          $relationship->type     = "2";
          $relationship->save();
        }
        return Redirect::guest("/user/teacher")->with("success", "Professor vinculado com sucesso!");
      }

      // Tipo P é professor com conta liberada. Ele mesmo deve atualizar as suas informações e não a instituição.
      if ($user->type == "P") {
        return Redirect::guest("/user/teacher")->with("error", "Professor não pode ser editado!");
      }
      $user->email = Input::get("email");
      // $user->enrollment = Input::get("enrollment");
      $user->name = Input::get("name");
      $user->formation = Input::get("formation");
      $user->gender = Input::get("gender");
      $user->save();
      return Redirect::guest("/user/teacher")->with("success", "Professor editado com sucesso!");
    } else {
      $verify = Relationship::whereEnrollment(Input::get("enrollment"))->where('idUser', $this->idUser)->first();
      if ( isset($verify) || $verify != null ) {
        return Redirect::guest("/user/teacher")->with("error", "Este número de inscrição já está cadastrado!");
      }
      $user = new User;
      $user->type = "M";
      // $user->email = Input::get("email");
      // $user->enrollment = Input::get("enrollment");
      $user->name = Input::get("name");
      $user->formation = Input::get("formation");
      $user->gender = Input::get("gender");
      if (Input::has("date-year")) {
        $user->birthdate = Input::get("date-year") . "-"
                         . Input::get("date-month") . "-"
                         . Input::get("date-day");
      }
      $user->save();

      $relationship = new Relationship;
      $relationship->idUser   = $this->idUser;
      $relationship->idFriend = $user->id;
      $relationship->enrollment = Input::get("enrollment");
      $relationship->status   = "E";
      $relationship->type     = "2";
      $relationship->save();

      $this->postInvite($user->id);

      return Redirect::guest("/user/teacher")->with("success", "Professor cadastrado com sucesso!");
    }
  }

  public function getProfileStudent()
  {
    $user = User::find($this->idUser);
    $profile = Crypt::decrypt(Input::get("u"));
    $classes = DB::select("SELECT Classes.id, Classes.name, Classes.class FROM Classes, Periods, Courses "
                      . "WHERE Courses.idInstitution=? AND Courses.id=Periods.idCourse AND Periods.id=Classes.idPeriod AND Classes.status='E'",
                        [$user->id]);
    $listclasses = [];
    $listidsclasses = [];
    foreach($classes as $class){
      $listclasses[$class->class] = $class->class;
      $listidsclasses[Crypt::encrypt($class->id)] = "[$class->class] $class->name";

    }

    if ( $profile ) {
      $profile = User::find($profile);
      $attests = Attest::where("idStudent", $profile->id)->where("idInstitution", $user->id)->orderBy("date", "desc")->get();
      return View::make("modules.profilestudent", ["user" => $user, "profile" => $profile, "listclasses" => $listclasses, "attests" => $attests, "listidsclasses" => $listidsclasses]);
    }else {
      return Redirect::guest("/");
    }
  }

  public function anyReporterStudentClass()
  {
    $student = Crypt::decrypt(Input::get("student"));
    $disciplines = DB::select("SELECT  Courses.id as course, Disciplines.name, Offers.id as offer, Attends.id as attend, Classes.status as statusclasse "
                      . "FROM Classes, Periods, Courses, Disciplines, Offers, Units, Attends "
                      . "WHERE Courses.idInstitution=? AND Courses.id=Periods.idCourse AND Periods.id=Classes.idPeriod AND Classes.class=? AND Classes.id=Offers.idClass AND Offers.idDiscipline=Disciplines.id AND Offers.id=Units.idOffer AND Units.id=Attends.idUnit AND Attends.idUser=? "
                      . "group by Offers.id",
                        [$this->idUser, Input::get("class"), $student ]);

    foreach($disciplines as $discipline)
    {
      $sum = 0;
      $discipline->units = Unit::where("idOffer", $discipline->offer)->get();
      foreach($discipline->units as $unit)
      {
        $unit->exams = Exam::where("idUnit", $unit->id)->orderBy("aval")->get();
        foreach($unit->exams as $exam)
          $exam->value = ExamsValue::where("idExam", $exam->id)->where("idAttend", $discipline->attend)->first();
        $value = $unit->getAverage($student);
        // return $value;
        $sum += isset($value[1]) ? $value[1] : $value[0];
      }
      $discipline->average = sprintf("%.2f", ($sum+.0)/count($discipline->units));
      $discipline->final = FinalExam::where("idUser", $student)->where("idOffer", $discipline->offer)->first();
      $offer = Offer::find($discipline->offer);
      $discipline->absencese = sprintf("%.1f", (100.*($offer->maxlessons - $offer->qtdAbsences($student)))/$offer->maxlessons);

      $course = Course::find($discipline->course);
      $discipline->course = $course;
      $discipline->aproved = "-";
      if ($discipline->statusclasse == "C") {
        $discipline->aproved = "Aprovado";
        if ($discipline->absencese + $course->absentPercent < 100)
          $discipline->aproved = "Reprovado";
        if ( $discipline->average < $course->average and (!$discipline->final or $discipline->final->value < $course->averageFinal) )
          $discipline->aproved = "Reprovado";
      }
    }
    return View::make("institution.reportStudentDetail", ["disciplines" => $disciplines]);
  }

  public function getReporterStudentOffer()
  {
    return Input::all();
  }

  public function postProfileStudent()
  {
    try {
      $idUser = (int)Crypt::decrypt(Input::get("student"));

      foreach(Input::get("offers") as $offer)
      {
        $units = Unit::where("idOffer", Crypt::decrypt($offer))->get();
        foreach ($units as $unit)
        {
          $attend = Attend::where("idUser", $idUser)->where("idUnit", $unit->id)->first();
          if ( $attend )
          {
            $disc = Offer::find(Crypt::decrypt($offer))->getDiscipline();
            //$status = ["E" => "Cursando", "D" => "Disabilitado"];
            return Redirect::back()
                           ->with("error", "O aluno não pode ser inserido.<br>"
                           . "O aluno já está matriculado na oferta da disciplina <b>" . $disc->name . "</b>.");//. " com o status " . $attend->status . ".");
          }
        }
      }



      foreach(Input::get("offers") as $offer)
      {
        $units = Unit::where("idOffer", Crypt::decrypt($offer))->get();
        foreach ($units as $unit)
        {
          $attend = new Attend;
          $attend->idUser = $idUser;
          $attend->idUnit = $unit->id;
          $attend->save();
          $exams = Exam::where("idUnit", $unit->id)->get();
          foreach ($exams as $exam)
          {
            $value = new ExamsValue;
            $value->idExam = $exam->id;
            $value->idAttend = $attend->id;
            $value->save();
          }
          $lessons = Lesson::where("idUnit", $unit->id)->get();
          foreach ($lessons as $lesson)
          {
            $value = new Frequency;
            $value->idLesson = $lesson->id;
            $value->idAttend = $attend->id;
            $value->save();
          }
        }
      }
      return Redirect::back()->with("success", "Inserido com sucesso!");
    }
    catch (Exception $ex)
    {
      return Redirect::back()->with("error", "Ocorreu algum erro inesperado.<br>Informe o suporte.");
    }
  }

  /**
   * Cadastra um atestada e retorna para a página anterior
   */
  public function postAttest()
  {
    $idStudent = Crypt::decrypt(Input::get("student"));
    $relation = Relationship::where("idUser", $this->idUser)->where("idFriend", $idStudent)->whereType(1)->whereStatus("E")->first();

    if ( $relation )
    {
      $attest = new Attest;
      $attest->idInstitution = $this->idUser;
      $attest->idStudent     = $idStudent;
      $attest->date          = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
      $attest->days          = Input::get("days");
      $attest->description   = Input::get("description");
      $attest->save();

      return Redirect::back()->with("success", "Operação realizada com sucesso.");
    }
    else
      return Redirect::back()->with("error", "Essa operação não pode ser realizado. Consulte o suporte.");
  }

  public function getProfileTeacher()
  {
    $user = User::find($this->idUser);
    $profile = Crypt::decrypt(Input::get("u"));
    if ( $profile ) {
      $profile = User::find($profile);
      switch ($profile->formation) {
        case '0': $profile->formation = "Não quero informar"; break;
        case '1': $profile->formation = "Ensino Fundamental"; break;
        case '2': $profile->formation = "Ensino Médio"; break;
        case '3': $profile->formation = "Ensino Superior Incompleto"; break;
        case '4': $profile->formation = "Ensino Superior Completo"; break;
        case '5': $profile->formation = "Pós-Graduado"; break;
        case '6': $profile->formation = "Mestre"; break;
        case '7': $profile->formation = "Doutor"; break;
      }
      return View::make("modules.profileteacher", ["user" => $user, "profile" => $profile]);
    }else {
      return Redirect::guest("/");
    }
  }

  public function postInvite($id = null)
  {
    $user = User::find($this->idUser);
    if ($id)
      $guest = User::find($id);
    else
      $guest = User::find(Crypt::decrypt( Input::has("teacher") ? Input::get("teacher") : Input::get("guest")));

    if (($guest->type == "M" or $guest->type == "N") and Relationship::where("idUser", $this->idUser)->where("idFriend", $guest->id)->first()) {
      if (User::whereEmail(Input::get("email"))->first()) {
        return Redirect::back()->with("error", "O email " . Input::get("email") . " já está cadastrado.");
      }
      try
      {
        $guest->email = Input::get("email");
        $password = substr(md5(microtime()),1,rand(4,7));
        $guest->password = Hash::make($password);
        Mail::send('email.invite', [
          "institution" => $user->name,
          "name" => $guest->name,
          "email" => $guest->email,
          "password" => $password
        ], function($message) use ($guest)
        {
          $message->to( Input::get("email"), $guest->name )
                  ->subject("Seja bem-vindo");
        });
        $guest->save();
        return Redirect::back()->with("success", "Operação realizada com sucesso. Os dados de acesso de $guest->name foi enviado para o email $guest->email.");
      }
      catch (Exception $e)
      {
        return Redirect::back()->with("error", "Erro ao realizar a operação, tente mais tarde (" . $e->getMessage() .")");
      }
    }
    else {
      return Redirect::back()->with("error", "Operação inválida");
    }
  }

  public function getStudent()
  {
    if ($this->idUser)
    {
      $block = 30;
      $search = Input::has("search") ? Input::get("search") : "";
      $current = (int) Input::has("current") ? Input::get("current"): 0;
      $user = User::find($this->idUser);
      $courses = Course::where("idInstitution", $this->idUser)
                       ->whereStatus("E")
                       ->orderBy("name")
                       ->get();

      $listCourses = ["" => ""];
      foreach ($courses as $course) {
        $listCourses[$course->name] = $course->name;
      }

      $relationships = DB::select("SELECT Users.id, Users.name, Users.enrollment "
                                  . "FROM Users, Relationships "
                                  . "WHERE Relationships.idUser=? AND Relationships.type='1' AND Relationships.idFriend=Users.id "
                                  . "AND (Users.name LIKE ? OR Users.enrollment=?) "
                                  . " ORDER BY name LIMIT ? OFFSET ?",
                                  [$this->idUser, "%$search%", $search, $block, $current*$block ]);

      $length = DB::select("SELECT count(*) as 'length' "
                                  . "FROM Users, Relationships "
                                  . "WHERE Relationships.idUser=? AND Relationships.type='1' AND Relationships.idFriend=Users.id "
                                  . "AND (Users.name LIKE ? OR Users.enrollment=?) ", [$this->idUser, "%$search%", $search ]);

      return View::make("modules.addStudents",
                        [
                          "courses"       => $listCourses,
                          "user"          => $user,
                          "relationships" => $relationships,
                          "length"        => (int) $length[0]->length,
                          "block"         => (int) $block,
                          "current"       => (int) $current
                        ]
                      );
    }
    else
    {
      return Redirect::guest("/");
    }
  }

  public function anyFindUser($search)
  {
    $users = User::where("name", "like", "%".$search."%")->orWhere("email", $search)->get();
    return View::make("user.list-search", ["users" => $users, "i" => 0]);
  }

  public function postStudent()
  {
    $user = new User;
    $user->enrollment = Input::get("enrollment");
    $user->name = Input::get("name");
    $user->email = strlen(Input::get("email")) ? Input::get("email") : null;
    $user->course = Input::get("course");
    $user->birthdate = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
    $user->type = "N";
    $user->save();

    $relationship = new Relationship;
    $relationship->idUser   = $this->idUser;
    $relationship->idFriend = $user->id;
    $relationship->status   = "E";
    $relationship->type     = "1";
    $relationship->save();

    return Redirect::guest("/user/student")->with("success", "Aluno cadastrado com sucesso!");
  }

  public function postUnlink()
  {
    $idTeacher = Crypt::decrypt(Input::get("input-trash"));

    $offers = DB::select("SELECT Courses.name AS course, Periods.name AS period, Classes.class as class, Disciplines.name AS discipline "
            . "FROM Courses, Periods, Classes, Offers, Lectures, Disciplines "
            . "WHERE Courses.idInstitution=? AND Courses.id=Periods.idCourse AND "
              . "Periods.id=Classes.idPeriod AND Classes.id=Offers.idClass AND "
              . "Offers.idDiscipline=Disciplines.id AND "
              . "Offers.id=Lectures.idOffer AND Lectures.idUser=?", [$this->idUser, $idTeacher]);

    if(count($offers))
    {
      $str = "Erro ao desvincular professor, ele está associado a(s) disciplina(s): <br><br>";
      $str .= "<ul class='text-justify text-sm list-group'>";
      foreach ($offers as $offer) {
        $str .= "<li class='list-group-item'>$offer->course/$offer->period/$offer->class/$offer->discipline</li>";
      }
      $str .= "</ul>";

      return Redirect::back()->with("error", $str);
    }
    else
    {
      Relationship::where('idUser', $this->idUser)
                  ->where('idFriend', $idTeacher)
                  ->whereType(2)
                  ->update(["status" => "D"]);

      return Redirect::to("/user/teacher")->with("success", "Professor excluído dessa Instituição");
    }
  }

  public function getInfouser()
  {
    $user = User::find(Crypt::decrypt(Input::get("user")));
    $user->enrollment = DB::table('Relationships')->where('idUser', $this->idUser)->where('idFriend', $user->id)->pluck('enrollment');
    $user->password = null;
    return $user;
  }

  public function anyLink($type, $user){
    switch($type) {
      case "student":
        $type = 1;
        break;
      default:
        return Redirect::back()->with("error", "Cadastro errado.");
    }
    $user = Crypt::decrypt($user);

    $r = Relationship::where("idUser", $this->idUser)->where("idFriend", $user)->whereType($type)->first();
    if($r and $r->status == "E" )
      return Redirect::back()->with("error", "Já possui esse relacionamento.");
    elseif($r)
       $r->status = "E";
    else {
       $r = new Relationship;
       $r->idUser = $this->idUser;
       $r->idFriend = $user;
       $r->type = $type;
    }
    $r->save();

    return Redirect::back()->with("success", "Relacionamento criado com sucesso.");
  }
}
