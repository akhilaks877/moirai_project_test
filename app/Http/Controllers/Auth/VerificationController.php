<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Auth;
use DB;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
   // protected $redirectTo = RouteServiceProvider::HOME;  // commented by jk
   public function redirectTo()
   {
       if(Auth::user()->is_administrator == 1){ // check admin
           $pth='/moirai-admin/dashboard';
           }
           else if(DB::table('users')->select('users.*','teachers.user_id')->join('teachers','teachers.user_id','=','users.id')->whereRaw('users.id = ?',array(Auth::user()->id))->get()->count() == 1){ // check teacher
            $pth='/teacher/dashboard';
           }
           else if(DB::table('users')->select('users.*','students.user_id')->join('students','students.user_id','=','users.id')->whereRaw('users.id = ?',array(Auth::user()->id))->get()->count() == 1){ // check student
            $pth='/student/dashboard';
            }
       return $pth;
   }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
