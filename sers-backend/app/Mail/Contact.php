<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $user_email;
    public $msg;


    public function __construct($name, $user_email, $msg)
    {
        //
        $this->name = $name;
        $this->user_email = $user_email;
        $this->msg = $msg;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mail.contact')
        ->subject('Contact Form')
        ->with([
            'name' => $this->name,
            'user_email' => $this->user_email,
            'msg' => $this->msg
        ]);
    }
}
