<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Google2FAHandler extends Controller
{
    //
    public function check()
    {
    	if (isset($_POST['code'])) {
    		if (($w = User::google2FA($_POST['code'])) && isset($_POST['session'])) {
    			$_POST['session'] = json_decode($_POST['session'], true);
    			is_array($_POST['session']) and session($_POST['session']);
    		}
    		return response()->json($w);
    	}
    }
}
