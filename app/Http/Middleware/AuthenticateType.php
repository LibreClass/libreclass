<?php namespace App\Http\Middleware;

use Closure;

class AuthenticateType
{
	public function handle($request, Closure $next, $type)
	{
		if (auth()->guest()) {
			if ($request->ajax() || $request->wantsJson()) {
				return response('unauthenticated', 401);
			} else {
				return redirect('/login');
			}
		}

		if ( strripos($type, auth()->user()->type) === false || auth()->user()->status == 'D') {
			if ($request->ajax() || $request->wantsJson()) {
				return response('unauthorized', 401);
			} else {
				return redirect('oops');
			}
		}

		return $next($request);
	}
}
