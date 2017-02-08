<?php

class SocialController extends \BaseController {
  private $idUser;

  public function __construct()
  {
    $id = Session::get("user");
    if ( $id == null || $id == "" )
      $this->idUser = false;
    else
      $this->idUser = Crypt::decrypt($id);
  }

  public function getIndex()
  {
    if ( Session::has("redirect") )
      return Redirect::to(Session::get("redirect"));

    $user = User::find($this->idUser);
    Session::put("type", $user->type);

    return View::make("social.home", ["user" => $user]);
  }

  public function postQuestion()
  {
    //~ print_r(Input::all());
    foreach( Input::all() as $key => $value )
      return User::whereId($this->idUser)->update([$key => $value]);
  }

  public function postSuggestion()
  {
    $suggestion = new Suggestion;
    $suggestion->idUser      = $this->idUser;
    $suggestion->title       = Input::get("title");
    $suggestion->value       = Input::get("value");
    $suggestion->description = Input::get("description");
    $suggestion->save();

    $user = User::find($this->idUser);

    Mail::send('email.suporte', ["descricao" => Input::get("description"), "email" => $user->email, "title" => Input::get("title")], function($message)
    {
      $op = ["B" => "Bugson", "O" => "Outros", "S" => "Sugestão"];
      $message->to( "suporte@sysvale.com", "Suporte" )
              ->subject("LibreClass Suporte - " . $op[Input::get("value")]);
    });

    return Redirect::back()->with("success", "Obrigado pela sua mensagem. Nossa equipe irá analisar e responderá o mais breve possível.");
  }



}
