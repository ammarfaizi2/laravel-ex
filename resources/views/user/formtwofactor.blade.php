<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
		<!-- Security -->
		<div id="security">
			<h2>{{{ trans('user_texts.security')}}}</h2>
			
			@if(! $user->google2fa_secret)
			<?php
            /*	<h4 class="alert alert-danger">{{{ trans("user_texts.two_factor_auth")}}}: <span id="twofaStatus">{{{trans('user_texts.disabled')}}}</span></h4>
				{{ Clef::button( 'register', 'https://sweedx.com/user/profile/two-factor-auth/clef' ,Session::token()  , 'blue|white', 'button|flat' ) }}*/
            ?>
            <?php 

            $request = $that->request;

            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            // Save the registration data in an array
            $registration_data = $request->all();

            // Add the secret key to the registration data
            $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

            // Save the registration data to the user session for just the next request
            $request->session()->flash('registration_data', $registration_data);

            // Generate the QR image. This is the image the user will scan with their app
            // to set up two factor authentication
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $registration_data['google2fa_secret']
            );
            // Pass the QR barcode image to our view
            ?>
            <h4 class="alert alert-danger">{{trans("user_texts.tfa_info")}}: <span id="twofaStatus">{{trans("user_texts.disabled")}}</span></h4>
              <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{trans("user_texts.set_up_tfa")}}</div>
                            <div class="panel-body" style="text-align: center;">
                                <p>{{trans('user_texts.tfa_2')}}<br /> <strong>{{$registration_data['google2fa_secret'] }}</strong></p>
                                <div>
                                    <img src="{{ $QR_Image }}" alt="{{$registration_data['google2fa_secret'] }}">
                                </div>
                                @if (!@$reauthenticating) {{-- add this line --}}
                                    <p>{{trans('user_texts.tfa_1')}}</p><br>
                                    <div>
                                        <a href="javascript:void(0);"><button id="complete-reg" type="button" class="btn btn-primary">{{trans("user_texts.complete_reg_tfa")}}</button></a>
                                        <script type="text/javascript">
                                            document.getElementById("complete-reg").addEventListener("click", function () {
                                                    bootbox.prompt({
                                                        title: "{{trans('user_texts.tfa_3')}}",
                                                        inputType: 'number',
                                                        callback: function (result) {
                                                            if (result !== null) {
                                                                var ch = new XMLHttpRequest();
                                                                    ch.onreadystatechange = function () {
                                                                        if (this.readyState === 4) {
                                                                            try {
                                                                                var a = JSON.parse(this.responseText);
                                                                                if (a["alert"] != null) {
                                                                                    bootbox.alert({ 
                                                                                      size: "small",
                                                                                      title: "Error",
                                                                                      message: a["alert"], 
                                                                                      callback: function(){}
                                                                                    });
                                                                                }
                                                                                if (a["redirect"] != null) {
                                                                                    window.location = a["redirect"];
                                                                                }
                                                                            } catch (e) {
                                                                                alert(e.message);
                                                                            }
                                                                        }
                                                                    };
                                                                    ch.withCredentials = true;
                                                                    ch.open("POST", "{{route('user.complete_tfa')}}");
                                                                    ch.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                                                    ch.setRequestHeader("Requested-With", "XMLHttpRequest");
                                                                    ch.send("_token={{csrf_token()}}&secret={{$registration_data['google2fa_secret']}}&code="+result);
                                                            }
                                                        }
                                                    });
                                                });
                                        </script>
                                    </div>
                                @endif {{-- and this line --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <h4 class="alert alert-success">{{{ trans("user_texts.two_factor_auth")}}}: <span id="twofaStatus">{{{trans('user_texts.enabled')}}}</span></h4>
                <button style="cursor: pointer;" type="submit" id="disable-two-factor-auth" class="btn btn-danger">{{{ trans('user_texts.disable')}}} {{{ trans("user_texts.two_factor_auth")}}}</button>
                {{ HTML::script('assets/js/bootbox.min.js') }}
                <script type="text/javascript">
                    'use g2fahandler';
                    document.getElementById("disable-two-factor-auth").addEventListener("click", function () {
                         bootbox.prompt({
                            title: "{{trans('user_texts.tfa_3')}}",
                            inputType: 'number',
                            callback: function (result) {
                                if (result !== null) {
                                    var ch = new XMLHttpRequest();
                                        ch.onreadystatechange = function () {
                                            console.log(this.responseText);
                                            if (this.readyState === 4) {
                                                if (JSON.parse(this.responseText) == true) {
                                                    window.location = "{{route("user.disable_tfa")}}"
                                                } else {
                                                    bootbox.alert({ 
                                                      size: "small",
                                                      title: "Error",
                                                      message: "{{trans("user_texts.error_tfa_1")}}", 
                                                      callback: function(){}
                                                    });
                                                }
                                            }                                            
                                        };
                                        ch.withCredentials = true;
                                        ch.open("POST", "{{route('2fa_check')}}");
                                        ch.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                        ch.send("_token={{csrf_token()}}&session="+encodeURIComponent(JSON.stringify({"disable_2fa":true}))+"&code="+encodeURIComponent(result));
                                }
                            }
                        });
                    });
                </script>
            @endif
        </div>
        {{HTML::style('assets/css/flags.authy.css')}}
        {{HTML::style('assets/css/form.authy.css')}}
        {{HTML::script('assets/js/form.authy.js')}}


        </div>
    </div>
</div>