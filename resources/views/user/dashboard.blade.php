<!-- Box Header -->
<div class="box-header with-border">
  <h3 class="box-title">{{{ trans('user_texts.dashboard') }}}</h3>
  
  <div class="box-tools pull-right">
	
  </div>
  <!-- /.box-tools -->
</div>

<!-- Box Content -->
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Edit Profile -->
		<div id="dashboard">
			
		   
			<div class="panel panel-default panel-charts">
			  <div class="panel-heading"> 
				<span class="glyphicon glyphicon-market-charts"></span> Your Stats 
			  </div>
			  <div class="panel-body">
				<table style="border:1px solid #dddddd;" class="table table-striped">
				  <tbody>
					<tr><td width="50%" align="right">Total Trades</td><td width="50%">{{ HTML::link('user/profile/trade-history', $total_trades) }}</td></tr>
					<tr><td width="50%" align="right">Open Orders</td><td width="50%">{{ HTML::link('user/profile/orders', $total_openordes) }}</td></tr>
					<tr><td width="50%" align="right">Deposits Last 24 Hrs</td><td width="50%">{{ HTML::link('user/profile/deposits', $deposit_twentyfourhours) }}</td></tr>
					<tr><td width="50%" align="right">Withdrawals Last 24 Hrs</td><td width="50%">{{ HTML::link('user/profile/withdrawals', $withdraw_twentyfourhours) }}</td></tr>
					<tr><td width="50%" align="right">Pending Deposits</td><td width="50%">{{ HTML::link('user/profile/deposits', $deposit_pendings) }}</td></tr>
					<tr><td width="50%" align="right">Referred User</td><td width="50%">{{ HTML::link('user/profile/referred-users', $referred_user) }}</td></tr>
				  </tbody>
				</table>
			  </div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"> 
				  <span class="glyphicon"></span> Your Referrals 
				</div>
				<div class="panel-body">        
					<center><i></i></center>
					<h4>Referral Link Code:</h4>
					<table style="border:1px solid #dddddd;" class="table ">
					<tbody><tr><td align="center">{{URL::to('/')}}/referral/{{$user->username}}</td></tr>
					</tbody></table>   
					<h4>Stats:</h4>
					<table style="border:1px solid #dddddd;" class="table ">
					<tbody><tr><td align="center">Total Users Referred {{$referred_user}}</td></tr>
					</tbody></table>
				</div>
			</div> 
		</div>
	</div> 
</div>