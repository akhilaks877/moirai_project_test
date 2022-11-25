<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoMail extends Mailable
{
  use Queueable, SerializesModels;

   public $mailData;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($mailData)
   {
       $this->mailData = $mailData;
   }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $subject='Parish Admin Registration Successful Parish';
      return $this->from('amcf.census@gmail.com')
      ->view('mail')
      ->subject($subject);
    }
}
