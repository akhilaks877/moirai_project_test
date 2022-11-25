<?php

namespace App\Http\Controllers\Auth\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use DB;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'teacher/dashboard';

    public function showLoginForm()
    {
        return view('auth.teacher.login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
       // dd(Auth::user()->email);
      $this->validateLogin($request);
      $email=$request->input('email');
      $user_check=DB::table('users')->select('users.*','teachers.user_id')->join('teachers','teachers.user_id','=','users.id')->whereRaw('users.email = ?',array($email))->get();
      if($user_check->count() == 1){
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
      }
      else{
          return redirect()->back()->with('login_error','Teacher user not exists.');
      }
    }
}
