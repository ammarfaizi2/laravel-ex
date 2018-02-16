@extends('layouts.master')

@section('content')
    @include('messenger.partials.flash')

    <!-- @ each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads') -->
    <div id="messages_bound"></div>
    <script type="text/javascript">
    	class message_index {
    		constructor () {
    			this.bound = $("#messages_bound")[0];
    			this.routeBound = "{{route('messages.show', '~~route~~')}}";
    		}
    	}
    	message_index.prototype.listen = function() {
    		var that = this;
                that.getChat();
    		setInterval(function () {
    			that.getChat();
    		}, 1000);
    	};
    	message_index.prototype.getChat = function() {
    		var that = this;
    		$.ajax({
    			type: "GET",
    			url: "?ajax_request=1",
    			datatype: "json",
    			success: function (response) {
    				that.buildChatContext(response);
    			}
    		});
    	};
    	message_index.prototype.buildChatContext = function(data) {
    		var x, that = this;
    		this.bound.innerHTML = "";
    		for (x in data) {
    			this.bound.innerHTML +=
    				'<div class="media alert '+(data[x]['is_unread'] ? 'alert-info' : '')+'">' +
    				'<h4 class="media-heading">'+
    				'<a href="'+that.routeBound.replace('~~route~~', data[x]['thread_id'])+'">'+data[x]['subject']+'</a>'+
                    ' ('+data[x]['unread_count']+' unread)</h4>'+
    				'<p>'+data[x]['latest_message']+'</p>'+
    				'<p><small><strong>Creator:</strong> '+data[x]['creator']+'</small></p>'+
    				'<p><small><strong>Participants:</strong> '+data[x]['participants'].join(",")+'</small></p>'+
    				'</div>';
    		}
    	};
    	var st = new message_index;
    		st.listen();
    </script>
@stop
