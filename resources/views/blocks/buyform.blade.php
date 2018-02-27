<form class="form-horizontal">
	<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{{ trans('texts.buy')}}} {{{ $coinmain }}}</h3>

          <div class="box-tools pull-right">
				{{{ trans('texts.your_balance')}}}: 
				<!-- <a id="buy_coin_link" data-amount="{{{ $balance_coinsecond }}}" href="javascript:void(0)" onclick="a_calc(17)"><b><span id="cur_to" class="money_rur">{{{ $balance_coinsecond }}}</span> {{{ $coinsecond }}}</b></a> -->
				<a id="buy_coin_link" data-amount="{{{ $balance_coinsecond }}}" href="#"><b><span id="cur_to" class="money_rur">{{{ $balance_coinsecond }}}</span> {{{ $coinsecond }}}</b></a>
          </div>
        </div>
        <div class="box-body inblock">
			@if($enable_trading != 1)
				<div class="notice notice-danger">
					<strong><i class="fa fa-exclamation-triangle fa-2x left"></i> {{ trans('texts.notice') }}</strong> {{ trans('texts.market_disabled') }}
				</div>
			@endif
			
			<div class="form-group">
			  <label class="col-lg-2 control-label" for="b_amount">{{{ trans('texts.amount')  }}}</label>
			  <div class="col-lg-10 input-group">      
				<input id="b_amount" name="b_amount" class="form-control" type="text" value="0">
				<span class="input-group-addon">{{{ $coinmain }}}</span> 
			  </div>
			</div>
			
			<div class="form-group">
			  <label class="col-lg-2 control-label" >{{{ trans('texts.price')}}} </label>
			  <div class="col-lg-10 input-group">
				<input id="b_price" name="b_price" class="form-control" type="text" value="{{$buy_highest}}">
				<span class="input-group-addon">{{{ $coinsecond }}}</span> 
			  </div>
			</div> 
			<div class="">
			  <!-- Data Slider-->
			  <div class="col-lg-11 col-centered">
				<div id="buy_slider" ></div>
			  </div>
			</div>
			
			  <div class="forConfirm">
				<div class="form-group">
				  <label class="col-lg-2 control-label" >{{{ trans('texts.total')}}}</label>
				  <div class="col-lg-10 input-group">
					  <span class="">
					   <span id="b_all">0.00 </span> <span>{{{ $coinsecond }}}</span>
					  </span>
					</div>
				</div>


				<div class="form-group">
				  <label class="col-lg-2 control-label" >{{{ trans('texts.trading_fee_short')}}} (<span id="fee_buy">{{$fee_buy}}</span>%)</label>
				  <div class="col-lg-10 input-group">
					  <span class="">
					   <span id="b_fee">0 </span> <span>{{{ $coinsecond }}}</span>
					  </span>
					</div>
				</div>
				

				<div class="form-group">
				  <label class="col-lg-2 control-label" >{{{ trans('texts.net_total')}}}</label>
				  <div class="col-lg-10 input-group">
					  <span class="">
					   <span id="b_net_total">0 </span> <span>{{{ $coinsecond }}}</span>
					  </span>
					</div>
				</div>
				
				
			</div>
			<div class="form-group">
			  <span id="b_message"></span>
			</div>
			
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          	<input type="hidden" name="buy_market_id" id="buy_market_id" value="{{{Session::get('market_id')}}}">     
			<!-- <button type="button" class="btn" id="calc_buy">{{trans('texts.caculate')}}</button> -->
			<button type="button" class="btn btn-primary btn-success btn-block" id="do_buy">{{ trans('texts.buy')}} {{{ $coinmain }}} <i class="fa fa-circle-o-notch fa-spin fa-1x hide"  id="buy_loader"></i></button> 
        </div>
        <!-- /.box-footer-->
</div>
</form>

