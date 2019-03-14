<?php

namespace App\Http\Middleware;

use App\Helpers\RolesHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
    public function handle(Request $request, Closure $next) {

		if(!RolesHelper::isAdmin($request)) {

			return Redirect::to('/')->with('message', 'Доступ к данному разделу доступен только администратору');

		} else {
			//
		}
        return $next($request);

    }

}
