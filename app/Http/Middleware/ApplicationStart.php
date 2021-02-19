<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Log;

class ApplicationStart
{
	public function handle($request, Closure $next)
	{
		defined('LARAVEL_START') or define('LARAVEL_START', microtime(true));
		if (env('DEBUGBAR_ENABLED', false)) {
			\DB::enableQueryLog();
		}

		$all = $request->all();

		if (isset($all['photo'])) {
			$all['photo'] = Str::limit($all['photo']);
		}
		$q = Str::start($request->path(), '/');
		unset($all['password']);
		unset($all['q']);
		$user = auth()->guest() ? 'guest' : auth()->id();
		Log::info("ACCESS $q [$user]", $all);

		return $next($request);
	}

	public function terminate($request, $response)
	{
		if ($response->getStatusCode() == 200) {
			$q = Str::start($request->path(), '/');

			Log::info("FINISH $q", [
				'time' => number_format(microtime(true) - LARAVEL_START, 3, '.', ' '),
				'size' => number_format(strlen($response->getContent()), 1, '.', ' '),
			]);
		}
	}
}
