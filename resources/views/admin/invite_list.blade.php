@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>{{ trans('admin_texts.all_invitation') }}</h2>
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
<div><a href="{{ route('admin.invite') }}">{{ trans('admin_texts.invite') }}</a></div>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>Invite ID</th>
        <th>E-Mail</th>
	 	<th>{{trans('admin_texts.title')}}</th>	
        <th>Content</th>
	 	<th>{{trans('admin_texts.action')}}</th>
	</tr>
	<?php 
	// @foreach($news as $page)
	// 	<tr>
 //            <td>{{$page->id}}</td>
 //            <td>{{$page->type." - ".$page->name}}</td>
 //            <td>{{$page->title}}</td>
 //            <td>{{substr($page->content, 0, 10).(strlen($page->content) > 10 ? "..." : "")}}</td>
 //            <td>
 //                <a href="{{URL::to('admin/edit-market-news')}}/{{$page->id}}" class="edit_page">{{trans('admin_texts.edit')}}</a>  | 
 //                <a href="#" onclick="deletePost({{$page->id}})" class="delete_page">{{trans('admin_texts.delete')}}</a>
 //            </td>
 //        </tr>
	// @endforeach
	?>
</table>
<div id="pager"></div>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">     
    <div class="modal-body">  
    ...      
    </div>
    <div class="modal-footer">
        <button class="btn close-popup" data-dismiss="modal">{{{ trans('texts.close')}}}</button>
    </div>
</div>

<script type='text/javascript'>

function deletePost(news_id){
    $.post('/admin/delete-market-news', {isAjax: 1, news_id: news_id }, function(response){
        var obj = $.parseJSON(response);
        console.log('obj: ',obj);
        if(obj.status == 'success'){
            /*
            $('#messageModal .modal-body').html('<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>');            
            $('#messageModal').on('hidden.bs.modal', function (e) {              
              location.reload();
            });
*/
            location.reload();
        }else{
            alert(obj.message);
            //$('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
        }
        //$('#messageModal').modal({show:true, keyboard:false}); 
    });
    return false;
}
	<?php 
    /*var options = {
        currentPage: <?php echo $cur_page ?>,
        totalPages: <?php echo $total_pages ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
            return "<?php echo '/admin/content/all-news'; ?>"+'/'+page; 
        }
    }
    $('#pager').bootstrapPaginator(options);*/
    ?>
</script>
@stop