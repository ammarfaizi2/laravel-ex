<div id="header" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" id="leftSidebarBtn" class="btn btn-info navbar-btn pull-left">
				<i class="fas fa-align-left"></i>
				<span> </span>
			</button>
							
			<a class="navbar-brand" href="{{ url('/') }}">
				{{{ Config::get('config_custom.company_name') }}}
			</a>
			
			<!-- >NAVBAR ICONS--> 
			<div class="navbar-custom-menu pull-left" >
				@if (! $authGuest = Auth::guest())
					@include('partials.notification')
				@endif
			</div>
			<!-- <NAVBAR ICONS--> 
			
			<!-- <button data-target="#navbar-main" data-toggle="collapse" type="button" class="navbar-toggle collapsed" aria-expanded="false"> -->
			
			<button type="button" id="rightSidebarBtn" class="btn btn-success navbar-btn pull-right" data-toggle="collapse-side" data-target=".side-collapse" data-target-content=".side-collapse-container" >
				<i class="fas fa-align-right"></i>
				<span> </span>
			</button>
			

		</div>
        
		
		<!-- >NAVBAR MENU--> 
        <div class="navbar-inverse side-collapse in pull-right" id="navbar-main">
            <nav class="navbar-collapse">
				<ul class="nav navbar-nav">
					<li @if(Request::is('page/voting')) {!! 'class="active"' !!} @endif>{{ HTML::link('page/voting', trans('user_texts.voting'), array('class' => Request::is('page/voting')?'active':'')) }}</li>
				</ul>
            <!--Lang menu  start-->
            <?php
                /*
				   @if(!empty($loc))            
				    	<li>
				            <a href="#" class="dropdown-toggle text-small" data-toggle="dropdown">{{trans('frontend_texts.select_language')}} <span class="caret"></span></a>                
				            <ul class="dropdown-menu" data-role="dropdown">
				      	      @foreach($loc as $locale)
				                   <li @if(Session::get( 'locale' )==$locale) {!! 'class="active"' !!} @endif>{{ HTML::link('locale/'.$locale, trans('frontend_texts.'.$locale),array('class' => 'text-small')) }}</li>
				             @endforeach
				          </ul>
				       </li>
				   @endif
				
				
				http://laravel-vsjr.blogspot.se/2013/08/managing-laravel-4-localization-language.html
				@if(!empty($loc))            
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get( 'frontend_texts.select_language' ) }} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							@foreach( $loc as $mKey => $mLanguage )
								<li>{{ HTML::linkAction( 'BaseController@setLocale', $mLanguage, $mKey ) }}</li>
							@endforeach
						</ul>
					</li>
				@endif
				*/
                ?>
            <!--Lang menu  stop-->
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <!-- This selector markup is completely customizable -->
                    <div class="columnSelectorWrapper">
                        <input id="colSelect1" type="checkbox" class="hidden">
                        <div id="columnMarketSelector" class="columnMarketSelector">
                            <!-- this div is where the column selector is added -->
                        </div>
                    </div>
                    <!-- Bootstrap popover button -->
                    <button type="button" id="popoverMarketSelector" class="btn btn-default navbar-btn hide">Show/Hide Columns</button>
                    <div class="hidden">
                        <div id="popoverMarketSelectorTarget"></div>
                    </div>
                </li>

				<li class="nav-btn btc_price">
					<span class="">
						BTC = $
					</span>
				</li>

                @if ( $authGuest )
					<li class="nav-btn register {{Request::is('register')?'active':''}}">
						<span class="">
						<a href="{{ url('/user/register') }}" class="navitem register {{Request::is('register')?'active':''}}"><i class="fa fa-user-plus"></i> {{trans('user_texts.register')}}</a>
						</span>
					</li>
					<li class="nav-btn login {{Request::is('login')?'active':''}}">
						<span class="">
						<a href="{{ url('/login') }}" class="navitem login {{Request::is('login')?'active':''}}"><i class="fa fa-sign-in"></i> {{trans('user_texts.login')}}</a>
						</span>
					</li>
                @else
				<li>
					<a href="{{ url('user/profile/balances')}}"><i class="fa fa-briefcase fa-2x"></i> </a>
				</li>
				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-alt fa-2x"></i> Orders</a>
					<ul class="dropdown-menu">
						<li @if(Request::is('user/profile/orders?status=active')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/orders?status=active', trans('user_texts.orders_open')) }}</li>
						<li @if(Request::is('user/profile/orders?status=partly_filled')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/orders?status=partly_filled', trans('user_texts.orders_partially_filled')) }}</li>
						<li @if(Request::is('user/profile/orders?status=filled')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/orders?status=filled', trans('user_texts.orders_closed')) }}</li>
					</ul>
				</li>
				<li id="menu_user_profile">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-2x"></i> {{trans('user_texts.hello')}} {{Confide::user()->username}} <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li @if(Request::is('user/profile/balances')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/balances', trans('user_texts.wallets')) }}</li>
						<li @if(Request::is('user/profile/dashboard')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/dashboard', trans('user_texts.dashboard')) }}</li>
						<li @if(Request::is('user/profile')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile', trans('user_texts.profile')) }}</li>
						<li @if(Request::is('user/messages')) {!! 'class="active"' !!} @endif>{{ HTML::link(route('messages'), trans('user_texts.message')) }}</li>
						<li @if(Request::is('user/profile/two-factor-auth')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/two-factor-auth', trans('user_texts.security')) }}</li>
						<li @if(Request::is('user/profile/orders')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/orders', trans('user_texts.orders')) }}</li>
						<li @if(Request::is('user/profile/trade-history')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/trade-history', trans('user_texts.trade_history')) }}</li>
						<li @if(Request::is('user/profile/login-history')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/login-history', trans('user_texts.login_history')) }}</li>
						<li>{{ HTML::link('user/logout', trans('user_texts.logout')) }}</li>

						<li class="menu_pos_left">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-2x"></i> {{trans('user_texts.ip_whitelist')}} <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li @if(Request::is('user/profile/ip-whitelist?p=login')) {!! 'class="active"' !!} @endif>{{ HTML::link(route('whitelist_ip', ["login"]), trans('user_texts.ip_whitelist_login')) }}</li>
								<li @if(Request::is('user/profile/ip-whitelist?p=trade')) {!! 'class="active"' !!} @endif>{{ HTML::link(route('whitelist_ip', ["trade"]), trans('user_texts.ip_whitelist_trade')) }}</li>
								<li @if(Request::is('user/profile/ip-whitelist?p=withdraw')) {!! 'class="active"' !!} @endif>{{ HTML::link(route('whitelist_ip', ["withdraw"]), trans('user_texts.ip_whitelist_withdraw')) }}</li>
							</ul>
						</li>
						
					</ul>
				</li>
				
				
                @endif
            </ul>
			</nav>
        </div>
		<!-- <NAVBAR MENU--> 
    </div>
</div>
<?php
    /*
	<div id="header" class="navbar navbar-default">
	  <div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	    <h1 id="logo" class="left"><a class="navbar-brand" href="<?=url('/', $parameters = array(), $secure = null);?>"><span></span></a></h1>
</div>
<!--  <h1 id="logo" class="left"><a href="<?=url('/', $parameters = array(), $secure = null);?>/"><span></span> </a></h1>  -->
<div class="navbar-collapse collapse navbar-responsive-collapse">
	<ul class="nav navbar-nav">
		<li @if(Request::is('page/voting')) {!! 'class="active"' !!} @endif>{{ HTML::link('page/voting', trans('user_texts.voting'), array('class' => Request::is('page/voting')?'active':'')) }}</li>
		<li @if(Request::is('page/fees')) {!! 'class="active"' !!} @endif>{{ HTML::link('page/fees', trans('user_texts.fees'), array('class' => Request::is('page/fees')?'active':'')) }}</li>
		@if(isset($menu_pages))
		@foreach($menu_pages as $menu_page)
		<li @if(Request::is('post/'.$menu_page->permalink)) {!! 'class="active"' !!} @endif>{{ HTML::link('post/'.$menu_page->permalink, $menu_page->title, array('class' => Request::is('post/'.$menu_page->permalink)?'active':'')) }}</li>
		@endforeach
		@endif
		<li @if(Request::is('page/api')) {!! 'class="active"' !!} @endif>{{ HTML::link('page/api', trans('user_texts.api'), array('class' => Request::is('page/api')?'active':'')) }}</li>
		<?php
			/*
			
			    </ul>
			    <ul class="nav navbar-nav navbar-right">
			      @if ( Auth::guest() )
			        <li @if(Request::is('register')) {!! 'class="active"' !!} @endif><a href="<?=url('/', $parameters = array(), $secure = null);?>/user/register" class="navitem login {{Request::is('register')?'active':''}}">{{trans('user_texts.register')}}</a></li>
		<li @if(Request::is('login')) {!! 'class="active"' !!} @endif><a href="<?=url('/', $parameters = array(), $secure = null);?>/login" class="navitem login {{Request::is('login')?'active':''}}">{{trans('user_texts.login')}}</a></li>
		@else
		<li class="">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{trans('user_texts.hello')}} {{Confide::user()->username}} <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li @if(Request::is('user/profile/balances')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/balances', trans('user_texts.wallets')) }}</li>
				<li @if(Request::is('user/profile/dashboard')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/dashboard', trans('user_texts.dashboard')) }}</li>
				<li @if(Request::is('user/profile')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile', trans('user_texts.profile')) }}</li>
				<li @if(Request::is('user/profile/two-factor-auth')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/two-factor-auth', trans('user_texts.security')) }}</li>
				<li @if(Request::is('user/profile/orders')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/orders', trans('user_texts.orders')) }}</li>
				<li @if(Request::is('user/profile/trade-history')) {!! 'class="active"' !!} @endif>{{ HTML::link('user/profile/trade-history', trans('user_texts.trade_history')) }}</li>
				<li>{{ HTML::link('user/logout', trans('user_texts.logout')) }}</li>
			</ul>
		</li>
		@endif
	</ul>
</div>
<div class="clear"></div>
</div>
*/
?>
