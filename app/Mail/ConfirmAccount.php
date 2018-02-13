<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject = "Account Confirmation";
        $user = $this->userInfo;
        $token = "";
        $r = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM___---";
        $l = strlen($r) - 1;
        for ($i=0; $i < 64; $i++) { 
            $token .= $r[rand(0, $l)];
        }
        return $this->view(
                'emails.password_reset', 
                [
                    'user' => [
                        'username' => $user["username"], 
                        'email' => $user["email"]
                    ], 
                    'confirmation_code' => $token
                ]
            );
    }
}
