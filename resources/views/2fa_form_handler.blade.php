<script type="text/javascript">
	function g2fahandler() {
		var form = document.getElementById("{{$formId}}"),
		mainHTML = document.getElementById("mainHTML");
		var act  = form.action, 
		sessionHandler = encodeURIComponent(JSON.stringify({{$json2FASession}}));
			form.action = "javascript:void(0);";
			var fx = function () {
				bootbox.prompt({
					title: "{{trans("user_texts.tfa_3")}}",
					inputType: "number",
					callback: function (result) {
						if (result !== null) {
							var ch = new XMLHttpRequest();
							ch.onreadystatechange = function () {
								if (this.readyState === 4) {
									if (JSON.parse(this.responseText) == true) {
										form = document.getElementById("{{$formId}}");
										var postContext = "_token={{csrf_token()}}&",
											inputs = [
												form.getElementsByTagName("input"), 
												form.getElementsByTagName("select"),
												form.getElementsByTagName("textarea")
											],
											i, ii;
										for (ii in inputs) {
											input = inputs[ii];
											for (i = 0 ; i < input.length; i++) {
												if (! input[i].disabled) {
													if (input[i].name !== "") {
														postContext += encodeURIComponent(input[i].name) + "=";
														if (input[i].value !== "") {
															postContext += encodeURIComponent(input[i].value);
														}
														postContext += "&";
													}
												}
											}
										}
										postContext = postContext.substr(0, postContext.length - 1);
										var ch2 = new XMLHttpRequest();
											ch2.onreadystatechange = function () {
												var frl = $('<iframe>');
													frl.appendTo('body');
													frl[0].contentDocument.open();
												if (this.readyState === 4) {
													try	{
														var msg = this.responseText;
														mainHTML.innerHTML = msg;
														var form = document.getElementById("{{$formId}}");
														var act  = form.action;
														form.action = "javascript:void(0);";
														form.onsubmit = fx;
														window.scrollTo(0, 0); 
													} catch (e) {
														alert("Error: " + e.message);
													}
												}
											};
											ch2.withCredentials = true;
											ch2.open("POST", act + "?ajax_2fa=1");
											ch2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
											ch2.setRequestHeader("Requested-With", "XMLHttpRequest");
											ch2.send(postContext);
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
							ch.open("POST", "{{route("2fa_check")}}");
							ch.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
							ch.setRequestHeader("Requested-With", "XMLHttpRequest");
							ch.send("_token={{csrf_token()}}&session_2fa_handler="+sessionHandler+"&code="+result);
						}
					}
				});
			};
			form.addEventListener("submit", fx);
		}
		g2fahandler();
</script>