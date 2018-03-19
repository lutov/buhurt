<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use App\Models\Helpers;

class ModeratorMiddleware
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
		if(!Helpers::is_moderator()) {

			return Redirect::to('/')->with('message', 'Доступ к данному разделу доступен только модератору');

		} else {

			die(1);

		}

        return $next($request);
    }
}
