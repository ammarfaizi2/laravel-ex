<li>
	<a href="{{ route('messages') }}">
		<i class="fa fa-envelope fa-2x"></i>
		<span class="label label-success" id="messages-unread-count"></span>
	</a>
</li>
<li>
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-bell fa-2x"></i>
      <span class="label label-warning" id="notification-count"></span>
    </a>
	<ul class="dropdown-menu">
		<li><a href="">Test</a></li>
	</ul>
</li>
<script type="text/javascript">
	
	class notification_handler {
		constructor() {
		}
	}

	notification_handler.prototype.listen = function() {
		var that = this;
		setInterval(function () {
			that.getNotif();
		}, 10000);	
	};

	notification_handler.prototype.getNotif = function(first_argument) {
		$.ajax({
			type: "GET",
			url: "{{ route('ajax.notif') }}",
			success: function (response) {
				if (response["unread_msg"] > 0) {
					$("#messages-unread-count")[0].innerHTML = response["unread_msg"];
				} else {
					$("#messages-unread-count")[0].innerHTML = "";
				}
			}
		});	
	};

	var st = new notification_handler;
		st.getNotif();
		st.listen();
	
</script>

