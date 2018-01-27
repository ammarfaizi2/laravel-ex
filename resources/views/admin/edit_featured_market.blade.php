@extends('admin.layouts.master')
@section('content')
<!-- Content -->
<!-- Main Content -->
<div id="container" class="clear">
<!-- Main content -->
<div class="main-contain">
<h2>Edit Featured Market</h2>
	@if ( is_array(Session::get('error')) )
        <div class="alert alert-error">{{ head(Session::get('error')) }}</div>
	@elseif ( Session::get('error') )
      <div class="alert alert-error">{{{ Session::get('error') }}}</div>
	@endif
	@if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
	@endif

	@if ( Session::get('notice') )
	      <div class="alert">{{{ Session::get('notice') }}}</div>
	@endif
<form class="form-horizontal" role="form" id="edit_user" method="POST" action="{{ route('admin.edit_featured_market') }}?id={{$_GET['id']}}" autocomplete=off>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Coin Name</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			 {!! $that->editFeaturedCoinName($q->coin) !!}
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Link</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" required  class="form-control" style="height:40px;width:400px;" name="link" value="{{$q->link}}">
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Message</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <textarea required name="message" style="width: 401px; height: 91px; resize: none;">{{$q->message}}</textarea>
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Start Date</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
	    		<?php $tm = strtotime($q->start_date); ?>
	    		{!! $that->generateDateForm('start', 0, ' (Today)', $tm - 3600 * 24 * 5, date('Y-m-d', $tm)) !!}
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">End Date</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
	    		<?php $tm = strtotime($q->end_date); ?>
	    		{!! $that->generateDateForm('end', -3600*24*100, ' (Today)', null, date('Y-m-d', $tm)) !!}
			</div>
	    </div>
	</div>
	<div class="form-group">
		<input type="hidden" class="form-control" id="user_id" value="100" name="user_id">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_edit">Save</button>
	    </div>
	</div>
</form>
<div id="messages"></div>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">		
	<div class="modal-body">		
	</div>
	<div class="modal-footer">
		<button class="btn close-popup" data-dismiss="modal">Close</button>
	</div>
</div>
<script src="http://bitbase2.dev/assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {    	
        $("#edit_user").validate({
            rules: {
                fullname: "required",                
                password: {
                    minlength: 8
                },
                password_confirmation: {
                    minlength: 8,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                fullname: "Please enter your full name.",               
                password: {
                    required: "Please provide a password.",
                    minlength: "Your password must be at least 8 characters long."
                },
                confirm_password: {
                    required: "Please provide a password.",
                    minlength: "Your password must be at least 8 characters long.",
                    equalTo: "Please enter the same password as above."
                },
                email: "Please enter a valid email address.",
            }
	});

   });
</script>
        </div>
        <!-- Sidebar right -->
            </div>