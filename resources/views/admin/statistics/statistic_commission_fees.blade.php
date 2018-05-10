@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>Commission Fees</h2>
@if ( is_array(Session::get('error')) )
        <div class="alert alert-danger">{{ head(Session::get('error')) }}</div>
    @elseif ( Session::get('error') )
      <div class="alert alert-danger">{{{ Session::get('error') }}}</div>
    @endif
    @if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
    @endif

    @if ( Session::get('notice') )
          <div class="alert">{{{ Session::get('notice') }}}</div>
    @endif
<div style="margin-top: 3px; margin-bottom: 10px;">
    <form action="javascript:void(0);" method="GET" id="filter_form">
        <!-- <button id="filter" type="button" onclick="filter();">{{trans('admin_texts.filter_data')}}</button> -->
        <div id="filter_wd">
            <h3>Filter Data:</h3>
            <table>
                <tbody id="filter_table">
                <tr>
                    <td>Start Date</td><td>:</td><td><select autocomplete="off" id="start_date"><option></option><?php
                        $y = time();
                        for ($i=0; $i < 100; $i++) { 
                            ?>
                            <option value="<?php print $s = date("Y-m-d", $y-(3600*24*$i)); ?>" <?php if(isset($_GET["start_date"]) && $_GET["start_date"] === $s) print "selected";?>><?php print date("d F Y", $y-(3600*24*$i)); ?></option>
                            <?php                            
                        }
                        ?></select></td>
                </tr>
                <tr>
                    <td>End Date</td><td>:</td><td><select autocomplete="off" id="end_date"><option></option>
                        <?php
                        $y = time();
                        for ($i=0; $i < 100; $i++) { 
                            ?><option value="<?php print $s = date("Y-m-d", $y-(3600*24*$i)); ?>" <?php if(isset($_GET["end_date"]) && $_GET["end_date"] === $s) print "selected";?>><?php print date("d F Y", $y-(3600*24*$i)); ?></option><?php
                        }
                        ?></select></td>
                </tr>
                <tr><td>Order By<div style="width: 200px;"></div></td><td>:</td><td><select id="order_by" autocomplete="off">
                    <option></option>
                    <option value="id" <?php if(isset($_GET["order_by"]) && $_GET["order_by"] == "id") print "selected"; ?>>{{trans('admin_texts.id')}}</option>
                    <option value="username" <?php if(isset($_GET["order_by"]) && $_GET["order_by"] == "username") print "selected"; ?>>{{trans('admin_texts.username')}}</option>
                    <option value="commission_receiver" <?php if(isset($_GET["order_by"]) && $_GET["order_by"] == "commission_receiver") print "selected"; ?>>{{trans('admin_texts.commission_receiver')}}</option>
                    <option value="amount" <?php if(isset($_GET["order_by"]) && $_GET["order_by"] == "amount") print "selected"; ?>>{{trans('admin_texts.amount')}}</option>
                    <option value="date" <?php if(isset($_GET["order_by"]) && $_GET["order_by"] == "date") print "selected"; ?>>{{trans('admin_texts.date')}}</option>
                </select></td><td><select id="sr"><option></option><option value="asc" <?php if (isset($_GET["order_by"], $_GET["sr"]) && $_GET["sr"] == "asc") {
                    print "selected";
                } ?>>Ascending</option><option value="desc" <?php if (isset($_GET["order_by"], $_GET["sr"]) && $_GET["sr"] === "desc") {
                    print "selected";
                } ?>>Descending</option></select></td></tr>
                </tbody>
            </table>
            <input type="hidden" id="filter_val" value="1"><br/>
            <button type="button" onclick="addFilter();">{{ trans('admin_texts.add_more_filter') }}</button><br/><br/>
            <button>Search</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $("#filter_val")[0].value = 1;
    <?php
    if (!empty($_GET["id"]) && is_string($_GET["id"])) {
        foreach (explode(",", trim($_GET["id"], ",")) as $v) {
            if (! empty($v)) {
                ?>createFilter("id", "<?php print $v; ?>");<?php
            }
        }
    }
    ?>
    <?php
    if (!empty($_GET["username"]) && is_string($_GET["username"])) {
        foreach (explode(",", trim($_GET["username"], ",")) as $v) {
            if (! empty($v)) {
                ?>createFilter("username", "<?php print $v; ?>");<?php
            }
        }
    }
    ?>
    <?php
    if (!empty($_GET["commission_receiver"]) && is_string($_GET["commission_receiver"])) {
        foreach (explode(",", trim($_GET["commission_receiver"], ",")) as $v) {
            if (! empty($v)) {
                ?>createFilter("commission_receiver", "<?php print $v; ?>");<?php
            }
        }
    }
    ?>  
    $("#filter_form")[0].addEventListener("submit", function() {
         var num        = parseInt($("#filter_val")[0].value), http_query = "";
         if ($("#start_date")[0].value != "") {
            http_query += "start_date="+encodeURIComponent($("#start_date")[0].value);
         }
         if ($("#end_date")[0].value) {
            http_query += "&end_date="+encodeURIComponent($("#end_date")[0].value)+"&";
         }
         var context = {
            "id": "",
            "username": "",
            "commission_receiver": ""
         };
         for(var i = 1; i < num; i++) {
            context[$("#filter_"+i+"_f")[0].value] += $("#filter_"+i)[0].value+",";
         }

         http_query += ""
                    +(context["id"] != "" ? "id="+encodeURIComponent(context["id"].trim(",")) : "")
                    +(context["username"] != "" ? "&username="+encodeURIComponent(context["username"].trim(",")) : "")
                    +(context["commission_receiver"] != "" ? "&commission_receiver="+encodeURIComponent(context["commission_receiver"].trim(",")) : "");
        if ($("#order_by")[0].value != "") {
            http_query+="&order_by="+$("#order_by")[0].value+"&";
            if ($("#sr")[0].value == "") {
                http_query+="sr=asc";
            } else {
                http_query+="sr="+$("#sr")[0].value;
            }
        }
        window.location = "/admin/statistic/commission-fees?"+http_query;

    });
    function createFilter(field, value) {
        var num = parseInt($("#filter_val")[0].value);
        var select  = document.createElement("select");
        var op0     = document.createElement("option");
        var op1     = document.createElement("option");
        op1.setAttribute("value", "id");
        op1.appendChild(document.createTextNode("{{trans('admin_texts.id')}}"));
        var op2     = document.createElement("option");
        op2.setAttribute("value", "username");
        op2.appendChild(document.createTextNode("{{trans('admin_texts.username')}}"));
        var op3     = document.createElement("option");
        op3.setAttribute("value", "commission_receiver");
        op3.appendChild(document.createTextNode("{{trans('admin_texts.commission_receiver')}}"));
        switch (field) {
            case 'id':
                op1.setAttribute("selected", "selected");
            break;
            case 'username':
                op2.setAttribute("selected", "selected");
            break;
            case 'commission_receiver':
                op3.setAttribute("selected", "selected");
            break;
        }
        select.setAttribute("id", "filter_"+num+"_f");
        select.appendChild(op0);
        select.appendChild(op1);
        select.appendChild(op2);
        select.appendChild(op3);
        var input   = document.createElement("input");
        input.setAttribute("type", "text");
        input.setAttribute("id", "filter_"+num);
        input.setAttribute("size", 18);
        input.setAttribute("value", value);
        var td1     = document.createElement("td");
        var td2     = document.createElement("td");
        var td3     = document.createElement("td");
        td1.appendChild(select);
        td2.appendChild(document.createTextNode(":"));
        td3.appendChild(input);
        var tr      = document.createElement("tr");
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        $("#filter_table")[0].appendChild(tr);
        $("#filter_val")[0].value = ++num;
    }
    function addFilter()
    {
        var num = parseInt($("#filter_val")[0].value);
        var select  = document.createElement("select");
        var op0     = document.createElement("option");
        var op1     = document.createElement("option");
        op1.setAttribute("value", "id");
        op1.appendChild(document.createTextNode("{{trans('admin_texts.id')}}"));
        var op2     = document.createElement("option");
        op2.setAttribute("value", "username");
        op2.appendChild(document.createTextNode("{{trans('admin_texts.username')}}"));
        var op3     = document.createElement("option");
        op3.setAttribute("value", "commission_receiver");
        op3.appendChild(document.createTextNode("{{trans('admin_texts.commission_receiver')}}"));
        select.setAttribute("id", "filter_"+num+"_f");
        select.appendChild(op0);
        select.appendChild(op1);
        select.appendChild(op2);
        select.appendChild(op3);
        var input   = document.createElement("input");
        input.setAttribute("type", "text");
        input.setAttribute("id", "filter_"+num);
        input.setAttribute("size", 18);
        var td1     = document.createElement("td");
        var td2     = document.createElement("td");
        var td3     = document.createElement("td");
        td1.appendChild(select);
        td2.appendChild(document.createTextNode(":"));
        td3.appendChild(input);
        var tr      = document.createElement("tr");
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        $("#filter_table")[0].appendChild(tr);
        $("#filter_val")[0].value = ++num;
    }
    // $("#filter")[0].addEventListener("click", function(a) {
    //     $("#filter")[0].style.display = "none";
    //     $("#filter_wd")[0].style.display = "";
    // });
