<?php 
/*
<!-- https://www.freeformatter.com/html-formatter.html#ad-output -->
<!-- https://dirtymarkup.com/ -->
*/?>
<!-- >#START MARKETS TABLE-->
<div class="row">
	<div class="contentinner">
			
			<div class="contentheader" >
				<div class="col-12-xs col-sm-12 col-lg-12 ">
				@if ( Auth::guest() )
				<h1 style="margin-top:5px;">{{{ Config::get('config_custom.company_name') }}}</h1>

				<h2>The Fast, Secure and Reliable Crypto Exchange with Low Fees!</h2>
				<div style="background-color: rgba(0,0,0,0.25); padding: 10px; border-radius: 4px;">
					<a class="button button-green btn btn-lg" href="{{{ URL::to('/user/register') }}}">
					  <i class="fa fa-user-plus"></i>
					  <span> {{trans('user_texts.register')}}</span>
					</a>
					<a class="button button-blue btn btn-lg" href="{{{ URL::to('/login') }}}">
					  <i class="fa fa-sign-in-alt "></i>
					  <span> {{trans('user_texts.login')}}</span>
					</a>
				</div>
				@endif
				</div>
			</div>

			<div class="row">
				<hr class="colorgraph" />
			</div>
			
			<!-- >#Markets Statistics Data -->
			<div class="contentmarketdata">
				
							<?php
							$number_btc = isset($statistic_btc->number_trade)? $statistic_btc->number_trade:0;
							$volume_btc = (isset($statistic_btc->total) && !empty($statistic_btc->total))? sprintf('%.8f',$statistic_btc->total):0;
							$number_ltc = isset($statistic_ltc->number_trade)? sprintf('%.8f',$statistic_ltc->number_trade):0;
							$volume_ltc = (isset($statistic_ltc->total) && !empty($statistic_ltc->total))? sprintf('%.8f',$statistic_ltc->total):0;
							?>
							
						<div class=" col-12-xs col-sm-12 col-lg-12 ">
							<div class="col-xs-6 col-sm-3">
								BTC Vol: {{$volume_btc}}
							</div>
							<div class="col-xs-6 col-sm-3">
								LTC Vol: {{$volume_ltc}}
							</div>
							<div class="col-xs-6 col-sm-3">
								# Trades: {{($number_btc+$number_ltc)}}
							</div>
							<div class="col-xs-6 col-sm-3">
								# Online: <span class="client_count"></span>
							</div>
						</div>
			</div>
			<!-- <#Markets Statistics Data -->
			

			
			<?php
				// Get Choosen MARKETS
				// BEX, Top Volume, Biggest Gain, Biggest Loss
				
				$all_markets_top = array();

				//Get market with BEX
				foreach ($all_markets as $am)
				{
					if ($am['to'] == 'BTC'){
						if($am['from'] == 'BEX'){
							$all_markets_top[3] = $am;
							$all_markets_top[3]['market_type'] = 'bex';
						}
					}
				}
				
				//Get market with Top Volume
				usort($all_markets, function ($item1, $item2) {
					if ($item1['to'] == 'BTC'){
						return $item2['volume'] <=> $item1['volume'];
					}
				});
				$all_markets_top[0] = $all_markets[0];
				$all_markets_top[0]['market_type'] = 'volume';
				
				
				//Get market with Biggest Gain
				usort($all_markets, function ($item1, $item2) {
					if ($item1['to'] == 'BTC'){
						return $item2['market_change'] <=> $item1['market_change'];
					}
				});
				$all_markets_top[1] = $all_markets[0];
				$all_markets_top[1]['market_type'] = 'gain';
				
				//Get market with Biggest Loss
				usort($all_markets, function ($item1, $item2) {
					if ($item1['to'] == 'BTC'){
						return $item1['market_change'] <=> $item2['market_change'];
					}
				});
				$all_markets_top[2] = $all_markets[0];
				$all_markets_top[2]['market_type'] = 'loss';
				
				

				
				//var_dump($all_markets_top);
				
				
				/*
				
				*/

				
				/*
				//You can do an ascending sort like this:
				usort($inventory, function ($item1, $item2) {
					return $item1['price'] <=> $item2['price'];
				});

				//Or a descending sort like this:
				usort($inventory, function ($item1, $item2) {
					return $item2['price'] <=> $item1['price'];
				});
				*/
				
				//Sort Market by Volume
				$volume_markets_ = array();
				foreach ($all_markets as $key => $row)
				{
					$volume_markets_[$key] = $row['volume'] ;
					//echo $volume_markets_[$key].' - ';
				}
				array_multisort($volume_markets_, SORT_DESC, $all_markets);
				
			/*
			$all_markets_json  = json_encode($all_markets);
			$all_markets_arr = json_decode($all_markets_json, true);
			var_dump($all_markets_arr);
			*/
			?>
			
			@if(!$market_predefined )
				<!-- >#Biggest Top  Volume Loss -Markets -->
				<div class="contentfeaturedmarkets top">
					<div class="col-12-xs col-sm-12 col-lg-12">
						@foreach($all_markets_top as $amtop)
						
						
							<div class="col-xs-6 col-sm-3">
								<div class="allmarkets_top_{{ $amtop['market_type'] }}">
								<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($amtop['market']->id).'_BTC')}}">
									<div class="inblock" data-markets-currency="{{$amtop['from'].'_'.$amtop['to']}}">
										
											<span class="featured_type_first">{{ $amtop['from'] }}</span><span class="featured_type_sec">/BTC</span>
										
											<span class="featured_change" >
												<div class="market_change" >
													@if ($amtop['market_change'] == 0)
													<span class="change btn btn-warning disabled" data-markets-currency="change">
														{{$amtop['market_change']}}%
													</span>
													
													@elseif ($amtop['market_change'] > 0)
													<span class="change up btn btn-success" data-markets-currency="change">
														{{round($amtop['market_change'], 2)}}% <i class="fa fa-arrow-up"></i>
													</span>
													
													@else ($amtop['market_change'] < 0)
													<span class="change down btn btn-danger" data-markets-currency="change">
														{{round($amtop['market_change'], 2)}}% <i class="fa fa-arrow-down"></i>
													</span>
													@endif
												</div>

											</span> <br />
										<span class="featured_price_first" data-markets-currency="price">{{ $amtop['latest_price'] }}</span> <span class="featured_price_fiat"></span> <br />
										<span class="featured_vol" data-markets-currency="volume">Vol: @if(empty($amtop['volume'])) {{{sprintf('%.8f',0)}}}  @else {{sprintf('%.8f',$amtop['volume'])}}  @endif</span> 

											@if($amtop['market_type'] == 'gain')
												<div class="badge coin_icon">
													<span >
														<img src="{{asset('')}}/{{$amtop['logo']}}" class="coin_icon_small" alt="Image of {{$amtop['from_name']}}"/>
													</span>
												</div>
												<div class="ribbon">Biggest Gain</div>
											@endif
											@if($amtop['market_type'] == 'loss')
												<div class="badge coin_icon">
													<span >
														<img src="{{asset('')}}/{{$amtop['logo']}}" class="coin_icon_small" alt="Image of {{$amtop['from_name']}}"/>
													</span>
												</div>
												<div class="ribbon">Biggest Loss</div>
											@endif
											@if($amtop['market_type'] == 'volume')
												<div class="badge coin_icon">
													<span >
														<img src="{{asset('')}}/{{$amtop['logo']}}" class="coin_icon_small" alt="Image of {{$amtop['from_name']}}"/>
													</span>
												</div>
												<div class="ribbon">Top Volume</div>
											@endif
											@if($amtop['market_type'] == 'bex')
												<div class="badge coin_icon">
													<span>
														<img src="{{asset('')}}/{{$amtop['logo']}}" class="coin_icon_small" alt="Image of {{$amtop['from_name']}}"/>
													</span>
												</div>
												<div class="ribbon">BayEX</div>
											@endif
									</div>
								</a>
								</div>
							</div>

						@endforeach
					</div>
				</div>
				<!-- <#Biggest Top  Volume Loss -Markets -->
			
			@endif
			
			@if(!$market_predefined && $stq = $that->hasFeaturedMarket()->toArray()) 
			
			<!-- >#Featured-Markets -->
			<div class="contentfeaturedmarkets sponsored">
				<div class="col-12-xs col-sm-12 col-lg-12">
					<div class="inblock">
						<div class="ribbon">Featured Markets</div><br />
						@foreach($stq as $q)
							
							<div class="col-xs-6 col-sm-3" data-markets-currency="{{$q->type}}_BTC">
								<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($q->market_id).'_BTC')}}">
									<div class="featured_markets">
									<!-- {{$q->type.' - '.$q->name}}-->
									
										<span class="featured_type_first" >{{$q->type}}</span><span class="featured_type_sec">/BTC</span><br />
									
										<span class="featured_price_first" data-markets-currency="price">0.0041219</span>  <br />
										<div class="market_change" >
											<span class="change btn btn-warning" data-markets-currency="change">
											{{round(0.0000, 2)}}%
											</span>
										</div>
									
									</div>
								</a>
							</div>
							
						@endforeach
					</div>
				</div>
				
			</div>
			<!-- <#Featured-Markets -->			
			@endif			
			
			<?php
			/*
			<div class="">
				<div class="row">
					<div class="col-12-xs col-sm-12 col-md-12 col-lg-12 col-centered">
						
						@if(!$market_predefined && $stq = $that->hasFeaturedMarket()->toArray())
							<?php $i = 1; ?>
							<div >
								<h2>Featured Market</h2>
								<ul>
									<li>@foreach(['No.', 'Coin', 'Link', 'Message', 'Start Date', 'End Date'] as $q)<li>{{$q}}</li>@endforeach
								@foreach($stq as $q)
									<li>{{$i++}} - {{$q->type.' - '.$q->name}} | {{$q->link}} || {{$q->message}} || sd: {{date("d F Y", strtotime($q->start_date))}} || ed: {{date("d F Y", strtotime($q->end_date))}}</li>
								@endforeach
								</ul>
							</div>
						@endif
						
						<!-- #Featured-Markets -->
					</div>
				</div>
			</div>
			*/
			?>
			

		
	</div>

	
