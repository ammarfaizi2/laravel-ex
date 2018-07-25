<!-- Box Header -->
<div class="box-header with-border">
  <h3 class="box-title rty">{{ trans('user_texts.ip_whitelist_'.$type)}}</h3>
  <div class="rty" style="margin-left: 10px;">
                <span style="color:{!! $w_status ? "green" : "red" !!}">{!! $w_status ?  trans('user_texts.on') : trans('user_texts.off') !!}</span>
 </div>
  <div class="box-tools pull-right">
	
  </div>
  <!-- /.box-tools -->
</div>

<!-- Box Content -->
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
        <div>
            <style type="text/css">
                .rty {
                    display: inline-block;
                }
            </style>

			<div class="mailbox-controls">
				<div class="btn-group">
					@if($w_status)
						<button onclick="turnOff();" class="btn btn-default btn-sm" >{{ trans('user_texts.turn_off_whitelist', ["type" => ucwords($type)]) }}</button>
					@else
						<button onclick="turnOn();" class="btn btn-default btn-sm">{{ trans('user_texts.turn_on_whitelist', ["type" => ucwords($type)]) }}</button>
					@endif
				</div>
				<!-- /.btn-group -->
				<button class="btn btn-default btn-sm" id="new_ip">{{ trans('user_texts.add_ip') }}</button>
			</div>

            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>{{ trans('user_texts.login_history_id') }}</th>
                    <th>{{ trans('user_texts.login_history_ip') }}</th>
                    <th>{{ trans('user_texts.created_at') }}</th>
                    <th>{{ trans('user_texts.action') }}</th>
                </tr>
                @foreach($w_ip as $ip)
                <tr>
                    <td>{{ $ip->id }}</td>
                    <td>{{ $ip->ip }}</td>
                    <td>{{ $ip->created_at }}</td>@php $p = "{$ip->id},'{$ip->ip}'"; @endphp
                    <td><button class="btn-danger btn" onclick="deleteIp({!! $p !!})">{{ trans('user_texts.delete_ip') }}</button></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <script type="text/javascript">
            function turnOn()
            {
                @if($user->google2fa_secret)
                    bootbox.prompt({
                        title: "{{trans("user_texts.tfa_3")}}",
                        inputType: "number",
                        callback: function (result) {
                            if (result != null) {
                                $.ajax({
                                    type: "POST",
                                    url: "{!! route('2fa_submit') !!}",
                                    success: function (res)  {
                                        if (typeof res["alert"] != "undefined") {
                                            bootbox.alert({ 
                                              size: "small",
                                              title: "",
                                              message: res["alert"], 
                                              callback: function(){}
                                            });
                                        }
                                        if (typeof res["success"] != "undefined" && res["success"]) {
                                            $.ajax({
                                                type: "POST",
                                                url: "{{ route('turn_on_ip') }}",
                                                success: function (res) {
                                                            if (typeof res["alert"] != "undefined") {
                                                                bootbox.alert({ 
                                                                  size: "small",
                                                                  title: "",
                                                                  message: res["alert"], 
                                                                  callback: function(){}
                                                                });
                                                            }

                                                            if (typeof res["redirect"] != "undefined") {
                                                                window.location = res["redirect"];
                                                            }
                                                        },
                                                data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}"
                                            });
                                        }
                                    },
                                    data: "_token={!! csrf_token() !!}&code="+result+"&payload={!! urlencode(base64_encode(route('turn_on_ip'))) !!}"
                                });
                            }
                        }
                    });
                @else
                 $.ajax({
                    type: "POST",
                    url: "{{ route('turn_on_ip') }}",
                    success: function (res) {
                                if (typeof res["alert"] != "undefined") {
                                    bootbox.alert({ 
                                      size: "small",
                                      title: "",
                                      message: res["alert"], 
                                      callback: function(){}
                                    });
                                }

                                if (typeof res["redirect"] != "undefined") {
                                    window.location = res["redirect"];
                                }
                            },
                    data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}"
                });
                 @endif
            }
            function turnOff()
            {
                @if($user->google2fa_secret)
                    bootbox.prompt({
                        title: "{{trans("user_texts.tfa_3")}}",
                        inputType: "number",
                        callback: function (result) {
                            if (result != null) {
                                $.ajax({
                                    type: "POST",
                                    url: "{!! route('2fa_submit') !!}",
                                    success: function (res)  {
                                        if (typeof res["alert"] != "undefined") {
                                            bootbox.alert({ 
                                              size: "small",
                                              title: "",
                                              message: res["alert"], 
                                              callback: function(){}
                                            });
                                        }
                                        if (typeof res["success"] != "undefined" && res["success"]) {
                                            $.ajax({
                                                type: "POST",
                                                url: "{{ route('turn_off_ip') }}",
                                                success: function (res) {
                                                            if (typeof res["alert"] != "undefined") {
                                                                bootbox.alert({ 
                                                                  size: "small",
                                                                  title: "",
                                                                  message: res["alert"], 
                                                                  callback: function(){}
                                                                });
                                                            }

                                                            if (typeof res["redirect"] != "undefined") {
                                                                window.location = res["redirect"];
                                                            }
                                                        },
                                                data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}"
                                            });
                                        }
                                    },
                                    data: "_token={!! csrf_token() !!}&code="+result+"&payload={!! urlencode(base64_encode(route('turn_off_ip'))) !!}"
                                });
                            }
                        }
                    });
                @else
                 $.ajax({
                    type: "POST",
                    url: "{{ route('turn_off_ip') }}",
                    success: function (res) {
                                if (typeof res["alert"] != "undefined") {
                                    bootbox.alert({ 
                                      size: "small",
                                      title: "",
                                      message: res["alert"], 
                                      callback: function(){}
                                    });
                                }

                                if (typeof res["redirect"] != "undefined") {
                                    window.location = res["redirect"];
                                }
                            },
                    data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}"
                });
                @endif
            }
            function deleteIp(id,ip)
            {
                @if($user->google2fa_secret)
                    bootbox.prompt({
                        title: "{{trans("user_texts.tfa_3")}}",
                        inputType: "number",
                        callback: function (result) {
                            if (result != null) {
                                $.ajax({
                                    type: "POST",
                                    url: "{!! route('2fa_submit') !!}",
                                    success: function (res)  {
                                        if (typeof res["alert"] != "undefined") {
                                            bootbox.alert({ 
                                              size: "small",
                                              title: "",
                                              message: res["alert"], 
                                              callback: function(){}
                                            });
                                        }
                                        if (typeof res["success"] != "undefined" && res["success"]) {
                                            bootbox.confirm({ 
                                                      size: "small",
                                                      message: (("{{ trans('user_texts.delete_ip_confirm') }}").replace("~~ip~~", ip)), 
                                                      callback: function(result){ 
                                                        if (result) {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "{{ route('ip_whitelist_remove') }}",
                                                                success: function (res) {
                                                                            if (typeof res["alert"] != "undefined") {
                                                                                bootbox.alert({ 
                                                                                  size: "small",
                                                                                  title: "",
                                                                                  message: res["alert"], 
                                                                                  callback: function(){}
                                                                                });
                                                                            }

                                                                            if (typeof res["redirect"] != "undefined") {
                                                                                window.location = res["redirect"];
                                                                            }
                                                                        },
                                                                data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}&data="+encodeURIComponent(JSON.stringify({
                                                                    "ip": ip,
                                                                    "id": id
                                                                }))
                                                            });
                                                        }
                                                      }
                                                    });
                                        }
                                    },
                                    data: "_token={!! csrf_token() !!}&code="+result+"&payload={!! urlencode(base64_encode(route('ip_whitelist_remove'))) !!}"
                                });
                            }
                        }
                    });
                @else
                bootbox.confirm({ 
                  size: "small",
                  message: (("{{ trans('user_texts.delete_ip_confirm') }}").replace("~~ip~~", ip)), 
                  callback: function(result){ 
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('ip_whitelist_remove') }}",
                            success: function (res) {
                                        if (typeof res["alert"] != "undefined") {
                                            bootbox.alert({ 
                                              size: "small",
                                              title: "",
                                              message: res["alert"], 
                                              callback: function(){}
                                            });
                                        }

                                        if (typeof res["redirect"] != "undefined") {
                                            window.location = res["redirect"];
                                        }
                                    },
                            data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}&data="+encodeURIComponent(JSON.stringify({
                                "ip": ip,
                                "id": id
                            }))
                        });
                    }
                  }
                });
                @endif
            }
            $("#new_ip")[0].addEventListener("click", function () {
                @if($user->google2fa_secret)
                    bootbox.prompt({
                        title: "{{trans("user_texts.tfa_3")}}",
                        inputType: "number",
                        callback: function (result) {
                            if (result != null) {
                                $.ajax({
                                    type: "POST",
                                    url: "{!! route('2fa_submit') !!}",
                                    success: function (res)  {
                                        if (typeof res["alert"] != "undefined") {
                                            bootbox.alert({ 
                                              size: "small",
                                              title: "",
                                              message: res["alert"], 
                                              callback: function(){}
                                            });
                                        }
                                        if (typeof res["success"] != "undefined" && res["success"]) {
                                            setTimeout(function() {
                                                $(".bootbox-input")[0].placeholder = "192.168.100.1,192.168.100.2,192.168.100.3,192.168.100.4,192.168.100.5,...";
                                            }, 300);
                                            bootbox.prompt({
                                                    title: "{{ trans('user_texts.add_ip_w') }}",
                                                    inputType: "text",
                                                    callback: function (result) {
                                                        if (result !== null) {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "{{ route('ip_whitelist_add') }}",
                                                                success: function (res) {
                                                                    if (typeof res["alert"] != "undefined") {
                                                                        bootbox.alert({ 
                                                                          size: "small",
                                                                          title: "",
                                                                          message: res["alert"], 
                                                                          callback: function(){}
                                                                        });
                                                                    }

                                                                    if (typeof res["redirect"] != "undefined") {
                                                                        window.location = res["redirect"];
                                                                    }
                                                                },
                                                                data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}&data="+encodeURIComponent(result)
                                                            });
                                                        }

                                                    }
                                                }
                                            );
                                        }
                                    },
                                    data: "_token={!! csrf_token() !!}&code="+result+"&payload={!! urlencode(base64_encode(route('ip_whitelist_add'))) !!}"
                                });
                            }
                        }
                    });
                @else
                setTimeout(function() {
                    $(".bootbox-input")[0].placeholder = "192.168.100.1,192.168.100.2,192.168.100.3,192.168.100.4,192.168.100.5,...";
                }, 300);
                bootbox.prompt({
                        title: "{{ trans('user_texts.add_ip_w') }}",
                        inputType: "text",
                        callback: function (result) {
                            if (result !== null) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('ip_whitelist_add') }}",
                                    success: function (res) {
                                        if (typeof res["alert"] != "undefined") {
                                            bootbox.alert({ 
                                              size: "small",
                                              title: "",
                                              message: res["alert"], 
                                              callback: function(){}
                                            });
                                        }

                                        if (typeof res["redirect"] != "undefined") {
                                            window.location = res["redirect"];
                                        }
                                    },
                                    data: "_token={!! csrf_token() !!}&type={!! $_GET['p'] !!}&data="+encodeURIComponent(result)
                                });
                            }

                        }
                    }
                );
                @endif
            });
        </script>
    </div>
</div>