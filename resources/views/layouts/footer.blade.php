<?php
//https://bootsnipp.com/snippets/zDvKX
/*
?>

<footer>
<section class="nb-footer">
<div class="container">
<div class="row">
<div class="col-md-3 col-sm-6">
	<div class="footer-single">
	<!-- 	<img src="images/logo.png" class="img-responsive" alt="Logo"> -->

		<!-- This is only for better view of theme if you want your image logo remove div dummy-logo bellow and replace your logo in logo.png and uncomment logo tag above-->
	<div class="dummy-logo">
	<div class="icon pull-left brand">
		<i class="fa fa-copy"></i>
	</div>
		<h2>{{{ Config::get('config_custom.company_name') }}}</h2>
		<p>Another responsive footer</p>
	</div>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus felis diam, vulputate in risus eu, porttitor condimentum purus. Phasellus ullamcorper, odio id feugiat bibendum.</p>
		<a href="" class="btn btn-footer">Read More</a>
	</div>
</div>

<div class="col-md-3 col-sm-6">
	<div class="footer-single useful-links">
	 <div class="footer-title"><h2>Navigation</h2></div>
	 <ul class="list-unstyled">
            <li><a href="<?php echo url('/', $parameters = array(), $secure = null);?>/post/about">About Us <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="<?php echo url('/', $parameters = array(), $secure = null);?>/page/voting">Voting <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="<?php echo url('/', $parameters = array(), $secure = null);?>/page/fees">Fees <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="<?php echo url('/', $parameters = array(), $secure = null);?>/page/api">Api <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="#">Contact Us <i class="fa fa-angle-right pull-right"></i></a></li>
	 </ul>
        </div>
</div>
<div class="clearfix visible-sm"></div>

<div class="col-md-3 col-sm-6">
	
	<div class="col-sm-12 left-clear right-clear footer-single footer-project">
		<div class="footer-title"><h2>Announcements</h2></div>
	      <ul class="list-unstyled">
            <li><a href="#">Ann 1 <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="#">Ann 2 <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="#">Ann 3 <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="#">Ann 4 <i class="fa fa-angle-right pull-right"></i></a></li>
            <li><a href="#">Ann 5 <i class="fa fa-angle-right pull-right"></i></a></li>
	     </ul>

	</div>

</div>

<div class="col-md-3 col-sm-6">
	<div class="footer-single">
		<div class="footer-title"><h2>Support</h2></div>
		<address> 
			<i class="fa fa-envelope"></i> {{{ Config::get('config_custom.company_support_mail') }}}<br>
		</address>					
	</div>
</div>

</div>
</div>
</section>	

<section class="nb-copyright">
<div class="container">
<div class="row">
<div class="col-sm-6 copyrt xs-center">
	Copyright &copy; <?php echo date('Y')?> {{{ Config::get('config_custom.company_name') }}} - All Rights Reserved. <a href="<?php echo url('/', $parameters = array(), $secure = null);?>/post/terms">Terms & Conditions</a>
</div>
<div class="col-sm-6 text-right xs-center">
	<ul class="list-inline footer-social">
		<li><a href="#"><i class="fa fa-facebook"></i></a></li>
		<li><a href="#"><i class="fa fa-twitter"></i></a></li>
		<li><a href="#"><i class="fa fa-youtube-play"></i></a></li>
		<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
		<li><a href="#"><i class="fa fa-skype"></i></a></li>
	</ul>
</div>
</div>
</div>
</section>
</footer>
*/
?>
<div class="row">
	<div class="contentinner">
		<div class="footer">
			<div class="col-12-xs col-sm-12 col-lg-12" >
		
			Copyright &copy; <?php echo date('Y')?> <strong>{{{ Config::get('config_custom.company_name') }}}</strong>
			<br />
			All Rights Reserved. {{{ Config::get('config_custom.company_slogan') }}}.
			<br />
			<hr class="colorgraph"/>
			@if(isset($menu_pages))
				@foreach($menu_pages as $menu_page)
					<span @if(Request::is('post/'.$menu_page->permalink)) {{'class="active"'}} @endif>{{ HTML::link('post/'.$menu_page->permalink, $menu_page->title, array('class' => Request::is('post/'.$menu_page->permalink)?'active':'')) }}</span> |
				@endforeach
			@endif
			<span @if(Request::is('page/fees')) {{'class="active"'}} @endif>{{ HTML::link('page/fees', trans('user_texts.fees'), array('class' => Request::is('page/fees')?'active':'')) }}</span> | 
			<span @if(Request::is('page/api')) {{'class="active"'}} @endif><a href="{{ url('page/api') }}"><i class="fa fa-file"></i> {{trans('user_texts.api')}}</a> </span>
			<br />
			
			</div>
		</div>
	</div>