</div>
<!-- <#START MARKETS TABLE-->
			
			

<!-- https://www.freeformatter.com/html-formatter.html#ad-output -->
<!-- https://dirtymarkup.com/ -->


<!-- ############ START MARKETS TABLE-->	
<div class="row markets_tab">
	<div class="col-12-xs col-sm-123 col-lg-12 col-centered">
		<ul class="nav nav-tabs">
			<li class="active"><a aria-expanded="true" href="#btc_market_tab" data-toggle="tab">BTC Markets</a></li>
			<li class=""><a aria-expanded="false" href="#ltc_market_tab" data-toggle="tab">LTC Markets</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="btc_market_tab">
				<!-- BTC MARKET CONTENT-->
				<h2 class="nav-pills" style="margin-top:0px; display: none;">BTC - Live Market Data</h2>
				<?php
					//var_dump($all_markets);
					?>
				
				<table class="table table-striped table-hover market market_table bootstrap-popup" id="btc_market_table">
					<thead>
						<tr class="header-tb">
							<th data-priority="critical">Market / Vol</th>
							<th data-priority="2">Currency</th>
							<th data-priority="critical">Last Price</th>
							<th data-priority="4">24H High</th>
							<th data-priority="5">24H Low</th>
							<th data-priority="critical">Change</th>
							<th data-priority="3">Volume</th>
						</tr>
					</thead>
					<tbody>
						<?php
							/*
							echo '<pre>';
							var_dump($all_markets);
							echo '</pre>';
							*/ 
						?>
						@foreach($all_markets as $am)
						@if ($am['to'] == 'BTC')
							
						<?php
							//Get and Format Price High and Low for Currency
							$coin_price_high = '-';
							$coin_price_low = '-'; 
							if (isset($am['prices']->max)) $coin_price_high=sprintf('%.8f',$am['prices']->max);
							if (isset($am['prices']->min)) $coin_price_low=sprintf('%.8f',$am['prices']->min);
									/*
									if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
										$coin_max_ = '';
									else
										$coin_max_ = sprintf('%.8f',$am['prices']->max);
									
									if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
										$coin_min_ = '';
									else
										$coin_min_ = sprintf('%.8f',$am['prices']->min);
									*/
								
						?>
						<tr id="mainCoin-{{$am['from'].'_'.$am['to']}}" class="mainCoin" data-markets-currency="{{$am['from'].'_'.$am['to']}}">
							<td>
								<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($am['market']->id).'_BTC')}}">
									
									<!-- 
									<img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" alt="Image of {{$am['from_name']}}"/>
									-->
									
									@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red fa-2x" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif

									<div><span class="first_assist">{{$am['from']}} </span><span class="sec_assist">/{{$am['to']}}</span></div>
									<div class="third_assist vol hidden-sm hidden-md hidden-lg">Vol <span data-markets-currency="volume">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}}  @else {{sprintf('%.8f',$am['prices']->volume)}}  @endif</span></div>
								</a>
							</td>
							<td class="from_name">
								<a href="{{$marketUrl}}">
									<span class="first_assist currency_light">{{$am['from_name']}}</span>  
								</a>
							</td>
							<td>
								<a href="{{$marketUrl}}">
									<div>
										<span class="first_assist" data-markets-currency="price">{{$am['latest_price']}}</span>
										<span class="sec_assist convert_to_fiat"></span>
									</div>
									<div>
										
									</div>
									
								</a>							
							</td>
							<td>
								<a href="{{$marketUrl}}" class="nostrong">
									<div>
									<span class="first_assist" data-markets-currency="high">{{$coin_price_high}}</span>
									</div>
								</a>
							</td>
							<td>
								<a href="{{$marketUrl}}" class="nostrong" >
									<div>
									<span class="first_assist" data-markets-currency="low">{{$coin_price_low}}</span>
									</div>
								</a>
							</td>
							<td class="market_change">
								
								
								<?php
								/*
								echo '<pre>';
								print_r($am);
								echo '</pre>';
								*/
								?>
								@if ($am['market_change'] == 0)
								<span class="change btn btn-warning" data-markets-currency="change">
									{{$am['market_change']}}% <i class="fa"></i>
								</span>
								
								@elseif ($am['market_change'] > 0)
								<span class="change up btn btn-success" data-markets-currency="change">
									+{{round($am['market_change'], 2)}}% <i class="fa fa-arrow-up"></i>
								</span>
								
								@else ($am['market_change'] < 0)
								<span class="change down btn btn-danger" data-markets-currency="change">
									{{round($am['market_change'], 2)}}% <i class="fa fa-arrow-down"></i>
								</span>
								@endif
								
								
								
							</td>
							<td class="_desktop_style_">
								<a href="{{$marketUrl}}">
									<div class="first_assist currency_light vol"><span data-markets-currency="volume">@if(empty($am['volume'])) {{{sprintf('%.8f',0)}}}  @else {{sprintf('%.8f',$am['volume'])}}  @endif</span></div>
								</a>
							</td>
						</tr>
						@endif
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="tab-pane fade" id="ltc_market_tab">
				<!-- LTC MARKET CONTENT-->
				<h2 class="nav-pills" style="margin-top:0px; display: none;">LTC - Live Market Data</h2>
				<table  class="table table-striped table-hover market market_table" id="ltc_market_table">
					<thead class="columnSelector-disable">
						<tr class="header-tb">
							<th data-priority="critical">Market</th>
							<th data-priority="4">Currency</th>
							<th data-priority="critical">Last Price</th>
							<th data-priority="1">% Change</th>
							<th data-priority="2">24 H High</th>
							<th data-priority="3">24 H Low</th>
							<th data-priority="critical">24 H Volume</th>
						</tr>
					</thead>
					<tbody>
						@foreach($all_markets as $am)
						@if ($am['to'] != 'BTC')
						<tr id="mainCoin-{{$am['from'].'_'.$am['to']}}" data-markets-currency="{{$am['from'].'_'.$am['to']}}">
							<td>
								@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a>
							</td>
							<td class="from_name">
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}}</a>
							</td>
							<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" data-markets-currency="price"> {{$am['latest_price']}}</a></td>
							<?php
								
								if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
									$coin_max_ = '';
								else
									$coin_max_ = sprintf('%.8f',$am['prices']->max);
								
								if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
									$coin_min_ = '';
								else
									$coin_min_ = sprintf('%.8f',$am['prices']->min);
								
								?>
							<td class="market_change">
								@if ($am['market_change'] == 0)
									<span class="change" data-markets-currency="change">{{$am['market_change']}}% <i class="fa fa-minus"></i></span>
								@elseif ($am['market_change'] > 0)
									<span class="change up" data-markets-currency="change" >{{round($am['market_change'], 2)}}% <i class="fa fa-arrow-up"></i></span>
								@else ($am['market_change'] < 0)
									<span class="change down" data-markets-currency="change">{{round($am['market_change'], 2)}}% <i class="fa fa-arrow-down"></i></span>
								@endif
								<?php
									/*
									@if ($am['market_change']['change'] == 0)
										<span class="change" >{{$am['market_change']['change']}}% <i class="fa fa-minus"></i></span>
									@elseif ($am['market_change']['change'] > 0)
										<span class="change up" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-up"></i></span>
									@else ($am['latest_price'] < 0)
										<span class="change down" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-down"></i></span>
									@endif
									*/
									?>
							</td>
							<td>
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" data-markets-currency="high"> @if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
							</td>
							<td>
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" data-markets-currency="low"> @if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
							</td>
							<td>
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" data-markets-currency="volume"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
							</td>
						</tr>
						@endif
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
		
	
	

