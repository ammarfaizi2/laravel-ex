<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Notifications extends Eloquent
{
    public static function getUserNotification()
    {
        DB::table("order_notification")
            ->select([]);
    }
}
