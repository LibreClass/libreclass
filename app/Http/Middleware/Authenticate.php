<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class Authenticate
{
	public function handle($request, Closure $next)
	{
		// Verifica se a requisição é para .../api/..
		$q = Str::start($request->path(), '/');
		$isAPI = str_contains($q, "/api/");

		if (auth()->guest() && !$isAPI) {
			if ($request->ajax() || $request->wantsJson()) {
				return response('unauthenticated', 401);
			} else {
				return redirect('/login');
			}
		}

		return $next($request);
	}
}
