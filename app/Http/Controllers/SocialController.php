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
		$suggestion->title = request()->get("title");
		$suggestion->value = request()->get("value");
		$suggestion->description = request()->get("description");
		$suggestion->save();

		$user = auth()->user();

		Mail::send('email.suporte', [
			"descricao" => request()->get("description"),
			"email" => $user->email, "title" => request()->get("title"),
		], function($message) {
			$op = ["B" => "Bug/Erro", "O" => "Outros", "S" => "Sugestão"];
			$message->to(env('MAIL_SUPORTE'), "Suporte")->subject("LibreClass Suporte - " . $op[request()->get("value")]);
		});

		return redirect()->back()
			->with('success', 'Obrigado pela sua mensagem. Nossa equipe irá analisar e responderá o mais breve possível.');
	}
}
