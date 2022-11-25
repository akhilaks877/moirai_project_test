<?php

namespace App\Listeners;

use App\Mail\DemoMail;
use App\Events\PodcastProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPodcastNotification
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  /**
   * Handle the event.
   *
   * @param  NewParishAdminDetected  $event
   * @return void
   */
  public function handle(NewParishAdminDetected $event)
  {


      // $message_data .='<h4><strong>YOU HAVE BEEN REGISTERED AS A FIRST LEVEL APPROVER</strong></h4>';
      $message_data .='<p>You have been added as a Parish Admin under the Parish&nbsp;<em><strong>'.$parish_admin->parish->name.'&nbsp;&nbsp;</strong></em></p>';
      $message_data .='<p>&nbsp;</p>';
      $message_data .='<p>Your Login User ID, Password and access levels are as follows:</p>';

    $v="akhilasasik@gmail.com";
          Mail::to($v)->send(new DemoMail($pass_array));
      }

  }
}
