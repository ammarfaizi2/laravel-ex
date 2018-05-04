<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Confide;
use Illuminate\Http\Request;
use App\Models\Notifications;

class NotificationController extends Controller
{
    public function ajaxNotification()
    {
        if (! Confide::user()) {
            abort(404);
            exit();
        }
    	$res = [
    		"unread_msg" => Auth::user()->countUnreadMessages(),
    		"order_notification" => Notifications::getOrderNotification()
    	];

    	return response()->json($res);
    }

    public function readNotification()
    {
        if (! Confide::user()) {
            abort(404);
            exit();
        }
    	$a = json_decode($_POST['data'], true);
    	foreach ($a as $val) {
    		DB::table("order_notification")
    			->where("id", "=", $val)
    			->limit(1)
    			->update([
    				"status" => "read"
    			]);
    	}
    }
}
