@extends('layouts.default')
@section('content')

<h1 style="color: red;" > CAUTION: DO NOT TRADE HERE:. SYSTEM IS UNDER CONSTRUCTION!</h1>
Users: <p id="client_count"></p>
@if(isset($show_all_markets) && $show_all_markets === true)
	<h2>BTC 24 Hour trade statistics</h2>
	<div class="market_search_box" >
		<input id="btc_market_search" type="search" data-column="1" placeholder="Search" class="form-control" />
	</div>
	<table class="table table-striped table-hover" id="btc_market_table">
		<thead>
			<tr class="header-tb">
			<th colspan=2>Currency</th>
				<th>Market</th>
				<th>Last Price</th>
				<th>24 Hour High</th>
				<th>24 Hour Low</th>
				<th>24 Hour Volume</th>
			</tr> 
		</thead>
		<tbody>
		@foreach($all_markets as $am)
			@if ($am['to'] == 'BTC')
			<tr id="mainCoin-{{$am['market']->id}}">
				<td >
					@if(!empty($am['logo']))                        
						<a href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" /></a>
					@else
					&nbsp;
					@endif
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}</a>
				</td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a></td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}">{{$am['latest_price']}}</a></td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}">@if(empty($am['prices']->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->max)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}">@if(empty($am['prices']->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->min)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
				</td>
			</tr>
			@endif
		@endforeach
		</tbody>
	</table>

	<h2 style="margin-top:0px;">LTC 24 Hour trade statistics</h2>
	<div class="market_search_box" >
		<input id="ltc_market_search" type="search" data-column="1" placeholder="Search" class="form-control" />
	</div>
	<table class="table table-striped table-hover" id="ltc_market_table">
		<thead>
			<tr class="header-tb">
			<th colspan=2>Currency</th>
				<th>Market</th>
				<th>Last Price</th>
				<th>24 Hour High</th>
				<th>24 Hour Low</th>
				<th>24 Hour Volume</th>
			</tr> 
		</thead>
		<tbody>
		@foreach($all_markets as $am)
			@if ($am['to'] != 'BTC')
			<tr id="mainCoin-{{$am['market']->id}}">
				<td >
					@if(!empty($am['logo']))                        
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img class="coin_icon_small" src="{{asset('')}}/{{$am['logo']}}" /></a>
					@else
					&nbsp;
					@endif
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}</a>
				</td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a></td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}"> {{$am['latest_price']}}</a></td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}"> @if(empty($am['prices']->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->max)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}"> @if(empty($am['prices']->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->min)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
				</td>
			</tr>
			@endif
		@endforeach
		</tbody>
	</table>
Sponsored currency
@endif

<h2 style="margin-top:0px;"><img width="32" border=0 height="32" src="{{asset('')}}/{{$coinmain_logo}}" /> {{$market_from}}/{{$market_to}} 
@if ( $market_from == 'Mileycyruscoin' )
<font size=1><a href="http://minemanic.com/mcc/">Block Viewer</a></font>
@endif

@if ( $market_from == 'Kimdotcoin' )
<font size=1><a href="http://minemanic.com/dot/">Block Viewer</a></font>
@endif

@if ( $market_from == 'Bitcentavo' )
<font size=1><a href="http://minemanic.com/nbe/">Block Viewer</a></font>
@endif

@if ( $market_from == 'Bongger' )
<font size=1><a href="http://minemanic.com/bgr/">Block Viewer</a></font>
@endif

@if ( $market_from == 'Chikun' )
<font size=1><a href="http://minemanic.com/kun/">Block Viewer</a></font>
@endif




</h2> 






@if ( $market_from == 'UFO' )
      <div class="alert alert-danger">UFO Market is closing, please withdraw your coins ASAP!</div>
@endif

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
	
	<div class="">
		<div class="item25">
			<div class="success box" id="lastprice-{{{Session::get('market_id')}}}">Last Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span></strong></div>
		</div>
		<div class="item25">
			<div class="success box">24 h High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span></strong></div>
		</div>
		<div class="item25">
			<div class="success box">24 h Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span></strong></div>
		</div>
		<div class="item25">
			<div class="success box">24 h Vol:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinsecond }}}</strong></div>
		</div>
	</div>

	
	<ul class="nav nav-tabs" id="chart_marketdepth_tab" role="tablist" >
		<li><a href="#orderdepth" role="tab" data-toggle="tab" data="order-chart" onclick="javascript: drawOrderDepthChart();">Order Depth</a></li>
		<li class="right active"><a href="#chartdiv" role="tab" data-toggle="tab" data="price-volume-chart">Price / Volume</a></li>
		
	</ul>

	<div class="tab-content chart_marketdepth">
		<div class="tab-pane active" id="chartdiv" style="width:100%; height:400px;"><div id="chartLoadingBox">Loading...</div></div>
		<div class="tab-pane" id="orderdepth" style="width:100%; height:400px;"></div>
	</div>


	<!-- Sell/Buy -->	
	@if ( Auth::guest() )
	@else
		<div class="wrapper-trading buysellform">
			<div class="inblock-left">
				@include('blocks.buyform')
			</div>	
			<div class="inblock-right">		
				@include('blocks.sellform')	
			</div>
		</div>
	@endif

	<div class="wrapper-trading buysellorders">
		<div class="inblock-left">
			@include('blocks.sellorders')
		</div>	
		<div class="inblock-right">		
			@include('blocks.buyorders')
		</div>
	</div>
	<!-- Trade history -->
	@include('blocks.tradehistory')				
	
		<!-- Your Active Order  -->
	@if ( Auth::guest() )
	@else
		@include('blocks.yourorders')
	@endif
<div id="messageModal" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">      
      <div class="modal-body">        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">{{{ trans('texts.close')}}}</button>       
      </div>
    </div>
  </div>
</div>

{{HTML::style('assets/amcharts/style.css')}}
{{ HTML::script('assets/amcharts/amcharts.js') }}
{{ HTML::script('assets/amcharts/serial.js') }}
{{ HTML::script('assets/amcharts/amstock.js') }}

{{ HTML::script('assets/js/jquery.tablesorter.js') }}
{{ HTML::script('assets/js/jquery.tablesorter.widgets.js') }}
<script type="text/javascript">

