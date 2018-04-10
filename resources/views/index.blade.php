@extends('layouts.default')
	<?php // Set individual Market title ?>
	@if ($market_predefined) 
		@section('title')
			<?php echo Config::get('config_custom.company_name_domain') . ' - ' . $market_from . ' / ' . $market_to . ' ' . trans('texts.market') ?>
		@stop
		@section('description')
			<?php echo Config::get('config_custom.company_name_domain') . ' - '. Config::get('config_custom.company_slogan') ?>
		@stop
	@endif
	<?php
	/*
		//@section('title', 'This is an individual page title')
		//@section('description', 'This is a description')
		*/
	/*
	if(Auth::check()) {
	echo "<h4>Logged in</h4>";
	} else {
	echo "<h4>Not logged in</h4>";
	}
	*/
	?>
	
@section('content')
	<div class="row">
		<div id="market_place">
			@if(isset($show_all_markets) && $show_all_markets === true)
				<!-- #Startpage Markets -->
				<div class="startpage">
					@include('blocks.startmarkets')
				</div>
			@endif

			@if($market_predefined)
				<!-- #Specific/Predefined Markets -->
				<div class="marketspage">
					<div class="">
						@include('blocks.predefinedmarket')
					</div>
				</div>
			@endif

		</div>
	</div>
	{{ HTML::script('assets/js/jquery.tablesorter.js') }}
	{{ HTML::script('assets/js/jquery.tablesorter.widgets.js') }}
	{{ HTML::script('assets/js/jquery.tablesorter.widgets.columnSelector.js') }}
	<script type="text/javascript"></script>
	<!-- <div class="container-fluid">
		<button onclick="testCal()">Test</button>
		</div>  -->

{{ HTML::script('https://cdn.socket.io/socket.io-1.2.0.js') }} 
<?php
      /*
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js" ></script>
	  
	  {{ HTML::script('assets/websocket/socket.io.min.js') }}
	 */
	  ?>
	  
