<!DOCTYPE html>
<html id="mainHTML">
<head>
	<title>Two Factor Auth</title>
	{{ HTML::style('assets/css/bootstrap.min.css') }}
    {{ HTML::style('assets/css/bootstrap-dialog.min.css') }}
    {{ HTML::script('assets/js/jquery-2.1.1.min.js') }}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/pnotify.custom.min.js') }}
    {{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
    {{ HTML::script('assets/js/prettyFloat.min.js') }}
	{{ HTML::script('assets/js/bootbox.min.js') }}
</head>
<body>
</body>
</html>
<script type="text/javascript">
	function promptCode() {
		bootbox.prompt({
				title: "{{trans("user_texts.tfa_3")}}",
				inputType: "number",
				callback: function (result) {
					if (result === null) {
						window.location = "{{route("logout")}}";
					} else {
						var ch = new XMLHttpRequest();
							ch.onreadystatechange = function () {
								if (this.readyState === 4) {
									if (this.responseText === "true") {
										@if(isset($force_redirect))
											window.location = "";
										@else
											var ch2 = new XMLHttpRequest();
												ch2.onreadystatechange = function () {
													if (this.readyState === 4) {
														if (this.responseText.match(/g2fahandler/gui)) {
															window.location = this.responseURL;
														} else {
															document.getElementById("mainHTML").innerHTML = this.responseText;
														}
													}
												};
												ch2.withCredentials = true;
												ch2.open("GET", "");
												ch2.send(null);
										@endif
									} else {
										bootbox.confirm({ 
										  size: "small",
										  message: "{{trans("user_texts.error_tfa_1")}}<br>Do you want to try again?", 
										  callback: function(result){ 
										  	if (result) {
										  		promptCode();
										  	} else {
										  		window.location = "{{route("logout")}}";
										  	}
										  }
										});
									}
								}
							};
							ch.withCredentials = true;
							ch.open("POST", "{{route("2fa_check_code")}}");
							ch.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							ch.setRequestHeader("Requested-Wiht", "XMLHttpRequest");
							@if(isset($admin_page))
								ch.send("_token={{csrf_token()}}&code="+result+"&admin_page=1");
							@else
								ch.send("_token={{csrf_token()}}&code="+result);
							@endif
						}		
				}
			});
	}
	promptCode();
</script>