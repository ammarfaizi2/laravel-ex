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
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" >
		{{ HTML::style('assets/css/bootstrap-dialog.min.css') }}
		{{ HTML::style('assets/css/pnotify.custom.min.css') }}

		{{ HTML::style('assets/css/style.css') }}	
		

		<!-- Scripts are placed here -->
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		
		{{ HTML::script('assets/js/pnotify.custom.min.js') }}
		
		{{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
		{{ HTML::script('assets/js/custom.js') }}
	
		
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