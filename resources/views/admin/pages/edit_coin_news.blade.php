@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('ckeditor/ckeditor.js') }}
{{ HTML::script('ckfinder/ckfinder.js') }}
<h2>Edit Market News</h2>
@if ( is_array(Session::get('error')) )
        <div class="alert alert-danger">{{ head(Session::get('error')) }}</div>
	@elseif ( Session::get('error') )
      <div class="alert alert-danger">{{{ Session::get('error') }}}</div>
	@endif
	@if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
	@endif

	@if ( Session::get('notice') )
	      <div class="alert">{{{ Session::get('notice') }}}</div>
	@endif
<?php // dd($news, $that->getWalletList()->toArray()); ?>
<form class="form-horizontal" role="form" method="POST" action="{{ route('admin.edit_market_news') }}" id="add_post">	
    <div class="form-group">
        <label for="market_id" class="col-sm-2 control-label">Coin</label>
        <div class="col-sm-10">
            <select class="form-control" name="market_id" id="market_id">
                @foreach ($that->getWalletList() as $key => $val)
                    <option value="{{$val->id}}" @if($val->id === $news->wallet_id) selected @endif>{{$val->type." - ".$val->name}}</option>
                @endforeach
            </select>
        </div>
    </div> 
	<div class="form-group">
	    <label for="Title" class="col-sm-2 control-label">Title</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="title" id="title" value="{{$news->title}}">	      
	    </div>
	</div>	
	<div class="form-group">	    
	    <div class="col-sm-10">	      
	      <textarea class="form-control" id="content" name="content" cols="90" rows="10">{{$news->content}}</textarea>	     
	       <script type="text/javascript"> 
	       $(function() {	 
	       	var editor = CKEDITOR.replace('content', { filebrowserBrowseUrl : '<?php echo asset("ckfinder/ckfinder.html"); ?>', filebrowserImageBrowseUrl : '<?php echo asset("ckfinder/ckfinder.html?Type=Images");?>', filebrowserFlashBrowseUrl : '<?php echo asset("ckfinder/ckfinder.html?Type=Flash"); ?>', filebrowserUploadUrl : '<?php echo asset("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files");?>', filebrowserImageUploadUrl : '<?php echo asset("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images");?>', filebrowserFlashUploadUrl : '<?php echo asset("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash");?>', filebrowserWindowWidth : '800', filebrowserWindowHeight : '480' }); 
            CKFinder.setupCKEditor( editor, "<?php echo asset('ckfinder/')?>" ); }) 
           </script>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" class="form-control" name="news_id" id="news_id" value="{{$news->id}}">
          <button type="submit" class="btn btn-primary" id="add_new">{{trans('admin_texts.save')}}</button>
        </div>
    </div>
</form>
{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
$(document).ready(function() {    	
        $("#add_post").validate({
            rules: {               
                title: "required",
                body: "required",
            },
            messages: {
                title: "Please provide a title for this article.", 
                body: "Please provide a body for this article.", 
            }
    });

   });
</script>
@stop