<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session2FA extends Model
{
    //
    public static function check()
    {
    	if (isset($_SERVER["HTTP_REQUESTED_WITH"]) && $_SERVER["HTTP_REQUESTED_WITH"] === "XMLHttpRequest") {
    		return true;
    	}
    	http_response_code(405);
    	header("Content-type:application/json");
    	print json_encode(["message" => trans("user_texts.error_tfa_1")]);
    	exit(405);
    }
}
