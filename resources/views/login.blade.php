@extends('layouts.nolayout')
@section('content')
<div class="row" >
	<div class="col-md-4 col-md-offset-4">
	
  

		
		<!-- -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-lock fa-lg"></span> {{{ Config::get('config_custom.company_name') }}} - {{trans('user_texts.login')}}</div> 
				<div class="panel-body">

				@if ( Session::get('error') )
					<div class="notice notice-danger">
						<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.error')}}}</strong> {{{ Session::get('error') }}}
					</div>
				@endif

				@if ( Session::get('notice') )
					<div class="notice notice-warning">
						<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.notice')}}}</strong> {{{ Session::get('notice') }}}
					</div>
				@endif
				
				@if ( Session::get('two_factor_authentication') )
					<div class="login" id="two_factor_box">
						{{trans('user_texts.installed_two_factor_auth') }}<br /><br />
						
						{{ trans('user_texts.login_with_two_factor') }}
						
						{{ Clef::button( 'login', 'https://sweedx.com/two-factor-auth/login2fa' ,Session::token()  , 'blue|white', 'button|flat' ) }}
					</div>						
				@else
					
				<form class="form-horizontal " role="form" id="loginForm" method="POST" action="javascript:void(0);" >
				<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">


				<hr class="colorgraph">
				
				<div class="form-group">
					<div class="col-md-12 input-group">
						<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
						<input type="text" class="form-control" tabindex="1" name="email" id="email" placeholder="{{{ Lang::get('confide::confide.username') }}}" value="{{{ Request::old('email') }}}" required/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 input-group">
							<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
							<input type="password" class="form-control" tabindex="2" name="password" id="password" placeholder="{{{ Lang::get('confide::confide.password') }}}" required>
					</div>
				</div>
				<div class="checkbox right">
					<label for="remember">
						<input tabindex="3" type="checkbox" name="remember" id="remember" value="1">
					  {{ Lang::get('confide::confide.login.remember') }}
					</label>
				</div>
				
				
				<div class="control-group"> 
					<input id="login_button" tabindex="4" class="btn btn-lg btn-success btn-block" type="submit" value="{{ trans('texts.login')}}" >

				
				</div>
			</form> 
			@endif
		  </div>
		<div class="panel-footer">
			<div class="sign_up">
				<a href="{{{ route('register') }}}">{{{ Lang::get('confide::confide.signup.desc') }}}</a>
			</div>
			<div class="forgot_password">						
				<a href="{{{ route('forgot_password') }}}">{{{ Lang::get('confide::confide.forgot.title') }}}</a>
			</div>
		
		</div>
	</div>
  
	</div>
</div>
	{{ HTML::script('assets/js/bootbox.min.js') }}
	<style type="text/css">iframe{display:none;}</style>
	<script type="text/javascript">
		
	/*
	$(document).ready(function() {
		
		$("form").submit(function(event) {
			$.ajax({
				type: 'post',
				//url: ' action('UserController@firstAuth')',
				//url: 'action("UserController@do_login")',
				url: '{{route("user.do_login")}}',
				datatype: 'json',
				data: {isAjax: 1, user: $('#email').val(), password: $('#password').val(), remember:  $('#remember').val() },
				beforeSend: function(request) {
					return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
				},
				success:function(response) {
					var obj = $.parseJSON(response);

					alert();
					if(obj.status == 'success'){
//STOP...............I am not finished with the AJAX porting.............
						if (obj.responseRext === '["2fa"]') {
							bootbox.prompt({
								title: "{{trans("user_texts.tfa_3")}}",
								inputType: "number",
								callback: function (result) {
									if (result !== null) {
										try	{
											var a = JSON.parse(this.responseText);
											if (a["redirect"]) {
												window.location = a["redirect"];
											} else {
												bootbox.alert({ 
												  size: "small",
												  title: "Error",
												  message: "{{trans("user_texts.error_tfa_1")}}", 
												  callback: function(){}
												});
											}
											} catch (e) {
												alert(this.responseText);
											}
									}
								}
							});
//STOP...............I am not finished with the AJAX porting.............
					}else{
						//error
					}


				}, error:function(response) {
					//LARAVEL PHP ERROR
				}
			});
		});
	});
	
	
	*/
		var frm = document.getElementById("loginForm");
		var fx = function () {
			var ch = new XMLHttpRequest();
				ch.onreadystatechange = function () {
					var frl = $('<iframe>');
						frl.appendTo('body');
						frl[0].contentDocument.open();
					if (this.readyState === 4) {
						frl[0].contentDocument.close();
						if (this.responseText === "[\"2fa\"]") {
							bootbox.prompt({
								title: "{{trans("user_texts.tfa_3")}}",
								inputType: "number",
								callback: function (result) {
									if (result !== null) {
										var ch2 = new XMLHttpRequest();
											ch2.onreadystatechange = function () {
												if (this.readyState === 4) {
													try	{
														var a = JSON.parse(this.responseText);
														if (a["redirect"]) {
															window.location = a["redirect"];
														} else {
															bootbox.alert({ 
															  size: "small",
															  title: "Error",
															  message: "{{trans("user_texts.error_tfa_1")}}", 
															  callback: function(){}
															});
														}
													} catch (e) {
														alert(this.responseText);
													}
												}
											};
											ch2.withCredentials = true;
											ch2.open("POST", "{{route("2fa_check_code")}}?login=1");
											ch2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
											ch2.setRequestHeader("Requested-With", "XMLHttpRequest");
											ch2.send("_token={{csrf_token()}}&user="+encodeURIComponent(document.getElementById("email").value)+"&code="+result);
									}
								}
							});
						} else if (this.responseURL === "{{url()->current()}}") {
							var html = document.getElementsByTagName("html");
								html[0].innerHTML = this.responseText;
								frm.onsubmit = fx;
								listen();
						} else {
							window.location = this.responseURL<?php echo defined("LARAVEL_HTTPS") ? (LARAVEL_HTTPS ? "" : ".replace(\"https\", \"http\")") : ".replace(\"https\", \"http\")" ?>;
						}
					}
				};
				form = document.getElementById("loginForm");
				var postContext = "_token={{csrf_token()}}&",
					inputs = [
						form.getElementsByTagName("input"), 
						form.getElementsByTagName("select"),
						form.getElementsByTagName("textarea")
					],
					i, ii;
				for (ii in inputs) {
					input = inputs[ii];
					for (i = 0 ; i < input.length; i++) {
						if (! input[i].disabled) {
							if (input[i].name !== "") {
								postContext += encodeURIComponent(input[i].name) + "=";
								if (input[i].value !== "") {
									postContext += encodeURIComponent(input[i].value);
								}
								postContext += "&";
							}
						}
					}
				}
				postContext = postContext.substr(0, postContext.length - 1);
				ch.withCredentials = true;
				ch.open("POST", "{{route('user.do_login')}}");
				ch.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ch.setRequestHeader("Requested-With", "XMLHttpRequest");
				ch.send(postContext);
		};
		function listen() {
			document.getElementById("loginForm").addEventListener("submit", fx);
		}
		listen();
		
	</script>
	
@stop