</script>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>{{trans('admin_texts.id')}}</th>
	 	<th>{{trans('admin_texts.username')}}</th>	
        <th>{{trans('admin_texts.commission_receiver')}}</th>
        <th>{{trans('admin_texts.amount')}}</th>
	 	<th>{{trans('admin_texts.date')}}</th>
	</tr>
    @if(! $cm->count())
        <tr><td colspan="5" align="center"><h1>{{ trans('admin_texts.no_result') }}</h1></td></tr>
    @endif
    @foreach($cm as $m)
        <tr>
            <td>{{ $m->id }}</td>
            <td>{{ $m->b }}</td>
            <td>{{ $m->a }}</td>
            <td>{{ ((string)$m->amount)." ".$m->type }}</td>
            <td>{{ $m->created_at }}</td>
        </tr>
    @endforeach
</table>
<div id="pager"></div>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">     
    <div class="modal-body">  
    ...      
    </div>
    <div class="modal-footer">
        <button class="btn close-popup" data-dismiss="modal">{{{ trans('texts.close')}}}</button>
    </div>
</div>

<script type='text/javascript'>
    var options = {
        currentPage: <?php print !$pager_page ? 1 : $pager_page; ?>,
        totalPages: <?php print ceil($total_page[0]->b/10); ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
            return "<?php echo URL::to('admin/statistic/commission-fees'); ?>"+'/'+page+"<?php print "?". http_build_query($_GET); ?>"; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop