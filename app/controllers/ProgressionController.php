<?php

class ProgressionController extends \BaseController
{

  private $idUser;

  public function __construct()
  {
    $id = Session::get("user");
    if ($id == null || $id == "") {
      $this->idUser = false;
    } else {
      $this->idUser = Crypt::decrypt($id);
    }
  }

	public function postStudentsAndClasses()
	{
		if(!Input::has('previous_classe_id')) {
			return ['status' => 0, 'message' => 'Nenhuma turma selecionada.'];
		}

		$previous_classe_id = Crypt::decrypt(Input::get('previous_classe_id'));
		$classe_id = Crypt::decrypt(Input::get('classe_id'));
		// $atual_classe = Classe::find($previous_classe_id);
		// $atual_period = Period::find($atual_classe->idPeriod);

		// //Se não há configuração de progressão da série atual
		// if(empty($atual_period->progression_value)) {
		// 	return ['status' => 0, 'message' => 'Não foi possível obter as turmas para progressão. Verifique a sequência de progressão em <a href="periods">Meus Períodos</a>.'];
		// }

		// $next_period = Period::where('idCourse', $atual_period->idCourse)->where('progression_value', $atual_period->progression_value + 1)->first();
    //
		// //Se não há configuração de progressão da próxima série
		// if(empty($next_period)) {
		// 	return ['status' => 0, 'message' => 'Não existe progressão configurada para a série. Verifique a sequência de progressão em <a href="periods">Meus Períodos</a>.'];
		// }
    //
		// $next_period->classes = Classe::where('idPeriod', $next_period->id)->where('schoolYear', $atual_classe->schoolYear + 1)->get();

		//Se não há configuração de progressão da próxima série
		// if(empty($next_period->classes)) {
		// 	return ['status' => 0, 'message' => 'Não existem turmas criadas para o próximo ano escolar.'];
		// }

		//Obtém alunos matriculados na turma atual
		$students = DB::select("
			SELECT Users.id
			FROM Classes, Offers, Units, Attends, Users
			WHERE Classes.id = Offers.idClass
				AND Offers.id = Units.idOffer
				AND Units.id = Attends.idUnit
				AND Users.id = Attends.idUser
				AND Classes.id = $classe_id
				AND Attends.status = 'M'
			GROUP BY Users.id
			ORDER BY Users.name
		");

		$students = implode(', ', array_pluck($students, 'id'));



		//Obtém alunos matriculados na turma anterior
		$attends = DB::select("
			SELECT Users.name as user_name, Attends.id as attend_id, Users.id as user_id
			FROM Classes, Offers, Units, Attends, Users
			WHERE Classes.id = Offers.idClass
				AND Offers.id = Units.idOffer
				AND Units.id = Attends.idUnit ".
				(!empty($students) ? "AND Users.id NOT IN ($students) " : "").
				"AND Users.id = Attends.idUser
				AND Classes.id = $previous_classe_id
				AND Attends.status = 'M'
			GROUP BY Users.id
			ORDER BY Users.name
		");

		foreach($attends as $attend) {
			$attend->user_id = Crypt::encrypt($attend->user_id);
			$attend->attend_id = Crypt::encrypt($attend->attend_id);
		}

		// return ['status' => 1, 'attends' => $attends, 'atual_classe' => $atual_classe, 'atual_period' => $atual_period, 'next_classe' => $next_period];
		return ['status' => 1, 'attends' => $attends ];
	}

	public function postImportStudent() {
		if(!count(Input::get('student_ids'))) {
			return ['status' => 0, 'message' => 'Nenhum aluno selecionado'];
		}

		$classe_id = Input::get('classe_id');
		$offers = Offer::where('idClass', Crypt::decrypt($classe_id))->get();

		if(!$offers) {
			return ['status' => 0, 'message' => 'A turma ainda não possui ofertas'];
		}

		$students_ids = Input::get('student_ids');
		foreach($offers as $offer) {
			if(!count($offer->units)) {
				return ['status' => 0, 'message' => 'Não foi possível importar. Existem ofertas sem unidades.'];
			}
		}

		foreach($offers as $offer) {
			foreach($offer->units as $unit) {
				foreach($students_ids as $student_id) {
					Attend::create(['idUser' => Crypt::decrypt($student_id), 'idUnit' => $unit->id]);
				}
			}
		}

		return ['status' => 1];

	}
}
