@extends('admin.layouts.master')
@section('content')
<!-- Content -->
<!-- Main Content -->
<div id="container" class="clear">
<!-- Main content -->
<div class="main-contain">
<h2>Edit Custom Field</h2>
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
<form class="form-horizontal" role="form" id="edit_user" method="POST" action="{{ route('admin.edit_custom_fields') }}?id={{$_GET['id']}}&amp;prgc={{$_GET['prgc']}}" autocomplete=off>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Coin Name</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			 {!! $that->editCustomFieldsName($q->id) !!}
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Name</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" required  class="form-control" style="height:40px;width:400px;" name="name" value="{{$q->name}}">
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Value</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <textarea required name="value" style="width: 401px; height: 91px; resize: none;">{{$q->value}}</textarea>
			</div>	      	      
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">Type</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
	    		<select name="type">
	    			@foreach(['Text', 'Link', 'Number'] as $q_)
	    				<option @if(strtolower($q_) === $q->type)selected @endif>{{$q_}}</option>
	    			@endforeach
	    		</select>
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_edit">Save</button>
	    </div>
	</div>
</form>
</div>
<!-- Sidebar right -->
</div>