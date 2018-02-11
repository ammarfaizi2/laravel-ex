<?php

namespace App\Mail;

use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $userInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        //
        $this->userInfo = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->subject = "Password Reset";
        $user = $this->userInfo;
        $token = "";
        $r = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM___---";
        $l = strlen($r) - 1;
        for ($i=0; $i < 64; $i++) { 
            $token .= $r[rand(0, $l)];
        }
        DB::table("password_reminders")
            ->insert(
                [
                    "email" => $this->userInfo["email"],
                    "token" => $token,
                    "created_at" => date("Y-m-d H:i:s")
                ]
            );
        return $this->view(
                'emails.password_reset', 
                [
                    'user' => [
                        'username' => $user["username"], 
                        'email' => $user["email"]
                    ], 
                    'token' => $token
                ]
            );
    }
}
