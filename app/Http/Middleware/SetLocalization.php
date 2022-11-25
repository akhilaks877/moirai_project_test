<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class SetLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
         if(Auth::guard($guard)->check()){
            $lang_arr=['en','fr']; $lng_l=DB::table('preferrable_langs')->where('id',Auth::user()->preferred_language)->first();
            $lng_set=(isset($lng_l->short_code) && in_array($lng_l->short_code, $lang_arr)) ? $lng_l->short_code : 'en';
            app()->setLocale($lng_set);
         }
        return $next($request);
    }
}
