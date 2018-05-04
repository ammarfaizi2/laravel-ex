<?php

namespace App\Mail;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmAccount extends Mailable
{
    use Queueable, SerializesModels;

    private $userInfo = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($p)
    {
        $this->userInfo = $p;
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

        $st = DB::table("confirmation_code")
            ->select(["code", "expired_at"])
            ->where("user_id", "=", $user["id"])
            ->first();
        $create = true;
        if ($st) {
            // delete old code if old code has been expired.
            if (strtotime($st->expired_at) < time()) {
                DB::table("confirmation_code")
                ->where("user_id", "=", $user["id"])
                ->delete();
            } else {
                $create = false;
                $token = $st->code;
            }
        }

        if ($create) {
            $token = "";
            $r = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM___---";
            $l = strlen($r) - 1;
            for ($i=0; $i < 64; $i++) { 
                $token .= $r[rand(0, $l)];
            }
            DB::table("confirmation_code")->insert(
                [
                    "user_id" => $user["id"],
                    "code" => $token,
                    "expired_at" => date("Y-m-d H:i:s", time()+env("ACCOUNT_CONFIRMATION_CODE_EXPIRED")),
                    "created_at" => date("Y-m-d H:i:s")
                ]
            );
        }

        return $this->view(
                'emails.confirm_account', 
                [
                    'user' => [
                        'username' => $user["username"], 
                        'email' => $user["email"],
                        'confirmation_code' => $token,
                    ], 
                    'confirmation_code' => $token,
                    'token' => $token
                ]
            );
    }
}
