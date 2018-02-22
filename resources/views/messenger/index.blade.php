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
    		}, 10000);
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
	
	
	<div class="row">
		<div class="col-12-xs col-sm-12 col-lg-12">
        <div class="col-md-3">
          <a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">12</span></a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
                </li>
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input class="form-control input-sm" placeholder="Search Mail" type="text">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">5 mins ago</td>
                  </tr>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">28 mins ago</td>
                  </tr>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">11 hours ago</td>
                  </tr>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">15 hours ago</td>
                  </tr>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">Yesterday</td>
                  </tr>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">2 days ago</td>
                  </tr>
                  <tr>
                    <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                    <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                    </td>
                    <td class="mailbox-date">2 days ago</td>
                  </tr>


                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
	  <!-- /.col-12 -->
     </div>
	  
@stop
