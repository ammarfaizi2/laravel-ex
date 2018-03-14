<ul class="nav navbar-nav">
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
				this.tmpRead = [];
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
						that.readNotification = that.readNotification.concat(that.tmpRead);
						var i = that.readNotification.length, te = [], re = [], u = 0;
						for(;i--;) {
							if (te.indexOf(that.readNotification[i]["id"]) == -1) {
								te[u] = that.readNotification[i]["id"];
								re[u++] = that.readNotification[i];
							}
						}
						that.readNotification = re.sort(function(a,b){
						  // Turn your strings into dates, and then subtract them
						  // to get a value that is either negative, positive, or zero.
						  if (a.updated_at == null && b.updated_at == null) {
							return new Date(b.created_at) - new Date(a.created_at);
						  } else if (a.updated_at == null && b.updated_at != null) {
							return new Date(b.updated_at) - new Date(a.created_at);
						  } else if (a.updated_at != null && b.updated_at == null) {
							return new Date(b.created_at) - new Date(a.updated_at);
						  }
						  return new Date(b.updated_at) - new Date(a.updated_at);
						});
						that.tmpRead = [];
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
					var nvt = $("#notif_field")[0], x, id = [];
					nvt.innerHTML = "";
					if (response["order_notification"]) {
						$("#notification-count")[0].innerHTML = response["order_notification"].length > 0 ? response["order_notification"].length : "";
						for(x in response["order_notification"]) {
							if (response["order_notification"][x] != null) {
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
					}
					that.buildOldNotification();
					$("#unread_bound")[0].value = JSON.stringify(id);
					that.tmpRead = that.tmpRead.concat(response["order_notification"]);
				}
			});	
		};

		notification_handler.prototype.buildOldNotification = function() {
			if (this.readNotification) {
				var nvt = $("#notif_field")[0], x, id = [];
				var i = this.readNotification.length, te = [], re = [], u = 0;
				for(;i--;) {
					if (te.indexOf(this.readNotification[i]["id"]) == -1) {
						te[u] = this.readNotification[i]["id"];
						re[u++] = this.readNotification[i];
					}
				}
				this.readNotification = re.sort(function(a,b){
				  // Turn your strings into dates, and then subtract them
				  // to get a value this is either negative, positive, or zero.
				  if (a.updated_at == null && b.updated_at == null) {
					return new Date(b.created_at) - new Date(a.created_at);
				  } else if (a.updated_at == null && b.updated_at != null) {
					return new Date(b.updated_at) - new Date(a.created_at);
				  } else if (a.updated_at != null && b.updated_at == null) {
					return new Date(b.created_at) - new Date(a.updated_at);
				  }
				  return new Date(b.updated_at) - new Date(a.updated_at);
				});
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

	<li class="dropdown messages-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				  <i class="fa fa-bell fa-2x"></i>
				  <span class="label label-success">4</span>
				</a>
				<ul class="dropdown-menu">
				  <li class="header">You have 4 Notifications</li>
				  <li>
					<!-- inner menu: contains the actual data -->
					<ul class="menu">
					  <li><!-- start message -->
						<a href="#">
						  <div class="pull-left">
							<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
						  </div>
						  <h4>
							Support Team
							<small><i class="fa fa-clock"></i> 5 mins</small>
						  </h4>
						  <p>Why not buy a new awesome theme?</p>
						</a>
					  </li>
					  <!-- end message -->
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
						  </div>
						  <h4>
							AdminLTE Design Team
							<small><i class="fa fa-clock"></i> 2 hours</small>
						  </h4>
						  <p>Why not buy a new awesome theme?</p>
						</a>
					  </li>
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
						  </div>
						  <h4>
							Developers
							<small><i class="fa fa-clock"></i> Today</small>
						  </h4>
						  <p>Why not buy a new awesome theme?</p>
						</a>
					  </li>
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
						  </div>
						  <h4>
							Sales Department
							<small><i class="fa fa-clock"></i> Yesterday</small>
						  </h4>
						  <p>Why not buy a new awesome theme?</p>
						</a>
					  </li>
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
						  </div>
						  <h4>
							Reviewers
							<small><i class="fa fa-clock"></i> 2 days</small>
						  </h4>
						  <p>Why not buy a new awesome theme?</p>
						</a>
					  </li>
					</ul>
				  </li>
				  <li class="footer_dropdown"><a href="#">See All Notifications</a></li>
				</ul>
			  </li>
</ul>

