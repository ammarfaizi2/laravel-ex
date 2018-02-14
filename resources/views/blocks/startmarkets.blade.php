<?php 
/*
<!-- https://www.freeformatter.com/html-formatter.html#ad-output -->
<!-- https://dirtymarkup.com/ -->
*/?>
<!-- >#START MARKETS TABLE-->
<div class="row">
	<div class="contentinner">
			@if ( Auth::guest() )
			<div class="contentheader" >

				<h1 style="margin-top:5px;">{{{ Config::get('config_custom.company_name') }}}</h1>

				<h2>The Fast, Secure and Reliable Crypto Exchange with Low Fees!</h2>


				<div style="background-color: rgba(0,0,0,0.25); padding: 10px; border-radius: 4px;">

					<a class="button button-green btn btn-lg" href="{{{ URL::to('/user/register') }}}">
					  <i class="fa fa-user-plus"></i>
					  <span>{{trans('user_texts.register')}}</span>
					</a>
					<a class="button button-blue btn btn-lg" href="{{{ URL::to('/login') }}}">
					  <i class="fa fa-sign-in "></i>
					  <span>{{trans('user_texts.login')}}</span>
					</a>

				</div>

			</div>
			<div class="row">
				<hr class="colorgraph" />
			</div>
			@endif
			
			<div class="contentmarketdata">
				<!-- ############ START VOLUME MARKETS-->
				<div class="row">
						<div class="col-12-xs col-sm-12 col-lg-12 ">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										BTC Vol: 1290
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										LTC Vol: 2191
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										# Trades: 2194
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										# Online: 987
							</div>

						</div>
				</div>
			</div>
			<div class="">
				<div class="row">
					<!-- >#Featured-Markets -->
					@if(!$market_predefined && $stq = $that->hasFeaturedMarket()->toArray())
						<?php $i = 1; ?>
						<div style="margin-left:15px;padding-bottom:10px;">
							<h2>Featured Market</h2>
							<table style="border-collapse: collapse;" border="1">
								<tr>@foreach(['No.', 'Coin', 'Link', 'Message', 'Start Date', 'End Date'] as $q)<th><center>{{$q}}</center></th>@endforeach</tr>
							@foreach($stq as $q)
								<tr><td style="padding:5px;" align="center">{{$i++}}.</td><td style="padding:5px;" align="center">{{$q->type.' - '.$q->name}}</td><td style="padding:5px;" align="center">{{$q->link}}</td><td style="padding:5px;" align="center">{{$q->message}}</td><td style="padding:5px;" align="center">{{date("d F Y", strtotime($q->start_date))}}</td><td style="padding:5px;" align="center">{{date("d F Y", strtotime($q->end_date))}}</td></tr>
							@endforeach
							</table>
						</div>
					@endif
					<!-- <#Featured-Markets -->
					
				</div>
			</div>
			

		
	</div>

	
</div>
<!-- <#START MARKETS TABLE-->
			
			

<!-- https://www.freeformatter.com/html-formatter.html#ad-output -->
<!-- https://dirtymarkup.com/ -->


<!-- ############ START MARKETS TABLE-->	
<div class="row markets_tab">
	<div class="col-11-xs col-sm-11 col-lg-11 col-centered">
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
						<tr id="mainCoin-{{$am['market']->id}}" class="mainCoin">
							<td>
								<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($am['market']->id).'_BTC')}}">
									<img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" alt="Image of {{$am['from_name']}}"/>
									@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif

									<div><span class="first_assist">{{$am['from']}} </span><span class="sec_assist">/ {{$am['to']}}</span></div>
									<div class="third_assist vol _mobile_style_">Vol <span class="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}}  @else {{sprintf('%.8f',$am['prices']->volume)}}  @endif</span></div>
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
										<span class="first_assist" id="mainLastPrice-{{$am['market']->id}}">{{$am['latest_price']}}</span>
									</div>
									<div>
										<span class="sec_assist convert_to_fiat">$ 1,070.15</span>
									</div>
									
								</a>							
							</td>
							<td>
								<a href="{{$marketUrl}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}">
									@if(empty($coin_max_)) - @else {{$coin_max_}} @endif
								</a>
							</td>
							<td>
								<a href="{{$marketUrl}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}">
									@if(empty($coin_min_)) - @else {{$coin_min_}} @endif
								</a>
							</td>
							<td class="market_change">
								
								
								<?php
								if (isset($am['prices']->max)){
									if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
										$coin_max_ = '';
									else
										$coin_max_ = sprintf('%.8f',$am['prices']->max);
									
									if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
										$coin_min_ = '';
									else
										$coin_min_ = sprintf('%.8f',$am['prices']->min);
								}

								/*
								echo '<pre>';
								print_r($am);
								echo '</pre>';
								*/
								?>
								@if ($am['market_change'] == 0)
								<span class="change btn btn-warning disabled">
									{{$am['market_change']}}%
								</span>
								
								@elseif ($am['market_change'] > 0)
								<span class="change up btn btn-success">
									+ {{$am['market_change']}}% <i class="fa fa-arrow-up"></i>
								</span>
								
								@else ($am['market_change'] < 0)
								<span class="change down btn btn-danger">
									+ {{$am['market_change']}}% <i class="fa fa-arrow-down"></i>
								</span>
								@endif						
								
								
								
							</td>
							<td class="_desktop_style_">
								<a href="{{$marketUrl}}">
									<div class="first_assist currency_light vol"><span class="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}}  @else {{sprintf('%.8f',$am['prices']->volume)}}  @endif</span></div>
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
						<tr id="mainCoin-{{$am['market']->id}}">
							<td>
								@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a>
							</td>
							<td class="from_name">
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}}</a>
							</td>
							<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}"> {{$am['latest_price']}}</a></td>
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
								<span class="change" >{{$am['market_change']}}% <i class="fa fa-minus"></i></span>
								@elseif ($am['market_change'] > 0)
								<span class="change up" >{{$am['market_change']}}% <i class="fa fa-arrow-up"></i></span>
								@else ($am['market_change'] < 0)
								<span class="change down" >{{$am['market_change']}}% <i class="fa fa-arrow-down"></i></span>
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
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}"> @if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
							</td>
							<td>
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}"> @if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
							</td>
							<td>
								<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong mainVolume-{{$am['market']->id}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
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
					<tr id="mainCoin-{{$am['market']->id}}" class="mainCoin">
                <tr id="mainCoin-{{$am['market']->id}}">
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
                    <td><a  href="{{$marketUrl}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}">{{$am['latest_price']}}</a></td>
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
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}">@if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}">@if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
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
                <tr id="mainCoin-{{$am['market']->id}}">
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
                    <td><a  href="{{$marketUrl}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}"> {{$am['latest_price']}}</a></td>
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
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}"> @if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}"> @if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
                    </td>
                    <td>
                        <a  href="{{$marketUrl}}" class="nostrong" id="mainVolume-{{$am['market']->id}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
*/?>

