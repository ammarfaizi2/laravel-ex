<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use Illuminate\Http\Request;

class Google2FAHandler extends Controller
{
    //
    public function check()
    {
    	if (isset($_POST['code'])) {
            $max = env("GOOGLE2FA_THROTTLE") - 1;
            $lockedTime = env("GOOGLE2FA_SECONDS_LOCKED");

            $tries = session()->get("g2fa_tt");
            $last_try = (int) session()->get("g2fa_lt");

            if (is_int($tries)) {
                if ($tries > $max) {
                    if ($last_try+$lockedTime > time()) {
                        session(["g2fa_lt" => time()]);
                        return response()->json("throttled");
                    } else {
                        $tries = 0;
                    }
                }
            } else {
                $tries = 0;
            }

            session(["g2fa_lt" => time(), "g2fa_tt" => $tries+1]);

    		if (($w = User::google2FA($_POST['code'])) && isset($_POST['session'])) {
                session(["g2fa_tt" => 0]);
    			$_POST['session'] = json_decode($_POST['session'], true);
    			is_array($_POST['session']) and session($_POST['session']);
    		}
    		return response()->json($w);
    	}
    }
}
