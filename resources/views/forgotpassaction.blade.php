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
				<span class="fa fa-lock fa-lg"></span> <a href="{{ url('/') }}">{{{ Config::get('config_custom.company_name') }}}</a> - <span>{{trans('user_texts.password_reset_action')}}</span></div> 
				<div class="panel-body"> 
					<form class="form-horizontal" method="POST" action="?token={{$_GET['token']}}" accept-charset="UTF-8">
						<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
							@if ( Session::get('error') )
							<div class="notice notice-danger" id="alert_field">
									<strong><i class="fa fa-exclamation-triangle fa-2x left"></i>{{{trans('texts.error')}}}</strong>
									@if ( is_array(Session::get('error')) )
										{{ head(Session::get('error')) }}
									@else
										{{ Session::get('error') }}
									@endif
							</div>
							@endif
						@if ( Session::get('notice') )
							<div class="alert alert-info">{{ Session::get('notice') }}</div>
						@endif
					
						<hr class="colorgraph"/>
						<div class="form-group">
							<div class="col-lg-12 input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="password" class="form-control" tabindex="3" name="password" id="password" placeholder="{{{ trans('user_texts.new_password_placeholder') }}}" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-12 input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="password" class="form-control" tabindex="4" name="password_confirmation" id="password_confirmation" placeholder="{{ trans('user_texts.new_password_placeholder2') }}" required>
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
					<div class="control-group">
						<button class="button button-green btn btn-lg btn-block" type="submit">
							<span>{{ trans('user_texts.reset_password_button') }}</span>
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
@stop