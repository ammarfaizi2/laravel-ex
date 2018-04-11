<?php
//Get and Format Price High and Low for Currency
$coin_price_high = '-';
$coin_price_low = '-'; 
if (isset($am['prices']->max)) $coin_price_high=sprintf('%.8f',$am['prices']->max);
if (isset($am['prices']->min)) $coin_price_low=sprintf('%.8f',$am['prices']->min);
?>
<div class="row">
	<div class="market_info">

		
		@if($enable_trading == 0)
			<!-- Alert Message -->
			<div class="row">
				<div class="col-12-xs col-sm-12 col-lg-12">
					<div class="notice notice-danger">
						<strong><i class="fa fa-exclamation-triangle fa-2x left"></i> {{ trans('texts.notice') }}</strong> {{ $coinmain }}/{{ $coinsecond }} - {{{ trans('texts.market_disabled')}}}
					</div>
				</div>
			</div>
		@endif
		
		<?php
		//var_dump($all_markets);
		
		?>
		<!-- Coin Name/Logo -->
		<!-- Coin Data -->
		<div class="row">
			<div class="wrapper-trading">
				<div class="col-xs-12 col-sm-12 col-lg-12">

					<div class="coin-info">
				
						<div class="box box-warning" data-market-currency="{{ $coinmain.'_'.$coinsecond }}">
							<div class="box-header with-border">
							  <h3 class="box-title">
									<img width="32" border=0 height="32" src="{{asset('')}}/{{$coinmain_logo}}" />
									<strong>{{$market_from}} - {{$coinmain}}</strong>/{{$coinsecond}}
									<span class="market_change">
										<?php 
										$market_change_class="";
										?>
										@if ($market_change == 0)
											<span class="change btn btn-warning disabled" data-market-currency="change">
												{{$market_change}}%
											</span>
											<?php $market_change_class = ""?>
										@elseif ($market_change > 0)
											<span class="change up btn btn-success" data-market-currency="change">
												+{{round($market_change,2)}}% <i class="fa fa-arrow-up"></i>
											</span>
											<?php $market_change_class = "change up"?>
										@else ($market_change < 0)
											<span class="change down btn btn-danger" data-market-currency="change">
												{{round($market_change,2)}}% <i class="fa fa-arrow-down"></i>
											</span>
											<?php $market_change_class = "change down"?>
										@endif
									</span>
							  </h3>
							  <div class="box-tools pull-right"></div>
							</div>
							
							<div class="box-body">
									
								<!-- Coin Data/Statistics-->
								<div class="col-12-xs col-sm-12 col-lg-12" >
									<div class="col-xs-6 col-sm-3">
										<div class="market"><span aria-hidden="true" class="glyphicon glyphicon-chevron-right" style="color: #2a9fd6;"></span> Price:<br><strong><span class="{{$market_change_class}}" data-market-currency="price">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span> </strong></div>
									</div>
									<div class="col-xs-6 col-sm-3">
												<div ><span aria-hidden="true" class="glyphicon glyphicon-stats"></span> 24H Vol:<br><strong><span data-market-currency="volume">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> </strong></div>
									</div>
									<div class="col-xs-6 col-sm-3">
												<div ><span aria-hidden="true" class="glyphicon glyphicon-export" style="color: #6bbf46;"></span> High:<br><strong><span data-market-currency="high">{{$coin_price_high}}</span></strong></div>
									</div>
									<div class="col-xs-6 col-sm-3">
												<div ><span aria-hidden="true" class="glyphicon glyphicon-import" style="color: #cc0000;"></span> Low:<br><strong><span data-market-currency="low">{{$coin_price_low}}</span></strong></div>
									</div>
								</div>
							
								<!-- Coin Information -->
								<!-- NEWS -->
								@php
									$news = $that->getNews($market_id);
								@endphp
								@if (isset($news) && $news)
									@foreach($news as $news)
											<div class="col-12-xs col-sm-12 col-lg-12">
											
												<div class="alert alert-info">
													<strong>{{ $news->title }}</strong>
													{{ $news->content }}
												</div>
											</div>

									@endforeach
								@endif

							</div>
							<!-- /.box-body -->

						</div>

					</div>

				</div>
			</div>
		</div>
		


			
		<!-- Coin name & Charts -->

		<!-- Session Errors and Messages -->
		@if ( is_array(Session::get('error')) )
			<div class="alert alert-error">{{ head(Session::get('error')) }}</div>
		@elseif ( Session::get('error') )
			<div class="alert alert-error">{{{ Session::get('error') }}}</div>
		@endif
		
		@if ( Session::get('success') )
			<div class="alert alert-success">{{{ Session::get('success') }}}</div>
		@endif
		@if ( Session::get('notice') )
			<div class="alert">{{{ Session::get('notice') }}}</div>
		@endif
			
		<!-- Coin name & Charts -->
		<!-- Charts and Info !-->
		
		<div class="row">
			<div class="col-12-xs col-sm-12 col-lg-12">
				<div class="nav-tabs-custom" >
					<!-- Tabs within a box -->
					<ul class="nav nav-tabs pull-right ui-sortable-handle" id="chart_marketdepth_tab">
					  <li class=""><a href="#orderdepth" data-toggle="tab" aria-expanded="false" data="order-chart" onclick="javascript: drawOrderDepthChart();">Order Depth</a></li>
					  <li class="active"><a href="#chartdiv" data-toggle="tab" aria-expanded="true" data="price-volume-chart">Chart</a></li>
					  <li class="pull-left header"><i class="fa fa-bar-chart"></i>
						<!--<img width="32" border=0 height="32" src="{{asset('')}}/{{$coinmain_logo}}" /> --> 
					  </li>
					</ul>
					<div class="tab-content chart_marketdepth">
					  <!-- Morris chart - Sales -->
					  <div class="chart tab-pane" id="orderdepth" style="width:100%; height:400px;">
						Market Depth Loading ...
						<div id="chartLoadingBox">Loading...</div>
					  </div>
					  <div class="chart tab-pane active" id="chartdiv" style="width:100%; height:400px;">
						Chart Loading ...
					  </div>
					</div>
				  </div>
			  </div>
			  
		</div>
		


		<!-- Sell / Buy -->
		<?php
			/*
			@if ( Auth::guest() )
			@else
			*/
			?>

		<?php
		if ($user = Confide::user()) {
			$wh = DB::table("whitelist_ip_state")
					->select("trade")
					->where("user_id", "=", $user->id)
					->first();
			$cip = App\Http\Controllers\UserController::get_client_ip();
			if (isset($wh->trade) && $wh->trade === "on") {
				$rr = DB::table("whitelist_trade_ip")
					->select("ip")
					->where("user_id", "=", $user->id)
					->get();
				if ($rr) {
					$f_ = false;
					foreach ($rr as $ip) {
						if (preg_match("/$ip->ip/i", $cip)) {
							$f_ = true;
							break;
						}
					}
				}
			}
		}
		?>
		@if(isset($f_) && $f_ === false)
		<script type="text/javascript">
			function blocked() {
				bootbox.alert({ 
				  size: "small",
				  title: "",
				  message: "{{ trans('user_texts.blocked_ip_trade') }}", 
				  callback: function(){}
				});
			}
		</script>
		@endif
		<div class="row">
			<div class="">
				<div class="wrapper-trading buysellform">
					<div class="col-12-xs col-sm-12 col-lg-12 no-padding">
						<div class="col-xs-12 col-sm-6">
							<!-- <div class="inblock-left"> </div>-->
								@include('blocks.buyform')
						</div>
						<div class="col-xs-12 col-sm-6">
							<!-- <div class="inblock-right"> </div>-->
								@include('blocks.sellform')	
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			//@endif
			?>
		<div class="row">
			<div class="">
				<div class="wrapper-trading buysellorders">
					<?php
					/*
					<div class="col-12-xs col-sm-12 col-lg-12">
						<h3>{{{ trans('texts.order_book')}}}</h3>
					</div>
					*/
					?>
					<div class="col-12-xs col-sm-12 col-lg-12 no-padding">
						<div class="col-xs-12 col-sm-6">
							@include('blocks.buyorders')
						</div>
						<div class="col-xs-12 col-sm-6">
							@include('blocks.sellorders')
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Active Orders  -->
		@if ( Auth::guest() )
		@else
		<div class="row">
			<div class="wrapper-trading">
				@include('blocks.yourorders')	
			</div>
		</div>
		@endif
		
		<!-- Trade History -->
		<div class="row">
			<div class="wrapper-trading">
				<div class="col-12-xs col-sm-12 col-lg-12">
					@include('blocks.tradehistory')
				</div>
			</div>
		</div>
		
		<!-- Coin Info -->
		<div class="row">
			<div class="wrapper-trading">
				<div class="col-xs-12 col-sm-12 col-lg-12">

			
					@if($qwe = $that->hasCustomFields($market_id))
					<div class="coin-info">
				
						<div class="box box-warning" >
							<div class="box-header with-border">
							  <h3 class="box-title">
									{{{ trans('texts.currency_info')}}}
							  </h3>
							  <div class="box-tools pull-right"></div>
							</div>
							
							<div class="box-body">
									
								<?php
								$coin_description ='';
								$coin_description_li =''; 
								foreach($qwe as $q){
										if ($q->name == 'description')
											$coin_description = $q->value;
										else
											$coin_description_li .= '<li><span>'.$q->name.'</span><p>'.$q->value.'</p></li>';
										//echo $q->name . ' '.$q->type . ' '.$q->value;
										//echo '<br />';
										
								}
									
								?>

								<!-- Coin Information -->
								<div class="col-xs-7 col-sm-5 col-lg-5" >
									<span>{{{ trans('texts.info')}}}</span>
									<hr />
									{{$coin_description}}
								</div>
								<div class="col-xs-7 col-sm-7 col-lg-7" >
									<div class="col-xs-7 col-sm-12 col-lg-12" >
										<ul class="nav ">
										<?php echo $coin_description_li?>
										</ul>
									
									</div>
								</div>
								<?php
								/*
								@php $no = 1; @endphp
								<table style="border-collapse: collapse;width:300px;" border="1">
									<tr>
										@foreach(['No.','Name','Value','Type'] as $qw)
											<th align="center"><center>{{$qw}}</center></th>
										@endforeach
									</tr>
									@foreach($qwe as $q)
										<tr><td align="center">{{$no++}}.</td><td align="center">{{$q->name}}</td><td align="center">{{$q->value}}</td><td align="center">{{$q->type}}</td></tr>
									@endforeach
								</table>
								*/
								?>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								Page: 1, 2, 3, 4, 5
							</div>
							<!-- /.box-footer-->
						</div>

					</div>
					@endif

				</div>
			</div>
		</div>
		

	</div>
</div>

	<!-- Assets for Charts -->
	{{ HTML::style('assets/amcharts/style.css') }}
	{{ HTML::script('assets/amcharts/amcharts.js') }}
	{{ HTML::script('assets/amcharts/serial.js') }}
	{{ HTML::script('assets/amcharts/amstock.js') }}
	<script type="text/javascript">		
		var getChartURL = "<?php echo action('HomeController@getChart')?>";
		var getMarketID = "<?php echo $market_id ?>";
		var getOrderDepthChart = "<?php echo action('OrderController@getOrderDepthChart')?>";
		var transError = "{{{ trans('texts.error') }}}";
		var coinSecond = "{{{$coinsecond}}}";
	</script>
	{{ HTML::script('assets/js/custom_charts.js') }}


