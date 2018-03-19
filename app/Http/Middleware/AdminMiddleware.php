<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use App\Models\Helpers;

class AdminMiddleware
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
		if(!Helpers::is_admin()) {

			return Redirect::to('/')->with('message', 'Доступ к данному разделу доступен только администратору');

		} else {
			//
		}
        return $next($request);
    }
}