<script type="text/javascript" charset="utf-8">	


		$(function(){
			window.socket = {};
			<?php /* <?php echo url('/', $parameters = array(), $secure = null);
				//socket = io.connect('https://sweedx.com:8090/',{secure: true});
			*/?>

			
			<?php
			/*
			  // This code works!
			  var socket = new WebSocket("ws://localhost:8080");
			  var socket = new WebSocket('wss://sweedx.com:8090');
			  // This code doesn't work and yells "cross origin!  Not allowed!"
			  var socket = io("ws://localhost:8080");
   		    */
			?>
			//
			//var socket = io('wss://sweedx.com:8090');
			//socket = io.connect('<?php echo url('/', $parameters = array(), $secure = true);?>:8090/',{secure: true});
			socket = io.connect('{{URL::to("/")}}:8090/',{secure: true});

			//socket = io.connect('https://sweedx.com:8090/',{secure: true});
			
			//socket = io.connect('https://sweedx.com:8090/',{secure: true});
			//var socket = io.connect('https://sweedx.com:8090',{secure: true, port:8090});
			
			<?php /* Node server is not running*/ ?>
			socket.on('error', function(exception) {
				showMessageSingle('Socket Error 1 - Live prices is not available. <br />Socket is not connected!', 'error');
			})
			/*
			socket.of('connected', function(exception) {
				showMessageSingle('Socket Error 2 - Live prices not available. <br />Socket is not connected!', 'error');
			})
			*/
			
			socket.on('connect', function(){
				socket.emit('create_room', '<?php echo Session::get('market_id')?>');
			});
			
			socket.on('users_count', function(data){
				$('.client_count').text(data).addClassDelayRemoveClass({'elemclass': 'blue'});
				
			});		

			socket.on( 'userOrder', function( data ) {
				
				console.log('========userOrder '+data);

				//console.log('data socket:',data);
				var market_id=data.market_id;
				var market_name=data.market_name;

					//Update balance
				if(data.data_price !== undefined){
					//console.log('update user balance');
					//Change buy/sell form balance
					$('#cur_to').text(data.data_price.balance_coinsecond.balance);
					$('#cur_from').text(data.data_price.balance_coinmain.balance);
					
					//Change sidebar balance
					
					$('#sidebar #spanBalance-'+data.data_price.balance_coinmain.wallet_id).text(data.data_price.balance_coinmain.balance);
					//$('#sidebar #spanBalance-'+data.data_price.balance_coinmain.wallet_id).text(balance_coinmain_sidebar);
					$('#sidebar #spanBalance-'+data.data_price.balance_coinsecond.wallet_id).text(data.data_price.balance_coinsecond.balance);
					//$('#sidebar #spanBalance-'+data.data_price.balance_coinsecond.wallet_id).text(balance_coinsecond_sidebar);
					
					
				}
					
				if( data.user_orders !== undefined ){
					$.each(data.user_orders, function(key, value){
						console.log(data);
						

						var order_type_value;
						if(value['order_b']!== undefined){
							order_type_value = 'order_b';
							order_type_string = 'buy';
							order_type_class_new = 'blue';
						}else if(value['order_s']!== undefined){
							order_type_value = 'order_s';
							order_type_string = 'sell';
							order_type_class_new = 'red';
						}
					
						if(value[order_type_value]!== undefined){
							console.log(value[order_type_value]['action']);

							var amount = prettyFloat(value[order_type_value]['amount'], 8);
							//var total = prettyFloat(value[order_type_value]['total'], 8);
							var total = value[order_type_value]['total'];

							
							//var price = prettyFloat(value[order_type_value]['price'], 8);
							var price = value[order_type_value]['price'];
							
							var class_price = price.replace(".","-");
							var class_price = class_price.replace(",","-");
							var order_date_ = value[order_type_value]['created_at']['date'];
							order_date_ = order_date_.substring(0, order_date_.indexOf('.'));	//Remove everything after a certain character

							
							
							switch(value[order_type_value]['action']){
								case "insert":
									console.log('insert private '+order_type_string+' order, market_id:' +market_id+', yourorder: '+ value[order_type_value]['id']);
									//insert your buy order, your current order list
									var your_order='<tr id="yourorder-'+value[order_type_value]['id'] +'" class="order price-'+class_price+'"><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td><td><span class="date"><small>'+order_date_ +'<small></span></td><td><button type="button" onclick="javascript:cancelOrder(this, '+value[order_type_value]['id'] +');" class="btn btn-danger btn-xs">{{trans('texts.cancel')}}</button></td></tr>';
									//$('#yourorders_'+market_id+' > table tr.header-tb').after(your_order);

									if( $('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+'  table > tbody > tr:first').length )
										$('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+'  table > tbody > tr:first').before(your_order);
									else	
										$('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+' table > tbody').append(your_order);
										
									
									$('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+' table > tbody > tr#yourorder-'+value[order_type_value]['id']).addClassDelayRemoveClass({'elemclass': order_type_class_new+' affected'});

								break;
							}
						}
						
						//if ($element.parent().length) { alert('yes') }

					});
				}
			});
			
			socket.on( 'subscribeMarket', function( data ) {
			
				console.log('========subscribeMarket Socket '+ data);

				
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
							// Ascending: first price less than the previous
							if(a.type == 'buy')
								return a.price - b.price;
							else
								return b.price - a.price;
						});

						
						//console.log('subscribeMarket Socket - before  ');
						var total = 0, market_id;
						$.each(data_history_trade, function(key, value){
							
							
							market_id=value['market_id'];
							if (market_id == {{{Session::get('market_id')}}} ){
								console.log('subscribeMarket Socket - market id  '+ market_id);
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
								
								
								//Update total maincoin and amount secondcoin on buy and sell side
								if(value['type'] == 'sell'){
									trade_new += '<td><span style="color:red; text-transform: capitalize;"><strong>'+value['type']+' <i class="icon-arrow-down icon-large" ></i></span></td>';
									
									updateTotalAmountOrders('buy', amount, total, market_id, 'yes');	//update on buy side
								}else{
									trade_new += '<td><span style="color:green; text-transform: capitalize;"><strong>'+value['type']+' <i class="icon-arrow-up icon-large" ></i></span></td>';
									
									updateTotalAmountOrders('sell', amount, total, market_id, 'yes');	//update on sell side
								}
									
									

								//console.log('history_trade total: ',total);
								//console.log('history_trade amount: ',amount);
								trade_new += '<td>'+parseFloat(value['price']).toFixed(8)+'</td>';
								trade_new += '<td>'+amount+'</td>';
								trade_new += '<td>'+total.toFixed(8)+'</td>';
								trade_new+='</tr>'; 
								//console.log('history_trade trade_new: ',trade_new);              		
								if($('#trade_histories_'+market_id+' > table > tbody > tr:first').length)
									$('#trade_histories_'+market_id+' > table > tbody > tr:first').before(trade_new);
								else	
									$('#trade_histories_'+market_id+' > table > tbody').append(trade_new);
									
								$('#trade_histories_'+market_id+' > table > tbody > tr#trade-'+value['id']).addClassDelayRemoveClass({'elemclass': 'new'});
								//$('#trade_histories_'+market_id+' > table tr.header-tb').after(trade_new);
								
							}
						});
						
	               	}
					
			});
			
			socket.on( 'subscribeAllMarkets', function( data ) {
				
				console.log('=========subscribeAllMarkets Socket '+ data);

				var market_id=data.market_id;
				var market_name=data.market_name;
				
            	
				$.each(data.message_socket, function(key, value){
				    console.log("message socket data: "+key + ": " + value);
					
					var order_type_value;
					if(value['order_b']!== undefined){
						order_type_value = 'order_b';
						order_type_string = 'buy';
						order_type_class_new = 'green';
						order_type_class_update = 'red';
					}else if(value['order_s']!== undefined){
						order_type_value = 'order_s';
						order_type_string = 'sell';
						order_type_class_new = 'green';
						order_type_class_update = 'blue';
					}
				
				    if(value[order_type_value]!== undefined){
						//console.log(order_type,value[order_type_value]);              		
	               		var amount = parseFloat(value[order_type_value]['amount']).toFixed(8);
						var total = parseFloat(value[order_type_value]['total']).toFixed(8);

	               		var price = parseFloat(value[order_type_value]['price']).toFixed(8);
	               		var class_price = price.replace(".","-");
	            		class_price = class_price.replace(",","-");

						
	            		console.log('class_price',class_price);
	            		console.log('action',value[order_type_value]['action']); 
	               		
						if(value[order_type_value]['action'] == 'insert'){

								console.log('Insert '+order_type_string);	//New buy/sell order
	               				if($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).length){
		               				//console.log('Update '+order_type_string);
		               				var amount_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text());
		               				var total_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text());

									$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).show();
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text((parseFloat(amount_old)+parseFloat(amount)).toFixed(8));
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text((parseFloat(total_old)+parseFloat(total)).toFixed(8));
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_new});
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+(parseFloat(amount_old)+parseFloat(amount)).toFixed(8)+')');
		               			}else{
		               				
		               				var new_order='<tr id="order-'+value[order_type_value]['id'] +'" class="order price-'+class_price+'" onclick="use_price(2,'+value[order_type_value]['price'] +','+amount+')" data-sort="'+price+'" data-counter=""><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td></tr>';
		               				if($('#orders_'+order_type_string+'_'+market_id+' > table > tbody tr.order').length){
		               					var i_d=0;
			               				$( '#orders_'+order_type_string+'_'+market_id+' tr.order').each(function( index ) {
								            var value = $(this).val(); 
								            var price_compare = parseFloat($(this).attr('data-sort'));					
								            
												//Place the new order on correct row in table
											if(order_type_value == 'order_b'){
												if(price>price_compare){
													i_d=1;
													$(this).before(new_order);
													return false;
												}
											}else if(order_type_value == 'order_s'){
												if(price<price_compare){
													i_d=1;
													$(this).before(new_order);
													return false;
												}
											}
								        });
								        if(i_d==0){
								        	//console.log( "add to the end");  
								        	$('#orders_'+order_type_string+'_'+market_id+' > table > tbody tr:last-child').after(new_order);
								        }
		               				}else{
	               						$('#orders_'+order_type_string+'_'+market_id+' > table > tbody').html(new_order);		
	               					}
									$('#order-'+value[order_type_value]['id']).addClassDelayRemoveClass({'elemclass': order_type_class_new});	//Add green bg for new order, delay and remove class
								
		               			}
								
								updateTotalAmountOrders(order_type_string, amount, total, market_id, 'no');	//sell side
								
		               			//console.log('insert '+order_type_string+' end'); 
	               		}else if (value[order_type_value]['action'] == 'update' || value[order_type_value]['action'] == 'delete'){

	               				console.log('update '+order_type_string+' init');
								//Update existing order, cancel or delete them
								
	               				var amount_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text());
	               				var total_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text());
	               				
	           					var new_amount = (parseFloat(amount_old)-parseFloat(amount)).toFixed(8);
	           					var new_total = (parseFloat(total_old)-parseFloat(total)).toFixed(8);

								
								var cancel = false;
								if(value[order_type_value]['type_sub'] !== undefined){
									if (value[order_type_value]['type_sub'] == 'cancel')
										cancel = true;
								}
									
	           					if(new_amount<='0.00000000' || new_amount<=0.00000000 || new_amount <= 0 || isNaN(new_amount)){
									//$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red', 'delaysec': 1000}).fadeOut();
									
									if(cancel == true)
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).fadeOut().remove();
									else
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update, 'delaysec': 1000}).fadeOut().remove();


									console.log('icee do'+order_type_string+'opposite: ' + new_amount);
									console.log('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price);
	           					}else{
	           						$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).attr('onclick','use_price(1,'+price +','+new_amount+')');
	           						$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text(new_amount);
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text(new_total);
									$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update});
									  
									if(cancel == false)
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update, 'delaysec': 1000});
									else
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).hide().fadeIn();
	           					}
	               		}
	               	}

				});              	



               	//update % change price
               	//console.log('change_price init: ',data.change_price);
               	if(data.change_price !== undefined){
               		//console.log('change init: ',data.change_price.change);
              		var change=parseFloat(data.change_price).toFixed(2);
              		//console.log('curr_price: ',parseFloat(data.change_price.curr_price).toFixed(8));

					
					$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="price"]').text(parseFloat(data.data_price.latest_price).toFixed(8));
					//$('#spanPrice-'+market_name).text(parseFloat(data.data_price.latest_price).toFixed(8));

					//$('#spanPrice-'+market_name).attr('data-yesterdayPrice',parseFloat(data.change_price.pre_price).toFixed(8));

					$('.sidebar li[data-markets-currency="'+market_name+'"] div[data-markets-currency="volume"]').attr('data-original-title', 'Vol: '+(parseFloat(data.data_price.get_prices.volume).toFixed(8)) + ' ' +market_name.split("_")[1] );
					//$('.sidebar li[data-markets-currency="'+market_name+'"] div[data-markets-currency="volume"]').data('original-title', (parseFloat(data.data_price.get_prices.volume).toFixed(8)) );

					//$('#volume-'+market_name+' div[data-toggle="tooltip"]').attr('data-original-title', (parseFloat(data.data_price.get_prices.volume).toFixed(8)) );



					//$(".sidebar_tabs ul.market li[data-side-market-volume='"+ market_name +"']").html('test');
					//$(".sidebar_tabs ul.market li[data-side-market-volume='" + current +"']");

              		//console.log('change: ',change);
              		//console.log('change 1: ',data.change_price.change);

					/*
					var balance_coinmain_sidebar;
					var balance_coinsecond_sidebar;
					
					if (data.data_price.balance_coinmain.balance == 0)
						balance_coinmain_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinmain.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-up"></span>';
					else if  (data.data_price.balance_coinmain.balance > 0)
						balance_coinmain_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinmain.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-down"></span>';
					else
						balance_coinmain_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinmain.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% </span>';

					if (data.data_price.balance_coinsecond.balance == 0)
						balance_coinsecond_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinsecond.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-up"></span>';
					else if  (data.data_price.balance_coinsecond.balance > 0)
						balance_coinsecond_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinsecond.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-down"></span>';
					else
						balance_coinsecond_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinsecond.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% </span>';
					*/

					$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').removeClass('up down btn-warning btn-danger btn-success');
					$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').removeClass('up down btn-warning btn-danger btn-success');
					
					$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').removeClass('up down');
					$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').removeClass('up down btn-warning btn-danger btn-success');
					
					
					$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').removeClass('up down');

					if(change==0){  
               			$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html(change+'%');

						$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('btn-warning');
						$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('btn-warning');
						//$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"] i').removeClass('fa-arrow-up fa-arrow-down');

						$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html(change+'%');
						$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html(change+'%');
						
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').addClass('btn-warning');
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').html(change+'%');
						
						
						//$('#spanChange-'+market_name).removeClass('up down');
               			//$('#spanChange-'+market_name).html('+'+change+'%');
					}else if(change>0){  
               			//console.log('Up ');
               			$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('up');
               			$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html('+'+change+'% <i class="fa fa-arrow-up"></i>');

						$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('up btn-success');
						$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('up btn-success');
						//$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"] i').removeClass('fa-arrow-up fa-arrow-up').addClass('fa-arrow-up');

						$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html('+'+change+'% <i class="fa fa-arrow-up"></i>');
						$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html('+'+change+'% <i class="fa fa-arrow-up"></i>');

						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').addClass('up btn-success');
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').html('+'+change+'% <i class="fa fa-arrow-up"></i>');
						
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').addClass('up');
               			//$('#spanChange-'+market_name).removeClass('up down').addClass('up');
               			//$('#spanChange-'+market_name).html('+'+change+'% <i class="fa fa-arrow-up"></i>');
               			//console.log('Up 1a ');   
               		}else{
               			//console.log('Down ');
						$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('down');
               			$('.sidebar li[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html(change+'% <i class="fa fa-arrow-down"></i>');

						$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('down btn-danger');
						$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').addClass('down btn-danger');
						//$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"] i').removeClass('fa-arrow-up fa-arrow-up').addClass('fa-arrow-down');

						$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html(change+'% <i class="fa fa-arrow-down"></i>');
						$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="change"]').html(change+'% <i class="fa fa-arrow-down"></i>');
						
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').addClass('down btn-danger');
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').html(change+'% <i class="fa fa-arrow-down"></i>');
						
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').addClass('down');
               			//$('#spanChange-'+market_name).removeClass('up down').addClass('down');
               			//$('#spanChange-'+market_name).html(''+change+'% <i class="fa fa-arrow-down"></i>');
               			//console.log('Down a');
               		}               		
               	}
               	//update block price, Markets & Market Data (Startpage and Sidebar and spec. Market)
               	if(data.data_price !== undefined){
               		//console.log('data_price',data.data_price);
               		if(data.data_price.latest_price!==undefined){
						//Set High,Low and Volume for viewed MarketID coin


						var old_lastprice = parseFloat( $('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').text() ).toFixed(8);

               			//var old_lastprice = parseFloat( $('#spanLastPrice-'+market_name).text() ).toFixed(8);
	               		var new_lastprice = parseFloat(data.data_price.latest_price).toFixed(8);

	               		console.log("if(new_lastprice<old_lastprice) "+ new_lastprice+'<'+old_lastprice );
						
						// Market & Markets UI animate changes upon price change
						if(new_lastprice<old_lastprice){
							//$('#lastprice-'+market_name).addClass('red');
							//$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="change"]').removeClass('up').addClass('down');


							if( $('.startpage tr[data-markets-currency="'+market_name+'"]').length )
								$('.startpage tr[data-markets-currency="'+market_name+'"]').addClassDelayRemoveClass({'elemclass': 'red'});
							
							if( $('.startpage div[data-markets-currency="'+market_name+'"]').length )
								$('.startpage div[data-markets-currency="'+market_name+'"]').addClassDelayRemoveClass({'elemclass': 'red'});

							if( $('.sidebar li[data-markets-currency="'+market_name+'"]').length )
								$('.sidebar li[data-markets-currency="'+market_name+'"]').addClassDelayRemoveClass({'elemclass': 'red'});
							
							//if( $('#mainLastPrice-'+market_name).length )
								//$('#mainCoin-'+market_name).addClassDelayRemoveClass({'elemclass': 'red'});
	               		}else{ 
							//$('#lastprice-'+market_name).addClass('blue');
							//$('div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').removeClass('down').addClass('up');

								//Set High,Low and Volume for index MarketID coin	
							if( $('.startpage tr[data-markets-currency="'+market_name+'"]').length )
								$('.startpage tr[data-markets-currency="'+market_name+'"]').addClassDelayRemoveClass({'elemclass': 'blue'});
							
							if( $('.startpage div[data-markets-currency="'+market_name+'"]').length )
								$('.startpage div[data-markets-currency="'+market_name+'"]').addClassDelayRemoveClass({'elemclass': 'blue'});
							
							if( $('.sidebar li[data-markets-currency="'+market_name+'"]').length )
								$('.sidebar li[data-markets-currency="'+market_name+'"]').addClassDelayRemoveClass({'elemclass': 'blue'});
							//if( $('#mainLastPrice-'+market_name).length )
								//$('#mainCoin-'+market_name).addClassDelayRemoveClass({'elemclass': 'blue'});
						}
						if(new_lastprice==old_lastprice){
							//$('div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').removeClass('down up');
						}

						
						$('.marketspage div[data-market-currency="'+market_name+'"] span[data-market-currency="price"]').text(new_lastprice);
						
	               		//$('#spanLastPrice-'+market_name).text(new_lastprice);


						if( $('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="price"]').length )
							$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="price"]').text(new_lastprice);
						
						if( $('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="price"]').length )
							$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="price"]').text(new_lastprice);
						//if( $('#mainLastPrice-'+market_name).length )
							//$('#mainLastPrice-'+market_name).text(new_lastprice);

						//Set High,Low and Volume for index MarketID coin
               		}               		
               		if(data.data_price.get_prices!==undefined){
						//Set High,Low and Volume for viewed MarketID coin


						$('div[data-market-currency="'+market_name+'"] span[data-market-currency="high"]').text(parseFloat(data.data_price.get_prices.max).toFixed(8));
						//$('#spanHighPrice-'+market_name).text(parseFloat(data.data_price.get_prices.max).toFixed(8));
	               		$('div[data-market-currency="'+market_name+'"] span[data-market-currency="low"]').text(parseFloat(data.data_price.get_prices.min).toFixed(8));
	               		//$('#spanLowPrice-'+market_name).text(parseFloat(data.data_price.get_prices.min).toFixed(8));
	               		$('div[data-market-currency="'+market_name+'"] span[data-market-currency="volume"]').text(parseFloat(data.data_price.get_prices.volume).toFixed(8));
						//$('#spanVolume-'+market_name).text(parseFloat(data.data_price.get_prices.volume).toFixed(8));

							//Update Coin Data for Startpage and Sidebar - High,Low and Volume 
						if( $('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="high"]').length )
							$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="high"]').text(parseFloat(data.data_price.get_prices.max).toFixed(8));
						if( $('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="low"]').length )
							$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="low"]').text(parseFloat(data.data_price.get_prices.min).toFixed(8));
						if( $('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="volume"]').length )
							$('.startpage tr[data-markets-currency="'+market_name+'"] span[data-markets-currency="volume"]').text(parseFloat(data.data_price.get_prices.volume).toFixed(8));
						
						if( $('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="volume"]').length )
							$('.startpage div[data-markets-currency="'+market_name+'"] span[data-markets-currency="volume"]').text(parseFloat(data.data_price.get_prices.volume).toFixed(8));
						/*
						if( $('#mainHighPrice-'+market_name).length )
							$('#mainHighPrice-'+market_name).text(parseFloat(data.data_price.get_prices.max).toFixed(8));
						if( $('#mainLowPrice-'+market_name).length )
							$('#mainLowPrice-'+market_name).text(parseFloat(data.data_price.get_prices.min).toFixed(8));
						if( $('.mainVolume-'+market_name).length )
							$('.mainVolume-'+market_name).text(parseFloat(data.data_price.get_prices.volume).toFixed(8));
						*/
						//spanLastPrice-PINK_BTC

               		}
               	}

				/*
               	setTimeout(function(){
               		$('table > tr').removeClass("new");
               		//$('table tr,li, div.box').removeClass("blue red green");               		
               		$('#s_message, #b_message').text('');
               	},10000);
				*/
				
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







