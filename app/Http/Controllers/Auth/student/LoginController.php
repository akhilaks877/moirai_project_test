<?php

namespace App\Http\Controllers\Auth\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use DB;
use Spatie\WebhookServer\WebhookCall;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'student/dashboard';

    public function showLoginForm()
    {
        return view('auth.student.login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
      //dd("login");


       
      $this->validateLogin($request);
      $email=$request->input('email');
      $user_check=DB::table('users')->select('users.*','students.user_id')->join('students','students.user_id','=','users.id')->whereRaw('users.email = ?',array($email))->get();
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
        //dd("user alla");
        /*$response = WebhookCall::create()
         ->url('https://stackoverflow.com')
         ->payload(['email' => $email])
         ->useSecret('mysecretkey')
         ->dispatch();
        dd($response);*/
    
      /*  $client = new Client();
        $res = $client->request('POST', 'https://stackoverflow.com', [
            'form_params' => [
                'client_id' => 'test_id',
                'secret' => 'test_secret',
            ]
        ]);
        echo $res->getStatusCode();
        // 200
       // echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();*/
        // {"type":"User"...'
 // { "type": "User", ....

        /* if($response)
         {
          dd("poyittund");
         }*/
         /*$client = new Client();

         $res = $client->request('POST', 'https://stackoverflow.com/company', [
          'form_params' => [
              'name' => 'george',
          ]
        ]);

        if ($res->getStatusCode() == 200) { // 200 OK
          //dd("poyittund");

          $response_data = $res->getBody()->getContents();
          dd($response_data);
        }*/

          //return redirect()->back()->with('login_error','Teacher user not exists.');
      }
    }
}