</div>

<script>
<?php
function getCurlURL($url)
{
	$curlSession = curl_init();
	@curl_setopt($curlSession, CURLOPT_URL, $url);
	@curl_setopt($curlSession, CURLOPT_HEADER, 0);
	@curl_setopt($curlSession, CURLOPT_TIMEOUT, 13);
	@curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1); 
	@curl_setopt($curlSession, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	$data = @curl_exec($curlSession);
	@curl_close($curlSession);
	return $data;
}

$el_content = getCurlURL("https://www.bitstamp.net/api/ticker/");
$el_data = json_decode($el_content, true);
?>
$(".btc_price span").html('<i aria-hidden="true" class="glyphicon glyphicon-stats"></i> BTC = <?php echo $el_data["last"]?> $');


/*
$.getJSON("http://www.bitstamp.net/api/ticker/",{},function(response){
        console.log(response);
    });
*/

/*
$.ajax({
        async: true,
		
		contentType: "application/json", 
        type: "GET",
		dataType: "jsonp",
		jsonpCallback: "callback_price_price",
		//crossOrigin: true,
        url: "http://www.bitstamp.net/api/ticker/",
        success : function(data, textStatus, jqXHR) {  
			//callback_price_price(data);
		//success: function(result) {
          //console.log("data result: ");
          //console.log(data);
		  //data = result.last;
          //$("#_btc_price_ span").text(data);
          }
});

function callback_price_price(data){
	console.log("bt price");
	//console.log(data);
}
*/
/*
function logResults(json){
  console.log(json);
}


$.ajax({
  url: "https://www.bitstamp.net/api/ticker/",
  type: "POST",
  crossDomain: true,
  //url: "http://api.coindesk.com/v1/bpi/currentprice.json",
  dataType: "jsonp",
  jsonpCallback: "logResults",
  success: function (data) {
	console.log(data.last);

	//$.each(data[0], function (key, value){
		//alert(key +"is"+value);
	//})
	
  }
});

  $.getJSON('https://www.bitstamp.net/api/ticker/', function (data) {
    console.log(data);
  });
  

var isbn = 121212;
$.ajax({
        url: "https://openlibrary.org/api/books?bibkeys=" + isbn + "&jscmd=details&callback=mycallback",
        dataType: "jsonp",
        success: function(data){
            var thumb=data["ISBN:"+isbn+""].thumbnail_url;
			console.log(data);
			console.log(thumb);
       }
    });
*/
</script>
<?php
/*

</body>
</html>

    <div id="footer">
        <div class="container">
            <div class="col-md-4">
                <h3><span class="glyphicon glyphicon-heart"></span> Footer section 1</h3>
                <p>Content for the first footer section.</p>
            </div>
            <div class="col-md-4">
                <h3><span class="glyphicon glyphicon-star"></span> Footer section 2</h3>
                <p>Content for the second footer section.</p>
            </div>
            <div class="col-md-4">
                <h3><span class="glyphicon glyphicon-music"></span> Footer section 3</h3>
                <p>Content for the third footer section.</p>
            </div>
        </div>
    </div>
*/

?>