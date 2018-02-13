@extends('layouts.nolayout')
@section('content')


<div class="row" >
	<div class="col-md-4 col-md-offset-4">
	
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-lock fa-lg"></span> <a href="{{ url('/') }}">{{{ Config::get('config_custom.company_name') }}}</a> - <span>{{{ Lang::get('confide::confide.forgot.title') }}}</span></div> 
				<div class="panel-body">
			
					<div class="notice notice-danger hide" id="form_callback">
						<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.error')}}}</strong> <span id="form_callback_msg"></span>
					</div>
						
					@if ( Session::get('error') )
						<div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
					@endif

					@if ( Session::get('notice') )
						<div class="alert alert-info">{{{ Session::get('notice') }}}</div>
					@endif
					
					<hr class="colorgraph">
				
					<!-- <form class="form-horizontal" role="form" id="forgotForm" method="POST" class="login clearfix" action="{{ (Auth::check('UserController@do_forgot_password')) ?: URL::to('/user/forgot') }}" accept-charset="UTF-8"> -->
					<form class="form-horizontal" role="form" id="forgotForm" method="POST" class="login clearfix" action="javascript:void(0);" accept-charset="UTF-8">
					<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
					<fieldset>
						<div class="form-group">
							<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="text" class="form-control" tabindex="1" name="email" id="email" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" value="{{{ Request::old('email') }}}" required>
							</div>
						</div>
						
						<div class="checkbox">
							<label for="reset_terms">
								<input tabindex="3" type="checkbox" name="reset_terms" id="reset_terms" value="1">
							  I acknowledge that my account will be locked for a minimum of XX hours.
							</label>
						</div>
	
						
						<div class="control-group">
						<button tabindex="2" id="forgot_password_button" class="button button-yellow btn btn-lg btn-block g-recaptcha" data-sitekey="6LcdnUUUAAAAALwXU3jX_VrciJdIDmcrN1Q5UVDw" data-callback="onSubmit" type="button" >
							<i class="fa fa-unlock fa-2x"></i> 
							<span>{{{ trans('texts.reset_password') }}}</span>
						</button>
					</div>
					
					</fieldset>
					</form>
				<div>
					
				</div>
				
				
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



{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript"> 

function onSubmit(token) {
	document.getElementById("forgotForm").submit();
}
	
$(document).ready(function() {
	
	$("#forgotForm").submit(function(event) {
		event.preventDefault();
		$("#forgot_password_button").click();
	});
	
	$("#forgot_password_button").on( "click", function(e) {
		e.preventDefault();
		
		// console.log( $( this ).text() );
		if (!$("#form_callback").hasClass("hide")) 
			$("#form_callback").addClass("hide");


		var email = $('#forgotForm #email').val();
		$.ajax({
			type: 'post',
			url: '<?php echo action('UserController@forgot_password')?>',
            datatype: 'json',
            data: {isAjax: 1, email: email },
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
            },
            success:function(response) {
				 var data = $.parseJSON(response);
				console.log(data);
                if(data.status == "success"){
					$("#form_callback").removeClass("hide").removeClass("notice-danger").addClass("notice-success");
					$("#form_callback_msg").text(data.msg);
					$("#forgot_password_button").addClass("hide");
					$(".checkbox").addClass("hide");
					$('input[type=submit]').attr('disabled', 'disabled');
					//$('form').bind('submit',function(e){e.preventDefault();});
					
				}else{
					$("#form_callback").removeClass("hide").removeClass("notice-danger").addClass("notice-danger");
					$("#form_callback_msg").text(data.msg);
				}
				

            }, error:function(response) {
				var data = $.parseJSON(response)
				$("#form_callback").removeClass("hide").removeClass("notice-success").addClass("notice-danger");
				$("#form_callback_msg").text(data.msg);
            }
        });
    
        /*
        $.post('<?php echo action('UserController@forgot_password')?>', {isAjax: 1, email: email}, function(response){

              var title = '{{{ Lang::get('confide::confide.login.submit') }}}';
                var msg = response;
                
                BootstrapDialog.show({
                    title: title,
                    message: msg
                });

        });
        */
        return false;
    });
   
    $('#email').keypress(function(e) {
		
      if (e.keyCode == '13') {
		  $("#forgot_password_button").click();
      }
	}); 
}); 
  


</script>

@stop
