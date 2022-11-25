<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectPath()  // done by jk
    {
      $us_type=auth()->user()->is_administrator;
      if($us_type == 1){ // check admin
        $path='/moirai-admin/dashboard';
        }
     else if(DB::table('users')->select('users.*','teachers.user_id')->join('teachers','teachers.user_id','=','users.id')->whereRaw('users.id = ?',array(auth()->user()->id))->get()->count() == 1){ // check teacher
        $path='/teacher/dashboard';
        }
     else if(DB::table('users')->select('users.*','students.user_id')->join('students','students.user_id','=','users.id')->whereRaw('users.id = ?',array(auth()->user()->id))->get()->count() == 1){ // check student
        $path='/student/dashboard';
        }

        return $path;
    }

}
