<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
  public function html_mail()
   {
       $info = array(
           'name' => "Alex"
       );
       Mail::send('mail', $info, function ($message)
       {
           $message->to('alex@example.com', 'w3schools')
               ->subject('HTML test eMail from W3schools.');
           $message->from('karlosray@gmail.com', 'Alex');
       });
       echo "Successfully sent the email";
   }

}
