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
    	$user = Confide::user();
        if ($user->google2fa_secret) {
            if ($s = session()->get("2fa_payload")) {
                
                if ($s != url()->current()) {
                    abort(404);   
                } else {
                    session(["2fa_payload" => null]);
                }
            } else {
                abort(404);
            }
        }
    	if (isset($_POST["data"], $_POST["type"]) && in_array($_POST["type"], ["login", "trade", "withdraw"]) && $user) {
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
                    "type" => $_POST["type"],
    				"created_at" => date("Y-m-d H:i:s")
    			];
    		}
    		DB::table("whitelist_ip")->insert($valid);
    		exit(json_encode(
    			[
    				"alert" => trans("user_texts.add_ip_success"),
    				"redirect" => ""
    			]
    		));
    	}
    }

    public function remove()
    {
    	$user = Confide::user();
        if ($user->google2fa_secret) {
            if ($s = session()->get("2fa_payload")) {
                if ($s != url()->current()) {
                    abort(404);   
                } else {
                    session(["2fa_payload" => null]);
                }
            } else {
                abort(404);
            }
        }
    	if (isset($_POST["data"], $_POST["type"]) && in_array($_POST["type"], ["login", "trade", "withdraw"]) && $user) {
    		header("Content-type:application/json");
    		if ($user) {
    			$d = json_decode($_POST["data"], true);
    			if (! isset($d["id"], $d["ip"])) {
    				http_response_code(403);
    				exit; 
    			}
    			DB::table("whitelist_ip")
    				->where("id", "=", $d["id"])
    				->where("ip", "=", $d["ip"])
    				->where("user_id", "=", $user->id)
                    ->where("type", "=", $_POST["type"])
    				->limit(1)
    				->delete();
    		}
    		exit(json_encode(
    			[
    				"alert" => trans("user_texts.remove_ip_success", ["ip" => $d["ip"], "from" => ucfirst($_POST["type"])." Whitelist IP"]),
    				"redirect" => ""
    			]
    		));
    	}
    }

    private function validIp($ip)
    {
    	return preg_match("/[\d\*\:]/", $ip);
    }

    public function turnOff()
    {
        $user = Confide::user();
        if ($user->google2fa_secret) {
            if ($s = session()->get("2fa_payload")) {
                if ($s != url()->current()) {
                    abort(404);   
                } else {
                    session(["2fa_payload" => null]);
                }
            } else {
                abort(404);
            }
        }
        if ($user && isset($_POST["type"]) && in_array($_POST["type"], ["login", "trade", "withdraw"])) {
            header("Content-type:application/json");
            $g = DB::table("whitelist_ip_state")
            ->select("user_id")
            ->where("user_id", "=", $user->id)
            ->first();
            if ($g) {
               DB::table("whitelist_ip_state")
               ->where("user_id", "=", $user->id)
               ->limit(1)
               ->update(
                    [
                        $_POST["type"] => "off",
                        "updated_at" => date("Y-m-d H:i:s")
                    ]
               );
            } else {
                DB::table("whitelist_ip_state")
                ->insert(
                    [
                        "user_id" => $user->id,
                        "trade" => ($_POST["type"] === "trade" ? "off" : "on"),
                        "login" => ($_POST["type"] === "login" ? "off" : "on"),
                        "withdraw" => ($_POST["type"] === "withdraw" ? "off" : "on"),
                        "created_at" => date("Y-m-d H:i:s")
                    ]
                );
            }
            exit(json_encode(
                [
                    "alert" => trans("user_texts.turn_off_ipw", ["type" => ucfirst($_POST["type"])]),
                    "redirect" => ""
                ]
            ));
        }
    }

    public function turnOn()
    {
        $user = Confide::user();
        if ($user->google2fa_secret) {
            if ($s = session()->get("2fa_payload")) {

                if ($s != url()->current()) {
                    abort(404);   
                } else {
                    session(["2fa_payload" => null]);
                }
            } else {
                abort(404);
            }
        }
        if ($user && isset($_POST["type"]) && in_array($_POST["type"], ["login", "trade", "withdraw"])) {
            header("Content-type:application/json");
            $g = DB::table("whitelist_ip_state")
            ->select("user_id")
            ->where("user_id", "=", $user->id)
            ->first();
            if ($g) {
               DB::table("whitelist_ip_state")
               ->where("user_id", "=", $user->id)
               ->limit(1)
               ->update(
                    [
                        $_POST["type"] => "on",
                        "updated_at" => date("Y-m-d H:i:s")
                    ]
               );
            } else {
                DB::table("whitelist_ip_state")
                ->insert(
                    [
                        "user_id" => $user->id,
                        "trade" => ($_POST["type"] === "trade" ? "on" : "off"),
                        "login" => ($_POST["type"] === "login" ? "on" : "off"),
                        "withdraw" => ($_POST["type"] === "withdraw" ? "on" : "off"),
                        "created_at" => date("Y-m-d H:i:s")
                    ]
                );
            }
            exit(json_encode(
                [
                    "alert" => trans("user_texts.turn_on_ipw", ["type" => ucfirst($_POST["type"])]),
                    "redirect" => ""
                ]
            ));
        }
    }
}
