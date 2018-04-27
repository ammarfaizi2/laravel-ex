<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div id="orders_history">
            <h2>{{ trans('user_texts.referred_user')}}</h2>
            <p><strong>{{ trans('user_texts.total_referred_user') }}: {{ $referred_user }}</strong></p>
            <p><strong>{{ trans('user_texts.commission_fees') }}: {{ $commission_fees }}%</strong></p>
            <table class="table table-striped" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.no') }}</th>
                    <th>{{ trans('user_texts.username') }}</th>
                    <th>{{ trans('user_texts.email') }}</th>
                    <th>{{ trans('user_texts.joined_at') }}</th>
                </tr>
                <tr>
                    @php $no = 1; @endphp
                    @foreach($referred_users as $user)
                        <td>{{ $no++ }}.</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->joined_at }}</td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>