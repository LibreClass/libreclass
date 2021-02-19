<?php namespace App\Http\Controllers;

use DB;
use App\Offer;
use App\Attend;
use App\Unit;
use App\User;

class ProgressionController extends Controller
{
  public function __construct()
  {
		$this->middleware('auth.type:I');
  }

	public function postStudentsAndClasses()
	{
		if(!request()->has('previous_classe_id')) {
			return ['status' => 0, 'message' => 'Nenhuma turma selecionada.'];
		}

		$previous_classe_id = decrypt(request()->get('previous_classe_id'));
		$classe_id = decrypt(request()->get('classe_id'));

		//Obtém alunos matriculados na turma atual
		$offer_ids = Offer::whereClassId($classe_id)
			->get(['id'])
			->pluck('id')
			->all();
		$unit_ids = Unit::whereIn('offer_id', $offer_ids)
			->get(['id'])
			->pluck('id')
			->all();
		$user_ids = Attend::whereIn('unit_id', $unit_ids)
			->whereStatus('M')
			->get(['user_id'])
			->pluck('user_id')
			->all();

		//Obtém alunos matriculados na turma anterior
		$offer_ids = Offer::whereClassId($previous_classe_id)
			->get(['id'])
			->pluck('id')
			->all();
		$unit_ids = Unit::whereIn('offer_id', $offer_ids)
			->get(['id'])
			->pluck('id')
			->all();
		$attends = Attend::whereIn('unit_id', $unit_ids)
			->whereNotIn('user_id', $user_ids)
			->whereStatus('M')
			->get(['id', 'user_id']);

		$users = User::orderBy('name')->find($attends->pluck('user_id')->all());
		$attends = $attends->keyBy('user_id');

		$output = [];
		foreach($users as $user) {
			$output[] = [
				'user_id' => encrypt($user->id),
				'attend_id' => encrypt($attends->get($user->id)->id),
				'user_name' => $user->name,
			];
		}

		return ['status' => 1, 'attends' => $output, ];
	}

	public function postImportStudent()
	{
		if(!count(request()->get('student_ids'))) {
			return ['status' => 0, 'message' => 'Nenhum aluno selecionado'];
		}

		$classe_id = request()->get('classe_id');
		$offers = Offer::where('class_id', decrypt($classe_id))->get();

		if(!$offers) {
			return ['status' => 0, 'message' => 'A turma ainda não possui ofertas'];
		}

		$students_ids = request()->get('student_ids');
		foreach($offers as $offer) {
			if(!count($offer->units)) {
				return ['status' => 0, 'message' => 'Não foi possível importar. Existem ofertas sem unidades.'];
			}
		}

		foreach($offers as $offer) {
			foreach($offer->units as $unit) {
				foreach($students_ids as $student_id) {
					Attend::create(['user_id' => decrypt($student_id), 'unit_id' => $unit->id]);
				}
			}
		}

		return ['status' => 1];
	}
}
