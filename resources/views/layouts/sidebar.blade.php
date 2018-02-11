<?php

//Required Classes

$trade = new Trade();

?>
@if(isset($market_predefined))
@if($market_predefined)
<!-- >#Sidebar-Wrapper -->
	
	<div class="sidebar-bg">
	</div>
	<div class="sidebar">
		
				
				@if ( Auth::check() )
					<div class="panel panel-profile">
						<div class="panel-heading bg-primary clearfix">
							Welcome back 
							<p>username</p>
						</div>
						<ul class="list-group">
							<li class="list-group-item">
								<i class="fa fa-envelope-o"></i>
								Messages
								<span class="label label-primary">14</span>
							</li>
							<li class="list-group-item">
								<i class="fa fa-comments"></i>
								Chat
								<span class="label label-warning">2</span>
							</li>
							<li class="list-group-item">
								<i class="fa fa-music"></i>
								Morbi leo risus
								<span class="label label-info text-right">1</span>
							</li>
							<li class="list-group-item">
								<i class="fa fa-tags"></i>
								Vestibulum at eros
								<span class="label label-success align-right">3</span>
							</li>
						</ul>
					</div>

					@if(isset($available_balances))
					  <div class="panel panel-default">
						<div class="panel-heading">
						  
								<h4 class="panel-title">
								  Available Balances
								</h4>
						  
						</div>
						  <div class="panel-body">
								<div class="balance nano clear">
									<ul class="market well nano-content">
										<li class="title">
											<span class="name">Coin</span>
											<span class="price">Amount</span>			
										</li>
										<?php
										$ib = 0?>
										@foreach($available_balances as $key=>$available_balance)	
											@if(floatval($available_balance['balance'])>0)	
											<?php
											//var_dump($available_balance);
											?>
											<li>
												<?php
                                                // Dont not link BTC or LTC itself
												if ($available_balance['type'] === 'BTC' || $available_balance['type'] === 'LTC') :?>
													<a href="#" class="maincoin_available">
														<span class="name">{{$available_balance['type']}}</span><span class="price" id="spanBalance-{{$key}}">{{sprintf('%.8f',$available_balance['balance'])}}</span>
													</a>

												<?php
												else :?>
													<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($available_balance['market_id'], 2))}}" >
														<span class="name">{{$available_balance['type']}}</span><span class="price" id="spanBalance-{{$key}}">{{sprintf('%.8f',$available_balance['balance'])}}</span>
													</a>                    
												<?php
												endif;
												?>
											</li>
											<?php $ib++?>
											@endif
											
										@endforeach
										<?php 
										if($ib==0){
										?>
											<li>
												<a href="#">
													<span class="empty_balance">{{{ trans('texts.balance_empty')}}}</span>
												</a>
											</li>
										<?php
										}
										?>
									</ul>
								</div>
						  </div>

					  </div>
					  @endif
				  @endif
			<!-- Search  -->
			<!--
			<div class="sidebar_search" >
				
				<input id="sidebar_search_market" class="form-control hasclear" placeholder="{{ trans('texts.search_market')}}" type="text">
				<span class="clearer fa fa-times-circle  fa fa-search fa-lg form-control-feedback"></span>
			</div>
			-->
			<div class="row">
			<div class="col-md-12">
				<div class="sidebar_search" >
					<div class="clear-input">
						<input id="sidebar_search_market" type="text" class="form-control" placeholder="{{ trans('texts.search')}}">
						<span class="fa fa-search fa-lg"></span>
					</div>
				</div>
			</div>
			</div>

			<!--
			<div class="form-group has-feedback">
      <label for="txt1" class="col-sm-2 control-label">Label 1</label>
      <div class="col-sm-10">
        <input id="txt1" class="form-control hasclear" placeholder="Textbox 1" type="text">
        <span class="clearer glyphicon glyphicon-remove-circle form-control-feedback"></span>
      </div>
    </div>
	-->
	
	


			<!--  
			<div class="sidebar_search" >
				<i class="fa fa-search fa-lg" id="sidebar_search_icon"></i>
				<input type="search" class="form-control" placeholder="{{ trans('texts.search_market')}}" id="sidebar_search_market" />
			</div>
			-->

			<!-- >#Sidebar Market Tabs -->
			
				<div class="sidebar_tabs">

				
				<!-- Sidebar Markets Nav Tabs -->
								<div class="card">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#side_btc_market" aria-controls="side_btc_market" role="tab" data-toggle="tab">BTC</a></li>
                                        <li role="presentation"><a href="#side_ltc_market" aria-controls="side_ltc_market" role="tab" data-toggle="tab">LTC</a></li>
                                        <li role="presentation" class="navbar-right"><a href="#side_fav_market" aria-controls="side_fav_market" role="tab" data-toggle="tab"><i class="fa fa-star"></i> Fav</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
										<div id="side_btc_market" class="tab-pane active" role="tabpanel">
										  <!-- BTC Markets -->
										  <div class="panel panel-default">
											  <div class="panel-body">
												<div class="btc_market coinmarket nano clear">
										<?php
										//var_dump($btc_datainfo);
										//var_dump($btc_markets); 
										//$btc_markets = [];
										//$ltc_markets = [];
										?>
										
												<ul class="market well nano-content">
														<li class="title">
															<span class="name">Coin</span>
															<span class="price">Price</span>
															<span class="change">% Change</span>
														</li>

														@foreach($btc_markets as $btc_market)
														<?php 
															$total_btc = isset($btc_datainfo[$btc_market->id]['total'])? $btc_datainfo[$btc_market->id]['total']:0; 
															$curr_price = isset($btc_datainfo[$btc_market->id][0]['price'])? $btc_datainfo[$btc_market->id][0]['price']:0;
															$pre_price = isset($btc_datainfo[$btc_market->id][1]['price'])? $btc_datainfo[$btc_market->id][1]['price']:0;
															$change = 0;
															//$change = ($pre_price!=0) ? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100) : 0;
															//$change = $change +0;
															
															if ( isset($btc_datainfo[$btc_market->id][1]['created_at']) ){
																$pre_price = $trade->getChangeDayPrevPrice($btc_datainfo[$btc_market->id][1]['created_at'], $pre_price);
															}
																$change = $trade->getChangeDayPrice($pre_price, $curr_price, $pre_price);


															
															//echo "Cur: ".$curr_price." -- Pre: ".$pre_price;
															//if($change>0) $change = '+'.$change;
															
															
														?>
															<li class="volume" id="volume-{{$btc_market->id}}" data-toggle="popover" data-placement="right" title="Volume" data-content="{{sprintf('%.8f',$total_btc)}} BTC">
																<a href="{{$marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($btc_market->id).'_BTC')}}">
																	<span class="name">
																		@if($btc_market->enable_trading == 0) <i class="fa fa-exclamation-triangle red" data-toggle="popover" data-placement="bottom" title="{{$btc_market->type}}" data-content="{{trans('texts.market_disabled')}}" ></i> @endif
																		{{$btc_market->type}}
																	</span>
																	<span class="hide">{{$btc_market->name}}</span>
																	<span class="price" yesterdayPrice="{{sprintf('%.8f',$pre_price)}}" id="spanPrice-{{$btc_market->id}}">{{sprintf('%.8f',$curr_price)}}</span>
																		@if($change==0)
																			<span class="change" id="spanChange-{{$btc_market->id}}">{{$change}}% <i class="fa fa-minus"></i></span>
																		@elseif($change>0)
																			<span class="change up" id="spanChange-{{$btc_market->id}}">{{$change}}% <i class="fa fa-arrow-up"></i></span>
																		@else
																			<span class="change down" id="spanChange-{{$btc_market->id}}">{{$change}}% <i class="fa fa-arrow-down"></i></span>
																		@endif
																</a>
															<?php /* <div class="volume" id="volume-{{$btc_market->id}}" data-toggle="popover" data-placement="right" title="sdasd">Vol: {{sprintf('%.8f',$total_btc)}} BTC</div> */?>
															</li> 
														@endforeach
													</ul>
													</div>
													
											  </div>
											
										  </div>
										  
										</div>
										<div id="side_ltc_market" class="tab-pane " role="tabpanel">
										  <!-- LTC Markets-->
										  <div class="panel panel-default">
											  <div class="panel-body">
													<div class="ltc_market coinmarket nano clear">
														<ul class="market well nano-content">
															<li class="title">
																<span class="name">Coin</span>
																<span class="price">Price</span>
																<span class="change">% Change</span>
															</li>
															@foreach($ltc_markets as $ltc_market)
															<?php 
																$total_ltc = isset($ltc_datainfo[$ltc_market->id]['total'])? $ltc_datainfo[$ltc_market->id]['total']:0; 
																$curr_price = isset($ltc_datainfo[$ltc_market->id][0]['price'])? $ltc_datainfo[$ltc_market->id][0]['price']:0;
																$pre_price = isset($ltc_datainfo[$ltc_market->id][1]['price'])? $ltc_datainfo[$ltc_market->id][1]['price']:0;
																
																//if($change>0) $change = '+'.$change;
																
																if ( isset($ltc_datainfo[$ltc_market->id][1]['created_at']) ){
																	$pre_price = $trade->getChangeDayPrevPrice($ltc_datainfo[$ltc_market->id][1]['created_at'], $pre_price);
																}
																$change = $trade->getChangeDayPrice($pre_price, $curr_price, $pre_price);
																
																
																
																/*
																if ( isset($ltc_datainfo[$ltc_market->id][1]['created_at']) ) {
																	//Check previous trade date and compare to the previous day
																	if ( strtotime($ltc_datainfo[$ltc_market->id][1]['created_at']) < strtotime('yesterday') )
																		$pre_price = 0;
																}
																$change = ($pre_price!=0)? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100) : 0;
																$change = $change +0;
																*/
																
																/*
																echo '<pre>';
																print_r($ltc_datainfo[$ltc_market->id]);
																echo '</pre>';
																*/
															?>
																<li class="volume" id="volume-{{$ltc_market->id}}" data-toggle="popover" data-placement="right" title="Volume" data-content="{{sprintf('%.8f',$total_ltc)}} BTC">
																	<a href="{{ $marketUrl = route('market', \App\Http\Controllers\HomeController::buildMarketUrl($ltc_market->id).'_LTC')}}">
																	<span class="name">
																		@if($ltc_market->enable_trading == 0) <i class="fa fa-exclamation-triangle red" data-toggle="popover" data-placement="bottom" title="{{$ltc_market->type}}" data-content="{{trans('texts.market_disabled')}}"></i> @endif
																		{{$ltc_market->type}}
																	</span>
																	<span class="price" yesterdayPrice="{{sprintf('%.8f',$pre_price)}}" id="spanPrice-{{$ltc_market->id}}">{{sprintf('%.8f',$curr_price)}}</span>
																	
																		@if($change==0)
																			<span class="change" id="spanChange-{{$ltc_market->id}}">{{$change}}% <i class="fa fa-minus"></i></span>
																		@elseif($change>0)
																			<span class="change up" id="spanChange-{{$ltc_market->id}}">{{$change}}% <i class="fa fa-arrow-up"></i></span>
																		@else
																			<span class="change down" id="spanChange-{{$ltc_market->id}}">{{$change}}% <i class="fa fa-arrow-down"></i></span>
																		@endif
																	</a>
																	<?php /*<div class="volume" id="volume-{{$ltc_market->id}}" >Vol: {{sprintf('%.8f',$total_ltc)}} LTC</div> */?>
																</li>
															@endforeach
														</ul>
													</div>
											  </div>

										  </div>
										  
										</div>
										<div id="side_fav_market" class="tab-pane fade" role="tabpanel">
										  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
										</div>
									</div>
								</div>


					
				</div>
			
		  <!-- <#Sidebar Market Tabs -->
			

			  
			<!-- Statics -->
			<div class="panel panel-default">
				<div class="panel-heading">
						<h4 class="panel-title">
						  Statistics
						</h4>
				</div>
				<div id="collapse24hStatistics" >
				  <div class="panel-body">
						<ul class="market well stats">
							<?php
							$number_btc = isset($statistic_btc->number_trade)? $statistic_btc->number_trade:0;
							$volume_btc = (isset($statistic_btc->total) && !empty($statistic_btc->total))? sprintf('%.8f',$statistic_btc->total):0;
							$number_ltc = isset($statistic_ltc->number_trade)? sprintf('%.8f',$statistic_ltc->number_trade):0;
							$volume_ltc = (isset($statistic_ltc->total) && !empty($statistic_ltc->total))? sprintf('%.8f',$statistic_ltc->total):0;
							?>
							<li>BTC Volume <span class="change">{{$volume_btc}} BTC</span></li>
							<li>LTC Volume <span class="change">{{$volume_ltc}} LTC</span></li>
							<li>Number of Trades <span class="change">{{$number_ltc+$number_btc}}</span></li> 
						</ul>
				  </div>
				</div>
				
		</div>
			  
		<div class="panel panel-default">
			<div id="onlineUsers">
				<ul class="market well stats">
					<li>
					{{{ trans('texts.online_clients')}}}: <span id="client_count"></span>
					</li>
				</ul>
			</div>
		</div>
		
			<!-- Support -->
			  <div class="panel panel-default">
				<div class="panel-heading">
					<a data-toggle="collapse"  href="#supportBase">
						<h4 class="panel-title">
						<span class="glyphicon glyphicon-minus"></span>
						  Support/Feedback
						</h4>
					</a>
				</div>
				<div id="supportBase" >
				  <div class="panel-body">
						
						<ul class="market well stats">
							<li>
							<a href="mailto:<?php echo Config::get('config_custom.company_support_mail')?>"><?php echo Config::get('config_custom.company_support_mail')?></a>
							</li>
						</ul>
				  </div>
				</div>
				
			  </div>
				
				<?php
				/*
				<div id="twitter-feed">
					<a class="twitter-timeline" href="https://twitter.com/Sweedx_com" data-widget-id="533686062652997632">Tweets by @Sweedx_com</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

				</div>
				*/?>
				
			  


				
						

					
					<br />
					
					
						<?php
						/*
						<script type="text/javascript" src="https://www4.yourshoutbox.com/shoutbox/start.php?key=505247175"></script>
						

						
						<br />

						
						*/
						?>
				
				
				
				<br/><br/>
	</div>
	

<!-- <#Sidebar-Wrapper -->
@endif
@endif