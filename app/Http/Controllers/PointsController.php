<?php

namespace App\Http\Controllers;

use DB;
use App\Models\News;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Limits;
use App\Models\Market;
use App\Models\Wallet;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Setting;
use App\Models\FeeTrade;
use App\Models\CoinVote;
use App\Models\Transfer;
use App\Models\Withdraw;
use App\Models\Giveaways;
use App\Models\FeeWithdraw;
use App\Models\Notifications;
use App\Models\Giveawayclaims;
use App\Models\Authentication;
use App\Models\WalletLimitTrade;
use App\Models\SecurityQuestion;
use App\Models\UserAddressDeposit;
use App\Models\UserSecurityQuestion;

class PointsController extends Controller
{

    public static function buildCommissionFees($r)
    {
        $c = json_decode(env("COMMISSION_FEES"), true);
        foreach ($c as $key => $v) {
            $key = explode("-", $key);
            if (in_array($r, range($key[0], $key[1]))) {
                return $v;
            }
        }
        if ($r > $key[1]) {
            return $v;
        }
        return [0, ""];
    }

    public static function getReferral($userId)
    {
        if ($user = DB::table("users")->select("referral")->where('id', '=', $userId)->first()) {
            $id = $user->referral;
            $st = DB::table("users")
                ->select("id")
                ->where("id", "=", $id)
                ->first();
            if (isset($st->id)) {
                return [
                    "user_id" => $st->id
                ];
            }
        }
        return;
    }

    public static function countReferredUser($userId)
    {
        $st = @DB::table("users")
                ->select([DB::raw("count(`username`) as c")])
                ->where("referral", "=", $user->id)
                ->where("confirmed", "=", 1)
                ->get()[0]->c;  
        return isset($st) ? $st : 0;
    }

    public static function createCommission($userId, $walletId, $amount, $ref_user_id)
    {
        DB::table("commission_fees")
            ->insert(
                [
                    "user_id" => $userId,
                    "ref_user_id" => $ref_user_id,
                    "wallet_id" => $walletId,
                    "amount" => $amount,
                    "created_at" => date("Y-m-d H:i:s")
                ]
            );
    }

    public static function commissionCheck($walletId, $fee_buy, $fee_sell, $buyer_id, $seller_id)
    {
        $b = new Balance;
        if ($fee_buy > 0) {
            if ($e = self::getReferral($buyer_id)) {
                $r = self::countReferredUser($e["user_id"]);
                $c = self::buildCommissionFees($r);
                $commission_fees = $fee_buy * $c[0]/100;
                $b->addMoney($commission_fees, $walletId, $e["user_id"]);
                self::createCommission($e["user_id"], $walletId, $commission_fees, $buyer_id);
            }
        }
        if ($fee_sell > 0) {
            if ($e = self::getReferral($seller_id)) {
                $r = self::countReferredUser($e["user_id"]);
                $c = self::buildCommissionFees($r);
                $commission_fees = $fee_sell * $c[0]/100;
                $b->addMoney($commission_fees, $walletId, $e["user_id"]);
                self::createCommission($e["user_id"], $walletId, $commission_fees, $seller_id);
            }
        }
    }


    /*
    **$amount_fee is fee of trade
    ** $wallet_id wallet of amount_fee (BTC or LTC)
    */
    public function addPointsTrade($user_id, $amount_fee, $trade_id, $wallet_init)
    {
        $logFile = 'points.log';
        //Log::useDailyFiles(storage_path().'/logs/points/'.$logFile);
        $setting= new Setting();
        $balance=new Balance();
        $wallet=wallet::where('type', 'CTP')->first();
        $user=User::find($user_id);
        //Log::info("\n".'------------------------- Add Point Trade -----------------------------');
        //Log::info("\n".'amount_fee '.$amount_fee.' . trade_id: '.$trade_id." -- wallet_init: ".$wallet_init);
        if (isset($wallet->id)) {
            $point_per_btc=$setting->getSetting('point_per_btc', 1);
            $percent_point_reward_trade=$setting->getSetting('percent_point_reward_trade', 0);
            $percent_point_reward_referred_trade=$setting->getSetting('percent_point_reward_referred_trade', 0);
            //Log::info("\n".'Setting -- point_per_btc: '.$point_per_btc.' . percent_point_reward_trade: '.$percent_point_reward_trade." % -- percent_point_reward_referred_trade: ".$percent_point_reward_referred_trade." %");
            //cong point cho $user_id
            if ($percent_point_reward_trade>0) {
                $amount_reward=($amount_fee*$percent_point_reward_trade)/100;
                $point_reward=$amount_reward/$point_per_btc;
                
                    //Log::info("\n".'Add point for '.$user->username.' . amount_reward: '.$amount_reward." BTC -- point_reward: ".$point_reward." POINTS");
                if ($point_reward > 0) {
                    $balance->addMoney($point_reward, $wallet->id, $user->id);
                    $deposit=new Deposit();
                    $deposit->user_id=$user->id;
                    $deposit->wallet_id=$wallet->id;
                    $deposit->amount=$point_reward;
                    $deposit->paid=1;
                    $deposit->transaction_id= "Points earned from trade ".$trade_id;
                    $deposit->save();
                }
            }
            //cong point cho nguoi da gioi thieu $user_id nay neu co
            if (!empty($user->referral) && $percent_point_reward_referred_trade>0) {
                $user_referred=User::where('username', $user->referral)->first();
                $amount_reward=($amount_fee*$percent_point_reward_referred_trade)/100;
                $point_reward=$amount_reward/$point_per_btc;
                //Log::info("\n".'Add point for user referred: '.$user_referred->username.' . amount_reward: '.$amount_reward." BTC -- point_reward: ".$point_reward." POINTS");
                if ($point_reward > 0) {
                    $balance->addMoney($point_reward, $wallet->id, $user_referred->id);

                    $deposit=new Deposit();
                    $deposit->user_id=$user_referred->id;
                    $deposit->wallet_id=$wallet->id;
                    $deposit->amount=$point_reward;
                    $deposit->paid=1;
                    $deposit->transaction_id= "Points earned from User ".$user->username. "( Trade: ".$trade_id.")";
                    $deposit->save();
                }
            }
        } else {
            //Log::info("\n".'No wallet POINTS');
        }
    }
}
