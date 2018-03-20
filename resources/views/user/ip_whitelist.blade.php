<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div>
            <style type="text/css">
                .rty {
                    display: inline-block;
                }
            </style>
            <div class="rty">
                <h2>{{ trans('user_texts.ip_whitelist_'.$type)}}</h2>
            </div>
            <div class="rty" style="margin-left: 10px;">
                <h3 style="color:{!! $w_status ? "green" : "red" !!}">{!! $w_status ?  trans('user_texts.on') : trans('user_texts.off') !!}</h3>
            </div>
            <div style="margin-bottom: 5px;">
                @if($w_status)
                    <button>{{ trans('user_texts.turn_off_whitelist', ["type" => ucwords($type)]) }}</button>
                @else
                    <button>{{ trans('user_texts.turn_on_whitelist', ["type" => ucwords($type)]) }}</button>
                @endif
                <button>{{ trans('user_texts.add_ip') }}</button>
            </div>
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.login_history_id') }}</th>
                    <th>{{ trans('user_texts.login_history_ip') }}</th>
                    <th>{{ trans('user_texts.created_at') }}</th>
                    <th>{{ trans('user_texts.updated_at') }}</th>
                </tr>
                @foreach($w_ip as $ip)
                <tr>
                    <td>{{ $ip->id }}</td>
                    <td>{{ $ip->ip }}</td>
                    <td>{{ $ip->created_at }}</td>
                    <td>{{ $ip->updated_at }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>