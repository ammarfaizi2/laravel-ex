<!DOCTYPE html>
<html lang="en" id="mainHTML">
	<head>
	<!-- Set the viewport so this responsive site displays correctly on mobile devices -->
    <meta name="viewport" content="width=device-width">
		<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
		<title> 
			@section('title') {{{ Config::get('config_custom.company_name_domain') }}} - {{{ Config::get('config_custom.company_slogan') }}} @show
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="_token" content="{{ csrf_token() }}" />
		
		<!-- CSS are placed here -->
		
		<!-- Latest compiled and minified CSS -->
		<?php /* 
			{{ HTML::style('assets/css/bootstrap.min.css') }}
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> 
			<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.6/css/all.css" >
		*/ ?>
			
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" >
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/css/bootstrap-slider.css" integrity="sha256-qkeO+BtgpANRnm6UfrclSLyB+QdfOK4qtspUK6qpnGk=" crossorigin="anonymous" />

		{{ HTML::style('assets/css/bootstrap-dialog.min.css') }}
		{{ HTML::style('assets/css/pnotify.custom.min.css') }}
		
		{{ HTML::style('assets/css/style.css') }}	
		
	
	<?php /* {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js') }} */?>

	
	
	<!-- Scripts are placed here -->
	<?php /* {{ HTML::script('assets/js/jquery-1.10.2.js') }} 	*/ ?>
	<?php /* {{ HTML::script('https://code.jquery.com/jquery-2.1.1.min.js') }}  */ ?>	
	<?php /* https://cdnjs.com/libraries/pnotify 
	{{ HTML::script('assets/js/jquery-2.1.1.min.js') }}
	*/ ?>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	@if(Request::is("/user/messages"))
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
	@endif
	<!-- Include all compiled plugins (below), or include individual files as needed -->

	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.min.js" integrity="sha256-ssw743RfM8cbNhwou26tmmPhiNhq3buUbRG/RevtfG4=" crossorigin="anonymous"></script> -->
	
	{{ HTML::script('assets/js/bootstrap-slider.js') }}
	

	<!-- {{ HTML::script('assets/js/bootstrap.min.js') }} -->
	{{ HTML::script('assets/js/pnotify.custom.min.js') }}
	{{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
	{{ HTML::script('assets/js/prettyFloat.min.js') }}
	{{ HTML::script('assets/js/bootbox.min.js') }}
	{{ HTML::script('assets/js/custom.js') }}
	

	<?php
	/*
	<script type="text/javascript">
    var queries = {{ json_encode(DB::getQueryLog()) }};
    console.log('//////////////////////////////// Database Queries /////////////////////////////////');
    console.log(' ');
    queries.forEach(function(query) {
        console.log('   ' + query.time + ' | ' + query.query + ' | ' + query.bindings[0]);
    });
    console.log(' ');
    console.log('///////////////////////////////// End Queries /////////////////////////////////');
	</script>
	*/
	?>


	</head>
	<body class="@if ( Auth::guest() ) guest @else logged @endif">
		<div class="wrapper">

			
			<?php
			//Full width for only Startpage
			//Get the PAGE
			//echo '<h3> test is: '.Request::url().'</h3>';
			//echo '<h3> test is2: '.Route::current()->getName().'</h3>';

			$content_class="content_center";
			if( Route::current()->getName() == '' )
				$content_class="content";

			?>
			<!-- Header -->
				@include('layouts.header')
			<!-- End Header -->
			<!-- Content -->
			<div id="content" class="side-collapse-container {{ $content_class }} ">
					<!-- Sidebar -->
					@include('layouts.sidebar')
					<!-- End Sidebar -->

					
					
					<!-- Content -->
					<div id="main">
						@yield('content')
					</div>
					<!-- End Content -->
					


			</div>
		</div>
			<!-- Footer -->
				@include('layouts.footer')
			<!-- End Footer -->		
		
		@if(isset($need2fa) and $need2fa === true)
			<!--Google 2fa -->
			<?php $user = Confide::user(); ?>
			@if($user->google2fa_secret)
				@include('2fa_form_handler')
			@endif
		@endif
	

		
		<!-- Overlay -->
		<div class="side-content-overlay hide"></div>
		

		
	</body>
</html>