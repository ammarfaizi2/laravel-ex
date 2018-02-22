<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Config route site frontent

// Chac goi 1

// Route::get('/', function(){
    // return View::make('index');
// });

// Cach goi 2

Route::get('/test',function(){
});



Route::get('testLocate','BaseController@testLocate');

Route::get('/', 'HomeController@index'); // call index page
Route::get('market/{market}', 'HomeController@index')->name('market');
Route::get('page/{page}', 'HomeController@routePage');
Route::post('get-chart', 'HomeController@getChart');
Route::post('voting', 'VoteCoinController@doVoting');
Route::get('maintenance', 'HomeController@maintenanceMode');

//locate
Route::get( '/locale/{locale}', 'BaseController@setLocale' );
//end locate

//added by krm
Route::post('page/contact', 'HomeController@sendEmail');
Route::post('page/submit-coin', 'HomeController@submitCoin');

//pages , news
Route::get('post/{post}', 'HomeController@viewPost');
#################################################################################
Route::group(array('before' => array('auth','admin'),'prefix' => 'admin', 'middleware' => ['App\Http\Middleware\admin', '2fa_admin']), function()
{
    Route::get('/', 'admin\\AdminSettingController@routePage');
    Route::get('setting', 'admin\\AdminSettingController@routePage');
    Route::get('setting/{page}', 'admin\\AdminSettingController@routePage');
    Route::get('setting/{page}/{pager_page}', 'admin\\AdminSettingController@routePage');

    Route::get('statistic/{page}', 'admin\\AdminSettingController@routePage');

    //content
    Route::get('content/{page}', 'admin\\AdminSettingController@routePage');
    Route::get('content/{page}/{pager_page}', 'admin\\AdminSettingController@routePage');

    //manage
    Route::get('manage/{page}', 'admin\\AdminSettingController@routePage')->name('admin.manage');
    Route::post('manage/{page}', 'admin\\AdminSettingController@routePage');
    Route::post('manage/{page}/{pager_page}', 'admin\\AdminSettingController@routePage');
    Route::get('manage/{page}/{pager_page}', 'admin\\AdminSettingController@routePage');

    Route::post('add-wallet', 'admin\\AdminSettingController@addNewWallet');
    Route::get('edit-wallet/{wallet}', 'admin\\AdminSettingController@editWallet');
    Route::post('edit-wallet', 'admin\\AdminSettingController@doEditWallet');
    Route::post('delete-wallet', 'admin\\AdminSettingController@deleteWallet');

    Route::post('delete-user', 'admin\\AdminSettingController@deleteUser');
    Route::post('ban-user', 'admin\\AdminSettingController@banUSer');


    Route::post('add-new-user', 'admin\\AdminSettingController@addNewUser')->name('admin.addNewUser');
    Route::get('edit-user/{user_id}', 'admin\\AdminSettingController@editUser');
    Route::post('edit-user/{user_id}', 'admin\\AdminSettingController@doEditUser');
    Route::post('edit-user', 'admin\\AdminSettingController@doEditUser');
    // Route::post('')

    //market
    Route::post('add-market', 'admin\\AdminSettingController@addNewMarket');
    Route::post('delete-market', 'admin\\AdminSettingController@deleteMarket');

    //pages , news
    Route::post('add-post', 'admin\\AdminSettingController@addNewPost');
    Route::get('edit-post/{post}', 'admin\\AdminSettingController@editPost');
    Route::post('edit-post', 'admin\\AdminSettingController@doEditPost');
    Route::post('delete-post', 'admin\\AdminSettingController@deletePost');

    //coin news
    Route::post('add-coin-news', 'admin\\AdminSettingController@addCoinNews');
    Route::get('edit-coin-news/{post}', 'admin\\AdminSettingController@editCoinNews');
    Route::post('edit-coin-news', 'admin\\AdminSettingController@doEditCoinNews');
    Route::post('delete-coin-news', 'admin\\AdminSettingController@deleteCoinNews');

    //withdraw limits
    Route::post('add-withdraw-limit', 'admin\\AdminSettingController@addWithdrawLimit');
    Route::get('edit-withdraw-limit/{post}', 'admin\\AdminSettingController@editWithdrawLimit');
    Route::post('edit-withdraw-limit', 'admin\\AdminSettingController@doEditWithdrawLimit');
    Route::post('delete-withdraw-limit', 'admin\\AdminSettingController@deleteWithdrawLimit');
	
    //coin giveaways
    Route::post('add-coin-giveaway', 'admin\\AdminSettingController@addCoinGiveaway');
    Route::get('edit-coin-giveaway/{post}', 'admin\\AdminSettingController@editCoinGiveaway');
    Route::post('edit-coin-giveaway', 'admin\\AdminSettingController@doEditCoinGiveaway');
    Route::post('delete-coin-giveaway', 'admin\\AdminSettingController@deleteCoinGiveaway');
	
    Route::post('send-coin', 'admin\\AdminSettingController@doSendCoin');

    Route::get('backup', 'admin\\AdminSettingController@formBackup');
    Route::post('restore', 'admin\\AdminSettingController@doBackup');
    Route::get('restore', 'admin\\AdminSettingController@formRestore');
    Route::post('restore', 'admin\\AdminSettingController@doRestore');

    //limit trade
    Route::post('add-limit-trade', 'admin\\AdminSettingController@addNewLimitTrade')->name('admin.add_new_limit_trade');
    Route::get('edit-limit-trade/{wallet}', 'admin\\AdminSettingController@editLimitTrade');
    Route::any('edit-limit-trade', 'admin\\AdminSettingController@doEditLimitTrade');
    Route::post('delete-limit-trade', 'admin\\AdminSettingController@deleteLimitTrade');
    Route::post('update-setting', 'admin\\AdminSettingController@updateSetting');

    Route::any('set-fee-trade', 'admin\\AdminSettingController@setFeeTrade');
    Route::post('set-fee-withdraw', 'admin\\AdminSettingController@setFeeWithdraw');

    Route::post('delete-coin-vote', 'admin\\AdminSettingController@deleteCoinVote');

    Route::post('add-coin-vote', 'admin\\AdminSettingController@addNewCoinVote');

    Route::post('add-post', 'admin\\AdminSettingController@addNewPost');

    Route::any('add-fee', 'admin\\AdminSettingController@addFee');

    Route::get('featured_market', 'admin\\AdminSettingController@featuredMarket')->name('admin.featured_market');
    Route::post('add_featured_market', 'admin\\AdminSettingController@addFeaturedMarket')->name('admin.add_featured_market');
    Route::get('edit_featured_market', 'admin\\AdminSettingController@editFeaturedMarket')->name('admin.edit_featured_market');
    Route::post('edit_featured_market', 'admin\\AdminSettingController@editFeaturedMarketPost')->name('admin.edit_featured_market');

    Route::get('custom_fields', 'admin\\AdminSettingController@customFields')->name('admin.custom_fields');
    Route::post('add_custom_fields', 'admin\\AdminSettingController@addCustomFields')->name('admin.add_custom_fields');
    Route::get('edit_custom_fields', 'admin\\AdminSettingController@editCustomFields')->name('admin.edit_custom_fields');
    Route::post('edit_custom_fields', 'admin\\AdminSettingController@editCustomFieldsPost');
});
// Confide routes
Route::get( 'referral/{referral}',                 'UserController@create');
Route::get( 'user/register',                 'UserController@create');
Route::get( 'user/register',                 'UserController@register')->name('register');
Route::post('user',                        'UserController@store');
Route::get( 'login',                        'UserController@login')->name('user.login');
Route::post('user/login',                  'UserController@do_login')->name("user.do_login");
Route::get('user/login', function () {
    return redirect(route('user.login'));
});
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password')->name('forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::any( 'user/logout',                 'UserController@logout')->name('logout');
Route::post( 'check-captcha',               'UserController@checkCaptcha');
Route::any( 'user/update-setting',         'UserController@updateSetting');
Route::get('user/confirm', 'UserController@confirmAccount')->name("user.confirm_account");


