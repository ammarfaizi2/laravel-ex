<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Notifications;

class NotificationController extends Controller
{
    public function ajaxNotification()
    {
    	$res = [
    		"unread_msg" => Auth::user()->countUnreadMessages(),
    		"order_notification" => Notifications::getOrderNotification()
    	];

    	return response()->json($res);
    }

    public function readNotification()
    {
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
