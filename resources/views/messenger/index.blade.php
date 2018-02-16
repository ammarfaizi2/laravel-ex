@extends('layouts.master')

@section('content')
    @include('messenger.partials.flash')

    <!-- @ each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads') -->
    @if(! $threads->count())
        @include('messenger.partials.no-threads')
    @endif
    <div id="messages_bound"></div>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <script type="text/javascript">
    	class message_index {
    		constructor () {
    			this.bound = $("#messages_bound")[0];
    			this.routeBound = "{{route('messages.show', '~~route~~')}}";
                this.selfUser = "{{Confide::user()->username}}";
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
        class postContextMaker {
            constructor(type, id, name) {
                this.type = type;
                this.id = id;
                this.name = name;
                this.context = "";
            }
        }
        postContextMaker.prototype.make = function() {
            var that = this;
            this.context = {                    
                "thread_id": that.id,
                "thread_name": that.name
            };
            if (this.type === 'delete') {
                this.context["action"] = "delete";
            } else if (this.type === "leave") {
                this.context["action"] = "leave";
            }
        };
        postContextMaker.prototype.getContext = function() {
            return "_token="+encodeURIComponent($("#_token").val())+"&data="+encodeURIComponent(JSON.stringify(this.context));
        };
        function deleteThread(id, name) {
            bootbox.confirm(("{!! trans('msg.delete_confirm') !!}").replace("~~name~~", '<b>'+name+'</b>'), function(result){ 
                var postContext = new postContextMaker('delete', id, name);
                    postContext.make();
                $.ajax({
                    type: "POST",
                    url:"{!! route('messages.delete_thread') !!}",
                    data: postContext.getContext(),
                    dataType: 'json',
                    success: function (response) {
                        bootbox.alert(response['message']);
                    }
                });
            });
        }
        function leaveThread(id, name) {
            bootbox.confirm(("{!! trans('msg.leave_confirm') !!}").replace("~~name~~", '<b>'+name+'</b>'), function(result){ 
                var postContext = new postContextMaker('leave', id, name);
                    postContext.make();
                $.ajax({
                    type: "POST",
                    url:"{!! route('messages.leave_thread') !!}",
                    data: postContext.getContext(),
                    dataType: 'json',
                    success: function (response) {
                        bootbox.alert(response['message']);
                    }
                })
            });
        }
    	message_index.prototype.buildChatContext = function(data) {
    		var x, that = this;
    		this.bound.innerHTML = "";
    		for (x in data) {
    			this.bound.innerHTML +=
                    '<div style="border:1px solid #000;">'+
    				'<div class="media alert '+(data[x]['is_unread'] ? 'alert-info' : 'info')+'">' +
    				'<h4 class="media-heading">'+
    				'<a href="'+that.routeBound.replace('~~route~~', data[x]['thread_id'])+'">'+data[x]['subject']+'</a><br><br>'+
                    (data[x]['unread_count'] != 0 ? ' ('+data[x]['unread_count']+' unread)</h4>' : '')+
    				'<p>'+data[x]['latest_message']+'</p>'+
    				'<p><small><strong>Creator:</strong> '+data[x]['creator']+'</small></p>'+
    				'<p><small><strong>Participants:</strong> '+data[x]['participants'].join(",")+'</small></p>'+
                    '<div>'+
                        '<button onclick="leaveThread('+data[x]['thread_id']+', \''+data[x]['subject']+'\')" class="btn btn-warning">Leave Thread</button> '+
                        (data[x]['creator'] === that.selfUser ? '<button onclick="deleteThread('+data[x]['thread_id']+', \''+data[x]['subject']+'\')" class="btn btn-danger">Delete Thread</button> ' : '')+
                    '</div>'+
    				'</div></div>';
    		}
    	};
    	var st = new message_index;
    		st.listen();
    </script>
@stop
