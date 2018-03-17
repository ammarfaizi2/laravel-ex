<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div id="orders_history">
            <h2>{{{ trans('texts.orders_history')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h2>
           
            <table class="table table-striped" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{{ trans('texts.market')}}}</th>
                    <th>{{{ trans('texts.type')}}}</th>
                    <th>{{{ trans('texts.price')}}}</th>
                    <th>{{{ trans('texts.amount')}}}</th>
                    <th>{{{ trans('texts.total')}}}</th>
                    <th>{{{ trans('texts.remaining_amount')}}}</th>
                    <th>{{{ trans('texts.status')}}}</th>
                    <th>{{{ trans('texts.action')}}}</th> 
                </tr>
                <?php
                
                    //$active = array('active','partially_filled');
					//array with orders which can be cancelled
                    $active = array('active','partly_filled','partly filled');
                
                ?>
                </tbody>
            </table>
            <div id="pager"></div>
        </div>
    </div>
</div>