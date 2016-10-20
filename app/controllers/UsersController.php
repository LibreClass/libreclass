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

        $relationships = DB::select("SELECT Users.id, Users.name, Users.enrollment, Users.type "
                                    . "FROM Users, Relationships "
                                    . "WHERE Relationships.idUser=? AND Relationships.type='2' AND Relationships.idFriend=Users.id "
                                    . "AND (Users.name LIKE ? OR Users.enrollment=?) "
                                    . " ORDER BY name LIMIT ? OFFSET ?",
                                    [$this->idUser, "%$search%", $search, $block, $current*$block ]);

        $length = DB::select("SELECT count(*) as 'length' "
                                    . "FROM Users, Relationships "
                                    . "WHERE Relationships.idUser=? AND Relationships.type='2' AND Relationships.idFriend=Users.id "
                                    . "AND (Users.name LIKE ? OR Users.enrollment=?) ", [$this->idUser, "%$search%", $search ]);

     
        return View::make("modules.addTeachers",
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
    $verify = User::whereEnrollment(Input::get("enrollment"))->first();
    if ( isset($verify) || $verify != null ) {
      return Redirect::guest("/user/teacher")->with("error", "Este número de inscrição já está cadastrado!");
    }
    
    $user = new User;
    
    $user->enrollment = Input::get("enrollment");
    
    $user->name = Input::get("name");
    $user->course = Input::get("course");
    if (Input::has("date-year")) {
      $user->birthdate = Input::get("date-year") . "-"
                       . Input::get("date-month") . "-"
                       . Input::get("date-day");
    }
    $user->type = "M";
    $user->save();
    $relationship = new Relationship;
    $relationship->idUser   = $this->idUser;
    $relationship->idFriend = $user->id;
    $relationship->status   = "E";
    $relationship->type     = "2";
    $relationship->save();
    return Redirect::guest("/user/teacher")->with("success", "Professor cadastrado com sucesso!");
  }

  public function getProfile(){
    $user = User::find($this->idUser);
    $profile = Crypt::decrypt(Input::get("u"));
    if ( $profile ) {
      $profile = User::find($profile);
      return View::make("modules.profile", ["user" => $user, "profile" => $profile]);
    }else {
      return Redirect::guest("/");
    }
  }

  public function postInvite()
  {
    $user = User::find($this->idUser);
    $teacher = User::find(Crypt::decrypt(Input::get("teacher")));
    
    if ($teacher->type == "M" and Relationship::where("idUser", $this->idUser)->where("idFriend", $teacher->id)->first()) {
      if (User::whereEmail(Input::get("email"))->first()) {
        return Redirect::to("/user/teacher")->with("error", "O email " . Input::get("email") . " já está cadastrado.");
      }
      try
      {
        $teacher->email = Input::get("email");
        $password = substr(md5(microtime()),1,rand(4,7));
        $teacher->password = Hash::make($password);
        
        Mail::send('email.invite', ["institution" => $user->name, "name" => $teacher->name, "email" => $teacher->email, "password" => $password], function($message)
        {
         
          $user = User::find(Crypt::decrypt(Input::get("teacher")));
          $message->to( Input::get("email"), $user->name )
                  ->subject("Seja bem-vindo");
        });
        $teacher->save();
        return Redirect::to("/user/teacher")->with("success", "Operação realizada com sucesso. Os dados de acesso de $teacher->name foi enviado para o email $teacher->email.");
      }
      catch (Exception $e)
      {
        return Redirect::to("/user/teacher")->with("error", "Erro ao realizar a operação, tente mais tarde");
      }
    }
    else {
      return Redirect::to("/user/teacher")->with("error", "Operação inválida");
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

  public function anyFindstudent()
  {
    // TO DO
  }

  public function postStudent()
  {
    $user = new User;
    $user->enrollment = Input::get("enrollment");
    $user->name = Input::get("name");
    $user->email = Input::get("email");
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

    public function postUnlink() {
      return Redirect::to("/user/teacher")->with("error", "Pode deletar um professor com oferta ativa?");
    }
}
