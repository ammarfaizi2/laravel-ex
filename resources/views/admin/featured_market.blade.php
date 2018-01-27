<?php 
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; 
?>
@extends('admin.layouts.master')
@section('content')
<!-- <div class="alert alert-error">{{{ Session::get('error') }}}</div>
<div class="alert alert-success">{{{ Session::get('success') }}}</div> -->
<!-- Content -->
<!-- Main Content -->
<div id="container" class="clear">
<div class="main-contain">
<h2>Featured Market</h2>
@if ( is_array(Session::get('error')) )
<div class="alert alert-error">{{ head(Session::get('error')) }}</div>
@elseif ( Session::get('error') )
  <div class="alert alert-error">{{{ Session::get('error') }}}</div>
@endif
@if ( Session::get('success') )
  <div class="alert alert-success">{{{ Session::get('success') }}}</div>
@endif
@if (Session::get('notice'))
      <div class="alert">{{{ Session::get('notice') }}}</div>
@endif
<script src="{{ asset('assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-paginator.js') }}"></script>
<a href="javascript:void(0);" id="add_market_link">Add new featured market</a>
<script type="text/javascript">
	var q = document.getElementById('add_market_link');
	q.addEventListener('click', function () {
		q.style.display = 'none';
		document.getElementById('add_new_market').style.display = '';
	});
</script>
	<form style="display:none;" class="form-horizontal" role="form" id="add_new_market" method="POST" action="{{ route('admin.add_featured_market') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group">
	    <label class="col-sm-2 control-label">Coin Name</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  {!! $that->getAllCoinNameInDropDown() !!}
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Link</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" style="height:40px;width:400px;" name="link">
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Message</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <textarea name="message" style="width: 401px; height: 91px; resize: none;"></textarea>
			</div>	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Start Date</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
	    		{!! $that->generateDateForm('start', 0, ' (Today)') !!}
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">End Date</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
	    		{!! $that->generateDateForm('end', 3600*24) !!}
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label"></label>
	    <div class="col-sm-10">
	    	 <button type="submit" class="btn btn-primary" id="do_edit">Add</button>
	    </div>
	</div>
</form>
<div id="messages"></div>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>ID</th>
	 	<th>Link</th>
	 	<th>Coin Name</th>
	 	<th>Message</th>	
	 	<th>Start Date</th>	 	
	 	<th>End Date</th>
	 	<th></th>
	</tr> 	
	@foreach($that->getFeaturedMarket($page === 1 ? 0 : $page + 13) as $q)
		@php
			$q->coin = strlen($q->coin) > 20 ? substr($q->coin, 0, 20). '...' : $q->coin;
			$q->message = strlen($q->message) > 20 ? substr($q->message, 0, 20). '...' : $q->message;
			$q->link = strlen($q->link) > 20 ? substr($q->link, 0, 20). '...' : $q->link;
		@endphp
		<tr><td>{{$q->id}}</td><td>{{$q->link}}</td><td>{{$q->type.' - '.$q->name}}</td><td>{{$q->message}}</td><td>{{date('d F Y', strtotime($q->start_date))}}</td><td>{{date('d F Y', strtotime($q->end_date))}}</td><td><a href="{{ route('admin.edit_featured_market') }}?id={{$q->id}}" class="edit_page">Edit</a>  | <a href="?page={{$page}}&amp;delete={{$q->id}}">Delete</a></td></tr>
	@endforeach
</table>
<div id="pager"></div>
<script type='text/javascript'>
    var options = {
        currentPage: {{$page}},
        totalPages: {{(int)ceil($that->featuredMarketPaginator() / 15)}},
        alignment:'right',
        pageUrl: function(type, page, current){
        	return "{{route('admin.featured_market').'?page='}}"+page;
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">		
	<div class="modal-body">		
	</div>
	<div class="modal-footer">
		<button class="btn close-popup" data-dismiss="modal">Close</button>
	</div>
</div>
        </div>
        <!-- Sidebar right -->
            </div>
    <!-- Footer -->
    <!-- Footer -->
