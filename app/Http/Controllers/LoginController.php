<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Mail;
use App\User;

class LoginController extends Controller
{
	public function index()
	{
		if (auth()->guest()) {
			return view('user.login');
		}
		return redirect('/');
	}

	public function login(Request $request)
	{
		$user = User::whereEmail($request->email)->first();

		if ($user and Hash::check($request->password, $user->password)) {
			if ( $user->cadastre == "W" ) {
				// $url = url("/check/") . "/" . encrypt($user->id);
				// Mail::send('email.welcome', ["url" => $url, "name" => $user->name ], function($message)
				// {
				//   $user = User::whereEmail(request()->get("email"))->first();
				//   $message->to( $user->email, $user->name )
				//           ->subject("Seja bem-vindo");
				// });
				return back()
					->with('error', "O email <b>$request->email</b> ainda não foi validado.")
					->withInput();
			} else {
				if ( $user->type == "M" || $user->type == "N" ) {
					$user->type = "P";
					$user->save();
				}
				auth()->login($user);
				return redirect('/');
			}
		} else {
			return back()
				->with('error', 'Login ou senha incorretos.')
				->withInput();
		}
	}

	public function logout()
	{
		auth()->logout();

		return redirect('/');
	}

	public function forgotPassword(Request $request)
	{
		$user = User::whereEmail($request->email)->first();
		if (!$user) {
			return redirect('/login')
				->with('error', 'Erro: Email não está cadastrado.');
		}

		if ($user->cadastre == 'N' || $user->cadastre == 'W')
		{
			$password = str_shuffle('libreclass');
			$user->password = Hash::make($password);
			$user->save();

			Mail::send('email.forgot-password', [
				'password' => $password,
				'user' => $user,
			], function($message) use ($user) {
				$message->to( $user->email, $user->name )
					->subject('LibreClass Social - Sua nova senha');
			});
			return redirect('/login')
				->with('info', 'Uma nova senha foi enviada para seu e-mail.');
		}

		$msg = 'Erro: ';
		if ($user->cadastre == 'G') {
			$msg .= 'Seu login deve ser feito pelo Google.';
		}
		if ($user->cadastre == 'F') {
			$msg .= 'Seu login deve ser feito pelo Facebook.';
		}

		return redirect('/login')->with('error', $msg);
	}

}
