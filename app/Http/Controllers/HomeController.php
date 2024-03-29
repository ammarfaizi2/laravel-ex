<?php

namespace App\Http\Controllers;

use DB;
use Config;
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
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */


    public function index($market_id = 0)
    {
        $market_id = $market_id;
        if (! is_numeric($market_id)) {
            $success = 0;
            $a = explode('_', $market_id, 2);
            if (count($a) === 2) {
                $b = DB::table('wallets')
                    ->select(['market.id', 'market.wallet_to'])
                    ->join('market', 'market.wallet_from', '=', 'wallets.id')
                    ->where('wallets.type', '=', strtoupper($a[0]))
                    ->get();
                if ($b) {
                    foreach ($b as $b) {
                        $c = DB::table('wallets')
                        ->select(['type'])
                        ->where('id', '=', $b->wallet_to)
                        ->first();
                        if (isset($c->type) && strtoupper($a[1]) === $c->type) {
                            $market_id = $b->id;
                            $success = 1;
                            break;
                        }
                    }
                    if (! $success) {
                        abort(404);
                    }
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }

        $data['show_all_markets'] = ($market_id == 0) ? true : false;



        //Use of Markets page and for CSS classes
        $market_predefined = false;	


        $setting = new Setting;
        $trade = new Trade();
        $wallet = new Wallet();

		$wallets_temp = Wallet::get();
		$wallets = array();

        foreach ($wallets_temp as $wallet) {
            $wallets[$wallet->id] = $wallet;
        }
        $data['wallets'] = $wallets;
        if ($market_id == 0) {
            $market_id = $setting->getSetting('default_market', $market_id);
        }
        
        
        Session::put('market_id', $market_id);
        $market_default = Market::find($market_id);
        if (preg_match("/\/market\/\d+/", url()->current()) && ! $market_default) {
            abort(404);
        }
        if (!is_null($market_default)) {
            $market_predefined = true;
        } else {
            $market_predefined = false;
        }

        if (!$market_default) {
            //return view('404',$data);
            //exit();
        }


        //Get wallet1 and wallet2 only if market is predefined
            //is start-market predefined in backend?
            $data['market_predefined']= $market_predefined;

        if (!is_null($market_default)) {
            $wallet_from = isset($market_default->wallet_from) ? $market_default->wallet_from : '';
            $wallet_to = isset($market_default->wallet_to) ? $market_default->wallet_to : '';
            $from = strtoupper($wallet->getType($wallet_from));
            $to = strtoupper($wallet->getType($wallet_to));

            //get name of wallet		//$wallet1=Wallet::where('id',$wallet_from)->first();
            //get name of wallet and additional information
            $wallet1 = Wallet::leftJoin('pools', 'pools.coin_id', '=', 'wallets.id')
            ->select('*')->where('wallets.id', $wallet_from)->first();
            //->select('pools.url, pools.forum, pools.blockviewer, wallets.*')->where('wallets.id',$wallet_from)->first();

            //exit (var_dump($wallet1));
            $data['url']= $wallet1->url;
            $data['blockviewer']= $wallet1->blockviewer;
            $data['forum']= $wallet1->forum;
            
            $data['enable_trading']= $wallet1->enable_trading;
            
            //$_markets = Market::leftJoin('pools', 'pools.coin_id', '=', 'wallet.id')->select('*')->get();
            $wallet2=Wallet::where('id', $wallet_to)->first();
            $data['market_from']= isset($wallet1->name) ? $wallet1->name : '';
            $data['market_to']= isset($wallet2->name) ? $wallet2->name : '';



            
            $data['coinmain'] = $from;
            $data['coinsecond'] = $to;
            $data['coinmain_logo'] = $wallets[$wallet_from]->logo_coin;
            $data['coinsecond_logo'] = $wallets[$wallet_to]->logo_coin;

            //get balance
            $balance = new Balance();
            $data['balance_coinmain'] = sprintf('%.8f', $balance->getBalance($wallet_from, 0));
            $data['balance_coinsecond'] = sprintf('%.8f', $balance->getBalance($wallet_to, 0));

            //get Sell Lowest
            $data['sell_lowest'] = sprintf('%.8f', 0);
            $data['buy_highest'] = sprintf('%.8f', 0);
            $order = new Order();
                    
            $sell_lowest = $order->getSellLowest($market_id);
            $buy_highest = $order->getBuyHighest($market_id);
            if (isset($sell_lowest->price)) {
                $data['sell_lowest'] = sprintf('%.8f', $sell_lowest->price);
            }
            if (isset($buy_highest->price)) {
                $data['buy_highest'] = sprintf('%.8f', $buy_highest->price);
            }

            //fee_buy, fee_sell
            $fee_trade = new FeeTrade();
            $fee = $fee_trade->getFeeTrade($market_id);
            $data['fee_buy'] = $fee['fee_buy'];
            $data['fee_sell'] = $fee['fee_sell'];

            //get list(buy/sell) orders
            $num_transaction_display = $setting->getSetting('num_transaction_display', 25);
            
            $list_sell_orders = $order->getOrders($market_id, 'sell', $num_transaction_display);
            $list_buy_orders = $order->getOrders($market_id, 'buy', $num_transaction_display);
            $data['sell_orders'] = $list_sell_orders;
            $data['buy_orders'] = $list_buy_orders;
            //echo "<pre>list_buy_orders: "; print_r($list_buy_orders); echo "</pre>";

            $data['market_id']=$market_id;

            $trade_history =Trade::where('market_id', '=', $market_id)->orderBy('id', 'desc')->take($num_transaction_display)->get();
            $data['trade_history'] = $trade_history;


            $current_orders_user = $order->getCurrentOrdersUser($market_id);
            if ($current_orders_user) {
                $data['current_orders_user'] = $current_orders_user;
            }

            
            $datachart = $trade->getDatasChart($market_id, '6 hour');
            
            $news = Post::where('type', 'news')->orderby('created_at', 'desc')->get();
            $data['news'] = $news;

            //price and market change %
            $data_price = $trade->getBlockPrice($market_id);
            $data["get_prices"] = isset($data_price['get_prices']) ? $data_price['get_prices'] : 0;
            $data['latest_price'] = isset($data_price['latest_price']) ? $data_price['latest_price'] : 0;

			$data['market_change'] = $trade->calcMarketChange($data['get_prices']->opening_price, $data['latest_price']);
			
            //limit trade amount
            $limit_trade = WalletLimitTrade::where('wallet_id', $wallet_from)->first();
            //echo "<pre>limit_trade: "; print_r($limit_trade ); echo "</pre>"; exit;
            if ($limit_trade) {
                $data['limit_trade']=$limit_trade->toArray();
            } else {
                $data['limit_trade']=array('min_amount'=>0.0001,'max_amount'=>1000);
            }
        }
        
        //FetchAll markets Data, Home
        $_markets = Market::get();
        //$_markets = Market::leftJoin('pools', 'pools.coin_id', '=', 'market.id')->select('*')->get();
        
        
        $all_markets = array();
		foreach($_markets as $m) {
			
			$market_prices = $trade->getBlockPrice($m->id);
			//$market_change = $trade->getChange($m->id);
			$market_current_price = (empty($market_prices['latest_price'])) ? sprintf('%.8f',0) : sprintf('%.8f',$market_prices['latest_price']);
			
			$market_change = $trade->calcMarketChange($market_prices['get_prices']->opening_price, $market_current_price);
			
			
			
			$all_markets[] = array(
				'latest_price' => $market_current_price,
				'from' => $wallets[$m->wallet_from]->type,
				'to' => $wallets[$m->wallet_to]->type,
				'from_name' => $wallets[$m->wallet_from]->name,
				'to_name' => $wallets[$m->wallet_to]->name,
				'logo' => $wallets[$m->wallet_from]->logo_coin,
				'market' => $m,
				'prices' => $market_prices['get_prices'],
				'volume' => floatval($market_prices['get_prices']->volume),
				'market_change' => $market_change,
				'enable_trading' => $wallets[$m->wallet_from]->enable_trading
				

			);
		}
		
		/*
        foreach ($_markets as $m) {
            $market_prices = $trade->getBlockPrice($m->id);
            //$market_change = $trade->getChange($m->id);
            $market_current_price = (empty($market_prices['latest_price'])) ? sprintf('%.8f', 0) : sprintf('%.8f', $market_prices['latest_price']);
            
            
            //echo $market_prices->min;
            //echo $market_prices['latest_price'];
            //echo '<pre>';
            //var_dump($market_prices);
            //echo '</pre>';
            

            $market_prices_opening_price = (!isset($market_prices['get_prices']->opening_price) ? 0 : $market_prices['get_prices']->opening_price );
            $market_prices_volume = (!isset($market_prices['get_prices']->volume) ? 0 : $market_prices['get_prices']->volume );
            
            $market_change = $trade->calcMarketChange($market_prices_opening_price, $market_current_price);
            
            
            if (isset($wallets[$m->wallet_from]->type)) {
                $all_markets[] = array(
                    'latest_price' => $market_current_price,
                    'from' => $wallets[$m->wallet_from]->type,
                    'to' => $wallets[$m->wallet_to]->type,
                    'from_name' => $wallets[$m->wallet_from]->name,
                    'to_name' => $wallets[$m->wallet_to]->name,
                    'logo' => $wallets[$m->wallet_from]->logo_coin,
                    'market' => $m,
                    'prices' => $market_prices,
                    //'prices' => $market_prices['get_prices'],
                    //'prices' => $market_prices_opening_price,
                    'volume' => floatval($market_prices_volume),
                    'market_change' => $market_change,
                    'enable_trading' => $wallets[$m->wallet_from]->enable_trading
                    
                   // ,
                //'pool-url' => $wallets[$m->wallet_from]->url,
                //'blockviewer' => $wallets[$m->wallet_from]->blockviewer,
                //'forum' => $wallets[$m->wallet_from]->forum,
                
                );
            }
        }
        */            
        //var_dump($all_markets);
        //exit;
        
        usort(
            $all_markets, function ($a, $b) {
                // sort market by volume
                if ($a['volume'] == $b['volume']) {
                    return 0;
                }
                return ($a['volume'] > $b['volume']) ? -1 : 1;
            }
        );
        //print_r($all_markets);
        $data['all_markets'] = $all_markets;

		
        //coin news
        $data['news'] = false;
        if ($data['show_all_markets'] === false) {
            $data['news'] = News::where('market_id', intval($market_id))->orderBy('created_at', 'desc')->get();
        }
        
        //site news
        $data['main_news'] = false;
        if ($data['show_all_markets'] === true) {
            //$data['main_news'] = News::where('market_id',intval($market_id))->orderBy('created_at','desc')->first();
        }
        $data['that'] = $this;
        $data['market_id'] = $market_id;
		
		//print_r($data);
        return view('index', $data);
    }

    public static function buildMarketUrl($marketId, $type = 1)
    {
        $a = DB::table('market')
            ->select(['wallets.type', 'market.wallet_to'])
            ->join('wallets', 'wallets.id', '=', 'market.wallet_from')
            ->where('market.id', '=', $marketId)
            ->first();
        if ($type === 1) {
            return isset($a->type) ? $a->type : ''; 
        } elseif ($type === 2) {
            if (isset($a->wallet_to, $a->type)) {
                $b = DB::table('wallets')
                    ->select(['type'])
                    ->where('id', '=', $a->wallet_to)
                    ->first();
                return isset($b->type) ? $a->type.'_'.$b->type : '';
            }
        }
        return '';
    }

    public function hasCustomFields($marketId)
    {
        return DB::table("custom_fields")
                ->select(
                    [
                        "custom_fields.*"
                    ]
                )
                ->join("wallets", "wallets.id", "=", "custom_fields.coin_id")
                ->join("market", "market.wallet_from", "=", "wallets.id")
                ->where("market.id", "=", $marketId)
                ->where("custom_fields.deleted_at", "=", null)
                ->get();
    }

    public function hasFeaturedMarket()
    {
        $now = date('Y-m-d 00:00:00');
        //return DB::table('featured_market')->select(['featured_market.*','wallets.type','wallets.name'])->join('wallets', 'wallets.id', '=', 'featured_market.coin', 'inner')->where('featured_market.deleted_at', '=', null)->where('featured_market.start_date', '<=', $now)->where('featured_market.end_date', '>=', $now)->get();
        return DB::table('featured_market')->select(['featured_market.*','wallets.type','wallets.name','market.id as market_id'])->join('wallets', 'wallets.id', '=', 'featured_market.coin', 'inner')->join('market', 'market.wallet_from', '=', 'featured_market.coin', 'inner')->where('featured_market.deleted_at', '=', null)->where('featured_market.start_date', '<=', $now)->where('featured_market.end_date', '>=', $now)->get();
    }

    public function sendEmail()
    {
        $user = Confide::user();

        $message = Input::get('message');
        $email = ($user) ? $user->email : Input::get('email');
        $name = ($user) ? "{$user->username}" : "Unregistered User";

        $_message = "Name: $name<br />";
        $_message .= "Email Address: $email<br />";
        $_message .= "IP Address: {$_SERVER['REMOTE_ADDR']}<br />";
        $_message .= "Message: ". nl2br(strip_tags($message)) . "<br />";
        
        include_once base_path()."/phpmailer/class.phpmailer.php";
        $mail = new PHPMailer();
        
        $mail->AddAddress(Config::get('config_custom.company_support_mail'), Config::get('config_custom.company_name'));
        $mail->AddReplyTo($email, $name);
        $mail->SetFrom($email, $name);
        
        $mail->Subject =  Config::get('config_custom.company_support_mail_name')  . ": $name";
        $mail->MsgHTML($_message);
        $mail->AltBody = nl2br(strip_tags($_message));
        $result = $mail->Send();

        // Redirect with success message
        return Redirect::to('page/contact')->with('notice', 'Your message has been sent!');
    }

    public function submitCoin()
    {
        $user = Confide::user();

        $message = Input::get('comments');
        $email = ($user) ? $user->email : Input::get('email');
        $name = ($user) ? $user->username : "Unregistered User";

        $_message = "Name: $name<br />";
        $_message .= "Email Address: $email<br />";
        $_message .= "IP Address: {$_SERVER['REMOTE_ADDR']}<br />";
        
        $_message .= "Name of Coin: ". trim(strip_tags(Input::get('coin_name'))) . "<br />";
        $_message .= "3 digit Ticker: ". trim(strip_tags(Input::get('coin_ticker'))) . "<br />";
        $_message .= "Forum Thread: ". trim(strip_tags(Input::get('coin_thread'))) . "<br />";
        $_message .= "Are you the Developer?: ". trim(strip_tags(Input::get('coin_dev'))) . "<br />";

        $_message .= "Comments: ". nl2br(strip_tags(Input::get('comments'))) . "<br />";
        
        include_once base_path()."/phpmailer/class.phpmailer.php";
        $mail = new PHPMailer();
        
        $mail->AddAddress(Config::get('config_custom.company_support_mail'), Config::get('config_custom.company_name'));
        $mail->AddReplyTo($email, $name);
        $mail->SetFrom($email, $name);

        $is_dev = (strtolower(trim(strip_tags(Input::get('coin_dev')))) == 'yes') ? ' from dev' : '';
        $mail->Subject = trim(strip_tags(Input::get('coin_ticker'))) . " Coin Submission $is_dev";
        $mail->MsgHTML($_message);
        $mail->AltBody = nl2br(strip_tags($_message));
        $result = $mail->Send();

        // Redirect with success message
        return Redirect::to('page/submit-coin')->with('notice', 'Your message has been sent!');
    }
    
    public function routePage($page = '')
    {
        //echo "<pre>user: "; print_r(Confide::user() ); echo "</pre>";
        switch ($page) {
        case "fees":
            $market = new Market();
            $wallet = new Wallet();
            $fees_trade = FeeTrade::get()->toArray();
            //echo "<pre>list_buy_orders: "; print_r($list_buy_orders); echo "</pre>";
            $fees_withdraw = FeeWithdraw::leftJoin('wallets', 'fee_withdraw.wallet_id', '=', 'wallets.id')
                ->select('fee_withdraw.*', 'wallets.type', 'wallets.name')->get();
                
            foreach ($fees_trade as $key => $value) {
                $wallet_type=$market->getWalletType($value['market_id']);
                $fees_trade[$key]['wallet_from'] = $wallet_type['wallet_from'];
                $fees_trade[$key]['wallet_to'] = $wallet_type['wallet_to'];
            }
                
            $fee['fees_trade'] = $fees_trade;
            $fee['fees_withdraw'] = $fees_withdraw;
            return view('fees', $fee);
                break;
        case "voting":
            $setting=new Setting();
            $coinvotes = DB::table('coin_votes')
             ->get();
            try {
                $wallet = Wallet::where('type', 'BTC')->first();
                $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                //var_dump($coinvotes);
                $_coinvoites = array();
                foreach ($coinvotes as $key => $value) {
                    $num_vote = Vote::where('coinvote_id', '=', $value->id)->count();
                        
                    //echo "<pre>getreceivedbyaccount"; print_r($wallet->getReceivedByAddress($value->btc_address)); echo "</pre>";//$value->label_address
                    $btc_payment = $wallet->getReceivedByAddress($value->btc_address);//'12X9jVe4S8pAqJ7EoKN7B4YwMQpzfgArtX'
                    $amount_btc_per_vote=$setting->getSetting('amount_btc_per_vote', 0.0001);
                    $num_payment = floor($btc_payment/$amount_btc_per_vote);
                    //$num_payment = 0;
                    //echo "btc_payment: ".$btc_payment;
                    //echo "<br>num_payment: ".$num_payment;
                    $coinvotes[$key]->num_vote = $num_vote + $num_payment;
                    $_coinvoites[] = array(
                        'id' => $value->id,
                        'code' => $value->code,
                        'name' => $value->name,
                        'btc_address' => $value->btc_address,
                        'label_address' => $value->label_address,
                        'num_vote' => $num_vote + $num_payment,
                    );
                }
                $coinvotes = $coinvotes->toArray();
                usort(
                    $coinvotes, function ($a, $b) {
                        if ($a->num_vote == $b->num_vote) {
                            return 0;
                        } return ($a->num_vote > $b->num_vote) ? -1 : 1;
                    }
                );
            } catch (Exception $e) {
                //$data['error_message']= 'Caught exception: '.$e->getMessage()."\n";  //"Not connect to this
                die('Page is in maintenance mode');
            }

            //echo "<pre>coinvotes"; print_r($coinvotes); echo "</pre>";
            $data['coinvotes'] = $coinvotes;
            return view('voting', $data);
                break;
        case "about":
            return view('about');
                break;
        case "security":
            return view('security');
                break;
        case "terms":
            return view('terms');
                break;
        case "api":
            if (isset($_REQUEST['method'])) {
                $method = $_REQUEST['method'];
                $value = $this->api($method);
            } else {
                $setting=new Setting();
                $data['pusher_app_key']=$setting->getSetting('pusher_app_key', '');
                return view('api', $data);
            }
            print_r($value);
            // $result = $this->api_query("getmarkets");
            // echo "<pre>".print_r($result, true)."</pre>";
            break;
        case "apiprivate":
            $value = $this->apiprivate();
			echo 'API - Private not implemented yet!';
            break;
        case "contact":
            $user = Confide::user();
            $data = array();
            $setting = new Setting();

            $data['recaptcha_publickey'] = $setting->getSetting('recaptcha_publickey', '');
            $data['email'] = ($user) ? $user->email : Input::get('email');
            $data['name'] = ($user) ? $user->fullname : "Unregistered User";

            return view('contact', $data);
                break;
        case "submit-coin":
            /*
			$user = Confide::user();
            $data = array();
            $setting = new Setting();

            $data['recaptcha_publickey'] = $setting->getSetting('recaptcha_publickey', '');
            $data['email'] = ($user) ? $user->email : Input::get('email');
            $data['name'] = ($user) ? $user->fullname : "Unregistered User";
			*/
            $data = array();
            $setting = new Setting();

            $data['recaptcha_publickey'] = $setting->getSetting('recaptcha_publickey', '');
            $data['email'] = Input::get('email');
            $data['name'] = "Unregistered User";
			
            return view('submit-coin', $data);
                break;
        default:
            return view('index');
                break;
        }
    }
    public function maintenanceMode()
    {
        return view('maintenance');
    }
    public function getChart()
    {
        $market_id = $_POST['market_id'];	
        $timeSpan = $_POST['timeSpan'];
        $trade = new Trade();
        $datachart = $trade->getDatasChart($market_id, $timeSpan);
        echo $datachart;
        exit;
    }

    public function viewPost($permalink)
    {
        $post = Post::where('permalink', $permalink)->first();
        if (!isset($post->id)) {
            return Redirect::to('/')->with('error', Lang::get('messages.page_not_found'));
        }
        $data['post'] = $post;
        return view('post', $data);
    }

    public function doTest()
    {
        //BrainSocket::message('doTest', array('message'=>Wallet::get()->toArray()));
        echo json_encode(Wallet::get()->toArray());
        exit;
    }


    public function api($method = '')
    {
       
        $api_response = ApiController::api($method);
        /*
        $request = Request::create('api/items', 'GET', $params);
        return Route::dispatch($request)->getContent();
        */
        return $api_response;
    }
    function apiprivate()
    {
        //$va = $_REQUEST['method'];
        $val=array('success' => 'aaaaaa');
        return $val;
    }
    /*
    function api_query($method, array $req = array()) {
        // API settings
        $key = '22f9c4becc188e0aace65b0860d60efe21e36119'; // your API-key
        $secret = 'f0c1d1f0513c167a44d3bab6d18bac72abb0270f23c65d29ce19bba0348aa1db479876a0a968047e'; // your Secret-key
 
        $req['method'] = $method;
        $mt = explode(' ', microtime());
        $req['nonce'] = $mt[1];
       
        // generate the POST data string
        $post_data = http_build_query($req, '', '&');

        $sign = hash_hmac("sha512", $post_data, $secret);
 
        // generate the extra headers
        $headers = array(
                'Sign: '.$sign,
                'Key: '.$key,
        );
 
        // our curl handle (initialize if required)
        static $ch = null;
        if (is_null($ch)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptsy API PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
        }
        curl_setopt($ch, CURLOPT_URL, 'https://api.cryptsy.com/api');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
        // run the query
        $res = curl_exec($ch);

        if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
        $dec = json_decode($res, true);
        if (!$dec) throw new Exception(Lang::get('messages.api_invalid_data_recieved'));
        return $dec;
    }
    */

    public function getNews($market_id)
    {
        $w1 = DB::table("market")
            ->select(["wallet_from", "wallet_to"])
            ->where("id", "=", $market_id)
            ->first();
        $w2 = $w1->wallet_from;
        $w1 = $w1->wallet_to;
        return DB::table("market_news")
            ->select(["title", "content"])
            ->where("wallet_id", "=", $w1)
            ->orWhere("wallet_id", "=", $w2)
            ->get();
    }
}
