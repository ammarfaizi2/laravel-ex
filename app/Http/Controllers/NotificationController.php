<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function ajaxNotification()
    {
    	$res = [
    		"unread_msg" => Auth::user()->countUnreadMessages()
    	];

    	return response()->json($res);
    }
}
