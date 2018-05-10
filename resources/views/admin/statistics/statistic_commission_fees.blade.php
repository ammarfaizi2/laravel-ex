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
                            ?><option value="<?php print $s = date("Y-m-d", $y-(3600*24*$i)); ?>" <?php if(isset($_GET["start_date"]) && $_GET["end_date"] === $s) print "selected";?>><?php print date("d F Y", $y-(3600*24*$i)); ?></option><?php
                        }
                        ?></select></td>
                </tr>
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
    $("#filter_form")[0].addEventListener("submit", function() {
         var num        = parseInt($("#filter_val")[0].value);
         var http_query = "start_date="+encodeURIComponent($("#start_date")[0].value)+"&end_date="+encodeURIComponent($("#end_date")[0].value)+"&";
         var context = {
            "id": "",
            "username": "",
            "commission_receiver": ""
         };
         for(var i = 1; i < num; i++) {
            context[$("#filter_"+i+"_f")[0].value] += $("#filter_"+i)[0].value+",";
         }

         http_query += ""
                    +"id="+encodeURIComponent(context["id"].trim(","))
                    +"&username="+encodeURIComponent(context["username"].trim(","))
                    +"&commission_receiver="+encodeURIComponent(context["commission_receiver"].trim(","));

        window.location = "?"+http_query;

    });
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