<?php
/*

//OLD MARKETS TABLE
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
		<h2 id="nav-pills" style="float: left; margin-top:0px;">BTC - Live Market Data</h2>
		<br />
		<?php
            //var_dump($all_markets);
            ?>
        <hr class="colorgraph"/>
        <table class="table table-striped table-hover market market_table bootstrap-popup" id="btc_market_table">
            <thead>
                <tr class="header-tb">
                    <th data-priority="4">Currency</th>
                    <th data-priority="critical">Market</th>
                    <th data-priority="critical">Last Price</th>
                    <th data-priority="1">% Change</th>
                    <th data-priority="2">24H High</th>
                    <th data-priority="3">24H Low</th>
                    <th data-priority="critical">24H Volume</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //var_dump($all_markets);
                    ?>
                @foreach($all_markets as $am)
                @if ($am['to'] == 'BTC')
					<tr id="mainCoin-{{$am['from'].'_'.$am['to']}}" class="mainCoin">
                <tr id="mainCoin-{{$am['from'].'_'.$am['to']}}">
                    <td class="from_name">
                        @if(!empty($am['logo']))                        
							<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($am['market']->id).'_BTC')}}"><img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" /></a>
                        @else
							&nbsp;
                        @endif
                        <a  href="{{$marketUrl}}">{{$am['from_name']}}</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}">
                        @if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
                        {{$am['from']}}/{{$am['to']}}
                        </a>
                    </td>
                    <td><a  href="{{$marketUrl}}" class="nostrong" id="mainLastPrice-{{$am['from'].'_'.$am['to']}}">{{$am['latest_price']}}</a></td>
                    <td class="market_change">
                        <?php
                            

                        if (isset($am['prices']->max)) {
                            if (sprintf('%.8f', $am['prices']->max)+0 == 0) {
                                $coin_max_ = '';
                            } else {
                                $coin_max_ = sprintf('%.8f', $am['prices']->max);
                            }
                                
                            if (sprintf('%.8f', $am['prices']->min)+0 == 0) {
                                $coin_min_ = '';
                            } else {
                                $coin_min_ = sprintf('%.8f', $am['prices']->min);
                            }
                        }
                            
                            
                            
							//echo '<pre>';
							//print_r($am);
							//echo '</pre>';
							
                            ?>
                        @if ($am['market_change'] == 0)
                        <span class="change" >{{$am['market_change']}}% <i class="fa fa-minus"></i></span>
                        @elseif ($am['market_change'] > 0)
                        <span class="change up" >{{$am['market_change']}}% <i class="fa fa-arrow-up"></i></span>
                        @else ($am['market_change'] < 0)
                        <span class="change down" >{{$am['market_change']}}% <i class="fa fa-arrow-down"></i></span>
                        @endif
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainHighPrice-{{$am['from'].'_'.$am['to']}}">@if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainLowPrice-{{$am['from'].'_'.$am['to']}}">@if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainVolume-{{$am['from'].'_'.$am['to']}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        <h2 id="nav-pills" style="float: left; margin-top:0px;">LTC - Live Market Data</h2>
        <table  class="table table-striped table-hover market market_table" id="ltc_market_table">
            <thead class="columnSelector-disable">
                <tr class="header-tb">
                    <th data-priority="4">Currency</th>
                    <th data-priority="critical">Market</th>
                    <th data-priority="critical">Last Price</th>
                    <th data-priority="1">% Change</th>
                    <th data-priority="2">24 H High</th>
                    <th data-priority="3">24 H Low</th>
                    <th data-priority="critical">24 H Volume</th>
                </tr>
            </thead>
            <tbody>
                @foreach($all_markets as $am)
                @if ($am['to'] != 'BTC')
                <tr id="mainCoin-{{$am['from'].'_'.$am['to']}}">
                    <td class="from_name">
                        @if(!empty($am['logo']))                        
                        <a  href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($am['market']->id).'_LTC')}}"><img class="coin_icon_small" src="{{asset('')}}/{{$am['logo']}}" /></a>
                        @else
                        &nbsp;
                        @endif
                        <a  href="{{$marketUrl}}">{{$am['from_name']}}</a>
                    </td>
                    <td>
                        @if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
                        <a  href="{{$marketUrl}}">{{$am['from']}}/{{$am['to']}}</a>
                    </td>
                    <td><a  href="{{$marketUrl}}" class="nostrong" id="mainLastPrice-{{$am['from'].'_'.$am['to']}}"> {{$am['latest_price']}}</a></td>
                    <?php
                        
                    if (sprintf('%.8f', $am['prices']->max)+0 == 0) {
                        $coin_max_ = '';
                    } else {
                        $coin_max_ = sprintf('%.8f', $am['prices']->max);
                    }
                        
                    if (sprintf('%.8f', $am['prices']->min)+0 == 0) {
                        $coin_min_ = '';
                    } else {
                        $coin_min_ = sprintf('%.8f', $am['prices']->min);
                    }
                        
                        ?>
                    <td class="market_change">
                        @if ($am['market_change'] == 0)
                        <span class="change" >{{$am['market_change']}}% <i class="fa fa-minus"></i></span>
                        @elseif ($am['market_change'] > 0)
                        <span class="change up" >{{$am['market_change']}}% <i class="fa fa-arrow-up"></i></span>
                        @else ($am['market_change'] < 0)
                        <span class="change down" >{{$am['market_change']}}% <i class="fa fa-arrow-down"></i></span>
                        @endif
                        <?php
                            
							//@if ($am['market_change']['change'] == 0)
							//	<span class="change" >{{$am['market_change']['change']}}% <i class="fa fa-minus"></i></span>
							//@elseif ($am['market_change']['change'] > 0)
							//	<span class="change up" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-up"></i></span>
							//@else ($am['latest_price'] < 0)
							//	<span class="change down" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-down"></i></span>
							//@endif
							
                            ?>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainHighPrice-{{$am['from'].'_'.$am['to']}}"> @if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainLowPrice-{{$am['from'].'_'.$am['to']}}"> @if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainVolume-{{$am['from'].'_'.$am['to']}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
*/?>

