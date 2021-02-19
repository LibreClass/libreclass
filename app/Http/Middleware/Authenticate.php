<?php namespace App\Http\Middleware;

use Closure;

class Authenticate
{
	public function handle($request, Closure $next)
	{
		if (auth()->guest()) {
			if ($request->ajax() || $request->wantsJson()) {
				return response('unauthenticated', 401);
			} else {
				return redirect('/login');
			}
		}

		return $next($request);
	}
}
