<li>
	<a href="{{ route('messages') }}">
		<i class="fa fa-envelope fa-2x"></i>
		<span class="label label-success" id="messages-unread-count"></span>
	</a>
</li>
<li>
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="notif_toggle">
      <i class="fa fa-bell fa-2x"></i>
      <input type="hidden" id="unread_bound" value="">
      <input type="hidden" id="handled_notif" value="[]">
      <span class="label label-warning" id="notification-count"></span>
    </a>
	<ul class="dropdown-menu">
		<div style="width:300px;height:400px;overflow-y:scroll;" id="notif_field">
			<!-- <li>
				<div style="border-bottom:1px solid #000;border-top: 1px solid #000;">
					<p class="notif ca">Your order GIV/BTC has been Partially Filled.</p>
					<div class="gh">
						<p class="notif">Type: Sell</p>
						<p class="notif">Price: 0.00085391</p>
						<p class="notif">Amount: 0.00085391</p>
						<p class="notif">Total: 0.06712587</p>
					</div>
					<div class="gh ax">
						<center><a href="http://bitbase2.dev/user/profile/orders?status=partly_filled"><button style="margin-left:80px;">Check Order</button></a></center>
					</div>
				</div>
			</li> -->
		</div>
	</ul>
</li>
<style type="text/css">
	.ca {
		margin-top: 2px;
		margin-bottom: -2px;
	}
	.notif {
		margin-left: 10px;
		font-size: 12px;
	}
	.gh {
		display: inline-flex;
	}
	.ax {
		margin-top: -2px;
		margin-bottom: 2px;
		align-self: center;
	}
</style>
<script type="text/javascript">
	
	class notification_handler {
		constructor() {
			this.readNotification = {!! json_encode(\App\Models\Notifications::getOldNotification()) !!};
		}
	}

	notification_handler.prototype.listen = function() {
		var that = this;
		$("#notif_toggle")[0].addEventListener("click", function () {
			$.ajax({
				type: "POST",
				url: "{{route('ajax.notif.read')}}",
				data: "data="+$("#unread_bound")[0].value+"&_token={!! csrf_token() !!}",
				success: function (res) {
					$("#notification-count")[0].innerHTML = "";
					setTimeout(function(){
						var el = document.getElementsByClassName("new-notification"), i = el.length;
						for(;i--;) {
							el[i].style["background-color"] = "#d3d3d3";
						}
					}, 3000);
				}
			})
		});
		setInterval(function () {
			that.getNotif();
		}, 10000);	
	};

	notification_handler.prototype.getNotif = function() {
		var that = this;
		$.ajax({
			type: "GET",
			url: "{{ route('ajax.notif') }}",
			success: function (response) {
				if (response["unread_msg"] > 0) {
					$("#messages-unread-count")[0].innerHTML = response["unread_msg"];
				} else {
					$("#messages-unread-count")[0].innerHTML = "";
				}
				if (response["order_notification"]) {
					var nvt = $("#notif_field")[0], x, id = [];
					$("#notification-count")[0].innerHTML = response["order_notification"].length > 0 ? response["order_notification"].length : "";
					nvt.innerHTML = "";
					for(x in response["order_notification"]) {
						id[x] = response["order_notification"][x]["id"];
						nvt.innerHTML += '<li>'+
							'<div class="new-notification" style="border-bottom:1px solid #000;border-top: 1px solid #000;background-color:#6bea7a;">'+
								'<p class="notif ca">Your order '+response["order_notification"][x]['coin_name']+' has been '+that.getStatus(response["order_notification"][x]['status'])+'.</p>'+
								'<div class="gh">'+
									'<p class="notif">Type: '+response["order_notification"][x]["type"]+'</p>'+
									'<p class="notif">Price: '+response["order_notification"][x]["price"]+'</p>'+
									'<p class="notif">Amount: '+response["order_notification"][x]["amount"]+'</p>'+
									'<p class="notif">Total: '+response["order_notification"][x]["total"]+'</p>'+
								'</div>'+
								'<div class="gh ax">'+
									'<center><a href="'+that.getLink(response["order_notification"][x]["status"])+'"><button style="margin-left:80px;">Check Order</button></a></center>'+
								'</div>'+
							'</div>'+
						'</li>';
					}
				}
				that.buildOldNotification();
				$("#unread_bound")[0].value = JSON.stringify(id);
				that.readNotification = that.readNotification.concat(response["order_notification"]);
			}
		});	
	};

	notification_handler.prototype.buildOldNotification = function() {
		if (this.readNotification) {
			var nvt = $("#notif_field")[0], x, id = [];
			for(x in this.readNotification) {
				nvt.innerHTML += '<li>'+
					'<div style="border-bottom:1px solid #000;'+(x==0?'border-top: 1px solid #000':'margin-top:-1px')+';background-color:#d3d3d3;">'+
						'<p class="notif ca">Your order '+this.readNotification[x]['coin_name']+' has been '+this.getStatus(this.readNotification[x]['status'])+'.</p>'+
						'<div class="gh">'+
							'<p class="notif">Type: '+this.readNotification[x]["type"]+'</p>'+
							'<p class="notif">Price: '+this.readNotification[x]["price"]+'</p>'+
							'<p class="notif">Amount: '+this.readNotification[x]["amount"]+'</p>'+
							'<p class="notif">Total: '+this.readNotification[x]["total"]+'</p>'+
						'</div>'+
						'<div class="gh ax">'+
							'<center><a href="'+this.getLink(this.readNotification[x]["status"])+'"><button style="margin-left:80px;">Check Order</button></a></center>'+
						'</div>'+
					'</div>'+
				'</li>';
			}
		}
	};

	notification_handler.prototype.getLink = function(status) {
		switch(status) {
			case 'partly filled':
				return "{{route('user.profile_page', 'orders')}}?status=partly_filled";
			case 'filled':
				return "{{route('user.profile_page', 'orders')}}?status=filled";
		}
	};

	notification_handler.prototype.getStatus = function(status) {
		switch(status) {
			case 'partly filled':
				return 'Partially Filled';
			case 'filled':
				return 'Filled';
		}
	};

	var st = new notification_handler;
		st.getNotif();
		st.listen();
</script>

