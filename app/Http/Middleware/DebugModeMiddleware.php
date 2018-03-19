<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use App\Models\Helpers;

class DebugModeMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		
        if(Helpers::is_admin()) {
			Config::set('app.debug', false);
		}

        $response = $next($request);
        return $response;
    }
}