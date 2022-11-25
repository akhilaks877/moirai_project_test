<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;

class VerifyIfTeacher
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
        if(Auth::user() &&  (DB::table('users')->select('users.*','teachers.user_id')->join('teachers','teachers.user_id','=','users.id')->whereRaw('users.id = ?',array(Auth::user()->id))->get()->count() == 1)){
            return $next($request);
            }
            Auth::logout();
            return redirect(route('login'));
            //abort(401, 'This action is unauthorized.');
    }
}
