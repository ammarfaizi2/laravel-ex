<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table("users")
    		->insert(
    			[
    				"fullname" => "Admin",
    				"username" => "admin",
    				"email" => "admin@admin.com",
    				"password" => password_hash("admin123", PASSWORD_BCRYPT),
    				"confirmation_code" => sha1(rand()),
    				"confirmed" => 1,
    				"banned" => 0,
    				"authy" => "",
    				"two_factor_auth" => "",
    				"created_at" => date("Y-m-d H:i:s"),
    				"updated_at" => null,
    				"lastest_login" => null,
    				"timeout" => "45 minutes",
    				"referral" => null,
    				"trade_key" => sha1(rand()),
    				"ip_lastlogin" => "",
    				"remember_token" => null,
    				"google2fa_secret" => null
    			]
    		);
    	DB::table("wallets")
    		->insert(
    			[
	    			[
	    				"type" => "BAY",
	    				"name" => "Bitbay",
	    				"wallet_username" => "username",
	    				"wallet_password" => "password",
	    				"wallet_ip" => "127.0.0.1",
	    				"port" => "99999",
	    				"download_wallet_client" => "",
	    				"logo_coin" => "upload/images/1437959471.png",
	    				"enable_deposit" => 1,
	    				"enable_withdraw" => 1,
	    				"confirm_count" => 1,
	    				"enable_trading" => 1
	    			],
	    			[
	    				"type" => "BTC",
	    				"name" => "Bitcoin",
	    				"wallet_username" => "username",
	    				"wallet_password" => "password",
	    				"wallet_ip" => "127.0.0.1",
	    				"port" => "99999",
	    				"download_wallet_client" => "",
	    				"logo_coin" => "upload/images/1405659460.png",
	    				"enable_deposit" => 1,
	    				"enable_withdraw" => 1,
	    				"confirm_count" => 1,
	    				"enable_trading" => 1
	    			]
	    		]
    		);
        DB::table("roles")
            ->insert(
                [
                    [
                        "id" => 1,
                        "name" => "Admin"
                    ],
                    [
                        "id" => 3,
                        "name" => "User"
                    ]
                ]
            );
        DB::table("users_roles")
            ->insert(
                [
                    "user_id" => 1,
                    "role_id" => 1
                ]
            );
    	DB::table("market")
    		->insert(
    			[
    				"wallet_from" => 1,
    				"wallet_to" => 2
    			]
    		);
    }
}