//$('li.volume').tooltip('show');

$(function () { 
	$("[data-toggle='tooltip']").tooltip( { 'delay': { show: 100, hide: 100 } } ); 
});
</script>
@stop

<?php
/*
https://jsfiddle.net/pco60ap2/6/

HTML:
//---------------------------------------------------------------------//
<a class="slide-link" href="#" data-slide="BAY_BTC">BAY/BTC</a>
<a class="slide-link" href="#" data-slide="PINK_BTC">PINK/BTC</a>
<a class="slide-link" href="#" data-slide="BEX_BTC" data-you="Hello">BEX/BTC</a>

CSS: 
//---------------------------------------------------------------------//
.active{
    background:red;
}

JAVASCRIPT:
//---------------------------------------------------------------------//
$('.slide-link[data-slide="BEX_BTC"]').addClass('active');
$('.slide-link[data-slide="BEX_BTC"]').text('BEX/BTC choosen');
$('.slide-link[data-slide="BEX_BTC"]').text('BEX/BTC choosen');

//HTML5 WAY
alert ( $('.slide-link[data-slide="BEX_BTC"]').data("you") );
$('.slide-link[data-slide="BEX_BTC"]').data("you", "Good Bye!")
alert ( $('.slide-link[data-slide="BEX_BTC"]').data("you") );

//JQUERY WAY
alert($('.slide-link[data-slide="BEX_BTC"]').attr("data-you")); // Hello mean

$('.slide-link[data-slide="BEX_BTC"]').attr("data-you", "yes change you atribute");

alert($('.slide-link[data-slide="BEX_BTC"]').attr("data-you")); // Hello mean
*/
//---------------------------------------------------------------------//
/*
===Market - When Trading

#spanLastPrice-PINK_BTC
#spanVolume-PINK_BTC
#spanHighPrice-PINK_BTC
#spanLowPrice-PINK_BTC

id="lastprice-{{ $coinmain.'_'.$coinsecond }}"
???
-->
data-market-coin="price"
data-market-coin="volume"
data-market-coin="high"
data-market-coin="low"
data-market-coin="change"

#marketCoinPrice-PINK_BTC
#marketCoinVolume-PINK_BTC
#marketCoinHigh-PINK_BTC
#marketCoinLow-PINK_BTC

===Sidebar Markets
#spanPrice-BAY_BTC
#spanChange-BAY_BTC

#volume-PINK_BTC div.data-toggle
	change attribute: data-original-title

===Startpage

#mainLastPrice-PINK_BTC
#mainHighPrice-PINK_BTC
#mainLowPrice-PINK_BTC
.mainVolume-PINK_BTC

data-markets-coin="PINK_BTC"
data-markets-coin="price"
data-markets-coin="volume"
data-markets-coin="high"
data-markets-coin="low"
data-markets-coin-change="change"
*/
?>
