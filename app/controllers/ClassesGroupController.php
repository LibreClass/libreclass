<?php

class ClassesGroupController extends \BaseController
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

  /**
   * Carrega view para agrupar ofertas.
   * @param  [String] $idClass [Id da turma].
   * @return [View]
   */
  public function loadClassGroup($idClass)
  {
    $disciplines = [];
    $offers = Offer::where('idClass', Crypt::decrypt($idClass))->where('grouping', 'N')->get();
    foreach ($offers as $offer) {
      if ($offer->discipline) {
        $disciplines[] = (object) [
          'name' => $offer->discipline->name,
          'id' => Crypt::encrypt($offer->id),
        ];
      }
    }

    $classe = Classe::find(Crypt::decrypt($idClass));
    $classe->disciplines = $disciplines;
    $classe->id = Crypt::encrypt($classe->id);

    $user = User::find($this->idUser);
    return View::make("modules.classesGroup", ['user' => $user, 'classe' => $classe]);
  }

  /**
   * Cria uma oferta 'Master', aquela que contém ofertas agrupadas.
   * @return [json] [Retorna status e, se necessário, uma mensagem de erro]
   */
  public function createMasterOffer()
  {
    try {
      if (!Input::has('offers') || !Input::has('classe') || !Input::has('name')) {
        throw new Exception('Informações incompletas.');
      }
      $master_discipline = Discipline::create(['name' => Input::get('name')]);
      $master_offer = Offer::create([
        'idClass' => Crypt::decrypt(Input::get('classe')),
        'idDiscipline' => $master_discipline->id,
        'grouping' => 'M',
      ]);
      foreach (Input::get('offers') as $offer_id) {
        $id = Crypt::decrypt($offer_id);
        $offer = Offer::find($id);
        $offer->idOffer = $master_offer->id;
        $offer->grouping = 'S';
        $offer->save();
      }
      return Response::json(['status' => 1, 'message' => 'Disciplinas agrupadas com sucesso!']);
    } catch (Exception $e) {
      return Response::json(['status' => 0, 'message' => 'Erro: ' . $e->getMessage() . ' (' . $e->getLine() . ')']);
    }
  }

}
