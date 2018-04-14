@extends('layouts.nolayout')
@section('content')
<?php
/*
https://www.google.com/recaptcha/admin#site/340106525?setup
https://developers.google.com/recaptcha/docs/invisible
https://developers.google.com/recaptcha/docs/verify
*/
?>

<div class="row" >
	<div class="col-md-4 col-md-offset-4">
 
 
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-lock fa-lg"></span> <a href="{{ url('/') }}">{{{ Config::get('config_custom.company_name') }}}</a> - <span>{{trans('user_texts.register')}}</span></div> 
				<div class="panel-body"> 
 
					<form class="form-horizontal" id="registerForm" method="POST" action="{{{ (Auth::check('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8">
						<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
											
						<div class="notice notice-danger hide alert_field_js" >
							<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.error')}}}</strong>
							<ul>
								<li class="hide username_msg">Username must contain only letters, numbers, or dashes and have a minimum of {{ env("MIN_USERNAME_LENGTH", 5) }} chars</li>
								<li class="hide email_msg">A valid email address is required</li>
								<li class="hide password_msg">Your password must be at least 8 characters long</li>
								<li class="hide password_confirmation_msg">Your confirmed password is not the same!</li>
								<li class="hide termsofservice_msg">ToS must be accepted!</li>
							</ul>
						</div>
						
						@if ( Session::get('error') )
							<div class="notice notice-danger">
								<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.error')}}}</strong>
									@if ( is_array(Session::get('error')) )
										{{ head(Session::get('error')) }}
									@else
										{{ head(Session::get('error')) }}
									@endif
							</div>
						@endif
						
						@if ( Session::get('notice') )
							<div class="notice notice-success">
								<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.success')}}}</strong>
								{{ Session::get('notice') }}
							</div>
						@endif
					
						<hr class="colorgraph"/>
						
						<div class="form-group">
							<div class="col-lg-12 input-group">
								<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
								<input tabindex="1" minlength="5" type="text" class="form-control" placeholder="{{{ Lang::get('confide::confide.username') }}}" name="username" id="username" value="{{{ Request::old('username') }}}" required>
								
								
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-12 input-group">
									<span class="input-group-addon"><i class="fa fa-envelope fa-lg"></i></span>
									<input tabindex="2" type="text" name="email" id="email" class="form-control" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" value="{{{ Request::old('email') }}}" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-12 input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="password" class="form-control" tabindex="3" name="password" id="password" placeholder="{{{ Lang::get('confide::confide.password') }}}" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-12 input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="password" class="form-control" tabindex="4" name="password_confirmation" id="password_confirmation" placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" required>
							</div>
						</div>


					<div id="captchaStatus"></div>

					<div>
						<input type="checkbox" name="termsofservice" id="termsofservice"><label for="termsofservice">&nbsp; {{trans('user_texts.i_agree_terms')}}</label> {{ HTML::link('post/terms', trans('user_texts.term_service')) }}<a href="/terms/"></a>.
						
					</div>
					
					<input type="hidden" value="@if(isset($referral)){{$referral}}@else{{{Request::old('referral')}}}@endif" name="referral" class="form-control">

							
					<div class="control-group">
						<!-- <button id="register_ajax" tabindex="4" class="button button-green btn btn-lg btn-block " type="submit" >Test</button> -->
						<!-- <button id="login_ajax" tabindex="4" class="button button-green btn btn-lg btn-block g-recaptcha" data-sitekey="6LcdnUUUAAAAALwXU3jX_VrciJdIDmcrN1Q5UVDw" data-callback="onSubmit" type="submit" > -->
						
						<button id="register_ajax" tabindex="4" class="button button-green btn btn-lg btn-block " type="submit" >
							<i class="fa fa-user-plus fa-2x"></i> 
							<span>{{{ Lang::get('confide::confide.signup.submit') }}}</span>
						</button>
					</div>
				</form>
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
<!-- --> 
 
