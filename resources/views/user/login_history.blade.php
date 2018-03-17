<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div id="orders_history">
            <h2>{{ trans('user_texts.login_history')}}</h2>
            <table class="table table-striped" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.login_history_id') }}</th>
                    <th>{{ trans('user_texts.login_history_ip') }}</th>
                    <th>{{ trans('user_texts.login_history_ua') }}</th>
                    <th>{{ trans('user_texts.login_history_2fa') }}</th>
                    <th>{{ trans('user_texts.login_history_datetime') }}</th>
                </tr>
                <tr>
                    @foreach($login_history as $h)
                        <td>{{ $h->id }}</td>
                        <td>{{ $h->ip_address }}</td>
                        <td>{{ $h->user_agent }}</td>
                        <td>{{ $h->{'2fa'} }}</td>
                        <td>{{ $h->created_at }}</td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>