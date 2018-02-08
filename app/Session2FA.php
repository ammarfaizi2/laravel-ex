<?php

namespace App;

use Confide;
use Illuminate\Database\Eloquent\Model;

class Session2FA extends Model
{
    //
    public static function check()
    {
        if (! Confide::user()->google2fa_secret) {
            return true;
        }
    	if (isset($_SERVER["HTTP_REQUESTED_WITH"]) && $_SERVER["HTTP_REQUESTED_WITH"] === "XMLHttpRequest") {
            if (isset($_GET["redirect"])) {
                session([
                    "2fa_post" => $_POST,
                    "2fa_ajax_postget" => true
                ]);
                \Session::save();
                exit();
            }
    		return true;
    	}
        if (session()->get("2fa_ajax_postget") && $_SERVER["REQUEST_METHOD"] === "GET") {
            session(["2fa_ajax_postget" => null]);
            return true;
        }
    	http_response_code(405);
    	header("Content-type:application/json");
    	print json_encode(["message" => trans("user_texts.error_tfa_1")]);
    	exit(405);
    }

    public static function post2fa()
    {
        $s = session()->get("2fa_post");
        session(
            [
                "2fa_post" => null
            ]
        );
        return $s;
    }
}
