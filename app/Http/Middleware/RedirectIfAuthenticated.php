<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->is_administrator == 1){ // check admin
            $pth='/moirai-admin/dashboard';
            return redirect($pth);
            }
            else if(DB::table('users')->select('users.*','teachers.user_id')->join('teachers','teachers.user_id','=','users.id')->whereRaw('users.id = ?',array(Auth::user()->id))->get()->count() == 1){ // check teacher
             $pth='/teacher/dashboard';
              return redirect($pth);
            }
            else if(DB::table('users')->select('users.*','students.user_id')->join('students','students.user_id','=','users.id')->whereRaw('users.id = ?',array(Auth::user()->id))->get()->count() == 1){ // check student
             $pth='/student/dashboard';
             return redirect($pth);
             }
        //     $pth='/moirai-admin/dashboard';
        //     return redirect($pth);
        //    // return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
