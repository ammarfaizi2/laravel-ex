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
								
						<div class="alert alert-error alert-danger" id="alert_field">
							@if ( Session::get('error') )
								
									@if ( is_array(Session::get('error')) )
										{{ head(Session::get('error')) }}
									@endif
								
							@endif
						</div>

						@if ( Session::get('notice') )
							<div class="alert alert-info">{{ Session::get('notice') }}</div>
						@endif
					
						<hr class="colorgraph"/>
						
						<div class="form-group">
							<div class="col-lg-12 input-group">
								<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
								<input tabindex="1" minlength="4" type="text" class="form-control" placeholder="{{{ Lang::get('confide::confide.username') }}}" name="username" id="username" value="{{{ Request::old('username') }}}" required>
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
					<?php
							/*
							<h3>Security Questions</h3>
							Please answer two security questions below, these will be used if you ever lose access to your account.<br>Be aware that these answers cannot be changed once you have set up your account.<br><br>
							<table class="table register">
								<tbody>
									<tr>
										<th style="width:180px;">Security Question 1</th>
										<td>
											<select name="question1" class="form-control">
												@foreach($question1s as $question1)
													<option value="{{$question1->id}}" @if(Request::old('question1')==$question1->id) selected @endif>{{$question1->questions}}</option>
												@endforeach
												<!-- <option value="1">What was the name of your first school?</option>
												<option value="2">In what city or town was your first job?</option>
												<option value="3">What is the name of your favorite childhood friend?</option>
												<option value="4">Who was your childhood hero?</option>
												<option value="5">Where were you when you had your first alcoholic drink or cigarette?</option>
												<option value="6">Where were you when you had your first kiss?</option>
												<option value="7">Where did you meet your significant other?</option> -->
											</select>
											<input type="text" name="answer1" value="{{{ Request::old('answer1') }}}">
										</td>
									</tr>
								<tr>
									<th>Security Question 2</th>
										<td>
											<select name="question2" class="form-control">
												@foreach($question2s as $question2)
													<option value="{{$question2->id}}" @if(Request::old('question2')==$question2->id) selected @endif>{{$question2->questions}}</option>
												@endforeach
												<!-- <option value="1">What is the name of your first pet?</option>
												<option value="2">What street did you grow up on?</option>
												<option value="3">What was the name of the hospital where you were born?</option>
												<option value="4">What was your dream job as a child?</option>
												<option value="5">What country is your dream holiday destination?</option>
												<option value="6">What was the make and model of your first car?</option>
												<option value="7">What is your mother's maiden name?</option> -->
											</select>
										<input type="text" name="answer2" value="{{{ Request::old('answer2') }}}">
									</td>
								</tr>
								</tbody>
							</table>
							*/
							?>




					<div id="captchaStatus"></div>

					<input type="checkbox" name="termsofservice" id="termsofservice"><label for="termsofservice">&nbsp; {{trans('user_texts.i_agree_terms')}}</label> {{ HTML::link('post/terms', trans('user_texts.term_service')) }}<a href="/terms/"></a>.
								
					<input type="hidden" value="@if(isset($referral)){{$referral}}@else{{{Request::old('referral')}}}@endif" name="referral">

							
					<div class="control-group">
						<button id="login_ajax" tabindex="4" class="button button-green btn btn-lg btn-block g-recaptcha" data-sitekey="6LcdnUUUAAAAALwXU3jX_VrciJdIDmcrN1Q5UVDw" data-callback="onSubmit" type="submit" >
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
	var formDom = $("#registerForm")[0];
	var urlAction = formDom.action;
		formDom.action = "javascript:void(0);";
	var aldom = $("#alert_field")[0];
		aldom.style.display = "none";
	function showAlert(msg, customClosure = null)
	{
		if (customClosure !== null) {
			customClosure();
		}
		let aldom = $("#alert_field")[0];
		aldom.style.display = "none";
		aldom.innerHTML = msg;
		aldom.style.display = "";
	}
	function s(param1, param2 = null)
	{
		return function () {
			showAlert(param1, param2);
		};
	}
	$(document).ready(function() {
        $.validator.addMethod("CharNumsOnly", function(value, element) {
            return this.optional(element) || /^[a-z0-9 _@\-]+$/i.test(value);
        }, "This field must contain only letters, numbers, or dashes.");

        $("#registerForm").validate({
        	submitHandler: function(form) {
        		if ($("#termsofservice")[0].checked) {
        			let aldom = $("#alert_field")[0];
					aldom.style.display = "none";
	        		var form = $("#registerForm")[0];
					var postContext = "_token={{csrf_token()}}&",
						inputs = [
							form.getElementsByTagName("input")
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
					var challengeField = $("input#recaptcha_challenge_field").val(), 
						responseField = $("input#recaptcha_response_field").val(); 
		            $.ajax({
		                url: '<?php echo action('UserController@checkCaptcha')?>',
		                type: 'POST',
		                datatype: 'json',
		                data: "recaptcha_challenge_field="+encodeURIComponent(challengeField)+"&recaptcha_response_field="+encodeURIComponent(responseField),
		                beforeSend: function(request) {
		                    return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
		                },
		                success:function(response) {
		                    if(response == 1){   
		                        document.getElementById("registerForm").submit();
		                        return true;
		                    }else{
		                        /*$("#captchaStatus").html("<label class='error'>Your captcha is incorrect. Please try again</label>");*/
		                        s("Your captcha is incorrect. Please try again")();
		                        Recaptcha.reload();
		                        return false;
		                    }
		                }, error:function(response) {
		                    showMessageSingle('{{{ trans('texts.error') }}}', 'error');
		                }
		            });
		        } else {
		        	s("Please accept our TOS.")();
		        }
  			},
            rules: {
            	password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                },
                password: {
                    required: true,
                    minlength: 8
                },
                email: {
                    CharNumsOnly: false,
                    required: true,
                    email: true
                },
                username: {
                    required: true,
                    minlength: 5,
					CharNumsOnly: true,
                }
            },
            messages: {
            	password_confirmation: {
                    required: s("Please provide a password."),
                    minlength: s("Your password must be at least 8 characters long."),
                    equalTo: s("Please enter the same password as above.")
                },
                password: {
                    required: s("Please provide a password."),
                    minlength: s("Your password must be at least 8 characters long.")
                },
                email: {
                	required: s("Please enter a username."),
                    email: s("Please enter a valid email address."),
                    CharNumsOnly: s("Email Address must contain only letters, numbers, or dashes."),
                },
                username: {
                    required: s("Please enter a username."),
                    CharNumsOnly: s("Username must contain only letters, numbers, or dashes.")
                }
            }
    	});

      /* 
        $("#registerForm").submit(function(event) {
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
						// alert(response);
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
            
            <?php
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
        });*/
		
		
   });


	function onSubmit(token) {
		document.getElementById("registerForm").submit();
	}

</script>
@stop