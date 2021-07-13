<?php

namespace App\Http\Middleware;

use Closure;
use App\Organization;

class CheckModule
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	
	protected $organization;

	public function __construct(Organization $organization) {
		$this->organization = $organization;
	}

	public function handle($request, Closure $next)
	{
		if($request->user() === null) {
			abort(403);
		}

		$actions = $request->route()->getAction();
		$organization_id =  $request->session()->get('organization_id'); 

		if($organization_id == null) {
			 return redirect()->to('home');
		}

		if(isset($actions['modules'])) {
			$modules = explode(',', $actions['modules']);
			if(count($modules) > 0) {
				foreach ($modules as $module) {
					if($this->organization->hasAnyModule($module, $organization_id) || !$module) {
						return $next($request);
					}
				}
			} else {
				if($this->organization->hasAnyModule($actions['modules'], $organization_id) || !$actions['modules']) {
						return $next($request);
					}
			}
			
		} 		

		abort(403);
	}
}
