<!-- Box Header -->
<div class="box-header with-border">
  <h3 class="box-title">{{{ trans('texts.transfer_in')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h3>
  
  <div class="box-tools pull-right">
	
  </div>
  <!-- /.box-tools -->
</div>

<!-- Box Content -->
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Transfer History -->
		<?php
        $query_string = '';
        foreach (Request::query() as $key => $value) {
            $query_string .= $key."=".$value."&";
        }
        $query_string = trim($query_string, '&');
		/*
        if (!empty($query_string)) {
            $query_string = "&"+$query_string;
        }
		*/
        ?>
        <div id="transferout">
            
            @if($filter=='')
            <form class="form-inline" method="GET" action="{{Request::url()}}">
				<div class="mailbox-controls">
					<div class="btn-group">
						<label>{{{ trans('texts.wallet')}}}</label>        
						<select id="pair" style="margin-right: 20px;" name="wallet">
							<option value="" @if(isset($_GET['wallet']) == '') selected @endif>{{trans('texts.all')}}</option>
							@foreach($wallets as $key=> $wallet)
								<option value="{{$wallet['id']}}" @if(isset($_GET['wallet']) && $_GET['wallet']==$wallet['id']) selected @endif>{{ $wallet['type']}}</option>
							@endforeach
						</select>					</div>
					<!-- /.btn-group -->
					<button type="submit" class="btn btn-default btn-sm">{{trans('texts.filter')}}</button>
				</div>
				
                
            </form>
            @endif
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>{{{ trans('texts.wallet')}}}</th>
                    <th>{{{ trans('texts.sender')}}}</th>
                    <th>{{{ trans('texts.amount')}}}</th>
                    <th>{{{ trans('texts.date')}}}</th>
                </tr>       
                @foreach($transferins as $transferin)
                    <tr>                
                        <td>{{$transferin->type}}</td>
                        <td>{{$transferin->username}}</td>
                        <td>{{sprintf('%.8f',$transferin->amount)}}</td>
                        <td>{{$transferin->created_at}}</td>                
                    </tr>
                @endforeach  
                </tbody>
            </table>
            <ul id="pager"></ul>
        </div>
        {{ HTML::script('assets/js/bootstrap-paginator.js') }}
        <script type="text/javascript">
        var options = {
                currentPage: <?php echo $cur_page ?>,
                totalPages: <?php echo $total_pages ?>,
                alignment:'right',
                pageUrl: function(type, page, current){            
                    <?php
                    if (empty($filter)) { ?>
                    return "<?php echo URL::to('user/profile/viewtranferin'); ?>"+'?pager_page='+page+'<?php echo $query_string ?>'; 
                    <?php } else {
?> return "<?php echo URL::to('user/profile/viewtranferin').'/'.$filter; ?>"+'?pager_page='+page+'<?php echo $query_string ?>'; 
                    <?php } ?>
                }
            }
            $('#pager').bootstrapPaginator(options);
            $('#pager').find('ul').addClass('pagination');
        </script>
    </div>
</div>