//Search sidebar
$('#search_market').keyup(function(){
	
   var valThis = $(this).val().toLowerCase();
    $('ul.market>li').each(function(){
     var text = $(this).text().toLowerCase();
        (text.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();            
   });
   
});


//Table sorting
$(function() {

	var $btc_table = $('table#btc_market_table').tablesorter({
		//theme: 'blue',
		//widgets: ["zebra", "filter"],
		widgets: ["filter"],
		widgetOptions : {
			// filter_anyMatch replaced! Instead use the filter_external option
			// Set to use a jQuery selector (or jQuery object) pointing to the
			// external filter (column specific or any match)
			filter_external : '#btc_market_search',
			// add a default type search to the first name column
			filter_defaultFilter: { 1 : '~{query}' },
			// Use the $.tablesorter.storage utility to save the most recent filters
			filter_saveFilters : true,
			// Delay in milliseconds before the filter widget starts searching; 
			filter_searchDelay : 300,
			// include column filters
			filter_columnFilters: false/*,
			filter_placeholder: { search : 'Search...' },
			filter_saveFilters : true,
			filter_reset: '.reset'*/
		}
	});
	var $ltc_table = $('table#ltc_market_table').tablesorter({
		//theme: 'blue',
		//widgets: ["zebra", "filter"],
		widgets: ["filter"],
		widgetOptions : {
			// filter_anyMatch replaced! Instead use the filter_external option
			// Set to use a jQuery selector (or jQuery object) pointing to the
			// external filter (column specific or any match)
			filter_external : '#ltc_market_search',
			// add a default type search to the first name column
			filter_defaultFilter: { 1 : '~{query}' },
			// Use the $.tablesorter.storage utility to save the most recent filters
			filter_saveFilters : true,
			// Delay in milliseconds before the filter widget starts searching; 
			filter_searchDelay : 300,
			// include column filters
			filter_columnFilters: false/*,
			filter_placeholder: { search : 'Search...' },
			filter_saveFilters : true,
			filter_reset: '.reset'*/
		}
	});

});

$(function() {
	$("#sellorders > tbody ").on('click', 'tr', function() {

			var tr_id = parseInt( $(this).attr('data-counter') );
			var tr_id_i = 0;
			var count = 0;
			var td_total = 0;
			var tr_string = '';
			
			//alert( $(this).find("td[class='amount']").text() );
			
			$("#sellorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('sellorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('sellorders_marked');
				}
			});
			
			count = prettyFloat(count, 8);
			//var price = parseFloat( $(this).find("td[class='price']").text() );
			var price = $(this).find("td[class='price']").text();
			//price = prettyFloat(price, 8);
			$('#b_amount').val(count);
			$('#b_price').val( price);
			$('#s_price').val(price);
			
		updateDataSell();
		updateDataBuy();
	});
	$("#buyorders > tbody ").on('click', 'tr', function() {

			var tr_id = parseInt( $(this).attr('data-counter') );
			var tr_id_i = 0;
			var count = 0;
			var td_total = 0;
			var tr_string = '';
			
			//alert( $(this).find("td[class='amount']").text() );
			
			$("#buyorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('buyorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('buyorders_marked');
				}
			});
			
			count = prettyFloat(count, 8);
			
			//var price = parseFloat( $(this).find("td[class='price']").text() );
			var price = $(this).find("td[class='price']").text();
			//price = prettyFloat(price, 8);

			$('#s_amount').val(count);
			$('#s_price').val(price);
			$('#b_price').val(price);
			
		updateDataSell();
		updateDataBuy();
	});
	
});

function myRound(value, places) {
    var multiplier = Math.pow(10, places);

    return (Math.round(value * multiplier) / multiplier);
}

function use_price(type, price, total_amount, el){
	return;
<?php
/*
	@if ( Auth::guest() )
	@else
		// var pre = 'b_';
		// if(type==2) pre = 's_';
		// $('#'+pre+'price').val(price.toFixed(8));
		// $('#'+pre+'amount').val(total_amount.toFixed(8));
			var tr_id = parseInt( $(el).attr('data-counter') );
			var tr_id_i = 0;
			var count = 0;
			var td_total = 0;
			var tr_string = '';

			
		if(type==1){	//buy

			$("#sellorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('sellorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('sellorders_marked');
				}
			});
			//alert(tr_string + ' || tr_id: ' +tr_id);
			//$('#b_amount').val(total_amount.toFixed(8));
			$('#b_amount').val(count.toFixed(8));
			$('#b_price').val(price.toFixed(8));
			$('#s_price').val(price.toFixed(8));
			
		}else if(type==2){		//sell

			$("#buyorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('buyorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('buyorders_marked');
				}
			});
			
			//alert ( $("table.buyorders").html() );
			//alert(tr_string + ' || tr_id: ' +tr_id);
			
			$('#s_amount').val(count.toFixed(8));
			$('#s_price').val(price.toFixed(8));
			$('#b_price').val(price.toFixed(8));
		}else{}
		updateDataSell();
		updateDataBuy();
		
		//alert( $(el).attr('data-counter') + ' id->'+$(el).attr('id') + ' type->' +type);

		
	@endif
*/
?>
}

var stack_bottomleft = {"dir1": "up", "dir2": "right", "push": "top", "spacing1": 10, "spacing2": 10};	
// type: "notice" - Type of the notice. "notice", "info", "success", or "error".
function showMessageSingle(message,type){
     var opts = {
        title: type,
        text: message,
        addclass: "stack-bottomleft",
        buttons: {
            closer_hover: false
        },
        stack: stack_bottomleft,
        animate_speed: 'fast'
    };
    switch (type) {
    case 'error':
        opts.type = "error";
        break;
    case 'info':
        opts.type = "info";
        break;
    case 'success':
        opts.type = "success";
        break;
    }
    new PNotify(opts);


}

function showMessage(messages,type){
    //var html;
    var message = '';
    for (i = 0; i < messages.length; i++) { 
        message = messages[i];
        
        //html='<div id="notifyjs-'+i+'" class="notifyjs-wrapper notifyjs-hidable '+type+'"><div class="notifyjs-container"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-success"><span data-notify-text="">'+message+'</span></div></div></div>';
        //$('.notifyjs-corner').append(html);
    
        showMessageSingle(message, type);
    }
    
}



var chart;
var defaultLoad=false;
var chartData=[];
AmCharts.loadJSON=function(timeSpan,buttonClick){
    chartData=[];
    createStockChart();
    var timeSpan_ = '6 hour';

    //console.log('timeSpan:',timeSpan);
    switch(timeSpan){
        case "1DD":
            timeSpan_ = '1 day';
            break;
        case "3DD":
            timeSpan_ = '3 day';
            break;
        case "7DD":
            timeSpan_ = '7 day';
            break;
        case "MAX":
            timeSpan_ = 'MAX';
            break;
        default:
            timeSpan_ = '6 hour';
    }
    console.log('timeSpan_:',timeSpan_);
    $('.loading').show();
    $.ajax({
        url:"<?php echo action('HomeController@getChart')?>",
        type:'post',
        dataType:'json',
        data: {Ajax:1,timeSpan:timeSpan_,market_id:<?php echo $market_id ?>},
        cache:false,
        async:true,
        success:function(rows){ 
            //console.log('rows: ',rows);       
            $('.loading').hide();
            for(var i=0; i<rows.length; i++){
                //console.log('chartData '+i+': ',rows[i]);
                var open=parseFloat(rows[i]['open']).toFixed(8);
                var close=parseFloat(rows[i]['close']).toFixed(8);
                var high=parseFloat(rows[i]['high']).toFixed(8);
                var low=parseFloat(rows[i]['low']).toFixed(8);              
                //console.log('rows '+i+' date: '+rows[i]['date']+' open: '+open+' close: '+close+' high: '+high+' low: '+low);
                chartData.push({date:rows[i]['date'],open:open,close:close,high:high,low:low,exchange_volume:rows[i]['exchange_volume']});
            }
            //console.log('chartData: ',chartData);
            //date=rows[rows.length-1]['date'];
            //date=new Date(date.replace(" ","T")+'Z');
            //var localOffset=date.getTimezoneOffset()*60000;
            //date.setTime(date.getTime()+ 600000+ localOffset);
            //chartData.push({date:date,open:rows[rows.length-1]['close'],close:rows[rows.length-1]['close'],high:rows[rows.length-1]['close'],low:rows[rows.length-1]['close'],exchange_volume:0});
            chart.dataProvider=chartData;
            chart.validateNow();
            if(buttonClick===false){
                //$('input[value="6 hours"]').click();
                $('input[value="1 week"]').click();
            }else{
                //$('input[value="MAX"]').removeClass('amChartsButtonSelected').addClass('amChartsButton');
                $('.amChartsPeriodSelector input[type=button]').removeClass('amChartsButtonSelected').addClass('amChartsButton');
                if(timeSpan=='6hh'){
                    $('input[value="6 hours"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
                }else if(timeSpan=='1DD'){
                    $('input[value="24 hours"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
                }else if(timeSpan=='3DD'){
                    $('input[value="3 days"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
                }else if(timeSpan=='7DD'){
                    $('input[value="1 week"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
                }else{
                    $('input[value="MAX"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
                }
            }

        }
    });
};
function buttonClickHandler(data){
    console.log('buttonClickHandler:',data);
    if(defaultLoad===true){
        if(typeof data.count!=='undefined'){AmCharts.loadJSON(data.count+ data.predefinedPeriod,true);
        }else{
            AmCharts.loadJSON(data.predefinedPeriod,true);
        }
    }else{
        defaultLoad=true;
    }
}
AmCharts.ready(function(){AmCharts.loadJSON('7DD',false);
    createStockChart();
});
function createStockChart(){
    chart=new AmCharts.AmStockChart();
    chart.pathToImages="/assets/js/amcharts/images/";
    var categoryAxesSettings=new AmCharts.CategoryAxesSettings();
    categoryAxesSettings.minPeriod="10mm";
    categoryAxesSettings.groupToPeriods=["10mm","30mm","hh","3hh","6hh","12hh","DD"];
    chart.categoryAxesSettings=categoryAxesSettings;
    chart.dataDateFormat="YYYY-MM-DD JJ:NN";
    var dataSet=new AmCharts.DataSet();
    dataSet.color="#7f8da9";
    dataSet.fieldMappings=[
        {fromField:"open",toField:"open"},
        {fromField:"close",toField:"close"},
        {fromField:"high",toField:"high"},
        {fromField:"low",toField:"low"},
        {fromField:"exchange_volume",toField:"exchange_volume"}
    ];
    dataSet.dataProvider=chartData;
    dataSet.categoryField="date";
    chart.dataSets=[dataSet];
    var stockPanel1=new AmCharts.StockPanel();
    stockPanel1.showCategoryAxis=false;
    stockPanel1.title="Price";
    stockPanel1.percentHeight=70;
    stockPanel1.numberFormatter={precision:8,decimalSeparator:'.',thousandsSeparator:','};
    var graph1=new AmCharts.StockGraph();
    graph1.valueField="value";
    graph1.type="candlestick";
    graph1.openField="open";
    graph1.closeField="close";
    graph1.highField="high";
    graph1.lowField="low";
    graph1.valueField="close";
    graph1.lineColor="#6bbf46";
    graph1.fillColors="#6bbf46";
    graph1.negativeLineColor="#db4c3c";//"#db4c3c";
    graph1.negativeFillColors="#db4c3c";//"#db4c3c";
    graph1.fillAlphas=1;
    graph1.balloonText="open:<b>[[open]]</b><br>close:<b>[[close]]</b><br>low:<b>[[low]]</b><br>high:<b>[[high]]</b>";
    graph1.useDataSetColors=false;
    stockPanel1.addStockGraph(graph1);
    var stockLegend1=new AmCharts.StockLegend();
    stockLegend1.valueTextRegular=" ";
    stockLegend1.markerType="none";
    stockPanel1.stockLegend=stockLegend1;
    var stockPanel2=new AmCharts.StockPanel();
    stockPanel2.title="Volume";
    stockPanel2.percentHeight=30;
    stockPanel2.numberFormatter={precision:3,decimalSeparator:'.',thousandsSeparator:','};
    var graph2=new AmCharts.StockGraph();
    graph2.valueField="exchange_volume";
    graph2.type="column";
    graph2.cornerRadiusTop=2;
    graph2.fillAlphas=1;
    graph2.periodValue="Sum";
    stockPanel2.addStockGraph(graph2);
    var stockLegend2=new AmCharts.StockLegend();
    stockLegend2.valueTextRegular=" ";
    stockLegend2.markerType="none";
    stockPanel2.stockLegend=stockLegend2;
    chart.panels=[stockPanel1,stockPanel2];
    var cursorSettings=new AmCharts.ChartCursorSettings();
    cursorSettings.valueBalloonsEnabled=true;
    cursorSettings.fullWidth=true;
    cursorSettings.cursorAlpha=0.1;
    chart.chartCursorSettings=cursorSettings;
    var periodSelector=new AmCharts.PeriodSelector();
    periodSelector.position="top";
    periodSelector.dateFormat="YYYY-MM-DD JJ:NN";
    periodSelector.inputFieldWidth=150;
    periodSelector.inputFieldsEnabled=false;
    periodSelector.hideOutOfScopePeriods=false;
    periodSelector.periods=[
        {period:"hh",count:6,label:"6 hours",selected:true},
        {period:"DD",count:1,label:"24 hours"},
        {period:"DD",count:3,label:"3 days"},
        {period:"DD",count:7,label:"1 week"},
        {period:"MAX",label:"MAX"}
    ];
    
    // let's add a listener to remove the loading indicator when the chart is
    // done loading
    chart.addListener("rendered", function (event) {
        $("#chartLoadingBox").text('');
    });
    
    periodSelector.addListener('changed',function(period){buttonClickHandler(period);});
    chart.periodSelector=periodSelector;
    var panelsSettings=new AmCharts.PanelsSettings();
    panelsSettings.usePrefixes=false;
    chart.panelsSettings=panelsSettings;
    var valueAxis=new AmCharts.ValueAxis();
    valueAxis.precision=8;
    chart.valueAxis=valueAxis;
    chart.chartScrollbarSettings.enabled=false;
    chart.write('chartdiv');
}
</script>
<!-- <div class="container-fluid">
        <button onclick="testCal()">Test</button>
    </div>  -->
{{ HTML::script('https://cdn.socket.io/socket.io-1.2.0.js') }}
<script type="text/javascript" charset="utf-8">	

(function ( $ ) {
$.fn.addClassDelayRemoveClass = function( options ) {
    // This is the easiest way to have default options.
    var settings = $.extend({
        // These are the defaults.
        elemclass: "",
        delaysec: 1000
    }, options );
    // Greenify the collection based on the settings variable.
    /*return this.css({
        color: settings.color,
        backgroundColor: settings.backgroundColor
    });
    */   
     return $(this).addClass(settings.elemclass)
                       .delay(settings.delaysec)
                       .queue(function() {
                           $(this).removeClass(settings.elemclass);
                           $(this).dequeue();
                       });
    
    //$(this).addClass(settings.elemclass).delay(settings.delaysec).queue(function() {$(this).removeClass(settings.elemclass);$(this).dequeue();})
    
    };
}( jQuery ));

//function updateYourOrdersTable(type, market_id, order_id, total, amount, amount_real_trading, amount_real_trading_total){
function updateYourOrdersTable(type, market_id, order_id, amount, price){
    //type = sell/buy
    //update your order
    //if($('#yourorders_'+market_id+' #yourorder-'+order_id+' .amount')!==undefined){
    if($('#yourorders_'+market_id+' #yourorder-'+order_id).length)  {
        //console.log('update your order '+type+' init'); 
        var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+order_id+' .amount').html());
        var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+order_id+' .total').html());

        y_amount_old = parseFloat(y_amount_old);
        y_total_old = parseFloat(y_total_old);
        
        amount = parseFloat(amount);
        price = parseFloat(price);
        var total = amount*price;
        
        var y_new_amount = (y_amount_old-amount).toFixed(8);
        var y_new_total = (y_total_old-total).toFixed(8);
        
        console.log('y_amount_old: ' + y_amount_old + ', y_total_old '+ y_total_old +', y_new_amount '+y_new_amount+', y_new_total '+y_new_total);
        //var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount_real_trading)).toFixed(8);
        if(y_new_amount<='0.00000000' || y_new_amount<=0.00000000 || y_new_amount <= 0 || isNaN(y_new_amount)){
            $('#yourorders_'+market_id+' #yourorder-'+order_id).remove();
            console.log('icee 4: ' + y_new_amount);
        }else{
            $('#yourorders_'+market_id+' #yourorder-'+order_id+' .amount').html(y_new_amount);
            $('#yourorders_'+market_id+' #yourorder-'+order_id+' .total').html(y_new_total);
            //$('#yourorders_'+market_id+' #yourorder-'+order_id).addClassDelayRemoveClass({'elemclass': 'blue'});
        }
        //console.log('update your order '+type+' end'); 
    }else{
        console.log('error '+type+' yourorder updating');
    }
}

        $(function(){
            window.socket = {};
            <?php /* <?php echo url('/', $parameters = array(), $secure = null);?> */?>
            //socket = io.connect('https://sweedx.com:8090/',{secure: true});
            
            <?php
            /*
			  // This code works!
			  var socket = new WebSocket("ws://localhost:8080");
			  // This code doesn't work and yells "cross origin!  Not allowed!"
			  var socket = io("ws://localhost:8080");
   		    */
            ?>
            socket = io.connect('<?php echo url('/', $parameters = array(), $secure = null);?>:8090/',{secure: true});



            <?php /* Node server is not running*/ ?>
            socket.on('error', function(exception) {
                showMessageSingle('Socket Error 1 - Live prices not available. <br />Socket is not connected!', 'error');
            })
            socket.of('connected', function(exception) {
                showMessageSingle('Socket Error 2 - Live prices not available. <br />Socket is not connected!', 'error');
            })
            
            socket.on('connect', function(){
                
                socket.emit('create_room', '<?php echo Session::get('market_id')?>');
                

                //socket.emit('storeClientInfo', { market_id:"{{{Session::get('market_id')}}}" });  
                
                /*
                var socket_market_id;
                socket.on('init_market', function(data){
                    
                    socket_market_id = data.market_id;
                    console.log('socket market id1:'+socket_market_id);             
                });
                console.log('socket market id2:'+socket_market_id);             
                */
            });
            
        
            /*
            if(socket.socket.connected == false){
                showMessageSingle('Warning - Live prices not available. <br />Socket is not connected!', 'error');
            }
            */

            
            /*
            socket.on('disconnect', function () {
                showMessageSingle('Warning - Live prices not available. <br />Socket is not connected!', 'error');
            });
            */

            socket.on( 'doTradeUser', function( data ) {
                

                
                console.log('doTradeUser '+data);

                //console.log('data socket:',data);
                var market_id=data.market_id;

                    //Update balance
                if(data.data_price !== undefined){
                    //console.log('update user balance');
                    $('#cur_to').html(data.data_price.balance_coinsecond);
                    $('#cur_from').html(data.data_price.balance_coinmain);
                }
                    
                if( data.user_orders !== undefined ){
                    $.each(data.user_orders, function(key, value){
                        console.log(data);
                        
                        
                        if(value['order_b']!== undefined){
                            console.log(value['order_b']['action']);

                            var amount = parseFloat(value['order_b']['amount']).toFixed(8);
                            var total = parseFloat(value['order_b']['total']).toFixed(8);

                            var price = parseFloat(value['order_b']['price']).toFixed(8);
                            var class_price = price.replace(".","-");
                            var class_price = class_price.replace(",","-");
                            
                            switch(value['order_b']['action']){
                                case "insert":
                                    console.log('insert private buy order, market_id:' +market_id+', yourorder: '+ value['order_b']['id']);
                                    //insert your buy order, your current order list
                                    var your_order='<tr id="yourorder-'+value['order_b']['id'] +'" class="order price-'+class_price+'"><td><b style="color:green">Buy</b></td> <td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td><td><span>'+value['order_b']['created_at']['date'] +'</span></td><td><a href="javascript:cancelOrder('+value['order_b']['id'] +');">Cancel</a></td></tr>';
                                    //$('#yourorders_'+market_id+' > table tr.header-tb').after(your_order);
                                    
                                    $('#yourorders_'+market_id+' > table > tbody > tr:first').before(your_order);
                                    $('#yourorders_'+market_id+' > table > tbody > tr#yourorder-'+value['order_b']['id']).addClassDelayRemoveClass({'elemclass': 'blue affected'});

                                break;
                            }
                        }
                        
                        //if ($element.parent().length) { alert('yes') }


                        if(value['order_s'] !== undefined){ 
                            console.log(value['order_s']['action']);
                            
                            var amount = parseFloat(value['order_s']['amount']).toFixed(8);
                            var total = parseFloat(value['order_s']['total']).toFixed(8);

                            var price = parseFloat(value['order_s']['price']).toFixed(8);
                            var class_price = price.replace(".","-");
                            var class_price = class_price.replace(",","-");
                            switch(value['order_s']['action']){
                                case "insert":
                                    console.log('insert private sell order, market_id:' +market_id+', yourorder: '+ value['order_s']['id']);
                                    //insert your sell order, your current order list
                                    var your_order='<tr id="yourorder-'+value['order_s']['id'] +'" class="order price-'+class_price+'"><td><b style="color:red">Sell</b></td> <td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td><td><span>'+value['order_s']['created_at']['date'] +'</span></td><td><a href="javascript:cancelOrder('+value['order_s']['id'] +');">Cancel</a></td></tr>';
                                    
                                    $('#yourorders_'+market_id+' > table > tbody > tr:first').before(your_order);
                                    $('#yourorders_'+market_id+' > table > tbody > tr#yourorder-'+value['order_s']['id']).addClassDelayRemoveClass({'elemclass': 'red affected'});
                                    
                                break;
                            }
                        }
                    });
                }
            });
            
            socket.on( 'doTradeHistory', function( data ) {
            
                console.log('doTradeHistory Socket '+ data);

                
                //update trade history
                    if(data !== undefined){
                        /*
                        var history_trade_reversed = data.history_trade;
                        history_trade_reversed = history_trade_reversed.reverse();
                        
                        console.log("message history_trade: "+key + ": " + value);
                        
                        

homes.sort(function(a,b) { return parseFloat(a.price) - parseFloat(b.price) } );
                        */
                        var data_history_trade = $.map(data, function(el) { return el; });
                        data_history_trade.sort(function(a, b) {
                            // Ascending: first age less than the previous
                            if(a.type == 'buy')
                                return a.price - b.price;
                            else
                                return b.price - a.price;
                        });

                        
                        console.log('doTradeHistory Socket - before  ');
                        var total = 0, market_id;
                        $.each(data_history_trade, function(key, value){
                            
                            market_id=value['market_id'];
                            console.log('doTradeHistory Socket - market id  '+ market_id);
                            //total = (parseFloat(value['price'])*parseFloat(value['amount'])).toFixed(8);
                            
                            //updateYourOrdersTable(value['type'], value['market_id'], value['id'], total, amount, total, amount);
                            updateYourOrdersTable(value['type'], value['market_id'], value['id'], value['amount'], value['price']);
                            
                            //console.log('history_trade',value);    
                            //console.log('history_trade id',value['id']);    
                            //console.log('history_trade init');
                            var trade_new = '<tr id="trade-'+value['id'] +'" class="order">';
                            trade_new += '<td><span>'+value['created_at']+'</span></td>';

                            //console.log('history_trade before total: ');
                            var total = parseFloat(value['price'])*parseFloat(value['amount']);
                            var amount = parseFloat(value['amount']).toFixed(8);
                            
                            
                            //Update total maincoin and amount secondcoin
                            if(value['type'] == 'sell'){
                                trade_new += '<td><span style="color:red; text-transform: capitalize;"><strong>'+value['type']+' <i class="icon-arrow-down icon-large" ></i></span></td>';           
                                var b_amount_all = parseFloat( $('#buyorders_amount_all_'+market_id).text()  );
                                b_amount_all = $('#buyorders_amount_all_'+market_id).text( b_amount_all - amount).fadeIn();
                            }else{
                                trade_new += '<td><span style="color:green; text-transform: capitalize;"><strong>'+value['type']+' <i class="icon-arrow-up icon-large" ></i></span></td>';
                                var s_total_all = parseFloat( $('#sellorders_total_all_'+market_id).text()  );
                                s_total_all = $('#sellorders_total_all_'+market_id).text( s_total_all - total).fadeIn();

                            }
                                
                                

                            //console.log('history_trade total: ',total);
                            //console.log('history_trade amount: ',amount);
                            trade_new += '<td>'+parseFloat(value['price']).toFixed(8)+'</td>';
                            trade_new += '<td>'+amount+'</td>';
                            trade_new += '<td>'+total.toFixed(8)+'</td>';
                            trade_new+='</tr>'; 
                            //console.log('history_trade trade_new: ',trade_new);                   
                            $('#trade_histories_'+market_id+' > table > tbody > tr:first').before(trade_new);
                            $('#trade_histories_'+market_id+' > table > tbody > tr#trade-'+value['id']).addClassDelayRemoveClass({'elemclass': 'new'});
                            //$('#trade_histories_'+market_id+' > table tr.header-tb').after(trade_new);
                            
                        });
                    }
                    
            });
            
            socket.on( 'doTrade', function( data ) {
                
                console.log('doTrade Socket '+ data);

                /*
                var socket_market_id;
                socket.on('init_market', function(data){
                    
                    socket_market_id = data.market_id;
                    console.log('socket market id1:'+socket_market_id);             
                });
                console.log('socket market id2:'+socket_market_id);             
                
                
                */
                var market_id=data.market_id;
                
                //update order buy                 
                //console.log('data message_socket: ',data.message_socket);
                
                $.each(data.message_socket, function(key, value){
                    //console.log('obj aaa: ',key);
                    console.log("message socket data: "+key + ": " + value);
                
                
                    if(value['order_b']!== undefined){
                        //console.log('order_b',value['order_b']);                      
                        var amount = parseFloat(value['order_b']['amount']).toFixed(8);
                        var amount_real = parseFloat(value['order_b']['amount']).toFixed(8);
                        
                        var total = parseFloat(value['order_b']['total']).toFixed(8);
                        var total_real = parseFloat(value['order_b']['total_real']).toFixed(8);

                        var amount_real_trading = parseFloat(value['order_b']['amount_real_trading']).toFixed(8);
                        var amount_real_trading_total = parseFloat(value['order_b']['amount_real_trading_total']).toFixed(8);
                        
                        var price = parseFloat(value['order_b']['price']).toFixed(8);
                        var class_price = price.replace(".","-");
                        var class_price = class_price.replace(",","-");

                        
                        //console.log('class_price',class_price);
                        console.log('action',value['order_b']['action']); 
                        switch(value['order_b']['action']){
                            case "insert":   
                                //console.log('insert orders_buy_',$('#orders_buy_'+market_id+' .price-'+class_price));
                                //if($('#orders_buy_'+market_id+' .price-'+class_price).html()!==undefined){
                                if($('#orders_buy_'+market_id+' .price-'+class_price).length){
                                    //console.log('Update buy:');
                                    var amount_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html());
                                    var total_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .total').html());

                                    $('#orders_buy_'+market_id+' .price-'+class_price).show();
                                    $('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html((parseFloat(amount_old)+parseFloat(amount)).toFixed(8));
                                    $('#orders_buy_'+market_id+' .price-'+class_price+' .total').html((parseFloat(total_old)+parseFloat(total)).toFixed(8));
                                    $('#orders_buy_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'green green1'});
                                    $('#orders_buy_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+(parseFloat(amount_old)+parseFloat(amount)).toFixed(8)+')');
                                }else{
                                    //console.log('Insert buy');    New buy order
                                    var buy_order='<tr id="order-'+value['order_b']['id'] +'" class="order price-'+class_price+' green2" onclick="use_price(2,'+value['order_b']['price'] +','+amount+')" data-sort="'+price+'" data-counter=""><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td></tr>';
                                    if($('#orders_buy_'+market_id+' > table > tbody tr.order').length){
                                        var i_d=0;
                                        $( '#orders_buy_'+market_id+' tr.order').each(function( index ) {
                                            var value = $(this).val(); 
                                            var price_compare = parseFloat($(this).attr('data-sort'));                  
                                            if(price>price_compare){
                                                i_d=1;
                                                $(this).before(buy_order);
                                                return false;
                                            }
                                        });
                                        if(i_d==0){
                                            //console.log( "add to the end");  
                                            $('#orders_buy_'+market_id+' > table > tbody tr:last-child').after(buy_order);
                                        }
                                    }else{
                                        $('#orders_buy_'+market_id+' > table > tbody').html(buy_order);
                                        
                                        
                                    }
                                    $('#order-'+value['order_b']['id']).addClassDelayRemoveClass({'elemclass': 'green'});   //Add green bg for new order, delay and remove class
                                }

                                //console.log('insert buy end'); 
                                break;
                            case "update":  
                                //console.log('update buy init');
                                //Update existing order, cancel or delete them
                                
                                var amount_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html());
                                var total_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .total').html());
                                
                                var new_amount = (parseFloat(amount_old)-parseFloat(amount_real_trading)).toFixed(8);
                                var new_total = (parseFloat(total_old)-parseFloat(amount_real_trading_total)).toFixed(8);

                                
                                var cancel = false;
                                if(value['order_b']['type_sub'] !== undefined){
                                    if (value['order_b']['type_sub'] == 'cancel')
                                        cancel = true;
                                }
                                    
                                if(new_amount<='0.00000000' || new_amount<=0.00000000 || new_amount <= 0 || isNaN(new_amount)){
                                    //$('#orders_buy_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red', 'delaysec': 1000}).fadeOut();
                                    
                                    if(cancel == true)
                                        $('#orders_buy_'+market_id+' .price-'+class_price).fadeOut();
                                    else
                                        $('#orders_buy_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red', 'delaysec': 1000}).fadeOut();


                                    console.log('icee doSell: ' + new_amount);
                                    console.log('#orders_buy_'+market_id+' .price-'+class_price);
                                }else{
                                    $('#orders_buy_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+new_amount+')');
                                    $('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html(new_amount);
                                    $('#orders_buy_'+market_id+' .price-'+class_price+' .total').html(new_total);
                                    
                                    if(cancel == false)
                                        $('#orders_buy_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red red1', 'delaysec': 1000});
                                    else
                                        $('#orders_buy_'+market_id+' .price-'+class_price).hide().fadeIn();
                                }
                                //console.log('update buy end');

                                //update your order
                                
                                //updateYourOrdersTable('buy', market_id, value['order_b']['id'], total, amount, amount_real_trading, amount_real_trading_total);
                                /*
                                if($('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .amount')!==undefined){
                                    console.log('update your buy order init');
                                    var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .amount').html());
                                    var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .total').html());
                                    var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount)).toFixed(8);
                                    if(y_new_amount=='0.00000000' || y_new_amount==0.00000000){
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']).remove();
                                        console.log('icee 2: ' + new_amount);
                                    }else{
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .amount').html(y_new_amount);
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .total').html((parseFloat(y_total_old)-parseFloat(total)).toFixed(8));
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']).addClassDelayRemoveClass({'elemclass': 'blue', 'delaysec': 1000});
                                    }
                                    //console.log('update your buy order end');
                                }else{
                                    console.log('error buy yourorder updating');
                                }
                                */
                                break;
                            case "delete":      //when someone sells (buyorders) and the row is deleted
                                //$('#orders_buy_'+market_id+' .price-'+class_price).remove();
                                    
                                    //updateYourOrdersTable('buy', market_id, value['order_b']['id'], total, amount, amount_real_trading, amount_real_trading_total);
                                    
                                    var amount_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html());
                                    var total_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .total').html());
                                    
                                    var new_amount = (parseFloat(amount_old)-parseFloat(amount_real_trading)).toFixed(8);
                                    var new_total = (parseFloat(total_old)-parseFloat(amount_real_trading_total)).toFixed(8);
                                    

                                    if(new_amount<='0.00000000' || new_amount<=0.00000000 || new_amount <= 0 || isNaN(new_amount) ){
                                        $('#orders_buy_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red', 'delaysec': 1000}).fadeOut();
                                    }else{
                                        $('#orders_buy_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+new_amount+')');
                                        $('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html(new_amount);
                                        $('#orders_buy_'+market_id+' .price-'+class_price+' .total').html(new_total);
                                        $('#orders_buy_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red red1'});
                                    }
                                
                                console.log('icee 3 delete 1 - buyorder');
                                //console.log('|-> price : ' + price + ' amount: ' + amount + ' amount_real: ' + amount_real + ' a_sell: ' + value['order_b']['a_sell'] + ' a_type: ' + value['order_b']['a_type']);
                                
                                //console.log('|-> total : '+ total + ' || amount : ' +amount);
                                //console.log('Delete '+'#orders_buy_'+market_id+' .price-'+class_price);
                                //$('#orders_buy_'+market_id+' #order-'+value['order_b']['id']).remove();
                                break;

                                
                        }
                        //alert (amount_real_trading);
                    }
                    //update order sell
                    if(value['order_s'] !== undefined){ 
                        var amount = parseFloat(value['order_s']['amount']).toFixed(8);
                        var amount_real = parseFloat(value['order_s']['amount_real']).toFixed(8);
                        
                        var total = parseFloat(value['order_s']['total']).toFixed(8);    
                        var total_real = parseFloat(value['order_s']['total_real']).toFixed(8);    
                        
                        var amount_real_trading = parseFloat(value['order_s']['amount_real_trading']).toFixed(8);
                        var amount_real_trading_total = parseFloat(value['order_s']['amount_real_trading_total']).toFixed(8);
                        
                        var price = parseFloat(value['order_s']['price']).toFixed(8);
                        var class_price = price.replace(".","-");
                        var class_price = class_price.replace(",","-");
                        
                        
                        //console.log('order_s',value['order_s']);  
                        //console.log('action',value['order_s']['action']);  
                        //console.log('class_price',class_price);           
                        console.log('action',value['order_s']['action']); 
                        switch(value['order_s']['action']){                         
                            case "insert":
                                //console.log('insert orders_sell_',$('#orders_sell_'+market_id+' .price-'+class_price));
                                //if($('#orders_sell_'+market_id+' .price-'+class_price).html()!==undefined){
                                if($('#orders_sell_'+market_id+' .price-'+class_price).length){
                                    //console.log('Update sell:');
                                    var amount_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html());
                                    var total_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .total').html());
                                    
                                    $('#orders_sell_'+market_id+' .price-'+class_price).show();
                                    $('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html((parseFloat(amount_old)+parseFloat(amount)).toFixed(8));
                                    $('#orders_sell_'+market_id+' .price-'+class_price+' .total').html((parseFloat(total_old)+parseFloat(total)).toFixed(8));
                                    $('#orders_sell_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'green green3'});
                                    $('#orders_sell_'+market_id+' .price-'+class_price).attr('onclick','use_price(1,'+price +','+(parseFloat(amount_old)+parseFloat(amount)).toFixed(8)+')');
                                }else{
                                    //console.log('Insert sell');   //new sell order
                                    var orders_sell='<tr id="order-'+value['order_s']['id'] +'" class="order price-'+class_price+' green4" onclick="use_price(1,'+value['order_s']['price'] +','+amount+')" data-sort="'+price+'" data-counter=""><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td></tr>';  
                                    //$('#orders_sell_'+market_id+' > table tr.header-tb').after(orders_sell);
                                    if($('#orders_sell_'+market_id+' > table > tbody tr.order').length){
                                        var i_d=0;
                                        $( '#orders_sell_'+market_id+' tr.order').each(function( index ) {
                                            var value = $(this).val(); 
                                            var price_compare = parseFloat($(this).attr('data-sort'));                  
                                            if(price<price_compare){
                                                i_d=1;
                                                $(this).before(orders_sell);
                                                return false;
                                            }     
                                        });
                                        if(i_d==0){
                                            //console.log( "add to the end");  
                                            $('#orders_sell_'+market_id+' > table > tbody tr:last-child').after(orders_sell);
                                        }
                                    }else{
                                        $('#orders_sell_'+market_id+' > table > tbody').html(orders_sell);
                                    }
                                    //$('#order-'+value['order_s']['id']).show();
                                    $('#order-'+value['order_s']['id']).addClassDelayRemoveClass({'elemclass': 'green'});   //Add green bg for new order, delay and remove class
                                }
                                if ( $('#yourorders_'+market_id+' > table > tbody > tr#yourorder-'+value['order_s']['id']).parent().length ) { 
                                    $('#yourorders_'+market_id+' > table > tbody > tr#yourorder-'+value['order_s']['id']).remove();
                                }
                                //console.log('insert sell init'); 
                                break;
                            case "update": 
                                //console.log('update sell init');
                                
                                var amount_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html());
                                var total_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .total').html());
                                
                                var new_amount = (parseFloat(amount_old)-parseFloat(amount_real_trading)).toFixed(8);
                                var new_total = (parseFloat(total_old)-parseFloat(amount_real_trading_total)).toFixed(8);

                                    var cancel = false;
                                    if(value['order_s']['type_sub'] !== undefined){
                                        if (value['order_s']['type_sub'] == 'cancel')
                                            cancel = true;
                                    }               
                                
                                 
                                if(new_amount<='0.00000000' || new_amount<=0.00000000 || new_amount <= 0 || isNaN(new_amount) ){
                                    
                                    if(cancel == true)
                                        $('#orders_sell_'+market_id+' .price-'+class_price).fadeOut();
                                    else
                                        $('#orders_sell_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'blue', 'delaysec': 1000}).fadeOut();


                                    console.log('icee doBuy: ' + new_amount);
                                    console.log('#orders_sell_'+market_id+' .price-'+class_price);
                                }else{
                                    $('#orders_sell_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+new_amount+')');
                                    $('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html(new_amount);
                                    $('#orders_sell_'+market_id+' .price-'+class_price+' .total').html(new_total);
                                    
                                    if(cancel == false)
                                        $('#orders_sell_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'blue blue1'});
                                    else
                                        $('#orders_sell_'+market_id+' .price-'+class_price).hide().fadeIn();
                                        
                                }
                                //console.log('update sell end'); 
                                //updateYourOrdersTable('sell', market_id, value['order_s']['id'], total, amount, amount_real_trading, amount_real_trading_total);
                                /*
                                
                                //update your order
                                if($('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .amount')!==undefined){
                                    console.log('update your order sell init'); 
                                    var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .amount').html());
                                    var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .total').html());

                                    var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount)).toFixed(8);
                                    if(y_new_amount=='0.00000000' || y_new_amount==0.00000000 || y_new_amount == 0 || isNaN(new_amount)){
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']).remove();
                                        console.log('icee 4: ' + y_new_amount);
                                    }else{
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .amount').html(y_new_amount);
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .total').html((y_total_old-total).toFixed(8));
                                        $('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']).addClassDelayRemoveClass({'elemclass': 'blue'});
                                    }
                                    //console.log('update your order sell end'); 
                                }else{
                                    console.log('error sell yourorder updating');
                                }
                                */                              
                                break;
                            case "delete":
                                //when someone buys (sellorders) and the row is deleted
                                //$('#orders_sell_'+market_id+' .price-'+class_price).remove();
                                
                                    //updateYourOrdersTable('sell', market_id, value['order_s']['id'], total, amount, amount_real_trading, amount_real_trading_total);
                                    
                                    var amount_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html());
                                    var total_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .total').html());
                                    
                                    var new_amount = (parseFloat(amount_old)-parseFloat(amount_real_trading)).toFixed(8);
                                    var new_total = (parseFloat(total_old)-parseFloat(amount_real_trading_total)).toFixed(8);
                                    
                                    if(new_amount<='0.00000000' || new_amount<=0.00000000 || new_amount <= 0 || isNaN(new_amount) ){
                                        //$('#orders_sell_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'blue', 'delaysec': 1000}).fadeOut();
                                        //$('#orders_sell_'+market_id+' #order-'+value['order_s']['id']).addClassDelayRemoveClass({'elemclass': 'blue', 'delaysec': 1000}).fadeOut();
                                        $('#orders_sell_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'blue', 'delaysec': 1000}).fadeOut();

                                    }else{
                                        $('#orders_sell_'+market_id+' .price-'+class_price).attr('onclick','use_price(1,'+price +','+new_amount+')');
                                        $('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html(new_amount);
                                        $('#orders_sell_'+market_id+' .price-'+class_price+' .total').html(new_total);
                                        //$('#orders_sell_'+market_id+' .price-'+class_price).addClass("blue").delay(10000).queue(function() {$(this).removeClass("blue");$(this).dequeue();}); 
                                        $('#orders_sell_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'blue'}); <?php /*matching buy "button" order*/?>
                                    }
                                    
                                console.log('icee 5 delete 2 - sellorder: ');
                                //console.log('|-> price : ' + price + ' amount: ' + amount + ' amount_real: ' + amount_real + ' a_buy: ' + value['order_s']['a_buy'] + ' a_type ' + value['order_s']['a_type']);
                                //console.log('Delete '+'#orders_sell_'+market_id+' .price-'+class_price);
                                //$('#orders_sell_'+market_id+' #order-'+value['order_s']['id']).remove();
                                break;
                        }
                        //alert (amount_real_trading);
                    }
                    
                });                 
                
                
                
                    
                    
                //update % change price
                //console.log('change_price init: ',data.change_price);
                if(data.change_price !== undefined){
                    //console.log('change init: ',data.change_price.change);
                    var change=parseFloat(data.change_price.change);
                    //console.log('curr_price: ',parseFloat(data.change_price.curr_price).toFixed(8));
                    $('#spanPrice-'+market_id).html(parseFloat(data.change_price.curr_price).toFixed(8));
                    $('#spanPrice-'+market_id).attr('yesterdayPrice',parseFloat(data.change_price.pre_price).toFixed(8));

                    $('#volume-'+market_id).attr('data-original-title', (parseFloat(data.data_price.get_prices.volume).toFixed(8)) );
                    //console.log('change: ',change);
                    //console.log('change 1: ',data.change_price.change);
                    if(change>=0){  
                        //console.log('Up ');                       
                        $('#spanChange-'+market_id).removeClass('up down').addClass('up');
                        $('#spanChange-'+market_id).html('+'+data.change_price.change+'%');
                        //console.log('Up 1a ');   
                    }else{
                        //console.log('Down ');                          
                        $('#spanChange-'+market_id).removeClass('up down').addClass('down');
                        $('#spanChange-'+market_id).html(''+data.change_price.change+'%');
                        //console.log('Down a');
                    }                       
                }
                //update block price
                if(data.data_price !== undefined){
                    //console.log('data_price',data.data_price);
                    if(data.data_price.latest_price!==undefined){
                        //Set High,Low and Volume for viewed MarketID coin
                        
                        var old_lastprice = parseFloat( $('#spanLastPrice-'+market_id).html() ).toFixed(8);
                        //var old_lastprice = $('#spanLastPrice-'+market_id).html();
                        var new_lastprice = parseFloat(data.data_price.latest_price).toFixed(8);
                        
                        console.log("if(new_lastprice<old_lastprice) "+ new_lastprice+'<'+old_lastprice );
                        if(new_lastprice<old_lastprice){
                            $('#lastprice-'+market_id).addClass('red');
                            
                                //Set High,Low and Volume for index MarketID coin   
                            if( $('#mainLastPrice-'+market_id).length )
                                $('#mainCoin-'+market_id).addClassDelayRemoveClass({'elemclass': 'red'});
                        }else{ 
                            $('#lastprice-'+market_id).addClass('blue');
                            
                                //Set High,Low and Volume for index MarketID coin   
                            if( $('#mainLastPrice-'+market_id).length )
                                $('#mainCoin-'+market_id).addClassDelayRemoveClass({'elemclass': 'blue'});
                        }
                        $('#spanLastPrice-'+market_id).html(new_lastprice);
                        
                        if( $('#mainLastPrice-'+market_id).length )
                            $('#mainLastPrice-'+market_id).html(new_lastprice);
                        
                        //Set High,Low and Volume for index MarketID coin
                    }                       
                    if(data.data_price.get_prices!==undefined){
                            //Set High,Low and Volume for viewed MarketID coin
                        $('#spanHighPrice-'+market_id).html(parseFloat(data.data_price.get_prices.max).toFixed(8));
                        $('#spanLowPrice-'+market_id).html(parseFloat(data.data_price.get_prices.min).toFixed(8));
                        $('#spanVolume-'+market_id).html(parseFloat(data.data_price.get_prices.volume).toFixed(8));

                            //Set High,Low and Volume for index MarketID coin
                        if( $('#mainHighPrice-'+market_id).length )
                            $('#mainHighPrice-'+market_id).html(parseFloat(data.data_price.get_prices.max).toFixed(8));
                        if( $('#mainHighPrice-'+market_id).length )
                            $('#mainLowPrice-'+market_id).html(parseFloat(data.data_price.get_prices.min).toFixed(8));
                        if( $('#mainHighPrice-'+market_id).length )
                            $('#mainVolume-'+market_id).html(parseFloat(data.data_price.get_prices.volume).toFixed(8));
                        

                    }
                }

                setTimeout(function(){
                    $('table > tr').removeClass("new");
                    //$('table tr,li, div.box').removeClass("blue red green");                      
                    $('#s_message, #b_message').html('');
                },10000);
                
                
                //Set new data-counter attributes (for real time trading updates)
                var i = 1;
                $('table.sellorders > tbody > tr').each(function() {
                    $(this).attr('data-counter', i);
                    i++;
                });
                var i = 1;
                $('table.buyorders > tbody > tr').each(function() {
                    $(this).attr('data-counter', i);
                    i++;
                });
                
            });
            

        });




        function drawOrderDepthChart(){
              $('.loading').show();
             $.ajax({
                 url:"<?php echo action('OrderController@getOrderDepthChart')?>",
                 type:'post',
                 dataType:'json',
                     data: {Ajax:1,market_id:<?php echo $market_id ?>},
                     cache:false,
                     async:true,
                     success:function(rows){
                     //console.log('response: ',response);
                    //var rows = $.parseJSON(response); 
                     console.log('Row: ',rows);
                    $('.loading').hide();
                     var chartData = [];               
               
                    for (var j = rows['buy'].length - 1; j >= 0; j--) {
                    chartData.push({
                        price: parseFloat(parseFloat(rows['buy'][j]['price']).toFixed(8)),
                        bid_total: parseFloat(rows['buy'][j]['total'])
                    });
                   }  

                   for (var i = 0; i < rows['sell'].length; i++) {
                    chartData.push({
                        price: parseFloat(parseFloat(rows['sell'][i]['price']).toFixed(8)),
                        ask_total: parseFloat(rows['sell'][i]['total'])
                    });
                   }
                   //console.log('chartData: ',chartData);
                   var chart = AmCharts.makeChart("orderdepth", {
                     "type": "serial",
                     "theme": "light",
                     "usePrefixes": true,
                     /*"pathToImages": "amcharts/images/",*/
                     "dataProvider": chartData,
                     "valueAxes": [{
                         "id": "v1",
                         "axisColor": "#EEE",
                         "axisThickness": 1,
                         "gridAlpha": 0,
                         "axisAlpha": 1,
                         "position": "left",
                         "visible": true,
                         "unit": " {{{$coinsecond}}}",
                         "titleBold": false
                     }],
                     "graphs": [{
                         "id": "g1",
                         "valueAxis": "v1",
                         "lineColor": "#00ff00",
                         "lineThickness": 2,
                         "hideBulletsCount": 30,
                         "valueField": "bid_total",
                         "balloonText": "<b>[[value]]</b> {{{$coinsecond}}} to get to [[price]]",
                         "fillAlphas": 0.4
                     }, {
                         "id": "g2",
                         "valueAxis": "v1",
                         "lineColor": "#ff0000",
                         "lineThickness": 2,
                         "hideBulletsCount": 30,
                         "valueField": "ask_total",
                         "balloonText": "<b>[[value]]</b> {{{$coinsecond}}} to get to [[price]]",
                         "fillAlphas": 0.4
                     }],
                     "chartCursor": {
                         "cursorPosition": "mouse"
                     },
                     "categoryField": "price",
                     "categoryAxis": {
                         "axisColor": "#BBB",
                         "minorGridEnabled": true,
                         "position": "bottom",
                         "labelRotation": 45
                     }
                 });

                setTimeout(function() {
                    drawOrderDepthChart();
                }, 60000);
            }
        });
    }




//$('li.volume').tooltip('show');

$(function () { 
    $("[data-toggle='tooltip']").tooltip( { 'delay': { show: 100, hide: 100 } } ); 
});
</script>

<?php
//var_dump(   $queries = DB::getQueryLog() );

?>
@stop
