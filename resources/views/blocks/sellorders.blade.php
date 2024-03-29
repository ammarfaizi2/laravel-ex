	<div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">
				{{{ trans('texts.sell_orders')}}}
		  </h3>

          <div class="box-tools pull-right">
			{{{ trans('texts.total')}}}: <span id="sellorders_total_all_{{{Session::get('market_id')}}}"></span> <?php echo $coinsecond ?>
          </div>
        </div>
        <div class="box-body no-padding">
			
			<div id="orders-s-list">
				<div id="orders_sell_{{{Session::get('market_id')}}}" class="clear">
				  <table class="table table-striped">
					<thead>
					  <tr class="header-tb">
							<th>{{{ trans('texts.price')}}}</th>
							<th>{{{ $coinmain }}}</th>
							<th>{{{ $coinsecond }}}</th>
						</tr>
					</thead>
				  </table>
				  
				  <div class="scrolltable  nano">
					  <div class="nano-content">
					
						  <table class="table table-striped sellorders" id="sellorders">
							<tbody>
							  <?php $total_amount_sell=0;  $total_value_sell=0; $tr_i = 0; 
							  //var_dump($sell_orders);
							  ?>
							  @foreach($sell_orders as $sell_order)
							   <?php 
								$tr_i++;
								$total_amount_sell+= $sell_order->total_from_value; 
								$total_value_sell+= $sell_order->total_to_value;
								$price = sprintf('%.8f',$sell_order->price);
								$class_price = str_replace(".", "-", $price);
								$class_price = str_replace(",", "-", $class_price);
							   ?>
								@if ( Auth::guest() )
								  <tr id="order-{{$sell_order->id}}" class="order price-{{$class_price}}" data-counter="{{$tr_i}}" onclick="use_price(1,<?php echo $sell_order->price ?>,<?php echo $sell_order->total_from_value ?>, this)" data-sort="{{{sprintf('%.8f',$sell_order->price)}}}">
									<td class="price">{{{sprintf('%.8f',$sell_order->price)}}}</td>
									<td class="amount">{{{sprintf('%.8f',$sell_order->total_from_value)+0}}}</td>
									<td class="total">{{{sprintf('%.8f',$sell_order->total_to_value)}}}</td>
								  </tr>
								@else
								  @if ( $sell_order->user_id == Confide::user()->id )
								  <?php /* Logged in users order */ ?>
									<!-- style="background-color:#b4d5ff !important;" -->
									<tr id="order-{{$sell_order->id}}" class="order price-{{$class_price}} user_order" data-counter="{{$tr_i}}" onclick="use_price(1,<?php echo $sell_order->price ?>,<?php echo $sell_order->total_from_value ?>, this)" data-sort="{{{sprintf('%.8f',$sell_order->price)}}}">
									  <td class="price">{{{sprintf('%.8f',$sell_order->price)}}} <i class="fa fa-star" data-toggle="tooltip" data-placement="top" title="{{ trans('user_texts.your_order') }}"></i></td>
									  <td class="amount">{{{sprintf('%.8f',$sell_order->total_from_value)+0}}}</td>
									  <td class="total">{{{sprintf('%.8f',$sell_order->total_to_value)}}}</td>
									</tr>
								  @else
									<tr id="order-{{$sell_order->id}}" class="order price-{{$class_price}}" data-counter="{{$tr_i}}" onclick="use_price(1,<?php echo $sell_order->price ?>,<?php echo $sell_order->total_from_value ?>, this)" data-sort="{{{sprintf('%.8f',$sell_order->price)}}}">
									  <td class="price">{{{sprintf('%.8f',$sell_order->price)}}}</td>
									  <td class="amount">{{{sprintf('%.8f',$sell_order->total_from_value)+0}}}</td>
									  <td class="total">{{{sprintf('%.8f',$sell_order->total_to_value)}}}</td>
									</tr>
								  @endif
								@endif
							  @endforeach
							</tbody>
						  </table>
						</div>
					</div>
				  </div>
			  </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          	Page: 1, 2, 3, 4, 5
        </div>
        <!-- /.box-footer-->
	</div>

	



<script type="text/javascript">
	$(document).ready(function() {
		$('#sellorders_total_all_'+{{{Session::get('market_id')}}}).text(  prettyFloat('<?php echo $total_value_sell ?>', 8) );
		$('#sellorders_total_all_box_'+{{{Session::get('market_id')}}}).text(  prettyFloat('<?php echo $total_value_sell ?>', 8) );
	});
</script>