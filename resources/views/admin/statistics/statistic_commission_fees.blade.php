@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>Commission Fees</h2>
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
<div style="margin-top: 3px; margin-bottom: 10px;">
    <form action="" method="GET">
        <button id="filter" type="button" onclick="filter();">{{trans('admin_texts.filter_data')}}</button>
        <div style="display: none;" id="filter_wd">
            <h3>Filter Data:</h3>
            <table>
                <tr><td>Start Date</td><td>:</td><td><select name="start_date">
                        <option></option>
                        <?php
                        $y = time();
                        for ($i=0; $i < 100; $i++) { 
                            ?>
                            <option value="<?php print date("Y-m-d", $y-(3600*24*$i)); ?>"><?php print date("d F Y", $y-(3600*24*$i)); ?></option>
                            <?php
                        }
                        ?>
                    </select></td></tr>
                <tr><td>End Date</td><td>:</td><td><select name="end_date">
                        <option></option>
                        <?php
                        $y = time();
                        for ($i=0; $i < 100; $i++) { 
                            ?>
                            <option value="<?php print date("Y-m-d", $y-(3600*24*$i)); ?>"><?php print date("d F Y", $y-(3600*24*$i)); ?></option>
                            <?php
                        }
                        ?>
                    </select></td></tr>
                <tr><td>{{trans('admin_texts.username')}}</td><td>:</td><td><input type="text" name="username"></td></tr>
                <tr><td>{{trans('admin_texts.commission_receiver')}}</td><td>:</td><td><input type="text" name="commission_receiver"></td></tr>
            </table>
            <button>Search</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $("#filter")[0].addEventListener("click", function(a) {
        $("#filter")[0].style.display = "none";
        $("#filter_wd")[0].style.display = "";
    });
</script>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>{{trans('admin_texts.id')}}</th>
	 	<th>{{trans('admin_texts.username')}}</th>	
        <th>{{trans('admin_texts.commission_receiver')}}</th>
        <th>{{trans('admin_texts.amount')}}</th>
	 	<th>{{trans('admin_texts.date')}}</th>
	</tr>
    @foreach($cm as $m)
        <tr>
            <td>{{ $m->id }}</td>
            <td>{{ $m->b }}</td>
            <td>{{ $m->a }}</td>
            <td>{{ ((string)$m->amount)." ".$m->type }}</td>
            <td>{{ $m->created_at }}</td>
        </tr>
    @endforeach
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
    var options = {
        currentPage: <?php print !$pager_page ? 1 : $pager_page; ?>,
        totalPages: <?php print ceil($total_page[0]->b/10); ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
            return "<?php echo URL::to('admin/statistic/commission-fees'); ?>"+'/'+page+"<?php print "?". http_build_query($_GET); ?>"; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop