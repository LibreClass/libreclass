<?php namespace App\Http\Controllers;

use Exception;
use Imagick;
use Hash;
use App\Country;
use App\State;
use App\City;

class ConfigController extends Controller
{
	private $select = [
		'gender' => [
			'M' => 'Masculino',
			'F' => 'Feminino',
		],
		'formation' => [
			'Não quero informar',
			'Ensino Fundamental',
			'Ensino Médio',
			'Ensino Superior Incompleto',
			'Ensino Superior Completo',
			'Pós-Graduado',
			'Mestre',
			'Doutor',
		],
		'type' => [
			'P' => 'Professor',
			'A' => 'Aluno',
			'T' => 'Professor/Aluno',
			'I' => 'Instituição',
		],
	];

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('user.config', [
			'user' => auth()->user(),
			'select' => $this->select,
		]);
	}

	public function postPhoto()
	{
		if (request()->hasFile("photo") && request()->file("photo")->isValid()) {
			$fileName = "/uploads/" . sha1(auth()->id()) . "_" . microtime(true) . ".jpg";

			switch (request()->file("photo")->getMimeType()) {
				case "image/png":
				case "image/jpeg":
				case "image/gif":
					break;
				default:
					return redirect("/config")->with("error", "Não pode ser modificado!");
			}

			$image = new Imagick(request()->file("photo")->getRealPath());
			$width = $image->getImageWidth();
			$height = $image->getImageHeight();
			if ( $width < $height ) {
				$image->cropImage($width, $width, 0, ($height-$width)/2);
			} else {
				$image->cropImage($height, $height, ($width-$height)/2, 0);
			}

			if ($image->getImageHeight() > 400) {
				$image->thumbnailImage(400, 400);
			}

			//~ request()->file("imageproduct")->move("uploads", $fileName);
			$image->writeImage(public_path($fileName));

			return auth()->user()->update(["photo" => $fileName ]) ?
				redirect("/config")->with("success", "Modificado com sucesso!") :
				redirect("/config")->with("error", "Não pode ser modificado!");
		}
		else {
			return redirect("/config")->with("error", "Não pode ser modificado!");
		}
	}

	public function postBirthdate()
	{
		$user = auth()->user();
		$user->birthdate = request()->get("birthdate-year") . "-" .
			request()->get("birthdate-month") . "-" .
			request()->get("birthdate-day");
		$user->save();

		return redirect("/config")->with("success", "Modificado com sucesso!");
	}

	/**
	 * Atualiza os campos no formulário de cadastro
	 * @return type update
	 */
	public function postCommon()
	{
		auth()->user()->update(request()->except([
			'_token',
			'q',
		]));

		return redirect()
			->back()
			->with("success", "Modificado com sucesso!");
	}

	public function postCommonselect()
	{
		return auth()->user()->update(request()->except(['_token', 'q'])) ?
			redirect("/config")->with("success", "Modificado com sucesso!"):
			redirect("/config")->with("erro", "Erro ao modificar!");
	}

	public function postGender()
	{
		$user = auth()->user();
		$user->gender = request()->get("gender");
		$user->save();

		return redirect("/config")->with("success", "Modificado com sucesso!");
	}

	public function postType()
	{
		$user = auth()->user();
		$user->type = request()->get("type");
		$user->save();

		return redirect("/config")->with("success", "Modificado com sucesso!");
	}

	public function postPassword()
	{
		$user = auth()->user();
		if (Hash::check(request()->get('password'), $user->password)) {
			$user->password = Hash::make(request()->get('newpassword'));
			$user->save();
			return redirect('/config')->with('success', 'Modificado com sucesso!');
		}

		return redirect('/config')->with('error', 'Senha atual inválida!');
	}

	public function postLocation()
	{
		$city = City::whereName(request()->get("city"))->first();
		if ( $city == null ) {
			$state = State::whereShort(request()->get("state"))->first();
			if ( $state == null ) {
				$country = Country::whereShort(request()->get("country"))->first();
				if ( $country == null ) {
					$country = new Country;
					$country->name  = request()->get("country");
					$country->short = request()->get("country_short");
					$country->save();
				}
				$state = new State;
				$state->name = request()->get("state");
				$state->short = request()->get("state_short");
				$state->country_id = $country->id;
				$state->save();
			}
			$city = new City;
			$city->name = request()->get("city");
			$city->state_id = $state->id;
			$city->save();
		}

		$user = auth()->user();
		$user->city_id = $city->id;
		$user->save();

		return request()->get("city") . ", " . request()->get("state") . ", " . request()->get("country");
	}

	public function postStreet()
	{
		$user = auth()->user();
		$user->street = request()->get('street');
		$user->save();
		return redirect('/config')->with('success', 'Modificado com sucesso!');
	}

	public function putStreet()
	{
		try {
			$user = auth()->user();
			$user->street = request()->get('street');
			$user->save();
			return response()->json([
				'status' => true,
				'street' => $user->street
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'error' => $th->getMessage()
			]);
		}
	}

	public function postUee()
	{
		$user = auth()->user();
		$user->uee = request()->get("uee");
		$user->save();

		return redirect("/config")->with("success", "Modificado com sucesso!");
	}

}
