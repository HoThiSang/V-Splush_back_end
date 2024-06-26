<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminReplyMail extends Mailable
{
    use Queueable, SerializesModels;
    public  $sendData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendData)
    {
        $this->sendData = $sendData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

     public function build()
     {
         return $this->from(getenv('MAIL_USERNAME'))
         ->subject($this->sendData['subject'])
             ->view('Mail.admin-contact-reply')
             ->with([
                 'name' => $this->sendData['name'],
                 'subject' => $this->sendData['subject'],
                 'message' => $this->sendData['message'],
             ]);
     }
}
