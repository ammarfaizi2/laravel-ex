@extends('layouts.default')
@section('content')
@include('messenger.partials.flash')
<?php $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>
    <div style="margin:4%;">
        <div style="margin-bottom:1%;">
            <a href="{{route("messages.create")}}">Create new message</a>
        </div>
        <div id="nf">
            @include("messenger.partials.no-threads")
        </div>
        <div id="messages_bound"></div><div class="pagination"></div>
    </div>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <script src="{{ asset('assets/js/bootstrap-pagination.js') }}"></script>
    <script type="text/javascript">
        $('.pagination').pagination({
            page: {{$page}}, 
            lastPage: {{ceil($that->countIndexMessage() / env("THREADS_PAGINATION_LIMIT"))}},
            url: function (page) {
                return '?page='+page;
            }
        });
    	class message_index {
    		constructor () {
    			this.bound = $("#messages_bound")[0];
    			this.routeBound = "{{route('messages.show', '~~route~~')}}";
                this.selfUser = "{{Confide::user()->username}}";
    		}
    	}
        class postContextMaker {
            constructor(type, id, name) {
                this.type = type;
                this.id = id;
                this.name = name;
                this.context = "";
            }
        }
    	message_index.prototype.listen = function() {
    		var that = this;
                that.getChat();
    		setInterval(function () {
    			that.getChat();
    		}, 3000);
    	};
    	message_index.prototype.getChat = function() {
    		var that = this;
    		$.ajax({
    			type: "GET",
    			url: "?ajax_request=1<?php print isset($_GET['page']) ? "&page=". ((int) $_GET['page']) : 0; ?>",
    			datatype: "json",
    			success: function (response) {
    				that.buildChatContext(response);
    			}
    		});
    	};
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
            } else if (this.type === "add") {
                this.context["action"] = "add";
            }
        };
        postContextMaker.prototype.getContext = function() {
            return "_token="+encodeURIComponent($("#_token").val())+"&data="+encodeURIComponent(JSON.stringify(this.context));
        };
        function deleteThread(id, name) {
            bootbox.confirm(("{!! trans('msg.delete_confirm') !!}").replace("~~name~~", '<b>'+name+'</b>'), function(result){ 
                if (result) {
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
                }
            });
        }
        function leaveThread(id, name) {
            bootbox.confirm(("{!! trans('msg.leave_confirm') !!}").replace("~~name~~", '<b>'+name+'</b>'), function(result){
                if (result) {
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
                    });
                }
            });
        }
        function addParticipants(id, name) {
            setTimeout(function () {
                $(".bootbox-input")[0].placeholder = "username1,username2,username3";
            }, 500);
            bootbox.prompt({
                title: ("{{trans('msg.add_participants')}}").replace("~~name~~", '<b>'+name+'</b>'),
                inputType: "text",
                callback: function (result) {
                    if (result !== null) {
                        var postContext = new postContextMaker('add', id, result);
                        postContext.make();
                        $.ajax({
                            type: "POST",
                            url:"{!! route('messages.add_participants') !!}",
                            data: postContext.getContext(),
                            dataType: 'json',
                            success: function (response) {
                                bootbox.alert(response['message']);
                            }
                        });
                    }
                }
            });
        }
    	message_index.prototype.buildChatContext = function(data) {
    		var x, that = this, nf = $("#nf")[0];
            this.bound.innerHTML = "";
            if (data.length) {
        		for (x in data) {
        			this.bound.innerHTML +=
                        '<div style="border:1px solid #000;margin-bottom:3px;" '+(data[x]['unread_count'] ? 'class="alert-info"' : '')+'>'+
        				'<div class="media alert">' +
        				'<a href="'+that.routeBound.replace('~~route~~', data[x]['thread_id'])+'"><h2 style="margin-top:-3px;">'+data[x]['subject']+'</h2></a>'+
                        (data[x]['unread_count'] != 0 ? ' ('+data[x]['unread_count']+' unread)' : '')+
        				'<p>'+data[x]['latest_message']+'</p>'+
        				'<p><small><strong>Creator:</strong> '+data[x]['creator']+'</small></p>'+
        				'<p><small><strong>Participants:</strong> '+data[x]['participants'].join(",")+'</small></p><br>'+
                        '<div>'+
                            '<button onclick="leaveThread('+data[x]['thread_id']+', \''+data[x]['subject']+'\')" class="btn btn-warning">Leave Thread</button> '+
                            (data[x]['creator'] === that.selfUser ? '<button onclick="deleteThread('+data[x]['thread_id']+', \''+data[x]['subject']+'\')" class="btn btn-danger">Delete Thread</button> ' : '')+
                            '<button onclick="addParticipants('+data[x]['thread_id']+', \''+data[x]['subject']+'\')" class="btn btn-primary">Add Participants</button>'+
                        '</div>'+
        				'</div></div>';
                }
                nf.style.display = "none";
            } else {
                nf.style.display = "";
            }
    	};
    	var st = new message_index;
    		st.listen();
    </script>
@stop
