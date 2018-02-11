<?php
var_dump($market_predefined)
?>
<div class="row">
	<div class="market_info">
		<div class="row">
			<div class="row_break"></div>
		</div>
		<!-- Alert Message -->
		@if($enable_trading == 0)
			<div class="row">
				<div class="col-12-xs col-sm-12 col-lg-12">
					<div class="notice notice-danger">
						<strong><i class="fa fa-exclamation-triangle fa-2x left"></i> {{ trans('texts.notice') }}</strong> {{ $coinmain }}/{{ $coinsecond }} - {{{ trans('texts.market_disabled')}}}
					</div>
			</div>
			
			
		@endif
		
		<!-- Coin Data -->
		<div class="row">
			<div class="bs-component">
				<div class="col-12-xs col-sm-12 col-lg-12">
					<div class="inblock" style="display: flex;">
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div id="lastprice-{{{Session::get('market_id')}}}"><span aria-hidden="true" class="glyphicon glyphicon-chevron-right" style="color: #2a9fd6;"></span> Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span> {{{ $coinsecond }}}</strong></div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div ><span aria-hidden="true" class="glyphicon glyphicon-export" style="color: #6bbf46;"></span> High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span>{{{ $coinsecond }}}</strong></div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div ><span aria-hidden="true" class="glyphicon glyphicon-import" style="color: #cc0000;"></span> Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span>{{{ $coinsecond }}}</strong></div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div ><span aria-hidden="true" class="glyphicon glyphicon-stats"></span> 24H Vol:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinsecond }}}</strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	<!-- Coin name & Charts -->
	<div class="row">
		<!-- Charts Graph  , Coin Name-->
		<div class="col-12-xs col-sm-12 col-lg-12">
			<h2 ><!--<img width="32" border=0 height="32" src="{{asset('')}}/{{$coinmain_logo}}" /> --> {{$market_from}} - {{ $coinmain }}/{{ $coinsecond }} </h2>
		</div>
	</div>
	</div>

	
	<!-- NEWS -->
	@if (isset($news) && $news)
		<div class="alert alert-info">
			<strong>{{ $news->title }}</strong>
			{{ $news->content }}
		</div>
	@endif
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
	<!--
		<div class="">
			<div class="item18">
				<div class="success box" id="lastprice-{{{Session::get('market_id')}}}">Last Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span> {{{ $coinsecond }}}</strong></div>
			</div>
			<div class="item18">
				<div class="success box">24 h High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span> {{{ $coinsecond }}}</strong></div>
			</div>
			<div class="item18">
				<div class="success box">24 h Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span> {{{ $coinsecond }}}</strong></div>
			</div>
			<div class="item18">
				<div class="success box">24 h Vol:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinsecond }}}</strong></div>
			</div>
			<div class="item25">
				<div class="success box">
					<div data-toggle="tooltip" data-placement="left" title="Total SELL in {{{ $coinsecond }}}">#Sell: <strong><span id="sellorders_total_all_box_{{{Session::get('market_id')}}}"></span> {{{ $coinsecond }}}</strong></div>
					<div data-toggle="tooltip" data-placement="left" title="Total BUY in {{{ $coinmain }}}">#Buy: <strong><span id="buyorders_amount_all_box_{{{Session::get('market_id')}}}"></span> {{{ $coinmain }}}</strong></div>
					
				</div>
			</div>
		</div>
		-->
	<!-- Charts and Info !-->
	<div class="row">
		<!-- Charts Graph  -->
		<!--  <div class="col-xs-12 col-sm-6 col-md-8"> -->
		<!--  <div class="col-12-xs col-sm-12 col-lg-12"> -->
		<div class="col-12-xs col-sm-12 col-lg-12">
			<ul class="nav nav-tabs" id="chart_marketdepth_tab" role="tablist" >
				<li><a href="#orderdepth" role="tab" data-toggle="tab" data="order-chart" onclick="javascript: drawOrderDepthChart();">Order Depth</a></li>
				<li class="right active"><a href="#chartdiv" role="tab" data-toggle="tab" data="price-volume-chart">Chart</a></li>
			</ul>
			<div class="tab-content chart_marketdepth">
				<div class="tab-pane active" id="chartdiv" style="width:100%; height:400px;">
					<div id="chartLoadingBox">Loading...</div>
				</div>
				<div class="tab-pane" id="orderdepth" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php
			/*
			
			<!-- Market Daily Info  -->
			<div class="col-xs-6 col-md-4">
			<div class="bs-component">
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						Basic panel	
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						Basic panel	
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						Basic panel	
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						Basic panel	
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						Basic panel	
					</div>
				</div>
			</div>
			</div>
			</div>
			
			*/
			?>
	</div>
	


	<!-- Sell / Buy -->
	<?php
		/*
		@if ( Auth::guest() )
		@else
		*/
		?>
	<div class="row">
		<div class="wrapper-trading buysellform">
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
	<?php
		//@endif
		?>
	<div class="row">
		<div class="wrapper-trading buysellorders">
			<div class="col-12-xs col-sm-12 col-lg-12">
				<h3>{{{ trans('texts.order_book')}}}</h3>
			</div>
	
			<div class="col-xs-12 col-sm-6">
				@include('blocks.buyorders')
			</div>
			<div class="col-xs-12 col-sm-6">
				@include('blocks.sellorders')
			</div>
		</div>
	</div>
	<!-- Active Orders  -->
	@if ( Auth::guest() )
	@else
	<div class="row">
			@include('blocks.yourorders')	
	</div>
	@endif
	
	<!-- Trade History -->
	<div class="row">
		<div class="col-12-xs col-sm-12 col-lg-12">
			@include('blocks.tradehistory')
		</div>
	</div>
	
	<!-- Coin Info -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-lg-12">

			@if($qwe = $that->hasCustomFields($market_id)->toArray())
			<div class="coin-info inblock">
				<?php
				var_dump($qwe);
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

				<div class="col-xs-7 col-sm-12 col-lg-12">
					Currency Information
					<hr />
				</div>
				<!-- Coin Information -->
				<div class="col-xs-7 col-sm-5 col-lg-5" >
					<span>Introduction</span>
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
			@endif

		</div>
	</div>
	
	<div class="row">
		<div class="col-12-xs col-sm-12 col-lg-12">
			&nbsp;
		</div>
	</div>
</div>
	<div class="clear"></div>
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


