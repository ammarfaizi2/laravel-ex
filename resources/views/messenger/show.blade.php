@extends('layouts.default')

@section('content')
<div style="margin:1% 3% 0% 1%; ">
    <div class="col-md-6">
        <div style="margin-bottom:2%;">
            <a href="{{ route('messages') }}">Back</a>
        </div>
        <button onclick="leaveThread({{$id}}, '{{ $thread->subject }}')" class="btn btn-warning">Leave Thread</button>
        <?php $user = Confide::user(); ?>
        @if($user->username === $creator)
            <button onclick="deleteThread({{$id}}, '{{ $thread->subject }}')" class="btn btn-danger">Delete Thread</button>
        @endif
        <button onclick="addParticipants({{$id}}, '{{ $thread->subject }}')" class="btn btn-primary">Add Participants</button>
        <br><br>
        <span>Participants: {{implode(",", $pars)}}</span>
        <br>
        <h3>{{ $thread->subject }}</h3>
        <?php  /*@ each('messenger.partials.messages', $thread->messages, 'message') */ ?> 
        <div style="margin-top:-20px;" class="pagination"></div>
        <div id="message_fields" style="border:1px solid #000; height:400px;padding: 2px 2px 2px 5px;margin-top:-30px; overflow-y: scroll;"></div>
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        <script src="{{ asset('assets/js/bootstrap-pagination.js') }}"></script>
        <script type="text/javascript">
            var thatPage = $('.pagination');
        	class qq {
        		constructor() {
        			this.msgField = $("#message_fields")[0];
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
            function scrollDownChat() {
                var msgField = $("#message_fields")[0];
                msgField.scrollTo(0, msgField.scrollHeight);
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
                } else if (this.type === "add") {
                    this.context["action"] = "add";
                }
            };
            postContextMaker.prototype.getContext = function() {
                return "_token="+encodeURIComponent($("#_token").val())+"&data="+encodeURIComponent(JSON.stringify(this.context));
            };
            qq.prototype.listen = function() {
                var that = this;
                that.getChat(1);
                setInterval(function() {
                    that.getChat();
                }, 1000);  
            };
            qq.prototype.buildMessage = function(data) {
                thatPage.pagination({
                    page: {{$page}}, 
                    lastPage: data['last_page'],
                    url: function (page) {
                        return '?page='+page;
                    }
                });
                data = data['data'];
                this.msgField.innerHTML = '<center>';
                for(var x in data) {
                    this.msgField.innerHTML += 
                    '<div class="media" style="background-color:'+(x%2 ? '#caff59' : '#bce4ff')+';margin-top:2px;margin-bottom:2px;">'+
                        '<a class="pull-left" href="#">'+
                            '<img src="//www.gravatar.com/avatar/{{ md5(rand()) }} ?s=64" alt="'+data[x]['name']+'" class="img-circle">'+
                        '</a>'+
                        '<div class="media-body">'+
                            '<h5 class="media-heading" style="color:grey;">'+data[x]['name']+'</h5>'+
                            '<p>'+data[x]['body']+'</p>'+
                            '<div class="text-muted">'+
                                '<small>Posted '+data[x]['posted']+'</small>'+
                            '</div>'
                        '</div>'+
                    '</div>';
                }
                this.msgField.innerHTML += '</center>';
            };
            qq.prototype.getChat = function(qe) {
                var that = this;
                $.ajax({
                    type: 'GET',
                    url: '?ajax_request=1<?php print isset($_GET['page']) ? "&page=".((int) $_GET['page']) : 1 ?>',
                    datatype: 'json',
                    success:function(response) {
                        that.buildMessage(response);
                        if (qe) {
                            scrollDownChat();
                        }
                    }
                });
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
                                window.location = "{{route('messages')}}";
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
                                window.location = "{{route('messages')}}";
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
        	var st = new qq();
        		st.listen();
        </script>
        @include('messenger.partials.form-message')
    </div>
</div>
@stop
