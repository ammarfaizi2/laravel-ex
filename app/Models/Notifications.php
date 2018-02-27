<?php

namespace App\Models;

use DB;
use Confide;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Notifications extends Eloquent
{	

	public static function getOldNotification()
	{
		return DB::table(DB::raw("order_notification AS a"))
			->select([
				"a.id",
				"a.order_id",
				DB::raw(
					"CONCAT(`d`.`type`,'/',`e`.`type`) as coin_name"
				),
				DB::raw(
					"(CASE WHEN `a`.`updated_at` IS NULL THEN `a`.`created_at` ELSE `a`.`updated_at` END) AS `date`"
				),
				"b.status",
				"b.price",
				"b.amount",
				"b.from_value as remaining_amount",
				"b.to_value as total",
				"b.type"
			])
			->join(DB::raw("orders AS b"), "a.order_id", "=", "b.id")
			->join(DB::raw("market AS c"), "b.market_id", "=", "c.id")
			->join(DB::raw("wallets AS d"), "c.wallet_from", "=", "d.id")
			->join(DB::raw("wallets AS e"), "c.wallet_to", "=", "e.id")
			->where("b.user_id", "=", Confide::user()->id)
			->where("a.status", "=", "read")
			->orderBy(
				DB::raw(
					"(CASE WHEN `a`.`updated_at` IS NULL THEN `a`.`created_at` ELSE `a`.`updated_at` END)"
				), "desc"
			)
			->limit(10)
			->offset(0)
			->get();
	}

    public static function getOrderNotification()
    {
  		// "SELECT `a`.`id`,`a`.`order_id`,
		// CONCAT(`d`.`type`,'/',`e`.`type`) AS coin_name,
		// (CASE WHEN 
		//   `a`.`updated_at` IS NULL 
		//   THEN `a`.`created_at`
		//   ELSE `a`.`updated_at`
		// END) AS `date`
		// FROM `order_notification` AS `a`
		// INNER JOIN `orders` AS `b` ON `a`.`order_id` = `b`.`id`
		// INNER JOIN `market` AS `c` ON `b`.`market_id` = `c`.`id`
		// INNER JOIN `wallets` AS `d` ON `c`.`wallet_from` = `d`.`id`
		// INNER JOIN `wallets` AS `e` ON `c`.`wallet_to` = `e`.`id`
		// WHERE `b`.`user_id` = 214 AND `a`.`status` = 'pending'
		// ORDER BY (CASE WHEN
		// `a`.`updated_at` IS NULL 
		// THEN `a`.`created_at` ELSE
		// `a`.`updated_at`
		// END) DESC;";
		return DB::table(DB::raw("order_notification AS a"))
			->select([
				"a.id",
				"a.order_id",
				DB::raw(
					"CONCAT(`d`.`type`,'/',`e`.`type`) as coin_name"
				),
				DB::raw(
					"(CASE WHEN `a`.`updated_at` IS NULL THEN `a`.`created_at` ELSE `a`.`updated_at` END) AS `date`"
				),
				"b.status",
				"b.price",
				"b.amount",
				"b.from_value as remaining_amount",
				"b.to_value as total",
				"b.type"
			])
			->join(DB::raw("orders AS b"), "a.order_id", "=", "b.id")
			->join(DB::raw("market AS c"), "b.market_id", "=", "c.id")
			->join(DB::raw("wallets AS d"), "c.wallet_from", "=", "d.id")
			->join(DB::raw("wallets AS e"), "c.wallet_to", "=", "e.id")
			->where("b.user_id", "=", Confide::user()->id)
			->where("a.status", "=", "pending")
			->orderBy(
				DB::raw(
					"(CASE WHEN `a`.`updated_at` IS NULL THEN `a`.`created_at` ELSE `a`.`updated_at` END)"
				), "desc"
			)->get();
    }
}
