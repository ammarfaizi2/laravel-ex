<!DOCTYPE html>
<html>
	<head>
		<!-- Set the viewport so this responsive site displays correctly on mobile devices -->
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
		<title> 
			@section('title')
				{{{ Config::get('config_custom.company_name_domain') }}} - {{{ Config::get('config_custom.company_slogan') }}}
			@show
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- CSS are placed here -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		{{ HTML::style('assets/css/bootstrap-dialog.min.css') }}
		{{ HTML::style('assets/css/pnotify.custom.min.css') }}

		{{ HTML::style('assets/css/style.css') }}	
		

		<!-- Scripts are placed here -->
		{{ HTML::script('assets/js/jquery-1.10.2.js') }} 	
		{{ HTML::script('assets/js/bootstrap.min.js') }}
		
		{{ HTML::script('assets/js/pnotify.custom.min.js') }}
		
		{{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
		{{ HTML::script('assets/js/custom.js') }}
	
		<script src="//www.google.com/recaptcha/api.js" async defer></script>
	</head>
<body class="@if ( Auth::guest() ) guest @else logged @endif">
    <!-- Content -->
	<div id="content marginauto">

		<div class="content_nolayout">
			@yield('content')
		</div>
		<div class="clear"></div>
	</div>
	
</html>