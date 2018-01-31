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
<h2>Custom Fields Market</h2>
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
<a href="javascript:void(0);" id="add_market_link">Add new custom field</a>
<script type="text/javascript">
    var q = document.getElementById('add_market_link');
    q.addEventListener('click', function () {
        q.style.display = 'none';
        document.getElementById('add_new_custom_field').style.display = '';
    });
</script>
    <form style="display:none;" class="form-horizontal" role="form" id="add_new_custom_field" method="POST" action="{{ route('admin.add_custom_fields') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label class="col-sm-2 control-label">Coin Name</label>
        <div class="col-sm-10">
            <div class="input-append">
              {!! $that->getAllCoinNameInDropDownCustomFields() !!}
            </div>                
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <div class="input-append">
              <input required type="text" class="form-control" style="height:40px;width:400px;" name="name">
            </div>                
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Value</label>
        <div class="col-sm-10">
            <div class="input-append">
              <textarea  required  name="value" style="width: 401px; height: 91px; resize: none;"></textarea>
            </div>        
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Type</label>
        <div class="col-sm-10">
            <div class="input-append">
                <select name="type">
                    <option>Text</option>
                    <option>Link</option>
                    <option>Number</option>
                </select>
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
        <th>No.</th>
        <th>Coin Name</th>
        <th>Total Custom Fields</th>
        <th></th>
    </tr>
    @php
        $no = $page === 1 ? 1 : $page + 15;
    @endphp
    @foreach($that->getCustomFields($page === 1 ? 0 : $page + 13) as $q)
        <tr><td>{{$no++}}</td><td>{{$q->name.' to '.$that->walletTo($q->id)}}</td><td>{{$q->total_custom_fields}}</td><td><a href="{{ route('admin.edit_custom_fields') }}?id={{$q->id}}" class="edit_page">Edit</a></td></tr>
    @endforeach
</table>
<div id="pager"></div>
<script type='text/javascript'>
    var options = {
        currentPage: {{$page}},
        totalPages: {{(int)ceil($that->customFieldsPaginator() / 15)}},
        alignment:'right',
        pageUrl: function(type, page, current){
            return "{{route('admin.custom_fields').'?page='}}"+page;
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
