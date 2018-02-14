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
		*/ ?>
			
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		{{ HTML::style('assets/css/bootstrap-dialog.min.css') }}
		{{ HTML::style('assets/css/pnotify.custom.min.css') }}
		{{ HTML::style('assets/css/nouislider.min.css') }}
		{{ HTML::style('assets/css/style.css') }}	
		
	
	<?php /* {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js') }} */?>

	
	
	<!-- Scripts are placed here -->
	<?php /* {{ HTML::script('assets/js/jquery-1.10.2.js') }} 	*/ ?>
	<?php /* {{ HTML::script('https://code.jquery.com/jquery-2.1.1.min.js') }}  */ ?>	
	<?php /* https://cdnjs.com/libraries/pnotify 
	{{ HTML::script('assets/js/jquery-2.1.1.min.js') }}
	*/ ?>

	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	{{ HTML::script('assets/js/bootstrap.min.js') }}
	
	{{ HTML::script('assets/js/pnotify.custom.min.js') }}
	{{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
	{{ HTML::script('assets/js/prettyFloat.min.js') }}
	{{ HTML::script('assets/js/nouislider.min.js') }}
	{{ HTML::script('assets/js/bootbox.min.js') }}
	{{ HTML::script('assets/js/custom.js') }}
	
<script >
$(document).ready(function() {   
            var sideslider = $('[data-toggle=collapse-side]');
            var sel = sideslider.attr('data-target');
            var sel2 = sideslider.attr('data-target-content');
            sideslider.click(function(event){
                $(sel).toggleClass('in');
                $(sel2).toggleClass('out');
				$( ".side-content-overlay" ).toggleClass('hide');
            });

			$( ".side-content-overlay" ).click(function(event){
				sideslider.click();
				console.log('clicked overlay');
			});
        });
</script>	
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
		<!-- Header -->
		@include('layouts.header')
		<!-- End Header -->
		<!-- Content -->
		<div id="content" class="side-collapse-container">
			<div class="row">
				<!-- Sidebar -->
				@include('layouts.sidebar')
				<!-- End Sidebar -->
				
				<!-- Content -->
				<div id="main">
					@yield('content')
				</div>
				<!-- End Content -->
				
				<!-- Footer -->
					@include('layouts.footer')
				<!-- End Footer -->
				
			</div>
			<div class="clear"></div>
		</div>
		
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