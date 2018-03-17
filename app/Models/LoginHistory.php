<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    public static function create($userId, $ip)
    {
    	$tfa = DB::table("users")
    		->select("google2fa_secret")
    		->where("id", "=", $userId)
    		->first();
    	$tfa = $tfa ? $tfa->google2fa_secret : null;
    	return DB::table("login_history")
    	->insert(
    		[
    			"user_id" => $userId,
    			"ip_address" => $ip,
    			"user_agent" => (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null),
    			"2fa" => ($tfa ? "on" : "off"),
    			"created_at" => date("Y-m-d H:i:s")
    		]
    	);
    }

    public static function get($userId = null)
    {
    	if (is_null($userId)) {
    		return DB::table("login_history")
    			->select("*")
    			->get();
    	} else {
    		return DB::table("login_history")
    			->select("*")
    			->where("user_id", $userId)
    			->get();
    	}
    }
}