Route::any('/2fa', function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return redirect(route('2fa'));    
        }
        $r = session()->get('2fa_redirect');
        return redirect($r ? $r : '/');
})->name('2fa')->middleware('2fa');

Route::any("/2fa_check_code", "UserController@check2facode")->name("2fa_check_code");



//user profile
Route::group(array('before' => 'auth', 'prefix' => 'user', 'middleware' => ['2fa', 'App\Http\Middleware\user']), function () {

    Route::get("ajax_notification", "NotificationController@ajaxNotification")->name("ajax.notif");

    Route::group(['prefix' => 'messages', 'middleware' => ['2fa', 'App\Http\Middleware\user']], function () {
        Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
        Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
        Route::post('/delete_thread', 'MessagesController@deleteThread')->name('messages.delete_thread');
        Route::post('/leave_thread', 'MessagesController@leaveThread')->name('messages.leave_thread');
        Route::post('/add_participants', 'MessagesController@addParticipants')->name('messages.add_participants');
    });
    Route::post('/2fa_check', 'Google2FAHandler@check')->name("2fa_check");
    Route::get('/disable-two-factor-auth', function () {
        if (! session()->get("disable_2fa")) {
            abort(404);
        }
        $user = Confide::user();
        DB::table('users')->where('id', '=', $user->id)->update(
            [
                'google2fa_secret' => null,
                'lastest_login' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        );
        session(['google2fa' => null, "disable_2fa" => null]);
        return redirect(route('user.profile_page', 'two-factor-auth'));
    })->name('user.disable_tfa');
    Route::post('/complete-two-factor-auth', 'UserController@completeTwoFactorAuth')->name('user.complete_tfa');

    //Normal route
    Route::get('profile', 'UserController@viewProfile')->name('user.view_profile');
    
	//Connect Clef to account. //Install 2fa
	Route::get('profile/two-factor-auth/clef', 'ClefController@first_authentication');	

    Route::get('profile/{page}', 'UserController@viewProfile')->name('user.profile_page');
    Route::post('profile/{page}', 'UserController@viewProfile');
    Route::get('profile/{page}/{filter}', 'UserController@viewProfile');
    Route::post('profile/{page}/{filter}', 'UserController@viewProfile');
    Route::get('deposit/{wallet_id}', 'UserController@formDeposit');
    
    Route::get('withdraw/{wallet_id}', 'UserController@formWithdraw');
    Route::any('withdraw', 'UserController@doWithdraw')->name('user.withdraw');
    Route::get('withdraw-confirm/{withdraw_id}/{confirmation_code}', 'UserController@confirmWithdraw');
    Route::post('referrer-tradekey', 'UserController@referreredTradeKey');
    Route::post('cancel-withdraw', 'UserController@cancelWithdraw');
	Route::post('coin-giveaway', 'UserController@doCoinGiveaway');
	
    //transfer
    Route::get('transfer-coin/{wallet_id}', 'UserController@formTransfer');
    Route::post('transfer-coin', 'UserController@doTransfer');
   /* Route::post('viewtranfer/{type}', 'UserController@viewTransferOut');*/
   
   /* Route::post('profile/notifications', 'UserController@viewProfile'); */
});


//trading
Route::group(['middleware' => ['2fa', 'App\Http\Middleware\user']], function() {
    Route::post('dobuy', 'OrderController@doBuy')->name('order.do.buy');
    Route::post('dosell', 'OrderController@doSell')->name('order.do.sell');
    Route::post('docancel', 'OrderController@doCancel');
});


//correct here
Route::post('get-orderdepth-chart', 'OrderController@getOrderDepthChart');
//end correct here






//Route::post('dotest', 'HomeController@doTest');

//deposit
Route::post('generate-addr-deposit', 'DepositController@generateNewAddrDeposit');
Route::get('cron-update-deposit', 'DepositController@cronUpdateDeposit');
Route::get('callback-update-deposit/{wallet_type}', 'DepositController@callbackUpdateDeposit');
Route::get('callback-update-deposit-test/{wallet_type}', 'DepositController@callbackUpdateDeposit_test');
Route::get('blocknotify-update-deposit/{wallet_type}', 'DepositController@blocknotifyUpdateDeposit');

//prevent CSRF attacks
//Route::when('*', 'csrf', array('post', 'put', 'delete'));
//Route::when('*', 'csrf', array('post'));
//Route::when('*', 'csrf', array('post', 'put', 'patch', 'delete'));
/*
//How to skip CSRF on webhooks
Route::filter('csrf', function()
{
    if( ! in_array(Route::currentRouteName(), array('blockchain','clef.logout')))
        if (Session::token() != Input::get('_token'))
            throw new Illuminate\Session\TokenMismatchException;
});

*/



//Connect Clef to account	(added in user group above)
//Route::get('user/profile/two-factor-auth/clef', 'ClefController@first_authentication');	//Install 2fa

//2fa two-factor-auth
Route::post( '/two-factor-auth/first_auth', 'UserController@firstAuth' );

//Route::post( 'user/verify_token', 'AuthController@ajVerifyToken' );
Route::post( '/two-factor-auth/disable', 'AuthController@removeTwoFactorAuth' );	