</div>
{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
$(document).ready(function() {

// Username can't be blank
$('#username').on('input', function() {
	var input=$(this);
	var is_username=input.val();
	if(is_username.length >= {{ env("MIN_USERNAME_LENGTH", 5) }}){
		input.removeClass("invalid").addClass("valid");
		$('.username_msg').addClass("hide");
	}
	else{
		input.removeClass("valid").addClass("invalid");
		$('.username_msg').removeClass("hide");
	}
});

// Email must be an email
$('#email').on('input', function() {
	var input=$(this);
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var is_email=re.test(input.val());
	if(is_email){input.removeClass("invalid").addClass("valid");}
	else{input.removeClass("valid").addClass("invalid");}
});
// Password can't be blank
$('#password').on('input', function() {
	var input=$(this);
	var is_password=input.val();
	if(is_password.length >= 8){input.removeClass("invalid").addClass("valid");}
	else{input.removeClass("valid").addClass("invalid");}
});
/*
// Password can't be blank
$('#password_confirmation').on('input', function() {
	var input=$(this);
	var is_password=input.val();
	console.log(is_password +" =="+$('#password').val());
	if(is_password === $('#password').val() ){input.removeClass("invalid").addClass("valid");}
	else{input.removeClass("valid").addClass("invalid");}
});
*/
// Password Confirmation
$('#password, #password_confirmation').on('keyup', function () {
  var password = String($('#password').val()), password_confirm = String($('#password_confirmation').val());

  if(password.length >= 8){
	  if ( (password === password_confirm)) {
		$('#password').removeClass("invalid").addClass("valid");
		$('#password_confirmation').removeClass("invalid").addClass("valid");
		//alert('same password');
		
	  } else {
		$('#password').removeClass("invalid").addClass("valid");
		$('#password_confirmation').removeClass("valid").addClass("invalid");
		//alert("not same");
	  }
  }else{
	  $('#password').removeClass("valid").addClass("invalid");
  }
});

$("#termsofservice").change(function(){
//$('#termsofservice').on('input', function() {
	var input=$(this);
	if( input.is(':checked') ){ input.removeClass("invalid").addClass("valid");}
	else{input.removeClass("valid").addClass("invalid");}
});

// After Form Submitted Validation
$("#registerForm button#register_ajax").click(function(event){
	event.preventDefault(); 
	$(".notice").addClass("hide");
	console.log("============================");
	var i, s, form_inputs = ["username", "email", "password", "password_confirmation", "termsofservice"], len = form_inputs.length;
	var error_show = false;
	for(i=0; i<len; ++i){
		if (i in form_inputs){
			s= form_inputs[i];
				//do something with s
			console.log("s->: "+s);
			//$("#"+s).next().html("test "+s);
			
			var element = $("#"+s);
			var element_error = $("."+s+"_msg");
			//element.removeClass("hide");
			
			var valid=element.hasClass("valid");
			console.log(s+ " is "+valid);
			if (valid){element_error.removeClass("error").addClass("hide");}
			else{element_error.removeClass("hide").addClass("error"); error_show=true;}
			
		}else{
			console.log("wot ?");
		}
	}
	
	if (!error_show){
		$(".alert_field_js").addClass("hide");
		document.getElementById("registerForm").submit();
	}
	else{
		$(".alert_field_js").removeClass("hide");
		event.preventDefault(); 
	}
	
	
	//https://formden.com/blog/validate-contact-form-jquery
	/*
	var form_data=$("#registerForm").serializeArray();
	var error_free=true;
	console.log(form_data);
	
	if( $("#termsofservice").is(':checked') ){ 
		var error_element=$("span", $("#registerForm  #termsofservice").parent());
		error_element.removeClass("error").addClass("hide"); error_free=true; 
		
			
	}else{
		var error_element=$("span", $("#registerForm #termsofservice").parent());
		error_element.removeClass("hide").addClass("error"); error_free=false; 
	}
	
	for (var input in form_data){
		form_input_name = form_data[input]['name'];
		if(form_input_name != "g-recaptcha-response" && form_input_name != "referral" && form_input_name != "_token"){
			var element=$("#registerForm #"+form_input_name);
			console.log('element: '+"#registerForm #"+form_input_name);
			var valid=element.hasClass("valid");
			var error_element=$("span", element.parent());
			if (!valid){error_element.removeClass("error").removeClass("hide"); error_free=false;}
			else{error_element.removeClass("hide").addClass("error"); }
		}
	}
	
	if (!error_free){
		event.preventDefault(); 
		alert("errors founds");
	}
	else{
		alert('No errors: Form will be submitted');
	}
	*/
});

       
        $("#registerForm").submit(function(event) {
			/*
			event.preventDefault();
            var challengeField = $("input#recaptcha_challenge_field").val();
            var responseField = $("input#recaptcha_response_field").val(); 
            console.log('responseField',responseField);         
			
			$.ajax({
				type: 'post',
				url: '<?php echo action('UserController@checkCaptcha')?>',
				datatype: 'json',
				data: {recaptcha_challenge_field: challengeField, recaptcha_response_field: responseField },
				beforeSend: function(request) {
					return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
				},
				success:function(response) {
					if(response == 1){   
						document.getElementById("registerForm").submit();                  
						return true;
					}else{
						$("#captchaStatus").html("<label class='error'>Your captcha is incorrect. Please try again</label>");
						Recaptcha.reload();
						return false;
					}
				}, error:function(response) {
					showMessageSingle('{{{ trans('texts.error') }}}', 'error');
				}
			});
			*/
            <?
			/*
			$.post('<?php echo action('UserController@checkCaptcha')?>', {recaptcha_challenge_field: challengeField, recaptcha_response_field: responseField }, function(response){
                if(response == 1){   
                    document.getElementById("registerForm").submit();                  
                    return true;
                }else{
                    $("#captchaStatus").html("<label class='error'>Your captcha is incorrect. Please try again</label>");
                    Recaptcha.reload();
                    return false;
                }
            });
			*/
			?>
        });
   });
   
	function onSubmit(token) {
		//document.getElementById("registerForm").submit();
		console.log("form validate is calling");
		//$("#registerForm").validate();		
	}
	
</script>
@stop