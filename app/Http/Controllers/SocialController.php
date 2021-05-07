<?php namespace App\Http\Controllers;

use App\Suggestion;
use Mail;

class SocialController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.type:I');
	}

	public function postQuestion()
	{
		foreach (request()->all() as $key => $value) {
			return auth()->user()->update([$key => $value]);
		}
	}

	public function postSuggestion()
	{
		$suggestion = new Suggestion;
		$suggestion->user_id = auth()->id();
		$suggestion->name = request()->get("name");
		$suggestion->emailUser = request()->get("emailUser");
		$suggestion->title = request()->get("title");
		$suggestion->value = request()->get("value");
		$suggestion->description = request()->get("description");
		$suggestion->textError = request()->get("textError");
		$suggestion->link = request()->get("link");
		$suggestion->save();

		$user = auth()->user();

		Mail::send('email.suporte', [
			"name" => request()->get("name"),
			"title" => request()->get("title"),
			"email" => request()->get("emailUser"),
			"descricao" => request()->get("description"),
			"link" => request()->get("link"),
			"textError" => request()->get("textError"),
		], function($message) {
			$op = ["B" => "Bug/Erro", "O" => "Outros", "S" => "Sugestão"];
			$message->to(config('app.mail_suporte'), "Suporte")->subject("LibreClass Suporte - " . $op[request()->get("value")]);
		});

		return redirect()->back()
			->with('success', 'Obrigado pela sua mensagem. Nossa equipe irá analisar e responderá o mais breve possível.');
	}
}