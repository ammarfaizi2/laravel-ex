<?php
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer as BaconQrCodeWriter;
$renderer = new Png();
$renderer->setWidth(200);
$renderer->setHeight(200);
$bacon = new BaconQrCodeWriter($renderer);
$data = $bacon->writeString(URL::to("/")."/referral/{$user->username}", 'utf-8');
$data = 'data:image/png;base64,'.base64_encode($data);
?>
<style type="text/css">
    .wx {
        display: inline-block;
    }
    .taa {
        display: inline-block;
    }
    .tbb {
        width: 560px;
    }
</style>
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div>
            <h2>{{ trans('user_texts.referred_user')}}</h2>
            <div style="border: 2px solid #000; padding: 10px;margin-top: -7px; margin-bottom: 15px;">
                <div class="wx">
                <img src="{{ $data }}">
                </div>
                <div class="wx" style="vertical-align: top;">
                    <div style="margin-top:-10px;">
                        <h3>{{ trans('user_texts.referral_username') }}: {{ $user->username }}</h3>
                        <h3>{{ trans('user_texts.referral_link') }}: <a href="{{ URL::to("/")."/referral/{$user->username}" }}" target="_blank">{{ URL::to("/")."/referral/{$user->username}" }}</a></h3>
                    </div>
                </div>
            </div>
            <p><strong>{{ trans('user_texts.total_referred_user') }}: {{ $referred_user }}</strong></p>
            <p><strong>{{ trans('user_texts.commission_fees') }}: {{ $commission_fees[0] }}% @if($commission_fees[1]) ({{$commission_fees[1]}}) @endif</strong></p>
            <div class="taa">
            <h4>{{ trans('user_texts.referred_user')}}</h4>
            <table class="table table-striped tbb" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.no') }}</th>
                    <th>{{ trans('user_texts.username') }}</th>
                    <th>{{ trans('user_texts.joined_at') }}</th>
                </tr>
                    @php $no = 1; @endphp
                    @foreach($referred_users as $user)
                    <tr>
                        <td>{{ $no++ }}.</td>
                        <td>{{ substr($user->username, 0, 3).str_repeat("*", strlen($user->username) - 2) }}</td>
                        <td>{{ $user->joined_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="taa">
            <h4>{{ trans('user_texts.latest_commission_history')}}</h4>
            <table class="table table-striped tbb" id="marketOrders">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.commission') }}</th>
                    <th>{{ trans('user_texts.username') }}</th>
                    <th>{{ trans('user_texts.date') }}</th>
                </tr>
                <tr>
                    <td colspan="3" align="center"><h6>No Commission History</h2></td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
