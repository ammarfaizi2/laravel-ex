@extends('layouts.default')

@section('content')

    <div class="col-md-6">
        <h1>{{ $thread->subject }}</h1>
        <?php  /*@ each('messenger.partials.messages', $thread->messages, 'message') */ ?> 
        <div id="message_fields"></div>
        <script type="text/javascript">
        	class qq {
        		constructor() {
        			this.msgField = $("#message_fields")[0];
        		}
        		listen() {
        			var that = this;
        			that.getChat();
        			setInterval(function() {
        				that.getChat();
        			}, 1000);
        		}
        		buildMessage(data) {
        			this.msgField.innerHTML = '<center>';
        			for(var x in data) {
        				this.msgField.innerHTML += 
						'<div class="media">'+
						    '<a class="pull-left" href="#">'+
						        '<img src="//www.gravatar.com/avatar/{{ md5(rand()) }} ?s=64" alt="'+data[x]['name']+'" class="img-circle">'+
						    '</a>'+
						    '<div class="media-body">'+
						        '<h5 class="media-heading">'+data[x]['name']+'</h5>'+
						        '<p>'+data[x]['body']+'</p>'+
						        '<div class="text-muted">'+
						            '<small>Posted '+data[x]['posted']+'</small>'+
						        '</div>'
						    '</div>'+
						'</div>';
        			}
        			this.msgField.innerHTML += '</center>';
        		}
        		getChat() {
        			var that = this;
        			$.ajax({
						type: 'GET',
						url: '?ajax_request=1',
						datatype: 'json',
						success:function(response) {
							that.buildMessage(response);
						}
					});
        		}
        	}
        	var st = new qq();
        		st.listen();
        </script>
        @include('messenger.partials.form-message')
    </div>

@stop
