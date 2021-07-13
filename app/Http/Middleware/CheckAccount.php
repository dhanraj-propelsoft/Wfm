<?php

namespace App\Http\Middleware;

use Closure;

class CheckAccount
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if($request->user() === null) {
			abort(403);
		}

		$actions = $request->route()->getAction();
		$accounts = isset($actions['account']) ? $actions['account'] : null;

		if($accounts == $request->session()->get('account_type')) {
			return $next($request);
		}

		return redirect()->to('home');
	}
}
