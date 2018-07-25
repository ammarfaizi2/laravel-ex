<!-- Box Header -->
<div class="box-header with-border">
  <h3 class="box-title">{{ trans('user_texts.login_history')}}</h3>

  <div class="box-tools pull-right">
	
  </div>
  <!-- /.box-tools -->
</div>

<!-- Box Content -->
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div id="orders_history">
            <table class="table table-hover table-striped" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.login_history_id') }}</th>
                    <th>{{ trans('user_texts.login_history_ip') }}</th>
                    <th>{{ trans('user_texts.login_history_ua') }}</th>
                    <th>{{ trans('user_texts.login_history_2fa') }}</th>
                    <th>{{ trans('user_texts.login_history_datetime') }}</th>
                </tr>
                @foreach($login_history as $h)
                    <tr>
                        <td>{{ $h->id }}</td>
                        <td>{{ $h->ip_address }}</td>
                        <td>{{ $h->user_agent }}</td>
                        <td>{{ $h->{'2fa'} }}</td>
                        <td>{{ $h->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>