<!-- Confirm Modal -->
<div class="modal fade" id="modal_ConfirmOrder" tabindex="-1" role="dialog" aria-labelledby="label_ConfirmOrder" aria-hidden="true" >
  <div class="modal-dialog bootstrap-dialog type-primary" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title bootstrap-dialog-title" id="myModalLabel">Confirm Trade Order</h4>
      </div>
      <div class="modal-body" id="confirm-trade-box">

                <div id="form-container">
                    <form role="form" class="form-horizontal">
						<div class="form-group" style="margin-bottom:10px">
                            <div class=" col-sm-2">
                                {{{ trans('texts.type')}}}: <span id="modal_ConfirmOrder_type" ></span> 
                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    {{{ trans('texts.market')}}}: <strong>{{{ $coinmain }}}/{{{ $coinsecond }}}</strong>
                                </div>
                            </div>
                        </div>
						
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
                                {{{ trans('texts.amount')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    
                                    <div id="confirm_trade_amount" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinmain }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
                                {{{ trans('texts.price')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_price" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
                                {{{ trans('texts.total')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_total" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
								{{{ trans('texts.trading_fee_short')}}} <small>(<span id="confirm_trade_fee_percent">{{$fee_buy}}</span>%)</small>
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_fee" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class="col-sm-2">
                                {{{ trans('texts.net_total')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_net_total" disabled class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

				<div class="row ">
					<div class="alert alert-warning">
						<p>{{{ trans('texts.disclaimer') }}}</p>
						<p>{{{ trans('texts.disclaimer_warning') }}}</p>
					</div>
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> {{{ trans('texts.cancel') }}}</button>
        <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> {{{ trans('texts.confirm') }}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

function doPostTradeOrder(tradeArray){

		var price, amount, market_id;
			
		price = tradeArray[0];
		amount = tradeArray[1];
		market_id = tradeArray[2];
		type = tradeArray[3];
		var ajax_trade_url;
		/*
		I dont wont pure URL in the code, it should use Laravel routing instead
		if(type == 'buy'){
			ajax_trade_url = '/dobuy';
		}else if(type == 'sell'){
			ajax_trade_url = '/dosell';
		}
		*/
		if(type == 'buy'){
			ajax_trade_url = '<?php echo action('OrderController@doBuy')?>';
		}else if(type == 'sell'){
			ajax_trade_url = '<?php echo action('OrderController@doSell')?>';
		}
		
	$.ajax({
		type: 'post',
		url: ajax_trade_url,
		datatype: 'json',
		data: {isAjax: 1, price: price, amount: amount, market_id: market_id },
		beforeSend: function(request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
		},
		success:function(response) {
			var obj = $.parseJSON(response);
			//app.BrainSocket.message('doTrade',obj.message_socket);          

			

			if(obj.status == 'success'){
				socket.emit( 'subscribeAllMarkets', obj.message_socket);
				socket.emit( 'userOrder', obj.message_socket_user);
				showMessage(obj.messages,'success');
			}else{
				showMessage(obj.messages,'error');
			}
			
			if(type == 'buy'){
				$('#buy_loader').addClass("hide");
			}else if(type == 'sell'){
				$('#sell_loader').addClass("hide");
			}

		}, error:function(response) {
			showMessageSingle('{{{ trans('texts.error') }}}', 'error');
		}
	});
}

		/**
         * BootstrapDialog Confirm Trade modal box
         * 
         * @param {type} tradeArr [price, amount, market_id]
         * @param {type} callback
         * @returns {undefined}
         */
        BootstrapDialog.confirmTrade = function(tradeArray, callback) {
			
			var price, amount, market_id;
			
			price = tradeArray[0];
			amount = tradeArray[1];
			market_id = tradeArray[2];
			type = tradeArray[3];
			
			if(type == 'buy'){
				  $('#confirm_trade_amount').text( $('#b_amount').val() );
				  $('#confirm_trade_price').text( $('#b_price').val() );
				  $('#confirm_trade_total').text( $('#b_all').text() );
				  $('#confirm_trade_fee_percent').text( $('#fee_buy').val() );
				  $('#confirm_trade_fee').text( $('#b_fee').text() );
				  $('#confirm_trade_net_total').text( $('#b_net_total').text() );
				  $('#modal_ConfirmOrder_type').text( '{{{ trans('texts.buy')}}}' );
			}else if(type == 'sell'){
				  $('#confirm_trade_amount').text( $('#s_amount').val() );
				  $('#confirm_trade_price').text( $('#s_price').val() );
				  $('#confirm_trade_total').text( $('#s_all').text() );
				  $('#confirm_trade_fee_percent').text( $('#fee_sell').val() );
				  $('#confirm_trade_fee').text( $('#s_fee').text() );
				  $('#confirm_trade_net_total').text( $('#s_net_total').text() );
				  $('#modal_ConfirmOrder_type').text( '{{{ trans('texts.sell')}}}' );
			}
			
			/*
			var $buySellContentConfirm = $('#modal_ConfirmOrder .modal-body').html();
			//$buySellContentConfirm.append( $('.buysellform form .forConfirm').html() );
			$buySellContentConfirm.append( $('#modal_ConfirmOrder .modal_body').html() );
			*/
			var $buySellContentConfirmBox = $('<div></div>');
			//$buySellContentConfirm.append( $('.buysellform form .forConfirm').html() );
			$buySellContentConfirmBox.append( $('#modal_ConfirmOrder .modal-body').html() );
		
		
			
			var callback = function(result) {
				
				// result will be true if button was click, while it will be false if users close the dialog directly.
				if(result) {
					doPostTradeOrder(tradeArray);
					/*
					if(type == 'buy'){
						doPostBuyOrder(tradeArray);
					}else if(type == 'sell'){
						doPostSellOrder(tradeArray);
					}
					*/
				}else {
					//showMessageSingle('{{{ trans('texts.error') }}}', 'error');
					if(type == 'buy'){
						$('#buy_loader').addClass("hide");
					}else if(type == 'sell'){
						$('#sell_loader').addClass("hide");
					}

				}
				
				//console.log(result);
				return result;
			};

            new BootstrapDialog({
                title: '{{{ trans('messages.confirm_buy_order')}}}',
				message: $buySellContentConfirmBox,
				//type: BootstrapDialog.TYPE_WARNING, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
				closable: true, // <-- Default value is false
				//btnOKClass: 'btn-info', // <-- If you didn't specify it, dialog type will be used,
				data: {
                    'callback': callback
                },
				onhide: function(dialog){
					if(type == 'buy'){
						$('#buy_loader').addClass("hide");
					}else if(type == 'sell'){
						$('#sell_loader').addClass("hide");
					}
				},
				buttons: [{
                        label: '<i class="fa fa-times"></i> {{{ trans('texts.cancel')}}}',
                        action: function(dialog) {
                            typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(false);
                            dialog.close();
                        }
                    }, {
                        label: '<i class="fa fa-check"></i> {{{ trans('texts.confirm')}}}',
                        cssClass: 'btn-primary',
                        action: function(dialog) {
                            typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(true);
                            dialog.close();
                        }
                    }]
            }).open();
			
			return callback;
        };
		
$(function(){
	
//------// START - SLIDER CODE //------//
	//Buy Slider Init
	//console.log(document.getElementById('buy_slider'));
	//var buyOrderSlider = document.getElementById('buy_slider');
	

	var buyOrderSlider = new Slider("#buy_slider", {
		value: 0,
		ticks: [0, 25, 50, 75, 100],
		ticks_labels: [0, '', '', '', 100],
		step: 1
	});
	
	buyOrderSlider.on('change', function( values) {
		console.log('values: ');
		console.log('oldVal: '+values.oldValue);
		console.log('newVal: '+values.newValue);
		$('#b_amount').val(values.newValue); 
		//updateSliderDataBuy();
		console.log(this);
	});
	
	
	
	//Disable BUY when user is not logged in 
	@if ( Auth::guest() ) 
		//buyOrderSlider.setAttribute('disabled', true);
		//buyInputAmount.setAttribute('disabled', true);
		//document.getElementById('b_price').setAttribute('disabled', true);
		//document.getElementById('do_buy').setAttribute('disabled', true);
	@endif
	
	//Function for updating Slider range
	function updateSliderRange (max=1) {

		max = (max <= 0) ? 1: max;
		
		//max=200;
		var splittedRange = (max / 4);
		var snap_bound_val = (max * 0.03).toFixed(8);
		var rangeArr = [0, splittedRange*1, splittedRange*2, splittedRange*3, max];
		console.log('splittedRange min: '+rangeArr[0]);
		console.log('splittedRange: '+rangeArr[1]);
		console.log('splittedRange: '+rangeArr[2]);
		console.log('splittedRange: '+rangeArr[3]);
		console.log('splittedRange max: '+rangeArr[4]);
		//if(min => max){min = 0; max=1;}else{}
		//update the min and max in range slider
		
		//if(newSliderValue > 0) $('#b_amount').val(newSliderValue);
			

		//buyOrderSlider.destroy();
		
		console.log('getValue: '+buyOrderSlider.getValue());
		
		buyOrderSlider
		.setAttribute("ticks", [rangeArr[0], rangeArr[1], rangeArr[2], rangeArr[3], rangeArr[4]])
		//.setAttribute("ticks_labels", [rangeArr[0], rangeArr[1], rangeArr[2], rangeArr[3], rangeArr[4]])
		.setAttribute("ticks_labels", [rangeArr[0], '', '', '', rangeArr[4]])
		.setAttribute("ticks_snap_bounds", snap_bound_val)
		.setAttribute("step", 0.01)
		.refresh();
		
		console.log('===========================');
		console.log('buyOrderSlider 2: ');
		console.log(buyOrderSlider);
			
		//Re-initiate the on change since we did a refresf of the slider above
		buyOrderSlider.on('change', function( values) {
			console.log('values2: ');
			console.log('oldVal2: '+values.oldValue);
			console.log('newVal2: '+values.newValue);
			$('#b_amount').val(values.newValue); 
			//updateSliderDataBuy();
			console.log(this);
		});
			
		
	}
	updateSliderDataBuy();	//initiate the buyslider
	$('#buy_coin_link').click(function(e) {
		updateSliderDataBuy();
	});
//------// STOP - SLIDER CODE //------//
function updateSliderDataBuy(){
			
		var amount = $('#b_amount').val(); 
		var price = $('#b_price').val();
		var fee = $('#fee_buy').html();
		var total = parseFloat(amount*price);
		var fee_amount = total*(fee/100);

		$('#b_all').html(total.toFixed(8)); 
		$('#b_fee').html(fee_amount.toFixed(8));
		$('#b_net_total').html( parseFloat(total+fee_amount).toFixed(8));
		

		var newAmount = +amount+0;
		//$('#b_amount').val(newAmount); 
		

		//->Calc the max slider range 
		var total_base = parseFloat($('#cur_to').html()) ; //balance
		var slider_range_max = (+(total_base/price)+0).toFixed(8);
		
		//if(newSliderValue>0) updateSliderRange(slider_range_max, newSliderValue);		//issue
		//else updateSliderRange(slider_range_max);		//issue
		
		$('#b_amount').val(slider_range_max); 
		console.log('b_amount: '+$('#b_amount').val());
		
		updateSliderRange(slider_range_max);
		buyOrderSlider.setValue(slider_range_max, true, true);
		//console.log('sliderRangeMax: '+slider_range_max);
		
		//var buyOrderSlider = document.getElementById('buy_slider');


		
	}
	$('#b_price').keyup(function(event) {
		//console.log(event);
		//this.value = parseFloat(this.value).toFixed(8);
		//var price = parseFloat(this.value).toFixed(8);
		var price = this.value;
		var total_base = parseFloat($('#cur_to').html()).toFixed(8) ; //balance
		var slider_range_max = (+(total_base/price)+0).toFixed(8);
		var oldValue = buyOrderSlider.getValue();
		updateSliderRange(slider_range_max);
		
		buyOrderSlider.setValue(parseFloat(oldValue).toFixed(8), true, true);
	});
	
	$('#b_amount, #b_price').change(function() {
		this.value = parseFloat(this.value).toFixed(8);
	});
	$('#b_amount').keyup(function(event) {
	//alert(event.target.id); id of the triggered element
		//this.value = parseFloat(this.value).toFixed(8);
		buyOrderSlider.setValue( parseFloat(this.value).toFixed(8), true, true);
	
  });	


/*	
if(balance < net_total)
    alert('print ok');
else
    alert('print false');
*/

  $('#do_buy').click(function(e) {
     e.preventDefault(); 
      var market_id = $('#buy_market_id').val();
      var price = prettyFloat($('#b_price').val(), 8);
      var amount = prettyFloat($('#b_amount').val(), 8); 
      //var balance = prettyFloat($('#cur_to').html(), 8);
	  var balance = parseFloat($('#cur_to').html());
	  
      var fee = $('#fee_buy').html();
      var total = amount*price;
      var fee_amount = total*(fee/100); 
      //var net_total = prettyFloat(total+fee_amount, 8);
      var net_total = total+fee_amount;
     
	  console.log('do_buy -> total : '+ total + ' || amount : ' +amount);

      if(!$('body').hasClass('logged')) {
        showMessage(["{{trans('messages.login_to_trade')}}"],'error'); 

       
      }else if(isNaN(price) || price < 0.00000001){
        
        showMessage(["{{trans('messages.message_min_price',array('price'=> '0.00000001'))}}"],'error'); 
      }
      else if(isNaN(amount) || amount < {{$limit_trade['min_amount']}} || amount > {{$limit_trade['max_amount']}}){
        showMessage(["{{trans('messages.message_limit_trade',array('min_amount'=> $limit_trade['min_amount'],'max_amount'=> $limit_trade['max_amount']))}}"],'error'); 

        
      }      
	  //else if(parseFloat(balance.toFixed(8)) < parseFloat(net_total.toFixed(8))){
      else if(balance < net_total  ){
		//Not enough Balance
        showMessage(['{{trans('messages.buy_not_enough')}} <br />balance: '+balance + ' < ' +net_total + ' net_total'],'error'); 
        //showMessage(['balance: '+balance + ' < ' +net_total + ' net_total'],'error'); 
       
      }
      /*else if((amount*price)>10){
        $('#b_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.message_max_total',array('total'=> '10'))}}</p>');
      }*/else{

		$('#buy_loader').removeClass("hide");

		

		var tradeArray = [price,amount,market_id, 'buy'];
		BootstrapDialog.confirmTrade(tradeArray);
		
		

		<?php
		/*
        $.post('<?php echo action('OrderController@doBuy')?>', {isAjax: 1, price: price, amount: amount, market_id:market_id }, function(response){
          var obj = $.parseJSON(response);
          //app.BrainSocket.message('doTrade',obj.message_socket);          
          socket.emit( 'subscribeAllMarkets', obj.message_socket);
		  socket.emit( 'userOrder', obj.message_socket_user);
          if(obj.status == 'success'){ 
            showMessage(obj.messages,'success');                       
            //showMessageSingle(obj.message['message'],obj.message['status']);                       
			//alert( obj.message['message'] );
          }else{
            showMessage(obj.messages,'error');           
          }
		  
          //$('#buy_loader').fadeOut(500, function() {
            //$('#do_buy').fadeIn();
          //});
		  
		  $('#do_buy').fadeIn();
		  $('#buy_loader').fadeOut(500);



          //console.log('Obj: ',obj);
        });
		*/
		?>
      }
    });
	




	
});

	
	
</script>