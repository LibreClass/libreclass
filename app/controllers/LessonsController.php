<?php

class LessonsController extends \BaseController
{
  private $idUser;

  public function LessonsController()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }

  }

  public function getIndex()
  {

    if ($this->idUser) {
      $user = User::find($this->idUser);
      $lesson = Lesson::find(Crypt::decrypt(Input::get("l")));

      $students = DB::select("SELECT Users.name AS name, Attends.id AS idAttend, Frequencies.value AS value, Units.idOffer, Attends.idUser, Units.id AS idUnit
                                FROM Frequencies, Attends, Users, Units
                                WHERE Frequencies.idAttend=Attends.id AND
                                      Attends.idUser=Users.id AND
                                      Frequencies.idLesson=? AND
                                      Attends.idUnit=Units.id
                                ORDER BY Users.name", [$lesson->id]);

			//Obtém todas a aulas da oferta da aula para calcular atestados;

      foreach ($students as $student) {
				//Obtém os atestados
				$allLessons = Unit::find($student->idUnit)->getOffer()->lessons();
				$attests = Attest::where('idStudent',$student->idUser)->get();
				$qtdAttests = 0;
				foreach($attests as $attest) {
					$attest->dateFinish = date('Y-m-d', strtotime($attest->date. '+ '. ($attest->days - 1) .' days'));

					//If true, aluno possui um atestado para o dia da aula.
					if (($lesson->date >= $attest->date) && ($lesson->date <= $attest->dateFinish))
					{
						$student->attest = true;
					}

					foreach($allLessons as $tmpLesson) {
						if (($tmpLesson->date >= $attest->date) && ($tmpLesson->date <= $attest->dateFinish))
						{
							$qtdAttests++;
						}
					}
				}

        $frequency = DB::select("SELECT Offers.maxlessons, COUNT(*) as qtd "
                                  . "FROM Offers, Units, Attends, Frequencies "
                                  . "WHERE Offers.id=? AND Offers.id=Units.idOffer AND Units.id=Attends.idUnit "
                                    . "AND Attends.idUser=? AND Attends.id=Frequencies.idAttend AND Frequencies.value='F'",
                                [$student->idOffer, $student->idUser])[0];
        $student->maxlessons = $frequency->maxlessons;
				// var_dump([$frequency->qtd, $qtdAttests]);
        $student->qtd = $frequency->qtd - $qtdAttests;
      }
			// return $students;
      return View::make("modules.lessons", ["user" => $user, "lesson" => $lesson, "students" => $students]);
    } else {
      return Redirect::guest("/");
    }
  }

  public function getNew()
  {
    $unit = Unit::find(Crypt::decrypt(Input::get("unit")));

    $lesson = new Lesson;
    $lesson->idUnit = $unit->id;
    $lesson->date = date("Y-m-d");
    $lesson->title = "Sem título";
    $lesson->save();
    $lessons_count = count(Lesson::where('idUnit', $unit->id)->get());

    $attends = Attend::where("idUnit", $unit->id)->get();
    foreach ($attends as $attend) {
      $frequency = new Frequency;
      $frequency->idAttend = $attend->id;
      $frequency->idLesson = $lesson->id;
      $frequency->value = "P";
      $frequency->save();
    }

    if ($unit->offer->grouping == 'M') {
      $offers = $unit->offer->slaves;
      foreach ($offers as $offer) {
        $unit_slave = $offer->units()->where('value', $unit->value)->first();
        $lessons_count_slave = count(Lesson::where('idUnit', $unit_slave->id)->get());
        if ($lessons_count_slave >= $lessons_count) {
          continue;
        }
        $lesson_slave = new Lesson;
        $lesson_slave->idUnit = $unit_slave->id;
        $lesson_slave->date = $lesson->date;
        $lesson_slave->title = $lesson->title;
        Log::info('Lesson Slave', [$lesson_slave]);
        $lesson_slave->save();

        $attends_slaves = Attend::where("idUnit", $unit_slave->id)->get();
        foreach ($attends_slaves as $attend_slave) {
          $frequency_slave = new Frequency;
          $frequency_slave->idAttend = $attend_slave->id;
          $frequency_slave->idLesson = $lesson_slave->id;
          $frequency_slave->value = "P";
          $frequency_slave->save();
        }
      }
    }

    return Redirect::to("/lessons?l=" . Crypt::encrypt($lesson->id))->with("success", "Uma nova aula foi criada com sucesso.");
    return $lesson;
  }

  public function postSave()
  {
    //~ var_dump(Input::all());

    $lesson = Lesson::find(Crypt::decrypt(Input::get("l")));

    $lesson->date = Input::get("date-year") . "-" . Input::get("date-month") . "-" . Input::get("date-day");
    $lesson->title = Input::get("title");
    $lesson->description = Input::get("description");
    $lesson->goals = Input::get("goals");
    $lesson->content = Input::get("content");
    $lesson->methodology = Input::get("methodology");
    $lesson->resources = Input::get("resources");
    $lesson->valuation = Input::get("valuation");
    $lesson->estimatedTime = Input::get("estimatedTime");
    $lesson->keyworks = Input::get("keyworks");
    $lesson->bibliography = Input::get("bibliography");
    $lesson->notes = Input::get("notes");
    $lesson->save();

    //~ return $lesson;
    $unit = DB::select("SELECT Units.id, Units.status
                          FROM Units, Lessons
                          WHERE Units.id = Lessons.idUnit AND
                            Lessons.id=?", [$lesson->id]);

    return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("success", "Aula atualizada com sucesso");
  }

  public function anyFrequency()
  {
    $attend = Attend::find(Crypt::decrypt(Input::get("idAttend")));
    $idLesson = Crypt::decrypt(Input::get("idLesson"));
    $value = Input::get("value") == "P" ? "F" : "P";

    $idOffer = DB::select(
      "SELECT Units.idOffer "
      . "  FROM Lessons, Units "
      . "WHERE Lessons.id = ? "
      . "  AND Lessons.idUnit = Units.id",
      [$idLesson]
    )[0]->idOffer;

    $status = Frequency::where("idAttend", $attend->id)->where("idLesson", $idLesson)->update(["value" => $value]);

    $frequency = DB::select(
      "SELECT Offers.maxlessons, COUNT(*) as qtd "
      . "  FROM Offers, Units, Attends, Frequencies "
      . "WHERE Offers.id = ? "
      . "  AND Offers.id = Units.idOffer "
      . "  AND Units.id = Attends.idUnit "
      . "  AND Attends.idUser = ? "
      . "  AND Attends.id = Frequencies.idAttend "
      . "  AND Frequencies.value = 'F'",
      [$idOffer, $attend->idUser]
    )[0];

    $this->slavesFrequency($attend->id, $idLesson, $value);

    return Response::json(["status" => $status, "value" => $value, "frequency" => sprintf("%d (%.1f %%)", $frequency->qtd, 100. * $frequency->qtd / $frequency->maxlessons)]);
  }

  /**
   * Replica a frequência para as ofertas slaves. A frequência é replicada por
   * aluno por aula em oferta. É verificado se o aluno existe nas ofertas slaves.
   *
   * @param  [type] $idAttend [description]
   * @param  [type] $idLesson [description]
   * @param  [type] $value    [description]
   * @return [type]           [description]
   */
  private function slavesFrequency($idAttend, $idLesson, $value)
  {
    if (Attend::find($idAttend)->getUnit()->offer->grouping != 'M') {
      return;
    }
    try {
      $lesson_number = -1;
      $unit = Attend::find($idAttend)->getUnit();
      $lessons = Lesson::where("idUnit", $unit->id)
        ->whereStatus('E')->orderBy("date", "desc")
        ->orderBy("id", "desc")
        ->get();
      foreach ($lessons as $key => $lesson) {
        if ($lesson->id == $idLesson) {
          $lesson_number = $key;
        }
      }
      if ($lesson_number == -1) {
        throw new Exception('Aula não encontrada!', 1);
      }
      $slaveOffers = Offer::where('idOffer', $unit->offer->id)->get();
      if (!$slaveOffers) {
        throw new Exception('Oferta "slave" não encontrada!', 1);
      }
      foreach ($slaveOffers as $o) {
        $o_unit = Unit::where('idOffer', $o->id)->where('value', $unit->value)->first();
        if (!$o_unit) {
          throw new Exception('Não foi encontrada uma unidade correspondente na oferta slave!', 1);
        }
        $o_lessons = $lessons = Lesson::where("idUnit", $o_unit->id)->whereStatus('E')->orderBy("date", "desc")->orderBy("id", "desc")->get();
        if (!$o_lessons[$lesson_number]) {
          throw new Exception('Não foi encontrada a aula correspondente na oferta slave!', 1);
        }
        $o_student = Attend::find($idAttend)->getUser();
        $o_attend = Attend::where('idUser', $o_student->id)->where('idUnit', $o_unit->id)->first();
        if (!$o_attend) {
          throw new Exception('Relacionamento inconsistente entre unidade e aluno', 1);
        }
        $o_frequency = Frequency::where("idAttend", $o_attend->id)->where("idLesson", $o_lessons[$lesson_number]->id)->update(["value" => $value]);
      }

    } catch (Exception $e) {
      // if ($e->getCode() == 1) {
        Log::info('slavesFrequency', [
          'message' => 'Erro: ' . $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
          'code' => $e->getCode(),
        ]);
      // }
    }
  }

  public function postDelete()
  {
    $lesson = Lesson::find(Crypt::decrypt(Input::get("input-trash")));

    $unit = DB::select("SELECT Units.id, Units.status
                          FROM Units, Lessons
                          WHERE Units.id = Lessons.idUnit AND
                            Lessons.id=?", [$lesson->id]);

    if ($unit[0]->status == 'D') {
      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("error", "Não foi possível deletar.<br>Unidade desabilitada.");
    }
    if ($lesson) {
      $lesson->status = "D";
      $lesson->save();
      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("success", "Aula excluída com sucesso!");
    } else {
      return Redirect::guest("/lectures/units?u=" . Crypt::encrypt($unit[0]->id))->with("error", "Não foi possível deletar");
    }
  }

  public function getInfo()
  {
    $lesson = Lesson::find(Crypt::decrypt(Input::get("lesson")));
    $lesson->date = date("d/m/Y", strtotime($lesson->date));
    return $lesson;
  }

  /**
   * Faz uma cópia de uma aula com ou sem frequecia
   *    1 - cópia para a mesma unidade sem frequencia
   *    2 - cópia para a mesma unidade com frequencia
   *    3 - cópia para uma outra unidade sem frequencia
   *
   * @return type
   */
  public function anyCopy()
  {
    $lesson = Lesson::find(Crypt::decrypt(Input::get("lesson")));
    $auth = DB::select("SELECT COUNT(*) as qtd FROM Units, Lectures WHERE Units.id=? AND Units.idOffer=Lectures.idOffer AND Lectures.idUser=?",
      [$lesson->idUnit, $this->idUser])[0]->qtd;
    if (!$auth) {
      return Response::JSON(false);
    }

    $copy = $lesson->replicate();
    if (Input::get("type") == 3) {
      $unit = Unit::where("idOffer", Crypt::decrypt(Input::get("offer")))->whereStatus("E")->orderBy("value", "desc")->first();
      $copy->idUnit = $unit->id;
      $copy->save();

      $attends = Attend::where("idUnit", $unit->id)->get();
      foreach ($attends as $attend) {
        $frequency = new Frequency;
        $frequency->idAttend = $attend->id;
        $frequency->idLesson = $copy->id;
        $frequency->value = "P";
        $frequency->save();
      }
    } else {
      $copy->save();
      $frequencies = Frequency::where("idLesson", $lesson->id)->get();
      foreach ($frequencies as $frequency) {
        $frequency = $frequency->replicate();
        $frequency->idLesson = $copy->id;
        if (Input::get("type") == 1) {
          $frequency->value = "P";
        }

        $frequency->save();

      }
      $copy->id = Crypt::encrypt($copy->id);
      $copy->date = date("d/m/Y", strtotime($copy->date));
      return $copy;
    }
  }

  /**
   * seleciona as ofertas ministradas pelo professor que está logado
   *
   * @return lista das ofertas
   */
  public function postListOffers()
  {
    $offers = DB::select("SELECT Offers.id, Disciplines.name, Classes.class FROM Lectures, Offers, Classes, Disciplines "
      . "WHERE Lectures.idUser=? AND Lectures.idOffer=Offers.id AND Offers.idClass=Classes.id AND Offers.idDiscipline=Disciplines.id",
      [$this->idUser]);

    foreach ($offers as $offer) {
      $offer->id = Crypt::encrypt($offer->id);
    }$frequency = new Frequency;
        $frequency->idAttend = $attend->id;
        $frequency->idLesson = $copy->id;
        $frequency->value = "P";
        $frequency->save();
  }

  public function anyDelete()
  {
    // return Crypt::decrypt(Input::get("input-trash"));
    Lesson::find(Crypt::decrypt(Input::get("input-trash")))->delete();
    return Redirect::back()->with("alert", "Aula excluída!");
  }
}
