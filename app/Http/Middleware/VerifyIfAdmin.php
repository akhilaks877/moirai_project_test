<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class VerifyIfAdmin
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
        if(Auth::user() && Auth::user()->is_administrator == 1){
            return $next($request);
            }
            Auth::logout();
            return redirect(route('login'));
            // abort(401, 'This action is unauthorized.');
    }
}
