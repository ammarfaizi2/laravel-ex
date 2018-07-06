<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Orders History -->
		<?php
        $query_string = '';
        foreach (Request::query() as $key => $value) {
            if ($key!='pager_page') {
                $query_string .= $key."=".$value."&";
            }
        }
        $query_string = trim($query_string, '&');
        if ($query_string!='') {
            $query_string = "&".$query_string;
        }
        ?>
        <script type="text/javascript">
            function showOrderDetails(id) {
                if ($("#order_details_"+id)[0].style.display == "none") {
                    $("#order_details_"+id)[0].style.display = "";
                    $("#button_show_details_"+id)[0].innerHTML = "{{ trans('texts.hide_details') }}";
                } else {
                    $("#order_details_"+id)[0].style.display = "none";
                    $("#button_show_details_"+id)[0].innerHTML = "{{ trans('texts.details') }}";
                }
            }
        </script>
        <div id="orders_history">
            <h2>{{{ trans('texts.orders_history')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h2>

            <form class="form-inline" method="GET" action="{{Request::url()}}">
                <input type="hidden" name="_token" value="{{{ Session::token() }}}">
                @if($filter=='')
                    <label>{{{ trans('texts.market')}}}</label>        
                    <select id="pair" style="margin-right: 20px;" name="market" class="form-control">
                         <option value="" @if(isset($_GET['market']) == '') selected @endif>{{trans('texts.all')}}</option>
                            @foreach($markets as $key=> $market)
                                <option value="{{$market['id']}}" @if(isset($_GET['market']) && $_GET['market']==$market['id']) selected @endif>{{ strtoupper($market['wallet_from'].'/'.$market['wallet_to'])}}</option>
                            @endforeach
                    </select>
                @endif
                <label>{{{ trans('texts.type')}}}</label>
                <select id="type" name="type" style="margin-right: 20px;" class="form-control">
                    <option value="" @if(isset($_GET['type']) == '') selected @endif>{{trans('texts.all')}}</option>
                        <option value="sell" @if(isset($_GET['type']) && $_GET['type'] == 'sell') selected @endif>{{trans('texts.sell')}}</option>
                        <option value="buy" @if(isset($_GET['type']) && $_GET['type'] == 'buy') selected @endif>{{trans('texts.buy')}}</option>
                </select>
                <label>{{{ trans('texts.show')}}}</label>
                <select id="view" name="status" class="form-control">
                    <option value="" @if(isset($_GET['status']) == '') selected @endif>{{trans('texts.all')}}</option>
                        <option value="active" @if(isset($_GET['status']) && $_GET['status'] == 'active') selected @endif>{{trans('texts.active')}}</option>
                        <option value="filled" @if(isset($_GET['status']) && $_GET['status'] == 'filled') selected @endif>{{trans('texts.filled')}}</option>
                        <option value="partly_filled" @if(isset($_GET['status']) && $_GET['status'] == 'partly_filled') selected @endif>{{trans('texts.partially_filled')}}</option>

                </select>
                <button type="submit" class="btn btn-primary">{{trans('texts.filter')}}</button>
            </form>
           <style type="text/css">
               td {
                font-size: 11.5px;
               }
           </style>
            <table class="table table-striped" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{{ trans('texts.id') }}}</th>
                    <th>{{{ trans('texts.market')}}}</th>
                    <th>{{{ trans('texts.type')}}}</th>
                    <th>{{{ trans('texts.price')}}}</th>
                    <th>{{{ trans('texts.amount')}}}</th>
                    <th>{{{ trans('texts.total')}}}</th>
                    <th>{{{ trans('texts.remaining_amount')}}}</th>
                    <th>{{{ trans('texts.total_remaining')}}}</th>
                    <th>{{{ trans('texts.status')}}}</th>
                    <th>{{ trans('texts.date') }}</th>
                    <th>{{{ trans('texts.action')}}}</th> 
                </tr>
                <?php
                
                    //$active = array('active','partially_filled');
					//array with orders which can be cancelled
                    $active = array('active','partly_filled','partly filled');
                
                ?>
                @foreach($ordershistories as $ordershistory)
                    <?php $k = $ordershistory->id; ?>
                    <tbody>
                        <tr id="order_id_{{{$ordershistory->id}}}">
                            <td>{{$k}}</td>
                            <td>{{$markets[$ordershistory->market_id]['wallet_from'].'/'.$markets[$ordershistory->market_id]['wallet_to']}}</td>
                            @if($ordershistory->type == 'sell')          
                                <td><b style="color:red">{{ ucwords($ordershistory->type) }}</b></td>            
                            @else          
                                <td><b style="color:green">{{ ucwords($ordershistory->type) }}</b></td>
                             @endif
                            <td>{{sprintf('%.8f',$ordershistory->price)." ".$markets[$ordershistory->market_id]['wallet_to']}}</td>
                            <td>{{sprintf('%.8f',$ordershistory->amount)." ".$markets[$ordershistory->market_id]['wallet_from']}}</td>
                            <td>{{sprintf('%.8f',$ordershistory->amount * $ordershistory->price)." ".$markets[$ordershistory->market_id]['wallet_to']}}</td>
                            <td>{{sprintf('%.8f',$ordershistory->from_value)." ".$markets[$ordershistory->market_id]['wallet_from']}}</td>
                            <td>{{sprintf('%.8f',$ordershistory->to_value)." ".$markets[$ordershistory->market_id]['wallet_to']}}</td>
                            <td><?php
                                //str_replace(' ', '_', $ordershistory->status);
                            if ($ordershistory->status =='partly_filled' || $ordershistory->status =='partly filled') {
                                echo trans('texts.partially_filled');
                            } else {
                                echo trans('texts.'.$ordershistory->status);
                            }
                                ?>
                            </td>
                            <td>{{ $ordershistory->created_at }}</td>
                            <td>
    							@if(in_array($ordershistory->status,$active)) 
    								<button type="button" onclick="javascript:cancelOrder({{{$ordershistory->id}}});" class="btn btn-danger btn-xs">{{trans('texts.cancel')}}</button>
    							@endif
                                <button id="button_show_details_{{$k}}" type="button" onclick="showOrderDetails('{{$k}}')" class="btn btn-primary btn-xs">{{trans('texts.details')}}</button>
    						</td>
                        </tr>
                        </tbody>
                    <tbody id="order_details_{{$k}}" style="display:none;" class="order_details">
                        <?php
                            $a = DB::table("order_transactions")
                                ->select("*")
                                ->where("order_id", $k)
                                ->orderBy("created_at")
                                ->get();
                        ?>
                        <tr><td colspan="10"><div class="dend"></div></td></tr>
                        <tr><td colspan="10" align="center">Detail of Order #{{$k}}</td></tr>
                         <tr><th align="center" colspan="2">Date</th><th align="center" colspan="2">Amount</th><th align="center" colspan="2">Price</th><th align="center" colspan="2">Fee Sell</th><th align="center" colspan="2">Fee Buy</th></tr>
                        @foreach($a as $a)
                            <tr><td colspan="2">{{$a->created_at}}</td><td colspan="2">{{$a->amount." ".$markets[$ordershistory->market_id]['wallet_from']}}</td><td colspan="2">{{number_format($a->price, 8)." ".$markets[$ordershistory->market_id]['wallet_to']}}</td><td colspan="2">{{number_format($a->fee_sell, 8)." ".$markets[$ordershistory->market_id]['wallet_to']}}</td><td colspan="2">{{number_format($a->fee_buy, 8)." ".$markets[$ordershistory->market_id]['wallet_to']}}</td></tr>
                        @endforeach
                        <tr><td colspan="10"><div class="dend se"></div></td></tr>
                    </tbody>
                @endforeach  
            </table>
            <div id="pager"></div>
        </div>
        <style type="text/css">
            .order_details {

            }
            .dend {
                padding-bottom: 2px;
                background-color: #000;
            }
            .se {
                margin-bottom: 10px;
            }
        </style>

        <script type="text/javascript">
        function cancelOrder(order_id){
                var title = '{{{ trans('user_texts.market_order')}}}';
                var msg ='';
                
                $.ajax({
                    type: 'post',
                    url: '/docancel',
                    datatype: 'json',
                    data: {isAjax: 1, order_id: order_id },
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                    },
                    success:function(response) {
                        var obj = $.parseJSON(response);
                        //app.BrainSocket.message('doTrade',obj.message_socket);          

                        
                        
                        if(obj.status == 'success'){
                            msg = obj.message;
                            $('#order_id_'+order_id).fadeOut(500);
                        }else{
                            msg = obj.message;
                        }
                    
                        BootstrapDialog.show({
                            title: title,
                            message: msg
                        });         
                        console.log('Obj: ',obj);
                    }, error:function(response) {
                        showMessageSingle('{{{ trans('texts.error') }}}', 'error');
                    }
                });
        }
        </script>
        {{ HTML::script('assets/js/bootstrap-paginator.js') }}
        <script type="text/javascript">
        var options = {
                currentPage: <?php echo $cur_page ?>,
                totalPages: <?php echo $total_pages ?>,
                alignment:'right',
                pageUrl: function(type, page, current){ console.log('Page: ',page);
                    var url="<?php echo URL::to('user/profile/orders'); ?>";
                    <?php if (!empty($filter)) { ?>
                        url="<?php echo URL::to('user/profile/orders').'/'.$filter; ?>"; 
                    <?php }?>
                    console.log('url: ',url);
                    console.log('query_string: ','<?php echo $query_string ?>');
                    return url+'?pager_page='+page+'<?php echo $query_string ?>';
                }
            }
            $('#pager').bootstrapPaginator(options);
            $('#pager').find('ul').addClass('pagination');
        </script>
    </div>
</div>