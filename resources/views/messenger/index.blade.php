@extends('layouts.default')
@section('content')
@include('messenger.partials.flash')
<?php $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>
	<div style="margin:4%;">
        <div style="margin-bottom:1%;">
            <a href="{{route("messages.create")}}">Create new message</a>
            <div id="search_cage">
            <form id="search_form" action="javascript:void(0);">
                <select id="search_type">
                    <option value="subject">Subject</option>
                    <option value="message">Message</option>
                </select>
                <input autocomplete="off" type="text" id="search_text" placeholder="Search...">
                <div id="search_bound" style="display:none;background:#e2e2e2;position:fixed;width:30%;height:45%;overflow-y:scroll;">
                </div>
                <button id="search_action">Search</button>
                <input type="hidden" id="search_bound" value="0">
            </form>
            </div>
        </div>
        <div id="nf">
            @include("messenger.partials.no-threads")
        </div>
        <div class="mass_action" style="margin-bottom:4px;margin-top:-10px;">
            <p>Mass Action: </p>
            <button onclick="mass_leave();" class="btn btn-warning">Leave Thread</button>
            <button onclick="mass_delete();" class="btn btn-danger">Delete Thread</button>
        </div>
        <div id="messages_bound" style=""></div><div class="pagination"></div>
    </div>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <script src="{{ asset('assets/js/bootstrap-pagination.js') }}"></script>
    <style type="text/css">
        .search_data {
        }
        .search_data_bind {
            background-color: #c9ffe7;
            padding-top: 4px;
            padding-bottom: 4px;
        }
        .search_data_bind:hover {
            background-color: #a3ce9f;
        }
    </style>
    <script type="text/javascript">
        function listenCheckBox()
        {
            var ac = document.getElementsByClassName("mass_checkbox"), iq = 0;
            for(; iq < ac.length ; iq++) {
                ac[iq].addEventListener("click", function () {
                    
                });
            }
        }
        function mass_delete()
        {
            var list = [];
            var ac = document.getElementsByClassName("mass_checkbox"), iq = 0;
            for(; iq < ac.length ; iq++) {
                if (ac[iq].checked) {
                    list[iq] = ac[iq].value;
                }
            }
            if (! list.length) {
                bootbox.alert("{{ trans('msg.no_check', ['action' =>'delete']) }}!");
            } else {
                var name = "", x;
                bootbox.confirm(("{!! trans('msg.delete_confirm') !!}").replace("~~name~~", '<b>'+list.length+'</b>'), function(result){
                    if (result) {
                        for(x in list) {
                            var postContext = new postContextMaker('delete', list[x], name);
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
                        st.getChat();
                    }
                });
            }
        }
        function mass_leave()
        {
            var list = [];
            var ac = document.getElementsByClassName("mass_checkbox"), iq = 0;
            for(; iq < ac.length ; iq++) {
                if (ac[iq].checked) {
                    list[iq] = ac[iq].value;
                }
            }
            if (! list.length) {
                bootbox.alert("{{ trans('msg.no_check', ['action' =>'leave']) }}!");
            } else {
                var name = "", x;
                bootbox.confirm(("{!! trans('msg.leave_confirm') !!}").replace("~~name~~", '<b>'+list.length+'</b>'), function(result){
                    if (result) {
                        for(x in list) {
                            var postContext = new postContextMaker('leave', list[x], name);
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
                        st.getChat();
                    }
                });
            }
        }
        window.addEventListener("click", function () {
            $('#search_bound')[0].style.display = 'none';
        });
        document.addEventListener("click", function () {
            $('#search_bound')[0].style.display = 'none';
        });
        $('#search_text')[0].value = "<?php print isset($_GET['search']) ? $_GET['search'] : ''; ?>";
        $('#search_text')[0].addEventListener("click", function () {
            $('#search_bound')[0].style.display = '';
        });
        $('#search_text')[0].addEventListener("focus", function () {
            $('#search_cage')[0].style.position = 'fixed';
            $('#search_bound')[0].style.display = '';
            $('#messages_bound')[0].style['margin-top'] = "3.4%";
        });
        $('#search_text')[0].addEventListener("input", function () {
            search_handler();
        });
        $('#search_text')[0].addEventListener("blur", function () {
            $('#search_cage')[0].style.position = '';
            $('#messages_bound')[0].style['margin-top'] = "0%";
            search_handler();
        });
        $('#search_form')[0].addEventListener("submit", function () {
            $('#search_bound')[0].style.display = '';
            search_handler();
        });
        function fillSearch()
        {
            var s = $("#search_text")[0].value.trim();
            var type = $("#search_type")[0].value;
            var ajax_url = "?ajax_request=1&search_type="+(type)+"&search="+encodeURIComponent(s)+"&<?php print isset($_GET['page']) ? "&page=". ((int) $_GET['page']) : 0; ?>";
            if (type == "subject") {
                $.ajax({
                    type: "GET",
                    url: ajax_url,
                    success: function (res) {
                        var sb = $("#search_bound")[0], x;
                        if (JSON.stringify(res) == "\"w\"") {
                            sb.innerHTML = "{{trans('msg.min_chars')}}";
                        } else
                        if (JSON.stringify(res) == "[]") {
                            sb.innerHTML = "<ul><li>Not Found!</li></ul>";
                        } else {
                            sb.innerHTML = "";
                            for(x in res) {
                                sb.innerHTML += "<a style=\"color:inherit;\" href=\""+st.routeBound.replace("~~route~~", res[x]['thread_id'])+"\"<div class=\"search_data\"><ul class=\"search_data_bind\"><li>Subject : "+res[x]['subject']+"</li><li>Creator : "+res[x]['creator']+"</li><li>Participants : "+(res[x]['participants'].join(','))+"</ul></div></a>";
                            }
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "GET",
                    url: ajax_url,
                    success: function (res) {
                        var sb = $("#search_bound")[0], x;
                        if (JSON.stringify(res) == "\"w\"") {
                            sb.innerHTML = "{{trans('msg.min_chars')}}";
                        } else
                        if (JSON.stringify(res) == "[]") {
                            sb.innerHTML = "<ul><li>Not Found!</li></ul>";
                        } else {
                            sb.innerHTML = "";
                            for(x in res) {
                                sb.innerHTML += "<a style=\"color:inherit;\" href=\""+st.routeBound.replace("~~route~~", res[x]['thread_id'])+"\"<div class=\"search_data\"><ul class=\"search_data_bind\"><li>Message: "+res[x]['body']+"</li><li>Subject : "+res[x]['subject']+"</li><li>Creator : "+res[x]['creator']+"</li><li>Participants : "+(res[x]['participants'].join(','))+"</ul></div></a>";
                            }
                        }
                    }
                });
            }
        }
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
                    st.getChat();
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
                    st.getChat();
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
            this.bound.innerHTML = "<form id=\"mass_action\" action=\"javascript:void(0);\">";
            if (data.length) {
        		for (x in data) {
        			this.bound.innerHTML +=
                        '<div style="border:1px solid #000;margin-bottom:3px;" '+(data[x]['unread_count'] ? 'class="alert-info"' : '')+'><input type="checkbox" value="'+data[x]['thread_id']+'" class="mass_checkbox">'+
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
                this.bound.innerHTML += "</form>";
                listenCheckBox();
                nf.style.display = "none";
            } else {
                nf.style.display = "";
            }
    	};
    	var st = new message_index;
    		st.listen();
        function search_handler()
        {
            var query = $('#search_text')[0].value.trim();
            if (query !== "") {
                $("#search_bound")[0].value = 1;
            } else {
                $("#search_bound")[0].value = 0;
            }
            fillSearch();
        }
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
