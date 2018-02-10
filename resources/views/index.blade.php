@extends('layouts.default')
	<?php
	// Set individual Market title
	if ($market_predefined) :?>
	@section('title')
		<?php echo Config::get('config_custom.company_name_domain') . ' - ' . $market_from . ' / ' . $market_to . ' ' . trans('texts.market') ?>
	@stop
	@section('description')
		<?php echo Config::get('config_custom.company_name_domain') . ' - '. Config::get('config_custom.company_slogan') ?>
	@stop
	<?php
	/*
		//@section('title', 'This is an individual page title')
		//@section('description', 'This is a description')
		*/
	endif;
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
			<div>

				<!-- #Startpage Markets -->
				@if(isset($show_all_markets) && $show_all_markets === true)
					@include('blocks.startmarkets')
				@endif
				<!-- #Specifik/Predefined Markets -->
				@if($market_predefined)
					@include('blocks.predefinedmarket')
				@endif
			</div>
			
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

@stop
