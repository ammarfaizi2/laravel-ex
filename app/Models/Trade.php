<?php

namespace App\Models;

use DB;
use Auth;
use ArrayObject;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Trade extends Eloquent
{
    protected $table = 'trade_history';
    public function addTradeHistory($trade_history)
    {
        if ($m = DB::table("market")->select("wallet_to")->where("id", "=", $trade_history['market_id'])->first()) {
            \App\Http\Controllers\PointsController::commissionCheck(
                $m->wallet_to, $trade_history['fee_buy'], $trade_history['fee_sell'], $trade_history['buyer_id'], $trade_history['seller_id']
            );
        }
        $this->order_id = $trade_history["order_id"];
        $this->seller_id = $trade_history['seller_id'];
        $this->buyer_id = $trade_history['buyer_id'];
        $this->amount = $trade_history['amount'];
        $this->price = $trade_history['price'];
        $this->market_id = $trade_history['market_id'];
        $this->type = $trade_history['type'];
        $this->fee_buy = $trade_history['fee_buy'];
        $this->fee_sell = $trade_history['fee_sell'];
        $this->save();

        if ($this->id) {
            include_once app_path().'/libraries/Pusher.php';
            $setting = new Setting();
            $pusher_app_id=$setting->getSetting('pusher_app_id', '');
            $pusher_app_key=$setting->getSetting('pusher_app_key', '');
            $pusher_app_secret=$setting->getSetting('pusher_app_secret', '');
            if ($pusher_app_id!='' && $pusher_app_key!='' && $pusher_app_secret!='') {
                $pusher = new Pusher($pusher_app_key, $pusher_app_secret, $pusher_app_id);

                $wallet=new Wallet();
                $market=Market::where('id', $this->market_id)->first();
                $from = strtoupper($wallet->getType($market->wallet_from));
                $to = strtoupper($wallet->getType($market->wallet_to));

                $message=array(
                'channel' => 'trade.'.$this->market_id,
                'trade'=>array(
                    'timestamp'=>strtotime($this->created_at),
                    'datetime'=>date("Y-m-d H:i:s T", strtotime($this->created_at)),
                    'marketid'=>$this->market_id,
                    'marketname'=>$from.'/'.$to,
                    'amount'=>sprintf("%.8f", $this->amount),
                    'price'=>sprintf("%.8f", $this->price),
                    'total'=>sprintf("%.8f", ($this->amount*$this->price)),
                    'type'=>$this->type
                    )
                );
                $pusher->trigger('trade.'.$this->market_id, 'message', $message);
            }
            
            if (!$setting->getSetting('disable_points', 0)) {
                //Cong point cho nguoi mua va nguoi da gioi thieu ho
				// Add point for buyers and sellers
                $points=new PointsController();
                if ($this->fee_buy > 0) {
                    $points->addPointsTrade($this->buyer_id, $this->fee_buy, $this->id, $market->wallet_to);
                }
                //Cong point cho nguoi ban va nguoi da gioi thieu ho
				// Add point to the seller and the buyer
                if ($this->fee_sell > 0) {
                    $points->addPointsTrade($this->seller_id, $this->fee_sell, $this->id, $market->wallet_to);
                }
            }
        }
    }

    /*
    ** get datas for map chart    
    */
    public function getDatasChart($market_id, $timeSpan = '1 day')
    {
        //$timeSpan='1 week';
        $setting = new Setting;
        //$setting->putSetting('price_open_start_chart',serialize(array('time'=>'02:30', 'open_previous'=>0,'close_previous'=> 0)));
        $time_frame = $setting->getSetting('time_frame_chart', 30);
        //if not reached to time frame and exist chart data from database, not update chart
        //$data_chart_bak = $setting->getSetting('datachart_market_'.$market_id,'');
        
        //echo var_dump($setting->getSetting('price_open_start_chart')); exit;
        $price_open_variable = $setting->getSetting('price_open_start_chart_'.$market_id, '');
        if (!empty($price_open_variable)) {
            $price_open_variable = unserialize($price_open_variable);
        }

        //$open_previous = isset($price_open_variable['open_previous']) ? $price_open_variable['open_previous']:0;
        //$close_previous = isset($price_open_variable['close_previous']) ? $price_open_variable['close_previous']:0;
        //$price_open_start_chart_time = isset($price_open_variable['time']) ? $price_open_variable['time']:0;
        //echo "<pre>price_open_variable: "; print_r($price_open_variable); echo "</pre>";  

        //else get new data for chart
        $get_date = $this->getStartTimeChart($time_frame, $timeSpan);
        $start_time = $get_date['start_time'];
        $start_date = $get_date['start_date'];

        $closeprice=$this->getPreviousDataChart($market_id, $start_date, $time_frame);
        //$open_previous = $prior_perior['open_price'];
        $close_previous = $closeprice;
        //echo "date: ".date("Y-m-d H:i:s")."<br>";
        //echo "<pre>get_date: "; print_r($get_date); echo "</pre>";  
        //echo "<pre>start_date: "; print_r($start_date); echo "</pre>";
        $trade_history = Trade::where('market_id', '=', $market_id)
            ->where('created_at', '>=', $start_date)
            ->orderBy('price', 'desc')
            ->get();
        $data = $trade_history->toArray();
        //echo "<pre>trade_history: "; print_r($data); echo "</pre>"; 
        $temp_time = 0;
        $temp = 0;
        $datas_chart = array();
        $new_date = $start_date;
        //$date_ = strtotime(date("Y-m-d")." ".date('H',strtotime($start_date)).":".date('i',strtotime($start_date)));
        $date_ = strtotime(date("Y-m-d H:i:s"));
        $end_date = date("Y-m-d H:i", $date_);
        $str = "\n"."new_date: ".$new_date."\n"."end_date: ".$end_date;
        //echo "new_date: ".$new_date."<br>";
        //echo "end_date: ".$end_date."<br>";
        //echo "str: ".$str;
        //echo "<pre>data 1: "; print_r($data); echo "</pre>";
        while (strtotime($new_date) <= strtotime($end_date)) {
            if ($temp == 0) {
                $temp_time = $start_time;
            }
            $add_minute = strtotime($temp_time . " +30 minute");
            $temp_time_new = strftime("%H:%M", $add_minute);

            $old_date = $new_date;
            $date_temp_time=date("Y-m-d H:i", strtotime($old_date));
            $str .= "\n".$date_temp_time;
            $new_date = date("Y-m-d H:i", strtotime($new_date." +30 minutes"));// condition for while
             //echo "<br>------------------------------------------";
             //echo "<br>temp_time: ".$temp_time;
            // echo "<br>Old date: ".$old_date;
            // echo "<br>new_date + 30minutes: ".$new_date;

            //lay du lieu chart trong khung gio hien tai, du lieu nay dc sap xep theo gia tu cao den thap
			// Get the chart data in the current time frame, this data is arranged in price from high to low
            $data_chart_this_time = array_filter(
                $data, function ($el) use ($old_date, $new_date) /*use ($temp_time, $temp_time_new)*/ {
                    $created_at_time = strtotime($el['created_at']);
                    return ( $created_at_time >= strtotime($old_date) && $created_at_time <= strtotime($new_date));
                }
            );
            //echo "<pre>filtered 1: "; print_r($data_chart_this_time); echo "</pre>";
            if (count($data_chart_this_time) > 0) {
                $data_chart_this_time = array_values($data_chart_this_time);
                
                //get high & low ($data_chart_this_time is sort with price desc)
                $high = isset($data_chart_this_time[0]['price']) ? $data_chart_this_time[0]['price']:0;
                $low = isset($data_chart_this_time[count($data_chart_this_time)-1]['price']) ? $data_chart_this_time[count($data_chart_this_time)-1]['price']:0;
                $volumn = array_sum(array_fetch($data_chart_this_time, 'amount'));

                //get close_price, open_price (sort array with created desc)
                $cmp = function ($a, $b) {
                    return $b['created_at'] > $a['created_at'];
                };
                usort($data_chart_this_time, $cmp);

                //echo "<pre>filtered eee: "; print_r($data_chart_this_time); echo "</pre>";
                $open_price = isset($data_chart_this_time[count($data_chart_this_time)-1]['price']) ? $data_chart_this_time[count($data_chart_this_time)-1]['price']:0;
                $close_price = isset($data_chart_this_time[0]['price']) ? $data_chart_this_time[0]['price']:0;

                if ($close_previous == 0) {
                    $close_previous = $close_price;
                }
                $ha_data = $this->getDataHACandlesticks(array('high'=>$high, 'low'=> $low, 'open' => $open_price, 'close' => $close_price), $close_previous);
                //add data to chart
                $datas_chart[] = array('date'=>$date_temp_time,'low'=>$ha_data['ha_low'],'open'=>$ha_data['ha_open'],'close'=>$ha_data['ha_close'],'high'=>$ha_data['ha_high'], 'exchange_volume'=>$volumn,'temp'=>'','close_previous'=>$close_previous);
            } else {
                $datas_chart[] = array('date'=>$date_temp_time,'low'=>$close_previous,'open'=>$close_previous,'close'=>$close_previous,'high'=>$close_previous, 'exchange_volume'=>0,'temp'=>'','close_previous'=>$close_previous);
            }
            $temp_time = $temp_time_new;
            $close_previous = isset($ha_data['ha_close']) ? $ha_data['ha_close'] : null;
            $temp++;
        }
        //echo $str;
        $datas_chart[] = array('date'=>date("Y-m-d H:i"),'low'=>$close_previous,'open'=>$close_previous,'close'=>$close_previous,'high'=>$close_previous, 'exchange_volume'=>0,'temp'=>$str);
        $result_data = json_encode($datas_chart);
        //$setting->putSetting('datachart_market_'.$market_id,serialize($datas_chart));
        return $result_data;
    }

    /*
    ** Calculation Heikin-Ashi Candlesticks    
        Heikin-Ashi Candlesticks are based on price data from the current open-high-low-close, the current Heikin-Ashi values and the prior Heikin-Ashi values. Yes, it is a bit complicated. In the formula below, a "(0)" denotes the current period. A "(-1)" denotes the prior period. "HA" refers to Heikin-Ashi. Let's take each data point one at a time.   
         
        1. The Heikin-Ashi Close is simply an average of the open, 
        high, low and close for the current period. 

        HA-Close = (Open(0) + High(0) + Low(0) + Close(0)) / 4

        2. The Heikin-Ashi Open is the average of the prior Heikin-Ashi 
        candlestick open plus the close of the prior Heikin-Ashi candlestick. 

        HA-Open = (HA-Open(-1) + HA-Close(-1)) / 2 

        3. The Heikin-Ashi High is the maximum of three data points: 
        the current period's high, the current Heikin-Ashi 
        candlestick open or the current Heikin-Ashi candlestick close. 

        HA-High = Maximum of the High(0), HA-Open(0) or HA-Close(0) 

        4. The Heikin-Ashi low is the minimum of three data points: 
        the current period's low, the current Heikin-Ashi 
        candlestick open or the current Heikin-Ashi candlestick close.

        HA-Low = Minimum of the Low(0), HA-Open(0) or HA-Close(0)
    */
    public function getDataHACandlesticks_bak($current, $open_previous, $close_previous)
    {
        $dataHACandlesticks = array();
        $dataHACandlesticks['ha_close'] = sprintf('%.8f', ($current['open'] + $current['high'] + $current['low'] +$current['close'])/4);
        $dataHACandlesticks['ha_open'] = sprintf('%.8f', ($open_previous + $close_previous)/2);
        $dataHACandlesticks['ha_high'] = sprintf('%.8f', max($current['high'], $dataHACandlesticks['ha_open'], $dataHACandlesticks['ha_close']));
        $dataHACandlesticks['ha_low'] = sprintf('%.8f', min($current['low'], $dataHACandlesticks['ha_open'], $dataHACandlesticks['ha_close']));
        return $dataHACandlesticks;
    }

    /*
    **open price = price of last trade (close price) of previous sale period (time interval, e.g. 1 hr)
     close price = price of last trade of in the current sale period (time interval, e.g. 1 hr)
     high price = highest price in the current sale period (time interval, e.g. 1 hr)
     lowest price = lowest price in the current sale period (time interval, e.g. 1 hr)
    if no trade close price = open price = highest price = lowest price
    */
    public function getDataHACandlesticks($current, $close_previous)
    {
        //echo "<pre>current: "; print_r($current); echo "</pre>";
        $dataHACandlesticks = array();
        $dataHACandlesticks['ha_open'] = sprintf('%.8f', $close_previous);
        $dataHACandlesticks['ha_close'] = ($current['close']>0)? sprintf('%.8f', $current['close']):$dataHACandlesticks['ha_open'];
        $dataHACandlesticks['ha_high'] = ($current['high']>0)? sprintf('%.8f', $current['high']):$dataHACandlesticks['ha_open'];
        $dataHACandlesticks['ha_low'] = ($current['low']>0)? sprintf('%.8f', $current['low']):$dataHACandlesticks['ha_open'];
        //echo "<pre>return dataHACandlesticks: "; print_r($dataHACandlesticks); echo "</pre>";
        return $dataHACandlesticks;
    }

    /*
    ** Get start time and start date of chart
    */
    public function getStartTimeChart($time_frame = 30, $timeSpan = '1 day')
    {
        if ($timeSpan=='MAX') {
            //exit('MAX - app/models/Trade.php');
            //echo ('MAX - app/models/Trade.php');
            
            
            $oldest_trade = Trade::orderBy('created_at', 'asc')->first();
            if (isset($oldest_trade->id)) {
                $previous_day = strtotime($oldest_trade->created_at);
            } else {
                $previous_day = strtotime(date('Y-m-d H:i:s'));
            }
            
            /*
            print_r($oldest_trade);
			
			
            if(isset($oldest_trade->id)) {
            $previous_day = strtotime($oldest_trade->created_at);
            echo '$previous_day1 '.$previous_day;
            }else {
            $previous_day = strtotime(date('Y-m-d H:i:s'));
            echo '$previous_day2 '.$previous_day;
            }
            exit('MAX - app/models/Trade.php');
            */
        } else {
            $previous_day = strtotime(date('Y-m-d H:i:s') . " -".$timeSpan);
        }
        // echo "<br>Cur: ".date("Y-m-d H:i:s");
        // echo "<br>timeSpan: ".$timeSpan;
        // echo "<pre>previous_day: "; print_r(date("Y-m-d H:i:s",$previous_day)); echo "</pre>";
        // $hour_minute = date('H:i',$previous_day);
        $hour = date("H", $previous_day);
        $minute = date("i", $previous_day);
        if ($minute < $time_frame) {
            $minute = 0;
        } else {
            $minute = $time_frame;
        }
        $hour_minute = mktime($hour, $minute, 0);
        $result['start_time'] = date("H:i", $hour_minute);
        
        $date = mktime($hour, $minute, 0, date('m', $previous_day), date('d', $previous_day), date('Y', $previous_day));
        $result['start_date'] = date("Y-m-d H:i:s", $date);
        
        return $result;
    }

    public function getPreviousDataChart($market_id, $date, $time_frame)
    {
        $close_price=0;
        $data=array();
        $new_date = date("Y-m-d H:i", strtotime($date));
         //echo "<br>------------------------------";
          //echo "<br>Start date: ".$new_date;
        $trade_history = Trade::where('market_id', '=', $market_id)->where('created_at', '<=', $date)->orderBy('created_at', 'desc')->first();
        //echo "<pre>data:"; print_r($data); echo "</pre>";
        if (isset($trade_history->price)) {
            $close_price=$trade_history->price;
        }
        return $close_price;
    }
        public function getBlockPrice($market_id){
            
        //price
        $previous_day = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . " -1 day")); 
        $latest = Trade::where('market_id',$market_id)->orderby('id','desc')->first();
        //icee $latest = Trade::where('market_id',$market_id)->orderby('created_at','desc')->first();
        

		/*
SELECT max( price ) AS 24h_high, price as previous_day_price, min( price ) AS 24h_low, sum( amount * price ) AS base_volume, sum(amount) as volume, created_at
FROM trade_history
WHERE market_id = '80'
AND created_at >= '2015-08-01 23:37:53'
		
/////////////
SELECT max( price ) AS 24h_high, price AS previous_day_price, min( price ) AS 24h_low, sum( amount * price ) AS base_volume, sum( amount ) AS volume, created_at, 
SUBSTRING_INDEX( GROUP_CONCAT( price ORDER BY created_at DESC ) , ',', 1 ) AS last_price
FROM trade_history
WHERE market_id = '80'
AND created_at >= '2015-08-01 23:37:53'		
/////////////		
		$price_high = $max;
		$price_low = $min;
		$price_opening = $opening_price;
		$base_volume = $volume;
		$coin_volume = $coin_volume;
		
		$change = ($previous_day_price!=0)? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100):0;
		*/
		// pure query
		// $select = "SELECT max(price) as max, min(price) as min, price as opening_price, sum(amount*price) as volume, sum(amount) as coin_volume, created_at from trade_history where market_id='".$market_id."' AND created_at>='".$previous_day."' ";
		//exit ($select);
        //echo "SQL: ".$select;
        // $get_price = DB::select($select);

        // prepared statement
        // var_dump("select max(price) as max, min(price) as min, price as opening_price, sum(amount*price) as volume, sum(amount) as coin_volume, `created_at` from `trade_history` where `market_id` = ? and `created_at` >= ?");

        // query builder
        $get_price = DB::table("trade_history")
                ->select([
                    DB::raw("max(price) as max"), 
                    DB::raw("min(price) as min"), 
                    DB::raw("price as opening_price"), 
                    DB::raw("sum(amount*price) as volume"), 
                    DB::raw("sum(amount) as coin_volume"), "created_at"
                ])
                ->where("market_id", "=", $market_id)
                ->where("created_at", ">=", $previous_day)
                ->get()->toArray();
        
        $data["get_prices"] = $get_price[0];
        //echo "<pre>"; print_r($get_price); echo "</pre>";
		//echo '<pre> '.print_r($latest).'</pre>';
		//echo '<hr />';
		//echo '<pre> '.print_r($get_price).'</pre>';
		
        $data['latest_price'] = isset($latest->price)? $latest->price:0;
        return $data;
    }
	
	public function getBlockPrice_bak($market_id)
    {
        //price
        $previous_day = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " -1 day"));
        $latest = Trade::where('market_id', $market_id)->orderby('id', 'desc')->first();
        //icee $latest = Trade::where('market_id',$market_id)->orderby('created_at','desc')->first();
        

        /*
        SELECT max( price ) AS 24h_high, price as previous_day_price, min( price ) AS 24h_low, sum( amount * price ) AS base_volume, sum(amount) as volume, created_at
        FROM trade_history
        WHERE market_id = '80'
        AND created_at >= '2015-08-01 23:37:53'
		
        /////////////
        SELECT max( price ) AS 24h_high, price AS previous_day_price, min( price ) AS 24h_low, sum( amount * price ) AS base_volume, sum( amount ) AS volume, created_at, 
        SUBSTRING_INDEX( GROUP_CONCAT( price ORDER BY created_at DESC ) , ',', 1 ) AS last_price
        FROM trade_history
        WHERE market_id = '80'
        AND created_at >= '2015-08-01 23:37:53'		
        /////////////		
        $price_high = $max;
        $price_low = $min;
        $price_opening = $opening_price;
        $base_volume = $volume;
        $coin_volume = $coin_volume;
		
        $change = ($previous_day_price!=0)? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100):0;
        */

        // pure query
        // $select = "SELECT max(price) as max, min(price) as min, price as opening_price, sum(amount*price) as volume, sum(amount) as coin_volume, created_at from trade_history where market_id='".$market_id."' AND created_at>='".$previous_day."' GROUP BY id";
        //exit ($select);
        //echo "SQL: ".$select;
        // $get_pricex = DB::select($select);
        // var_dump(strtolower($select));

        // prepared statement
        // var_dump("select max(price) as max, min(price) as min, price as opening_price, sum(amount*price) as volume, sum(amount) as coin_volume, `created_at` from `trade_history` where `market_id` = ? and `created_at` >= ? group by `id`");

        // query builder
        $get_price = DB::table("trade_history")
                    ->select([
                        DB::raw("max(price) as max"),
                        DB::raw("min(price) as min"),
                        DB::raw("price as opening_price"),
                        DB::raw("sum(amount*price) as volume"),
                        DB::raw("sum(amount) as coin_volume"),
                        "created_at"
                    ])->where("market_id", "=", $market_id)
                    ->where("created_at", ">=", $previous_day)
                    ->groupBy("id")
                    ->get()
                    ->toArray();

        /*
        icee- a lot of changes here recently, 20171213
        */


        
        $data = new ArrayObject();
        if (isset($get_price)) {
            if (empty($get_price)) {
                //$data['arr'] = 'array data';
                //https://secure.php.net/manual/en/class.arrayobject.php
                $data->min = 0;
                $data->max = 0;
                $data['latest_price'] = isset($latest->price)? $latest->price:0;
                $data["get_prices"] = 0; 
            }
        }

        /*
        echo "<pre>"; print_r($get_price); echo "</pre>";
		
        echo '<pre> '.print_r($latest).'</pre>';		
		
        echo '<hr />';
        echo '<pre> '.print_r($get_price).'</pre>';
        */

        return $data;
    }
    
	/*
	 @ Calculate the Market Change price when opening price and latest price is given.
	*/
    public function calcMarketChange($opening_price, $current_price)
    {
		/*
		change = (latest_price / opening price )
		if < 1
			change = (change -1)*100
		*/
		if ($opening_price!=0)
			$change = ( ($current_price / $opening_price) -1 ) * 100;
		else{
			$change = 0;
		}
		
        //$change = ($opening_price!=0)? sprintf('%.2f', (($current_price-$opening_price)/$opening_price)*100) : 0;
		
        $change = sprintf('%.8f', $change);
        return $change+0;
    }
    /*
    @ Check Previous date price
    @ arg: $trading date
    return previous date price
    */
    
    public function getChangeDayPrevPrice($created_at, $pre_price)
    {
        
        if (isset($created_at)) {
            /*
            echo strtotime($data_trade[1]['created_at']);
            echo '<br />';
            echo strtotime('yesterday');
            */
            
            //Check previous trade date and compare to the previous day
            if (strtotime($created_at) < strtotime('yesterday')) {
                $pre_price = 0;
            }
        }
        return $pre_price;

        //echo '<pre>';
        //print_r($data_trade);
        //echo '</pre>';
        
    }
    /*
    @ get the price change for the day
    */
    //public function getChangeDayPrice($pre_price, $curr_price, $pre_price){
    public function getChangeDayPrice($pre_price, $curr_price)
    {
        $change = ($pre_price!=0)? sprintf('%.2f', (($curr_price-$pre_price)/$pre_price)*100) : 0;
        return $change+0;
    }
        
    public function getChange($market_id)
    {
        //icee $data_trade = Trade::where('market_id',$market_id)->orderby('created_at','desc')->take(2)->get()->toArray();
        /*
        $previous_day = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . " -1 day")); 
        $data_trade = Trade::where('market_id',$market_id)->where('created_at','>=',$previous_day)->orderby('id','desc')->take(2)->get()->toArray();

        $curr_price = isset($data_trade[0]['price'])? $data_trade[0]['price']:0;
        $pre_price = isset($data_trade[1]['price'])? $data_trade[1]['price']:0;
        $change = ($pre_price!=0)? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100) :  0;
        */
        /*
        $data_trade = Trade::where('market_id',$market_id)->orderby('created_at','desc')->take(2)->get()->toArray();
        $curr_price = isset($data_trade[0]['price'])? $data_trade[0]['price']:0;
        $pre_price = isset($data_trade[1]['price'])? $data_trade[1]['price']:0;
        $change = 0;
		
        if(isset($data_trade[1]['created_at']))
        $pre_price = $this->getChangeDayPrevPrice($data_trade[1]['created_at'], $pre_price);
		
        $change = $this->getChangeDayPrice($pre_price, $curr_price, $pre_price);
        */
        
        $previous_day = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " -1 day"));
        $data_trade = Trade::where('market_id', $market_id)->where('created_at', '>=', $previous_day)->orderby('id', 'desc')->take(2)->get()->toArray();

        $curr_price = isset($data_trade[0]['price'])? $data_trade[0]['price']:0;
        $pre_price = isset($data_trade[1]['price'])? $data_trade[1]['price']:0;
        $change = ($pre_price!=0)? sprintf('%.2f', (($curr_price-$pre_price)/$pre_price)*100) :  0;

        // $select="SELECT SUM( amount * price ) AS total FROM trade_history WHERE `market_id`='".$market_id."' GROUP BY market_id";
        // $total_btc = DB::select($select);

        // prepared statement
        // var_dump("select SUM( amount * price ) as total from `trade_history` where `market_id` = ? group by `market_id`");

        // query builder
        $total_btc = DB::table("trade_history")
                    ->select(
                        [
                            DB::raw("SUM( amount * price ) as total")
                        ]
                    )->where("market_id", "=", $market_id)
                    ->groupBy("market_id")
                    ->get();
        

        if (isset($total_btc[0])) {
            $total_volume = $total_btc[0]->total;
        } else {
            $total_volume = 0;
        }
        
        /*
        if ($market_id == 69){
        echo "<pre>"; print_r($data_trade); echo "</pre>";
        exit;
        }
        */
        return array('curr_price'=>$curr_price,'pre_price'=>$pre_price,'change'=>$change,'total_volume'=>$total_volume);
    }
    
    public function icee_getMarketChange($market_id)
    {
        //price
        $previous_day = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " -1 day"));
        

        /*
        SELECT max( price ) AS 24h_high, price as previous_day_price, min( price ) AS 24h_low, sum( amount * price ) AS base_volume, sum(amount) as volume, created_at
        FROM trade_history
        WHERE market_id = '80'
        AND created_at >= '2015-08-01 23:37:53'
		
        /////////////
        SELECT max( price ) AS 24h_high, price AS previous_day_price, min( price ) AS 24h_low, sum( amount * price ) AS base_volume, sum( amount ) AS volume, created_at, 
        SUBSTRING_INDEX( GROUP_CONCAT( price ORDER BY created_at DESC ) , ',', 1 ) AS last_price
        FROM trade_history
        WHERE market_id = '80'
        AND created_at >= '2015-08-01 23:37:53'		
        /////////////		
        $price_high = $max;
        $price_low = $min;
        $price_opening = $opening_price;
        $base_volume = $volume;
        $coin_volume = $coin_volume;
		
        $change = ($previous_day_price!=0)? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100):0;
        */
        

  //       $select = "
		// SELECT 
		// 	max(price) AS price_high, min(price) AS price_low, price as opening_price, sum(amount*price) as base_bavolume, sum(amount) as coin_volume, created_at 
		// 	SUBSTRING_INDEX( GROUP_CONCAT( price ORDER BY created_at DESC ) , ',', 1 ) AS last_price
		// FROM 
		// 	trade_history 
		// WHERE 
		// 	market_id='".$market_id."' 
		// 	AND 
		// 	created_at>='".$previous_day."' ";
  //       //exit ($select);
  //       //echo "SQL: ".$select;
        

        
  //       $get_price = DB::select($select);
  //       $data["get_prices"] = $get_price[0];
  //       echo '<hr />';
  //       echo '<pre> '.print_r($get_price).'</pre>';
  //       echo '<hr />';
        exit;
        
        return $data;
    }


    public function getcandles($market_id, $time_frame, $timeSpan = '1 day')
    {
        $timeSpan = trim($timeSpan);
        if (empty($timeSpan)) {
            $timeSpan = "1 day";
        }

        //$timeSpan='1 week';
        $setting = new Setting;

        if (preg_match("/^\d{1,3}\s?h(ours?)?/Usi", $time_frame, $m)) {
            $time_frame = ((int) $m[0]) * 60;
        } elseif (preg_match("/^\d{1,3}\s?m(inutes?)?/Usi", $time_frame, $m)) {
            $time_frame = (int) $m[0];
        } elseif (preg_match("/^\d{1,3}\s?d(ays?)?/Usi", $time_frame, $m)) {
            $time_frame = ((int) $m[0]) * 60 * 24;
        } elseif (preg_match("/^\d{1,3}\s?w(eeks?)?/Usi", $time_frame, $m)) {
            $time_frame = ((int) $m[0]) * 60 * 24 * 7;
        } else {
            $time_frame = (int) $time_frame;
        }

        
        if (preg_match("/^\d{1,3}\s?h(ours?)?/Usi", $timeSpan, $m)) {
            $timeSpan = ((int) $timeSpan)." hours";
        } elseif (preg_match("/^\d{1,3}\s?m(inutes?)?/Usi", $timeSpan, $m)) {
            $timeSpan = ((int) $timeSpan)." minutes";
        } elseif (preg_match("/^\d{1,3}\s?d(ays?)?/Usi", $timeSpan, $m)) {
            $timeSpan = ((int) $timeSpan)." days";
        } elseif (preg_match("/^\d{1,3}\s?w(eeks?)?/Usi", $timeSpan, $m)) {
            $timeSpan = ((int) $timeSpan)." weeks";
        } else {
            $timeSpan = ((int) $timeSpan)." hours";
        }

        
        //$setting->putSetting('price_open_start_chart',serialize(array('time'=>'02:30', 'open_previous'=>0,'close_previous'=> 0)));
        //if not reached to time frame and exist chart data from database, not update chart
        //$data_chart_bak = $setting->getSetting('datachart_market_'.$market_id,'');
        
        //echo var_dump($setting->getSetting('price_open_start_chart')); exit;
        $price_open_variable = $setting->getSetting('price_open_start_chart_'.$market_id, '');
        if (!empty($price_open_variable)) {
            $price_open_variable = unserialize($price_open_variable);
        }

        //$open_previous = isset($price_open_variable['open_previous']) ? $price_open_variable['open_previous']:0;
        //$close_previous = isset($price_open_variable['close_previous']) ? $price_open_variable['close_previous']:0;
        //$price_open_start_chart_time = isset($price_open_variable['time']) ? $price_open_variable['time']:0;
        //echo "<pre>price_open_variable: "; print_r($price_open_variable); echo "</pre>";  

        //else get new data for chart
        $get_date = $this->getStartTimeChart($time_frame, $timeSpan);
        $start_time = $get_date['start_time'];
        $start_date = $get_date['start_date'];

        $closeprice=$this->getPreviousDataChart($market_id, $start_date, $time_frame);
        //$open_previous = $prior_perior['open_price'];
        $close_previous = $closeprice;
        //echo "date: ".date("Y-m-d H:i:s")."<br>";
        //echo "<pre>get_date: "; print_r($get_date); echo "</pre>";  
        //echo "<pre>start_date: "; print_r($start_date); echo "</pre>";
        $trade_history = Trade::where('market_id', '=', $market_id)
            ->where('created_at', '>=', $start_date)
            ->orderBy('price', 'desc')
            ->get();
            
        $data = $trade_history->toArray();
        //echo "<pre>trade_history: "; print_r($data); echo "</pre>"; 
        $temp_time = 0;
        $temp = 0;
        $datas_chart = array();
        $new_date = $start_date;
        //$date_ = strtotime(date("Y-m-d")." ".date('H',strtotime($start_date)).":".date('i',strtotime($start_date)));
        $date_ = strtotime(date("Y-m-d H:i:s"));
        // header("Content-Type: text/html");
        // dd(date("Y-m-d H:i:s"));
        $end_date = date("Y-m-d H:i", $date_);
        $str = "\n"."new_date: ".$new_date."\n"."end_date: ".$end_date;
        //echo "new_date: ".$new_date."<br>";
        //echo "end_date: ".$end_date."<br>";
        //echo "str: ".$str;
        //echo "<pre>data 1: "; print_r($data); echo "</pre>";
        // var_dump($end_date);die;
        header("Content-Type: text/plain");
        while (strtotime($new_date) <= strtotime($end_date)) {
            if ($temp == 0) {
                $temp_time = $start_time;
            }
            $add_minute = strtotime($temp_time . " +".$time_frame." minutes");
            $temp_time_new = strftime("%H:%M", $add_minute);

            $old_date = $new_date;
            $date_temp_time=date("Y-m-d H:i", strtotime($old_date));
            $str .= "\n".$date_temp_time;
            $new_date = date("Y-m-d H:i", strtotime($new_date." +".$time_frame." minutes"));// condition for while
             //echo "<br>------------------------------------------";
             //echo "<br>temp_time: ".$temp_time;
            // echo "<br>Old date: ".$old_date;
            // echo "<br>new_date + 30minutes: ".$new_date;

            //lay du lieu chart trong khung gio hien tai, du lieu nay dc sap xep theo gia tu cao den thap
            // Get the chart data in the current time frame, this data is arranged in price from high to low
            $data_chart_this_time = array_filter(
                $data, function ($el) use ($old_date, $new_date) /*use ($temp_time, $temp_time_new)*/ {
                    $created_at_time = strtotime($el['created_at']);
                    return ( $created_at_time >= strtotime($old_date) && $created_at_time <= strtotime($new_date));
                }
            );
            //echo "<pre>filtered 1: "; print_r($data_chart_this_time); echo "</pre>";
            if (count($data_chart_this_time) > 0) {
                $data_chart_this_time = array_values($data_chart_this_time);
                
                //get high & low ($data_chart_this_time is sort with price desc)
                $high = isset($data_chart_this_time[0]['price']) ? $data_chart_this_time[0]['price']:0;
                $low = isset($data_chart_this_time[count($data_chart_this_time)-1]['price']) ? $data_chart_this_time[count($data_chart_this_time)-1]['price']:0;
                $volumn = array_sum(array_fetch($data_chart_this_time, 'amount'));
                $basevol = 0;
                foreach($data_chart_this_time as $q)  {
                    $basevol += $q["amount"] * $q["price"];
                }
                //get close_price, open_price (sort array with created desc)
                $cmp = function ($a, $b) {
                    return $b['created_at'] > $a['created_at'];
                };
                usort($data_chart_this_time, $cmp);

                //echo "<pre>filtered eee: "; print_r($data_chart_this_time); echo "</pre>";
                $open_price = isset($data_chart_this_time[count($data_chart_this_time)-1]['price']) ? $data_chart_this_time[count($data_chart_this_time)-1]['price']:0;
                $close_price = isset($data_chart_this_time[0]['price']) ? $data_chart_this_time[0]['price']:0;

                if ($close_previous == 0) {
                    $close_previous = $close_price;
                }
                $ha_data = $this->getDataHACandlesticks(array('high'=>$high, 'low'=> $low, 'open' => $open_price, 'close' => $close_price), $close_previous);
                //add data to chart
                $datas_chart[] = array('date'=>$date_temp_time,'low'=>number_format($ha_data['ha_low'],8),'open'=>number_format($ha_data['ha_open'],8),'close'=>number_format($ha_data['ha_close'],8),'high'=>number_format($ha_data['ha_high'],8), 'exchange_volume'=>number_format($volumn,8),'base_volume' => number_format($basevol, 8), 'temp'=>'','close_previous'=>number_format($close_previous,8));
            } else {
                $datas_chart[] = array('date'=>$date_temp_time,'low'=>number_format($close_previous, 8),'open'=>number_format($close_previous, 8),'close'=>number_format($close_previous,8),'high'=>number_format($close_previous,8), 'exchange_volume'=>number_format(0,8),'base_volume' => number_format(0,8), 'temp'=>'','close_previous'=>number_format($close_previous, 8));
            }
            $temp_time = $temp_time_new;
            $close_previous = isset($ha_data['ha_close']) ? $ha_data['ha_close'] : null;
            $temp++;
        }
        //echo $str;
        // $datas_chart[] = array('date'=>date("Y-m-d H:i"),'low'=>$close_previous,'open'=>$close_previous,'close'=>$close_previous,'high'=>$close_previous, 'exchange_volume'=>0,'temp'=>$str);
        $result_data = json_encode($datas_chart, 128);
        //$setting->putSetting('datachart_market_'.$market_id,serialize($datas_chart));
        return $result_data;
    }
}
