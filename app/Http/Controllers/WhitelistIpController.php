<?php

namespace App\Http\Controllers;

use DB;
use Confide;
use Illuminate\Http\Request;

class WhitelistIpController extends Controller
{
    //
    public function add()
    {
    	if (isset($_POST["data"], $_POST["type"]) && in_array($_POST["type"], ["login", "trade", "withdraw"])) {
    		$user = Confide::user();
    		header("Content-type:application/json");
    		$ips = explode(",", $_POST["data"]);
    		$valid = [];
    		foreach ($ips as $ip) {
    			if (! $this->validIp($ip)) {
    				exit(json_encode(
    					[
    						"alert" => trans("user_texts.invalid_ip", ["ip" => $ip])
    					]
    				));
    			}
    			$valid[] = [
    				"user_id" => $user->id,
    				"ip"	=> $ip,
    				"created_at" => date("Y-m-d H:i:s")
    			];
    		}
    		DB::table("whitelist_".$_POST["type"]."_ip")->insert($valid);
    		exit(json_encode(
    			[
    				"alert" => trans("user_texts.add_ip_success"),
    				"redirect" => ""
    			]
    		));
    	}
    }

    private function validIp($ip)
    {
    	return filter_var($ip, FILTER_VALIDATE_IP);
    }
}
