<?php namespace App\Http\Controllers;

use Exception;
use App\Classe;
use App\Offer;
use App\Unit;
use App\Discipline;

class ClassesGroupController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth.type:I');
  }

  /**
   * Carrega view para agrupar ofertas.
   * @param  [String] $class_id [Id da turma].
   * @return [View]
   */
  public function loadClassGroup($class_id)
  {
    $classe = Classe::find(decrypt($class_id));
    $classe->disciplines = $this->getOffers($class_id);

    $user = auth()->user();
    return view("modules.classesGroup", ['user' => $user, 'classe' => $classe]);
  }

  /**
   * Obtém nomes e ids das ofertas associadas a uma turma
   * @param  [string] $class_id [Id criptografado da turma]
   * @return [array]           [Ofertas]
   */
  private function getOffers($class_id)
  {
    $o = [];
    $offers = Offer::where('class_id', decrypt($class_id))->get();
    foreach ($offers as $offer) {
      if ($offer->discipline) {
        $o[] = (object) [
          'name' => $offer->discipline->name,
          'id' => encrypt($offer->id),
          'grouping' => $offer->grouping,
          'master_discipline' => ($offer->grouping == 'S') ? Offer::find($offer->offer_id)->discipline->name : null,
        ];
      }
    }
    return $o;
  }

  /**
   * Retorna nomes e ids das ofertas associadas a uma turma em formato JSON
   * @param  [string] $class_id [Id criptografado da turma]
   * @return [json]            [Ofertas]
   */
  public function jsonOffers()
  {
    return [
      'status' => 1,
      'disciplines' => $this->getOffers(request()->get('class_id')),
    ];
  }

  /**
   * Cria uma oferta 'Master', aquela que contém ofertas agrupadas.
   * @return [json] [Retorna status e, se necessário, uma mensagem de erro]
   */
  public function createMasterOffer()
  {
      if (!request()->has('offers') || !request()->has('classe') || !request()->has('name')) {
        throw new Exception('Informações incompletas.');
      }
      $master_discipline = Discipline::create(['name' => request()->get('name')]);
      $master_offer = Offer::create([
        'class_id' => decrypt(request()->get('classe')),
        'discipline_id' => $master_discipline->id,
        'grouping' => 'M',
      ]);
      $unit = new Unit;
      $unit->offer_id = $master_offer->id;
      $unit->value = "1";
      $unit->calculation = "A";
      $unit->save();
      foreach (request()->get('offers') as $offer_id) {
        $id = decrypt($offer_id);
        $offer = Offer::find($id);
        $offer->offer_id = $master_offer->id;
        $offer->grouping = 'S';
        $offer->save();
      }
      return response()->json(['status' => 1, 'message' => 'Disciplinas agrupadas com sucesso!']);
  }
}
