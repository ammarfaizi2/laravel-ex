@extends('admin.layouts.master')
@section('content')
<!-- Content -->
<!-- Main Content -->
<div id="container" class="clear">
<!-- Main content -->
<div class="main-contain">
<script src="http://bitbase2.dev/assets/js/bootstrap-paginator.js"></script>
<h2>Manage users</h2>
<a href="#" id="add_user_link">Add new user</a>
	<form class="form-horizontal" role="form" id="add_new_user" method="POST" action="{{ route('admin.add_featured_market') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Link</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" name="fullname" id="fullname" value="">
			</div>	      	      
	    </div>
	</div>	
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Message</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" name="message" required="" class="form-control" placeholder="Email" value="">
			</div>	      
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Username</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input minlength="2" type="text" required="" class="form-control" placeholder="Username" name="username" id="username" value="">			  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			 <input type="password" name="password" id="password" class="form-control" placeholder="Password">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Confirm Password</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input class="form-control" placeholder="Confirm Password" type="password" name="password_confirmation" id="password_confirmation">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
		<input type="hidden" class="form-control" id="market_id" name="market_id">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_edit">Add</button>
	    </div>
	</div>
</form>
<div id="messages"></div>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>ID</th>
	 	<th>Name</th>
	 	<th>Username</th>
	 	<th>Email</th>	
	 	<th>Role</th>	 	
	 	<th>Action</th>
	</tr> 	
		<tr><td>100</td><td>giveaway</td><td>giveaway</td><td>bitbase.me@gmail.com</td>
		<td>
								</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/100" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(100)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(100)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>194</td><td>Sweedx</td><td>admino</td><td>bitbase.me@gmail.com</td>
		<td>
														Admin
																				, User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/194" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(194)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(194)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>195</td><td>Anoxxxyyy</td><td>Anoxxxyyy</td><td>bitbase.me@gmail.com</td>
		<td>
								</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/195" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(195)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(195)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>196</td><td>mcnamara.jeremy</td><td>PhatJ</td><td>bitbase.me@gmail.com</td>
		<td>
								</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/196" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(196)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(196)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>197</td><td></td><td>admin</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/197" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(197)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(197)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>198</td><td></td><td>moderator</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/198" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(198)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(198)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>199</td><td>August</td><td>Falc0n</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/199" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(199)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(199)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>200</td><td></td><td>zilveer</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/200" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(200)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(200)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>201</td><td></td><td>fayoled</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/201" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(201)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(201)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>202</td><td></td><td>blacktea286</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/202" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(202)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(202)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>203</td><td></td><td>ptcgroup10009</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/203" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(203)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(203)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>204</td><td></td><td>CraigClaussen</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/204" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(204)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(204)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>205</td><td></td><td>Munti</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/205" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(205)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(205)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>206</td><td></td><td>dude71</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/206" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(206)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(206)" class="ban_user">Ban user</a></td>
	</tr>
		<tr><td>207</td><td></td><td>jbar</td><td>bitbase.me@gmail.com</td>
		<td>
														User
													</td>
		<td><a href="http://bitbase2.dev/admin/edit-user/207" class="edit_page">Edit</a>  | <a href="javascript:void(0);" onclick="deleteUser(207)" class="delete_page">Delete</a>  | <a href="javascript:void(0);" onclick="banUser(207)" class="ban_user">Ban user</a></td>
	</tr>
		
</table>
<div id="pager"></div>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">		
	<div class="modal-body">		
	</div>
	<div class="modal-footer">
		<button class="btn close-popup" data-dismiss="modal">Close</button>
	</div>
</div>
<script src="http://bitbase2.dev/assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
function deleteUser(user_id){
	$.post('/admin/delete-user', {isAjax: 1, user_id: user_id }, function(response){
       	var obj = $.parseJSON(response);
	    console.log('obj: ',obj);
	    if(obj.status == 'success'){
            $('#messageModal .modal-body').html('<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>');            
            $('#messageModal').on('hidden.bs.modal', function (e) {              
              location.reload();
            });
        }else{
            $('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
        }
        $('#messageModal').modal({show:true, keyboard:false}); 
    });
    return false;
}
function banUser(user_id){
	try {
		$.post('/admin/ban-user', {isAjax: 1, user_id: user_id }, function(response){
	       	var obj = $.parseJSON(response);

		    console.log('obj: ',obj);
		    if(obj.status == 'success'){
	            $('#messageModal .modal-body').html('<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>');            
	            $('#messageModal').on('hidden.bs.modal', function (e) {              
	              location.reload();
	            });
	        }else{
	            $('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
	        }
	        $('#messageModal').modal({show:true, keyboard:false}); 
	    });
	} catch (e) {
		alert(e.message);
	}
    return false;
}
    $(document).ready(function() {
    	$('#add_new_user').hide();
        $('#add_user_link').click(function(event) {
        	$('#add_new_user').toggle("slow");
        });
        $("#add_new_user").validate({
            rules: {
                fullname: "required",                
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                fullname: "Please enter your full name.",               
                password: {
                    required: "Please provide a password.",
                    minlength: "Your password must be at least 8 characters long."
                },
                confirm_password: {
                    required: "Please provide a password.",
                    minlength: "Your password must be at least 8 characters long.",
                    equalTo: "Please enter the same password as above."
                },
                email: "Please enter a valid email address.",
            }
	});

   });
</script>
<script type='text/javascript'>
    var options = {
        currentPage: 1,
        totalPages: 2,
        alignment:'right',
        pageUrl: function(type, page, current){
        	return "http://bitbase2.dev/admin/manage/users"+'/'+page;
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
        </div>
        <!-- Sidebar right -->
            </div>
    <!-- Footer -->
    <!-- Footer -->
