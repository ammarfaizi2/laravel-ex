<?php

namespace App\Http\Controllers;

use DB;
use Lang;
use Hash;
use Mail;
use Config;
use Confide;
use Request;
use Redirect;
use Exception;
use Validator;
use App\Session2FA;
use App\Models\News;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Limits;
use App\Models\Market;
use App\Models\Wallet;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Setting;
use App\Models\FeeTrade;
use App\Models\CoinVote;
use App\Models\Transfer;
use App\Models\Withdraw;
use App\Models\Giveaways;
use App\Models\FeeWithdraw;
use App\Models\LoginHistory;
use App\Models\Notifications;
use App\Models\Giveawayclaims;
use App\Models\Authentication;
use App\Models\WalletLimitTrade;
use App\Models\SecurityQuestion;
use App\Models\UserAddressDeposit;
use App\Models\UserSecurityQuestion;

/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/
class UserController extends Controller
{

    public $request;

    public function __construct(\Illuminate\Http\Request $request)
    {   
        parent::__construct();
        $this->request = $request;
        
    }

    /**
     * Get ip of client
     */
    public function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    /**
     * Check ip of user login and send a mail if current ip different to ip last login
     */
    public function sendMailIPUser($user, $ip)
    {
        if ($ip!=$user->ip_lastlogin) {
            $data_send=array('user' => $user,'ip'=>$ip);
            Mail::send(
                'emails.ip_notification', $data_send, function ($message) use ($user) {
            
                    $message->to($user->email)->subject(Lang::get('texts.ip_notification'));
                }
            );
        }
    }

    public function reset_password_action()
    {
        if (isset($_GET["token"])) {
            $st = DB::table("password_reminders")
                    ->select(["token", "created_at"])
                    ->where("token", "=", $_GET["token"])
                    ->first();
            if (isset($st->token) && isset($st->created_at)) {
                $expired = env("PASSWORD_RESET_CODE_EXPIRED");
                if (strtotime($st->created_at)+$expired < time()) {
                    echo "<html><head><script>alert(\"Expired password reset code\");window.location=\"".route("user.login")."\";</script></head></html>";
                    exit(1);
                }
                return view("forgotpassaction");
            }
        }
        abort(404);
    }

    public function reset_password_action_post()
    {
        if (isset($_GET["token"])) {
            $st = DB::table("password_reminders")
                    ->select(["token", "created_at"])
                    ->where("token", "=", $_GET["token"])
                    ->first();
            if (isset($st->token) && isset($st->created_at)) {
                $expired = env("PASSWORD_RESET_CODE_EXPIRED");
                if (strtotime($st->created_at)+$expired < time()) {
                    echo "<html><head><script>alert(\"Expired password reset code\");window.location=\"".route("user.login")."\";</script></head></html>";
                    exit(1);
                }
                if (isset($_POST['password'], $_POST['password_confirmation'])) {
                    if ($_POST['password'] !== $_POST['password_confirmation']) {
                        return Redirect::to(url()->current()."?token=".$_GET["token"])->with("error", trans("user_texts.password_not_match"));
                    } else {
                        $st = DB::table("password_reminders")
                                ->select("user_id")
                                ->where("token", "=", $_GET["token"])
                                ->first();
                        $st = DB::table("users")
                                ->where("id", "=", $st->user_id)
                                ->limit(1)
                                ->update([
                                    "password" => password_hash($_POST["password"], PASSWORD_BCRYPT)
                                ]);
                        $st = DB::table("password_reminders")
                                ->where("token", "=", $_GET["token"])
                                ->limit(1)
                                ->delete();
                        return Redirect::to(route("user.login"))->with("notice", trans("user_texts.password_reset_success"));
                    }   
                }
            }
        }
        abort(404);
    }

    /**
     * Displays the form for account creation
     */
    public function create($referral = '')
    {
        $setting = new Setting();
        $recaptcha_publickey = $setting->getSetting('recaptcha_publickey', '');
        $data['recaptcha_publickey']=$recaptcha_publickey;
        $data['question1s'] = SecurityQuestion::where('type', '=', '1')->get();
        $data['question2s'] = SecurityQuestion::where('type', '=', '2')->get();
        if ($referral!='') {
            $data['referral'] =$referral;
        }
        return view(
            //Config::get('confide::signup_form')
            'register',
            $data
        );
    }

    public function register()
    {
        $setting = new Setting();
        $recaptcha_publickey = $setting->getSetting('recaptcha_publickey', '');
        $data['recaptcha_publickey']=$recaptcha_publickey;
        $data['question1s'] = SecurityQuestion::where('type', '=', '1')->get();
        $data['question2s'] = SecurityQuestion::where('type', '=', '2')->get();
        return view(
            /*Config::get('confide::signup_form')*/
            'register',
            $data
        );
    }

    public function confirmAccount()
    {
        if (isset($_GET["code"])) {
            $st = DB::table("confirmation_code")
                ->select(["user_id", "expired_at"])
                ->where("code", "=", $_GET["code"])
                ->first();
            if ($st) {
                if (strtotime($st->expired_at) <= time()) {
                    DB::table("confirmation_code")
                    ->where("code", "=", $_GET["code"])
                    ->limit(1)
                    ->delete();
                    return Redirect::to(route("user.login"))->with('notice', trans("email_messages.confirm_account_expired"));;
                } else {
                    DB::table("confirmation_code")
                        ->where("code", "=", $_GET["code"])
                        ->limit(1)
                        ->delete();
                    DB::table("users")
                        ->where("id", "=", $st->user_id)
                        ->limit(1)
                        ->update(
                            [
                                "confirmed" => 1
                            ]
                        );
                    return Redirect::to(route("user.login"))->with('notice', trans("email_messages.account_confirmed"));;
                }
            }
        }
        abort(404);
    }

    /**
     * Stores new account
     */
    public function store()
    {
        $user = new User;

        $user->fullname = '';//Request::get( 'fullname' );
        $user->username = Request::get('username');
        $user->email = Request::get('email');
        $user->password = Request::get('password');
        $user->referral = Request::get('referral');
        $user->banned = 0;
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Request::get('password_confirmation');
        $user->confirmation_code = '';
        $user->authy = '';
        $user->two_factor_auth = '';
        $user->timeout = '';
        $trade_key = md5($user->username.$user->email.time());
        $user->trade_key = $trade_key;
        $user->ip_lastlogin=$this->get_client_ip();
        // Save if valid. Password field will be hashed before save
        $user->save();


        if ($user->id) {
            Mail::to(Request::get('email'))
            ->send(new \App\Mail\ConfirmAccount(
                [
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email
                ]
            ));

            $user->addRole('User');
            
                //Only during beta test
            $balance = new Balance();
            
            $balance->addMoney(10, 23, $user->id);
            $balance->addMoney(1000000, 25, $user->id);
            $balance->addMoney(1000000, 39, $user->id);
            $balance->addMoney(1000000, 34, $user->id);
            $balance->addMoney(1000000, 45, $user->id);
            $balance->addMoney(5000000, 49, $user->id);
            $balance->addMoney(5000000, 50, $user->id);
            $balance->addMoney(1000, 24, $user->id);
            
            //add question
            /*
            $question1 = Request::get('question1');
            $question2 = Request::get('question2');
            $answer1 = Request::get('answer1');
            $answer2 = Request::get('answer2');
            UserSecurityQuestion::insert(array('user_id' => $user->id, 'question_id' => $question1, 'answer'=>$answer1));
            UserSecurityQuestion::insert(array('user_id' => $user->id, 'question_id' => $question2, 'answer'=>$answer2));
            */
            $notice = Lang::get('confide::confide.alerts.account_created') . ' ' . Lang::get('confide::confide.alerts.instructions_sent');

            // Redirect with success message
            return Redirect::action('UserController@login')
                            ->with('notice', $notice);
        } else {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');
            return Redirect::to('user/register')->withInput(Request::except('password'))->with('error', $error);
            /*$referral = Request::get( 'referral' );
            if(!empty(trim($referral)))
                return Redirect::to('referral/'.$referral)->withInput(Request::except('password'))->with( 'error', $error );
            else
               return Redirect::action('UserController@create')->withInput(Request::except('password'))->with( 'error', $error );*/
        }
    }

    /**
     * Displays the login form
     */
    public function login()
    {
        if ($u = Confide::user()) {
            // If user is logged, redirect to internal
            // page, change it to '/admin', '/dashboard' or something
            //return Redirect::to('/',302, array(), true);

            
            return Redirect::to("/");
        } else {
            if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
                exit;
            }
            $a = Config::get('confide::login_form');
            //var_dump($a);die;
            return view('login');
        }
    }

    /**
     *
     */

    public function firstAuth($input = array())
    {
        if (count($input) != 4) {
            $input = array(
                        'email'    => Request::get('email'), // May be the username too
                        'username' => Request::get('email'), // so we have to pass both
                        'password' => Request::get('password'),
                        'remember' => Request::get('remember'),
                    );
        }
        $user = User::where('email', '=', $input['email'])->orwhere('username', '=', $input['email'])->first();

        if (isset($user->password) && Hash::check($input['password'], $user->password)) {
                //Two factor authentication
            if (!empty($user->two_factor_auth)) {
                /*
                $authcontroller = new AuthController();
                $auth_controller = $authcontroller->getAuthy();
                $requestSms = $auth_controller->requestSms($user->authy);
                // echo "<pre>errors: "; print_r($requestSms->errors()); echo "</pre>";
                // echo "<pre>requestSms: "; print_r($requestSms); echo "</pre>";
                if($requestSms->ok()){
                    echo json_encode((array)$requestSms->ok()+array('status'=>'two_login', 'authy_id'=>$user->authy));
                    exit;
                }else{//not_sent_token
                    echo json_encode((array)$requestSms->errors()+array('status'=>'error'));
                    exit;
                }
                */
                //return view('login_2fa');
                /*
                $auth_message = trans('user_texts.installed_two_factor_auth') .'<br />'.trans('user_texts.login_with_two_factor') ;
                echo json_encode( array('status'=>'two_login', 'message'=>$auth_message) );
                */
                
                $err_msg = trans('messages.two_factor_auth') . ' - ' .trans('messages.two_factor_auth');
                return Redirect::action('UserController@login')
                            ->with('two_factor_authentication', $err_msg);
            } else {
                //Normal authentication
                
                // If you wish to only allow login from confirmed users, call logAttempt
                // with the second parameter as true.
                // logAttempt will check if the 'email' perhaps is the username.
                // Check that the user is confirmed.
                
                if ($c = Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
                    echo json_encode($input + array('status'=>'one_login_success','c'=>$c,'signup_confirm'=>Config::get('confide::signup_confirm')));
                    exit;
                } else {
                    $user = new User;

                    // Check if there was too many login attempts
                    if (Confide::isThrottled($input)) {
                        $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
                    } elseif ($user->checkUserExists($input) and ! $user->isConfirmed($input)) {
                        $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
                    } else {
                        $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
                    }
					
					echo json_encode(array('status'=>'error','c'=>$c,'message'=>$err_msg));
					exit;
                }
            }
        } else {
            echo json_encode(array('status'=>'error','message'=> trans('messages.not_match_user')));
            exit;
        }
    }

    private function reverify($user)
    {
        Mail::to($user->email)
            ->send(new \App\Mail\ConfirmAccount(
                [
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email
                ]
            ));
    }

    /**
     * Attempt to do login
     */
    public function do_login()
    {
        $input = array(
            'email'    => Request::get('email'), // May be the username too
            'username' => Request::get('email'), // so we have to pass both
            'password' => Request::get('password'),
            'remember' => Request::get('remember'),
        );

        
        $user = User::where('email', '=', Request::get('email'))->orwhere('username', '=', Request::get('email'))->first();
        
        /*var_dump($user->password);

        var_dump($input['password']);

        var_dump(password_verify($input['password'], $user->password));

        die;*/
        
        if (isset($user->password) && password_verify(Request::get('password'), $user->password)) {

            if (! $user->confirmed) {
                $this->reverify($user);
                $ww = [
                    "redirect" => route("user.login")
                ];
                session(["error" => trans("user_texts.re_confirm")]);
                return response()->json($ww, 200);
            }
            $cip = $this->get_client_ip();
            session(["_identificator" => [
                "ip_address" => $cip,
                "user_agent" => $_SERVER["HTTP_USER_AGENT"]
            ]]);
            $wh = DB::table("whitelist_ip_state")
                ->select("login")
                ->where("user_id", "=", $user->id)
                ->first();
            if (isset($wh->login) && $wh->login === "on") {
                $rr = DB::table("whitelist_login_ip")
                    ->select("ip")
                    ->where("user_id", "=", $user->id)
                    ->get();
                if ($rr) {
                    $f = false;
                    foreach ($rr as $ip) {
                        if (preg_match("/$ip->ip/i", $cip)) {
                            $f = true;
                            break;
                        }
                    }
                    if (! $f) {
                        if (Request::get("isAjax")) {
                            echo json_encode(array('status'=>'error','c'=>1,'message'=>trans("user_texts.blocked_ip", ["type" => "Login"])));
                            exit();
                        }
                        return Redirect::action('UserController@login')
                            ->withInput(Request::except('password'))
                            ->with('error', trans("user_texts.blocked_ip"));
                    }
                }
            }
            if (isset($user->google2fa_secret) && $user->google2fa_secret) {
                session(["tmp_login" => $input]);
				
				if (Request::get('isAjax')) {
					return json_encode(array('status'=>'success','c'=>true,'message'=>trans("user_texts.tfa_3"), 'callnext'=>'2fa'));
					//exit;
					//response()->json = means send back as json object, JS no need to convert to json object
				} else {
					return response()->json(["2fa"], 200);
				}
				//return json_encode(array('status'=>'success','c'=>'2fa','message'=>trans("user_texts.tfa_3")));
				
				//return response()->json(array('status'=>'success','c'=>'2fa','message'=>trans("user_texts.tfa_3")));
				//return response()->json(array('status'=>'success','c'=>'2fa','message'=>trans("user_texts.tfa_3")));
				//echo json_encode(array('status'=>'success','c'=>'2fa','message'=>trans("user_texts.tfa_3")));
				//exit;
				
            }
        }
    
        
        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        // Get the value from the config file instead of changing the controller
        if ($c = Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            // Redirect the user to the URL they were trying to access before
            // caught by the authentication filter IE Redirect::guest('user/login').
            // Otherwise fallback to '/'
            // Fix pull #145
            $user = Confide::user();
            $ip=$this->get_client_ip();
            LoginHistory::create($user->id, $ip);
            $this->sendMailIPUser($user, $ip);
            User::where('id', $user->id)->update(array('lastest_login' => date("Y-m-d H:i:s"), 'ip_lastlogin'=>$ip));
            if (Request::get('isAjax')) {
                //echo 1;
				//exit;
				return json_encode(array('status'=>'success','c'=>$c,'message'=>'', 'callnext'=>'login'));
				//return Redirect::to("/user/profile");
            } else {
                if (User::_hasRole('admin')) {
                    return Redirect::to("/admin");
                } else {
                    return Redirect::to("/admin"); // change it to '/admin', '/dashboard' or something
                }
            }
        } else {
            $user = new User;

            // Check if there was too many login attempts
            if (Confide::isThrottled($input)) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ($user->checkUserExists($input) and ! $user->isConfirmed($input)) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }
			
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error','c'=>$c,'message'=>$err_msg));
                exit;
            } else {
                return Redirect::action('UserController@login')
                            ->withInput(Request::except('password'))
                ->with('error', $err_msg);
            }
        }
    }

    public function check2facode()
    {
        if (isset($_POST['code'])) {
            $max = env("GOOGLE2FA_THROTTLE") - 1;
            $lockedTime = env("GOOGLE2FA_SECONDS_LOCKED");

            $tries = session()->get("g2fa_tt");
            $last_try = (int) session()->get("g2fa_lt");

            if (is_int($tries)) {
                if ($tries > $max) {
                    if ($last_try+$lockedTime > time()) {
                        session(["g2fa_lt" => time()]);
                        return response()->json("throttled");
                    } else {
                        $tries = 0;
                    }
                }
            } else {
                $tries = 0;
            }

            session(["g2fa_lt" => time(), "g2fa_tt" => $tries+1]);

            if ($ww = \App\User::google2fa($_POST['code'])) {
                session(["g2fa_tt" => 0]);
                session(["google2fa" => true]);
                if (isset($_POST['admin_page'])) {
                    session(["admin_page" => true]);
                }

            }
            if (isset($_GET["login"])) {                

                if ($ww && Confide::logAttempt(session()->get("tmp_login"), Config::get('confide::signup_confirm'))) {
                    $user = Confide::user();
                    $ip=$this->get_client_ip();
                    LoginHistory::create($user->id, $ip);
                    $this->sendMailIPUser($user, $ip);
                    User::where('id', $user->id)->update(array('lastest_login' => date("Y-m-d H:i:s"), 'ip_lastlogin'=>$ip));
                    session(["tmp_login" => null]);
                    $ww = [
                        "redirect" => "/"
                    ];
                } else {
                    $ww = [
                        "redirect" => null
                    ];
                }
            }
            return response()->json($ww, 200);
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param string $code
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
                        return Redirect::action('UserController@login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
                        return Redirect::action('UserController@login')
                            ->with('error', $error_msg);
        }
    }

    /**
     * Displays the forgot password form - VIEW
     */
    public function forgot_password()
    {
        return view('forgotpass');
    }

    /**
     * Attempt to send change password link to the given email
     */
    public function do_forgot_password()
    {

        // do the validation ----------------------------------
        // validate against the inputs from our form

        $error_msg = '';
        $error_msg_type = '';
        $error_msg_control = '';
        $token = Request::get('_token');
        
        
        // create the validation rules ------------------------
        $rules = array(
            'email'            => 'required|email'     // required email
        );

        // do the validation ----------------------------------
        // validate against the inputs from our form
        $validator = Validator::make(Request::all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {
            // get the error messages from the validator
            //$messages = $validator->messages();

            $error_msg = Lang::get('validation.email', array('attribute' => 'Email'));
			$error_msg_type = 'error';
            //echo $error_msg;
            //exit;
                
            // redirect our user back to the form with the errors from the validator
            //return Redirect::to('ducks')
            //    ->withErrors($validator);
        } else {
        // validation successful ---------------------------
        $error_msg = 'Email OK';
                // echo $error_msg;
                // exit;
			if (/*Confide::forgotPassword(Request::get('email'))*/ $this->forgotPasswordAction(Request::get('email'))) {
				$error_msg = Lang::get('confide::confide.alerts.password_forgot');
				$error_msg_type = 'notice';
				$error_msg_control = 'login';
			} else {
				$error_msg = Lang::get('confide::confide.alerts.instructions_sent');
				//$error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
				$error_msg_type = 'success';
				$error_msg_control = 'forgot_password';
				/*return Redirect::action('UserController@forgot_password')
								->withInput()
					->with( 'error', $error_msg );*/
			}
        }

        
        if (Request::get('isAjax')) {
                echo json_encode(array("status" => $error_msg_type, "msg" => $error_msg));
				//echo $error_msg;
                exit;
        } else {
            return Redirect::action('UserController@'.$error_msg_control)
            ->with($error_msg_type, $error_msg);
        }
    }

    private function forgotPasswordAction($email)
    {
        $user = DB::table("users")
                ->select(["username", "email", "id"])
                ->where("email", "=", $email)
                ->first();
        if (isset($user->username) && isset($user->email)) {
            Mail::to($email)
            ->send(new \App\Mail\ForgotPassword(
                [
                    "username" => $user->username,
                    "email" => $user->email,
                    "user_id" => $user->id
                ]
            ));
            return true;
        }
        return false;
    }

    /**
     * Shows the change password form with the given token
     */
    public function reset_password($token)
    {
        return view(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     */
    public function do_reset_password()
    {

        $input = array(
            'token'=>Request::get('token'),
            'password'=>Request::get('password'),
            'password_confirmation'=>Request::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if (Confide::resetPassword($input)) {
            //echo 'Test 1';
                    //exit;
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
                        return Redirect::action('UserController@login')
                            ->with('notice', $notice_msg);
        } else {
                    //echo 'Test 2, token: '.Request::get( 'token' ) .' , token__: '.Request::get( '_token' );
                    //exit;
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            
            /*
            return Redirect::action('UserController@reset_password', array('token'=>$input['token']))
                            ->with( 'error', $error_msg );
            */
                        
            return Redirect::action('UserController@reset_password', array('token'=>$input['token']))
                ->withInput()
                    ->with('error', $error_msg);
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        Confide::logout();
        session([
                    "google2fa" => null,
                    "admin_page" => null,
                    "_identificator" => null
                ]);
        return Redirect::to('/');
    }

    /**
     * ajax validate captcha
     */
    public function checkCaptcha()
    {
        echo 1;
        exit;   
        include app_path().'/libraries/recaptchalib.php';
        $setting = new Setting();
        $publickey = $setting->getSetting('recaptcha_publickey', '');// "6LeoOPASAAAAAPsHsCBdbM60dEBKVDydRItjlmHR"; // you got this from the signup page
        $privatekey = $setting->getSetting('recaptcha_privatekey', '');//"6LeoOPASAAAAAB_fPJ0h5iOmwp5p-lqldnLk0zjY";
        if ($_POST["recaptcha_response_field"]) {
            $resp = recaptcha_check_answer(
                $privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]
            );

            if ($resp->is_valid) {
                echo 1;
                exit;
            } else {
                exit(
                    "The reCAPTCHA wasn't entered correctly. Go back and try it again." .
                    "(reCAPTCHA said: " . $resp->error . ")"
                );
            }
        } else {
            exit("Not submit captcha!");
        }
    }

    public function updateSetting()
    {
        Session2FA::check();
        $update= ['timeout'=>Request::get('timeout'),'updated_at'=>date("Y-m-d H:i:s")];
        $fullname = Request::get('fullname');
        $password = Request::get('password');
        //$password2 = Request::get('password2');
        if ($password!='' && !Hash::check($password, Confide::user()->password)) {
            $update['password'] = Hash::make($password);
        }
        if (!empty($fullname)) {
            $update['fullname'] = $fullname;
        }
        $user = Confide::user();
        DB::table("users")
            ->where("id", $user->id)
            ->limit(1)
            ->update($update);
        return Redirect::to(route('user.view_profile'))
                            ->with('notice', "Profile updated successfully.");
    }

    public function viewprofile($page = '', $filter = '')
    {
        if (isset($_GET['success_edit_form'])) {
            return Redirect::to(route('user.view_profile'))
                            ->with('notice', "Profile updated successfully.");
        }
        $user = Confide::user();
        $user_id = $user->id;
        $data = array();
        $data['user_id'] = $user_id;
        $data['user'] = $user;
        if ($user_id > 0) {
            $profile = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->select('users.*', 'roles.name as rolename')
            ->where('users.id', '=', $user_id)
            ->get();

            if ($profile) {
                $data['profile'] = $profile->first()->toArray();
            }
        }
        $data['page'] = $page;
        $data['filter'] = $filter;
        $balance = new Balance();
        $order = new Order();
        $market = new Market();
        $wallet = new Wallet();
        $setting = new Setting();
        $data['disable_points']=$setting->getSetting('disable_points', 0);
        switch ($page) {
        case 'balances':
            $wallets = Wallet::orderBy('name')->get()->toArray();
            foreach ($wallets as $key => $value) {
                $wallet_id = $value['id'];
                //get balance
                $balance_amount = $balance->getBalance($wallet_id);
                $wallets[$key]['balance'] = sprintf('%.8f', $balance_amount);
                //get PENDING DEPOSITS
                $deposit_pendding = Deposit::where('user_id', '=', $user_id)
                                    ->where('wallet_id', '=', $wallet_id)
                                    ->where('paid', '=', 0)->sum('amount');
                $wallets[$key]['deposit_pendding'] = sprintf('%.8f', $deposit_pendding);
                //get PENDING WITHDRAWALS
                $withdraw_pendding = Withdraw::where('user_id', '=', $user_id)
                ->where('wallet_id', '=', $wallet_id)
                ->where('status', '=', 0)->sum('amount');
                $wallets[$key]['withdraw_pendding'] = sprintf('%.8f', $withdraw_pendding);
                //get HELD FOR ORDERS
                //giao dich ban se giam tien cua wallet hien tai, doi voi btc/ltc (dong tien trao doi) thi giao dich mua se giam tien no
                //vi vay can xac dinh dau la btc/ltc, bang cach dua vao market, wallet_to trong market chinh la dong tien chinh de trao doi
				
				// exchange rate of the currency pair, with the BTC / LTC (the exchange rate of the exchange) the transaction will reduce the exchange rate
                // With a loan from a real estate BTC / LTC, the only way to market, wallet_to in the market is the main
				
                $wallets_to = Market::select("market.wallet_to")->distinct()->get();
                $wal_to = array();
                foreach ($wallets_to as $value) {
                    $wal_to[] = $value->wallet_to;
                }
                //$wallets_to = array_column($market, 'wallet_to');

                $status_active = $order->getStatusActive();
                /*if(in_array($wallet_id,$wal_to)){
                    $held_order = Order::leftJoin('market', 'orders.market_id', '=', 'market.id')
                                ->where('market.wallet_to','=',$wallet_id)
                                ->where('orders.user_id','=',$user_id)
                                ->whereIn('status', $status_active)
                                ->sum('to_value');
                }else{*/
                    $held_order = Order::leftJoin('market', 'orders.market_id', '=', 'market.id')
                                ->where('market.wallet_from', '=', $wallet_id)
                                ->where('orders.user_id', '=', $user_id)
                                ->where('type', '=', 'sell')
                                ->whereIn('status', $status_active)
                                ->sum('from_value');
                //}
                //echo "<pre>getQueryLog: ".dd(DB::getQueryLog())."</pre>";
                $wallets[$key]['held_order'] = sprintf('%.8f', $held_order);
            }
            //echo "<pre>ggg?: "; print_r($wallets); echo "</pre>";
            $data['balances'] = $wallets;
            break;
        case 'orders':
            $record_per_page = 15;
            if (empty($_GET['pager_page'])) {
                $pager_page = 1;
            } else {
                $pager_page = $_GET['pager_page'];
				
            }
            $data['cur_page'] = $pager_page;
            $offset_start = ($pager_page-1)*$record_per_page;

            // pure query
            // $select = "select a.*, b.wallet_from as `from`, b.wallet_to as `to` from orders a left join market b on a.market_id=b.id where a.user_id='".$user_id."' ";

            $select2 = DB::table(DB::raw("orders a"))
                        ->select([
                            DB::raw("a.*"),
                            DB::raw("b.wallet_from as `from`"),
                            DB::raw("b.wallet_to as `to`")
                        ])
                        ->join(DB::raw("market b"), DB::raw("a.market_id"), "=", DB::raw("b.id"), "left")
                        ->where("a.user_id", $user_id);

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                // $select .= " AND (b.wallet_to='".$filter."' OR b.wallet_from='".$filter."') ";
            }
			
			if (isset($_GET['market']) && !empty($_GET['market'])) {
				// $select .= " AND a.market_id='".$_GET['market']."'";
                $select2 = $select2->where("a.market_id", "=", $_GET['market']);
			}
			if (isset($_GET['status']) && $_GET['status']!='') {
				$status_ = str_replace('_', ' ', $_GET['status']);
				// $select .= " AND a.status='".$status_."'";
                $select2 = $select2->where("a.status", "=", $status_);
			}
			if (isset($_GET['type']) && $_GET['type']!='') {
				// $select .= " AND a.type='".$_GET['type']."'";
                $select2 = $select2->where("a.type", "=", $_GET['type']);
			}

			/*
            if (isset($_GET['do_filter'])) {
                if (!empty($_GET['market'])) {
                    $select .= " AND a.market_id='".$_GET['market']."'";
                }
                if ($_GET['status']!='') {
					$status_ = str_replace('_', ' ', $_GET['status']);
                    $select .= " AND a.status='".$status_."'";
                }
                if ($_GET['type']!='') {
                    $select .= " AND a.type='".$_GET['type']."'";
                }
            }
			*/

            // $select_count = $select;
            // $total_records = DB::select($select_count);
            $total_records = $select2->get();

            $data['total_pages'] = ceil(count($total_records)/$record_per_page);

            // $select .= " order by `created_at` desc limit ".$offset_start.",".$record_per_page;
            // $ordershistory = DB::select($select);
            $ordershistory = $select2->orderBy("created_at", "desc")
                                ->limit($record_per_page)
                                ->offset($offset_start)->get();
            //echo "<pre>ordershistory: "; print_r($ordershistory); echo "</pre>";
            //echo "<pre>".dd(DB::getQueryLog())."</pre>";
            $data['ordershistories'] = $ordershistory;
            $markets = Market::get();
            $market_wallet = array();
            foreach ($markets as $value) {
                $market_wallet[$value->id] = $market->getWalletType($value->id);
            }
            $data['markets'] = $market_wallet;
            break;
        case 'trade-history':
            $record_per_page = 15;
            if (empty($_GET['pager_page'])) {
                $pager_page = 1;
            } else {
                $pager_page = (int)$_GET['pager_page'];
            }
            $data['cur_page'] = $pager_page;
            $offset_start = ($pager_page-1)*$record_per_page;

            // pure query
            // $select = "select a.*, b.wallet_from as `from`, b.wallet_to as `to` from trade_history a left join market b on a.market_id=b.id where (a.seller_id='".$user_id."' OR a.buyer_id ='".$user_id."') ";

            // prepared statement
            // var_dump("select `a`.*, `b`.`wallet_from` as `from`, `b`.`wallet_to` as `to` from trade_history a left join market b on `a`.`market_id` = `b`.`id` where `a`.`seller_id` = ? or `a`.`buyer_id` = ?");

            // query builder
            $user_id = (int) $user_id;
            $select2 = DB::table(DB::raw("trade_history a"))
                        ->select([
                            "a.*",
                            "b.wallet_from as from",
                            "b.wallet_to as to"
                        ])->join(DB::raw("market b"), "a.market_id", "=", "b.id", "left")
                        ->where(function($query) use ($user_id) {
                            $query
                             ->where("a.seller_id", "=", $user_id)
                             ->orWhere("a.buyer_id", "=", $user_id);
                        });

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                // $select .= " AND (b.wallet_to='".$filter."' OR b.wallet_from='".$filter."') ";
                $select2 = $select2->where(function ($query) use ($filter) {
                    $query
                    ->where("b.wallet_to", "=", $filter)
                    ->orWhere("b.wallet_from", "=", $filter);
                });
            }

			if (isset($_GET['market']) && !empty($_GET['market'])) {
				// $select .= " AND a.market_id='".$_GET['market']."'";
                $select2 = $select2->where("a.market_id", "=", $_GET["market"]);
			}
			if (isset($_GET['type']) && !empty($_GET['type'])) {
				// $select .= " AND a.type='".$_GET['type']."'";
                $select2 = $select2->where("a.type", "=", $_GET["type"]);
			}
		

			/*
            if (isset($_GET['do_filter'])) {
                if (!empty($_GET['market'])) {
                    $select .= " AND a.market_id='".$_GET['market']."'";
                }
                if (!empty($_GET['type'])) {
                    $select .= " AND a.type='".$_GET['type']."'";
                }
            }
			*/

            // $select_count = $select;
            // $total_records = DB::select($select_count);
            $total_records = $select2->get();

            //echo "<pre>total_records: "; print_r($total_records); echo "</pre>"; exit;

            $data['total_pages'] = ceil(count($total_records)/$record_per_page);

            // $select .= " order by `created_at` desc limit ".$offset_start.",".$record_per_page;
            // $trades = DB::select($select);

            $trades = $select2
                        ->orderBy("created_at", "desc")
                        ->limit($record_per_page)
                        ->offset($offset_start)
                        ->get();
            $data['tradehistories'] = $trades;
            $markets = Market::get();
            $market_wallet = array();
            foreach ($markets as $value) {
                $market_wallet[$value->id] = $market->getWalletType($value->id);
            }
            $data['markets'] = $market_wallet;
            break;
        case 'deposits':
            $deposits = Deposit::leftJoin('wallets', 'deposits.wallet_id', '=', 'wallets.id')
                ->select('deposits.*', 'wallets.name', 'wallets.type')
                ->where('user_id', '=', $user_id)
                ->where('wallets.type', '!=', "POINTS");

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                $deposits = $deposits->where('deposits.wallet_id', '=', $filter);
            }

			if (isset($_POST['wallet']) && $_POST['wallet']!='') {
				$deposits = $deposits->where('wallet_id', '=', $_POST['wallet']);
			}
			if (isset($_POST['status']) && $_POST['status']!='') {
				$deposits = $deposits->where('paid', '=', $_POST['status']);
			}
			/*
            if (isset($_POST['do_filter'])) {
                if (isset($_POST['wallet']) && $_POST['wallet']!='') {
                    $deposits = $deposits->where('wallet_id', '=', $_POST['wallet']);
                }
                if ($_POST['status']!='') {
                    $deposits = $deposits->where('paid', '=', $_POST['status']);
                }
            }
			*/

            $deposits = $deposits->orderBy('created_at', 'desc')->get();
            //echo "<pre>_POST: "; print_r($_POST); echo "</pre>";
            //echo "<pre>"; echo dd(DB::getQueryLog()); echo "</pre>";
            $data['deposits'] = $deposits;
            $wallets = Wallet::select('id', 'type', 'name')->get();
            $data['wallets'] = $wallets;
            break;
        case 'deposits-point':
            $deposits = Deposit::leftJoin('wallets', 'deposits.wallet_id', '=', 'wallets.id')
                ->select('deposits.*', 'wallets.name', 'wallets.type')
                ->where('user_id', '=', $user_id)
                ->where('wallets.type', '=', "POINTS");

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                $deposits = $deposits->where('deposits.wallet_id', '=', $filter);
            }
                
            $deposits = $deposits->orderBy('created_at', 'desc')->get();
            //echo "<pre>_POST: "; print_r($_POST); echo "</pre>";
            //echo "<pre>"; echo dd(DB::getQueryLog()); echo "</pre>";
            $data['deposits'] = $deposits;
            break;
        case 'withdrawals':
            $withdrawals = Withdraw::leftJoin('wallets', 'withdraws.wallet_id', '=', 'wallets.id')
                ->select('withdraws.*', 'wallets.name', 'wallets.type')
                ->where('user_id', '=', $user_id);

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                $withdrawals = $withdrawals->where('withdraws.wallet_id', '=', $filter);
            }

			if (isset($_POST['wallet']) && $_POST['wallet']!='') {
				$withdrawals = $withdrawals->where('wallet_id', '=', $_POST['wallet']);
			}
			if (isset($_POST['status']) && $_POST['status']!='') {
				$withdrawals = $withdrawals->where('status', '=', $_POST['status']);
			}
			/*
            if (isset($_POST['do_filter'])) {
                if ($_POST['wallet']!='') {
                    $withdrawals = $withdrawals->where('wallet_id', '=', $_POST['wallet']);
                }
                if ($_POST['status']!='') {
                    $withdrawals = $withdrawals->where('status', '=', $_POST['status']);
                }
            }
			*/

            $withdrawals = $withdrawals->orderBy('created_at', 'desc')->get();
            //echo "<pre>_POST: "; print_r($_POST); echo "</pre>";
            //echo "<pre>"; echo dd(DB::getQueryLog()); echo "</pre>";
            $data['withdrawals'] = $withdrawals;
            $wallets = Wallet::select('id', 'type', 'name')->get();
            $data['wallets'] = $wallets;
            break;
        case 'viewtranferin':
            $record_per_page = 2;
            if (empty($_GET['pager_page'])) {
                $pager_page = 1;
            } else {
                $pager_page = $_GET['pager_page'];
            }
            $data['cur_page'] = $pager_page;
            $offset_start = ($pager_page-1)*$record_per_page;
            //$offset_end = ($pager_page*$record_per_page)-1;

            // pure query
            // $select = "select a.*, b.type, b.name, c.username from transfer_history a left join wallets b on a.wallet_id=b.id left join users c on a.receiver=c.id where a.receiver='".$user_id."'";
            // $select_count = "select count(*) as total from transfer_history a where a.receiver='".$user_id."'";
            
            // prepared statement
            // var_dump("select a.*, b.type, b.name, c.username from transfer_history a left join wallets b on `a`.`wallet_id` = `b`.`id` left join users c on `a`.`receiver` = `c`.`id` where `a`.`receiver` = ?");

            // query builder
            $select2 = DB::table(DB::raw("transfer_history a"))
                        ->select([
                            DB::raw("a.*"),
                            DB::raw("b.type"),
                            DB::raw("b.name"),
                            DB::raw("c.username")
                        ])
                        ->join(DB::raw("wallets b"), "a.wallet_id", "=", "b.id", "left")
                        ->join(DB::raw("users c"), "a.receiver", "=", "c.id", "left")
                        ->where("a.receiver", "=", $user_id);

            // prepared statement
            // var_dump("select count(*) as total from transfer_history a where a.receiver = ?");

            // query builder
            $select_count2 = DB::table(DB::raw("transfer_history a"))
                            ->select(DB::raw("count(*) as total"))
                            ->where(DB::raw("a.receiver"), "=", $user_id);

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                // $select .= " AND a.wallet_id='".$filter."'";
                $select2 = $select2->where("a.wallet_id", "=", $filter);
            }
			$where = '';
			if ($where=='') {
				if (isset($_GET['wallet']) && !empty($_GET['wallet'])) {
					// $where = $where." AND a.wallet_id='".$_GET['wallet']."'";
                    $select_count2 = $select_count2->where("a.wallet_id", "=", $_GET["wallet"]);
                    $select2 = $select2->where("a.wallet_id", "=", $_GET["wallet"]);
				}
			}
			
			/*
            $where = '';
            if (isset($_GET['do_filter'])) {
                if ($where=='') {
                    if (!empty($_GET['wallet'])) {
                        $where = $where." AND a.wallet_id='".$_GET['wallet']."'";
                    }
                }
            }
			*/

            // $select_count = $select_count." ".$where." order by `created_at` desc";
            $select_count2 = $select_count2->orderBy("created_at", "desc");


            //$total_records = DB::select($select_count); 
            $total_records = $select_count2->get();
            //echo "<pre>total_records: "; print_r($total_records); echo "</pre>"; exit;

            $data['total_pages'] = ceil($total_records[0]->total/$record_per_page);

            // $select .= " ".$where." order by `created_at` desc limit ".$offset_start.",".$record_per_page;
            $select2 = $select2
                        ->orderBy("created_at", "desc")
                        ->limit($record_per_page)
                        ->offset($offset_start);

            // $transferins = DB::select($select);
            $transferins = $select2->get();
            $data['transferins'] = $transferins;

            $wallets_temp = Wallet::get();
            $wallets = array();
            foreach ($wallets_temp as $wallet) {
                $wallets[$wallet->id] = $wallet;
            }
            $data['wallets'] = $wallets;
            break;
        case 'viewtranferout':
            $record_per_page = 2;
            if (empty($_GET['pager_page'])) {
                $pager_page = 1;
            } else {
                $pager_page = $_GET['pager_page'];
            }
            $data['cur_page'] = $pager_page;
            $offset_start = ($pager_page-1)*$record_per_page;
            //$offset_end = ($pager_page*$record_per_page)-1;

            // pure query
            // $select = "select a.*, b.type, b.name , c.username from transfer_history a left join wallets b on a.wallet_id=b.id left join users c on a.sender=c.id where a.sender='".$user_id."'";
            // $select_count = "select count(*) as total from transfer_history a where a.sender='".$user_id."'";

            $select2 = DB::table(DB::raw("transfer_history a"))
                        ->select([
                            DB::raw("a.*"),
                            DB::raw("b.type"),
                            DB::raw("b.name"),
                            DB::raw("c.username")
                        ])
                        ->join(DB::raw("wallets b"), "a.wallet_id", "=", "b.id", "left")
                        ->join(DB::raw("users c"), "a.sender", "=", "c.id", "left")
                        ->where("a.sender", "=", $user_id);

            $select_count2 = DB::table(DB::raw("transfer_history a"))
                            ->select([
                                DB::raw("count(*) as total")
                            ])
                            ->where("a.sender", "=", $user_id);

            if ($filter!='') {
                $data['current_coin'] = $wallet->getType($filter);
                // $select .= " AND a.wallet_id='".$filter."'";
                $select2 = $select2->where("a.wallet_id", "=", $filter);
            }
			$where = '';
			if ($where=='') {
				if (isset($_GET['wallet']) && !empty($_GET['wallet'])) {
					// $where = $where." AND a.wallet_id='".$_GET['wallet']."'";
                    $select2 = $select2->where("a.wallet_id", "=", $_GET["wallet"]);
                    $select_count2 = $select_count2->where("wallet_id", "=", $_GET["wallet"]);
				}
			}
			
			/*
            $where = '';
            if (isset($_GET['do_filter'])) {
                if ($where=='') {
                    if (!empty($_GET['wallet'])) {
                        $where = $where." AND a.wallet_id='".$_GET['wallet']."'";
                    }
                }
            }
			*/
            // $select_count = $select_count." ".$where." order by `created_at` desc";
            $select_count2 = $select_count2->orderBy("created_at", "desc");
            
            // $total_records = DB::select($select_count);
            $total_records = $select_count2->get();
            //echo "<pre>total_records: "; print_r($total_records); echo "</pre>";

            $data['total_pages'] = ceil($total_records[0]->total/$record_per_page);
            // $select .= " ".$where." order by `created_at` desc limit ".$offset_start.",".$record_per_page;
            $select2 = $select2
                        ->orderBy("created_at", "desc")
                        ->limit($record_per_page)
                        ->offset($offset_start);


            // $transferouts = DB::select($select);
            $transferouts = $select2->get();
            $data['transferouts'] = $transferouts;

            $wallets_temp = Wallet::get();
            $wallets = array();
            foreach ($wallets_temp as $wallet) {
                $wallets[$wallet->id] = $wallet;
            }
            $data['wallets'] = $wallets;
            break;
        case 'dashboard':
            $total_trades=Trade::where('seller_id', $user_id)->orwhere('buyer_id', $user_id)->get()->toArray();
            $data['total_trades']=count($total_trades);

            $order=new Order();
            $total_openordes=Order::where('user_id', $user_id)->whereIn('status', $order->getStatusActive())->get()->toArray();
            $data['total_openordes']=count($total_openordes);

            $twentyfourhours=date('Y-m-d H:i:s', strtotime('-24 hour'));
            $deposit_twentyfourhours=Deposit::where('user_id', $user_id)->where('created_at', ">=", $twentyfourhours)->get()->toArray();
            $data['deposit_twentyfourhours']=count($deposit_twentyfourhours);

            $withdraw_twentyfourhours=Withdraw::where('user_id', $user_id)->where('created_at', ">=", $twentyfourhours)->get()->toArray();
            $data['withdraw_twentyfourhours']=count($withdraw_twentyfourhours);

            $deposit_pendings=Deposit::where('user_id', $user_id)->where('paid', 0)->get()->toArray();
            $data['deposit_pendings']=count($deposit_pendings);

            $total_referred=User::where('referral', $user->username)->get()->toArray();
            $data['total_referred']=count($total_referred);
            //echo "<pre>total_referred: "; print_r($total_referred); echo "</pre>";
            break;
        case "ecoinstraderpoint":
            $setting= new Setting();
            $data['point_per_btc']=$setting->getSetting('point_per_btc', 1);
            $data['percent_point_reward_trade']=$setting->getSetting('percent_point_reward_trade', 0);
            $data['percent_point_reward_referred_trade']=$setting->getSetting('percent_point_reward_referred_trade', 0);
            break;
        case 'coin-giveaway':
            $ga_user_id = 100;

            $coins = array();
            $balances = Balance::where('user_id', $ga_user_id)->get()->toArray();
            foreach ($balances as $b) {
                $coins[$b['wallet_id']] = $b['amount'];
            }

            $wallets_temp = Wallet::get()->toArray();
            $wallets = array();
            foreach ($wallets_temp as $wallet) {
                $wallets[$wallet['id']] = array(
                    'logo_coin' => $wallet['logo_coin']
                );
            }

            $giveaways = Giveaways::get()->toArray();
            foreach ($giveaways as $i => $g) {
                $giveaways[$i]['logo'] = $wallets[$g['wallet_id']]['logo_coin'];
                $giveaways[$i]['coins_left'] = isset($coins[$g['wallet_id']]) ? $coins[$g['wallet_id']] : 0;
                $giveaways[$i]['hdiff'] = -1;
                $giveaways[$i]['claim'] = true;
                // pure query
                // $check = DB::select("SELECT *, TIMESTAMPDIFF(HOUR, date_created, NOW()) as hdiff FROM giveaway_claims WHERE user_id = {$user_id} AND giveaway_id = {$g['id']} ORDER BY date_created DESC LIMIT 1");

                // query builder
                $check = DB::table("giveaway_claims")
                        ->select([
                            DB::raw("*"),
                            DB::raw("TIMESTAMPDIFF(HOUR, date_created, NOW()) as hdiff")
                        ])
                        ->where("user_id", "=", $user_id)
                        ->where("giveaway_id", "=", $g["id"])
                        ->orderBy("date_created", "desc")
                        ->limit(1)
                        ->get();
                if (isset($check[0])) {
                    if ($check[0]->hdiff < $g['time_interval']) {
                        $giveaways[$i]['claim'] = false;
                    }
                    $giveaways[$i]['hdiff'] = $check[0]->hdiff . " < " . $g['time_interval'];
                }
                if ($giveaways[$i]['coins_left'] < $g['amount']) {
                    $giveaways[$i]['claim'] = false;
                }
            }
            $data['giveaways'] = $giveaways;
            break;
                
        case 'notifications':
            View::composer('laravel-notify::notification', 'Ipunkt\LaravelNotify\Composers\ViewComposer');
                
            break;
        case 'login-history':
                if (strpos(url()->current(), "security") === false) {
                    return Redirect::to(route("login_history"));
                }
                $data["login_history"] = LoginHistory::get($user->id);
            break;
        case 'ip-whitelist':
            if (strpos(url()->current(), "settings") === false) {
                return Redirect::to(route("whitelist_ip")."?p=".$_GET["p"]);
            }
            if (! isset($_GET["p"])) {
                abort(404);
            }
            $e = DB::table("whitelist_ip_state")
                ->select(["trade", "login", "withdraw"])
                ->where("user_id", "=", $user->id)
                ->first();
            switch ($_GET["p"]) {
                case 'login':
                    $data["w_ip"] = DB::table("whitelist_login_ip")
                                    ->select("*")
                                    ->where("user_id", "=", $user->id)
                                    ->orderBy("created_at", "desc")
                                    ->get();
                    $data["w_status"] = isset($e->login) && $e->login === "on";
                    break;
                case 'trade':
                    $data["w_ip"] = DB::table("whitelist_trade_ip")
                                    ->select("*")
                                    ->where("user_id", "=", $user->id)
                                    ->orderBy("created_at", "desc")
                                    ->get();
                    $data["w_status"] = isset($e->trade) && $e->trade === "on";
                    break;
                case 'withdraw':
                    $data["w_ip"] = DB::table("whitelist_withdraw_ip")
                                    ->select("*")
                                    ->where("user_id", "=", $user->id)
                                    ->orderBy("created_at", "desc")
                                    ->get();
                    $data["w_status"] = isset($e->withdraw) && $e->withdraw === "on";
                    break;
                default:
                    abort(404);
                    break;
            }
            $data["type"] = $_GET["p"];

            break;
        case '':
            break;
        // default:
        //     abort(404);
        //     break;
        }
        $data["that"] = $this;

        return view('user.profile', $data);
    }

    public function doCoinGiveaway()
    {
        if (Auth::guest()) {
            echo json_encode(array('status'=>'error','message'=> 'You need to be logged in.'));
            exit;
        }

        $user = Confide::user();
        $user_id = $user->id;

        $giveaway_id = intval($_POST['giveaway_id']);
        $giveaway = Giveaways::where('id', $giveaway_id)->first();
        if (!$giveaway) {
            echo json_encode(array('status'=>'error','message'=> 'Invalid giveaway request.'));
            exit;
        } else {
            $allowed = true;
            //check giveaway

            // pure query
            // $check = DB::select("SELECT *, TIMESTAMPDIFF(HOUR, date_created, NOW()) as hdiff FROM giveaway_claims WHERE user_id = {$user_id} AND giveaway_id = {$giveaway->id} ORDER BY date_created DESC LIMIT 1");
            // var_dump("SELECT *, TIMESTAMPDIFF(HOUR, date_created, NOW()) as hdiff FROM giveaway_claims WHERE user_id = {$user_id} AND giveaway_id = {$giveaway->id} ORDER BY date_created DESC LIMIT 1");

            // query builder
            $check = DB::table("giveaway_claims")
                    ->select(["*", DB::raw("TIMESTAMPDIFF(HOUR, date_created, NOW()) as hdiff")])
                    ->where("user_id", "=", $user_id)
                    ->where("giveaway_id", "=", $giveaway->id)
                    ->orderBy("date_created", "desc")
                    ->limit(1)
                    ->get();

            if (isset($check[0])) {
                if ($check[0]->hdiff < $giveaway->time_interval) {
                    $allowed = false;
                }
            }

            if ($allowed) {
                //check user
                $ga_user_id = 100;  //giveaway has user id 100

                // pure query
                // $ga_user = DB::select("SELECT amount FROM balance WHERE user_id = {$ga_user_id} AND wallet_id = {$giveaway->wallet_id} LIMIT 1");
                // var_dump("SELECT amount FROM balance WHERE user_id = {$ga_user_id} AND wallet_id = {$giveaway->wallet_id} LIMIT 1");

                // query builder
                $ga_user = DB::table("balance")
                        ->select("amount")
                        ->where("user_id", "=", $ga_user_id)
                        ->where("wallet_id", "=", $giveaway->wallet_id)
                        ->limit(1)
                        ->get();

                if (isset($ga_user[0])) {
                    if ($ga_user[0]->amount >= $giveaway->amount) {

                        // pure query
                        // $total = DB::select("SELECT count(*) as total FROM trade_history WHERE buyer_id = {$user_id} OR seller_id = {$user_id}");
                        // var_dump("SELECT count(*) as total FROM trade_history WHERE buyer_id = {$user_id} OR seller_id = {$user_id}");

                        // query builder
                        $total = DB::table("trade_history")
                                    ->select([DB::raw("count(*) as total")])
                                    ->orwhere("buyer_id", "=", $user_id)
                                    ->orwhere("seller_id", "=", $user_id)
                                    ->get();

                        if ($total[0]->total >= 1) {
                            $claims = new Giveawayclaims();
                            $claims->wallet_id = $giveaway->wallet_id;
                            $claims->user_id = $user_id;
                            $claims->giveaway_id = $giveaway->id;
                            $claims->amount = $giveaway->amount;
                            $claims->save();

                            $balanceCoin = Balance::where('user_id', '=', $user_id)->where('wallet_id', '=', $giveaway->wallet_id)->first();
                            if (isset($balanceCoin->amount)) {//update balance
                                $new_amount = $balanceCoin->amount + $giveaway->amount;
                                Balance::where('id', $balanceCoin->id)->update(array('amount' => $new_amount));
                            } else { //insert balance
                                Balance::insert(array('user_id' => $user_id, 'wallet_id' => $giveaway->wallet_id, 'amount'=>$giveaway->amount));
                            }
                            
                            $subtract = DB::select("UPDATE balance SET amount = amount - {$giveaway->amount} WHERE user_id = {$ga_user_id} AND wallet_id = {$giveaway->wallet_id}");
                            $subtract = DB::table("balance")
                                        ->where("user_id", "=", $ga_user_id)
                                        ->where("wallet_id", "=", $giveaway->wallet_id)
                                        ->update([
                                            "amount" => DB::raw("amount - {$giveaway->amount}")
                                        ]);
                            echo json_encode(array('status'=>'success','message'=> "We have sent you {$giveaway->amount} free {$giveaway->wallet_type}, reload the page to see your balance" ));
                            exit;
                        } else {
                            echo json_encode(array('status'=>'error','message'=> "You need to have atleast 1 trade." ));
                            exit;
                        }
                    } else {
                        echo json_encode(array('status'=>'error','message'=> "Not enough coins to giveaway." ));
                        exit;
                    }
                } else {
                    echo json_encode(array('status'=>'error','message'=> "No Donor found." ));
                    exit;
                }
            } else {
                echo json_encode(array('status'=>'error','message'=> "You have already claimed this giveaway!"));
                exit;
            }
        }
    }
    
    public function formDeposit($wallet_id = 0)
    {
        $user = Confide::user();
        $user_id = $user->id;
        $data = array();
        $wallet_id = (int)$wallet_id;
        if ($wallet_id==0) {
            $wallet = Wallet::first();
        } else {
            $wallet =Wallet::find($wallet_id);
        }
        $balance = new Balance();
        $order = new Order();
        $market = new Market();

        $data['page'] = 'deposit';
        if (! isset($wallet->type)) {
            abort(404);
        }
        $data['current_coin'] = $wallet->type;//$wallet->getType($wallet_id);
        $data['name_coin'] = $wallet->name;

        $balance_amount = $balance->getBalance($wallet_id);
        $data['balance'] = sprintf('%.8f', $balance_amount);

        //echo "<pre>".dd(DB::getQueryLog())."</pre>";
        try {
            $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
        } catch (Exception $e) {
            $data['error_message']= "Not connected to this wallet";
        }
         //echo "Fee: ".$wallet->getTxFee();
         //echo "<br>getDepositAddress: ".$wallet->getDepositAddress('test');
        // echo "<br>getReceivedByAccount: ".$wallet->getReceivedByAccount('');
        $addr_deposit = UserAddressDeposit::where('wallet_id', $wallet->id)->where('user_id', $user->id)->first();
        $address='';
        if (!isset($addr_deposit->addr_deposit)) {
            try {
                $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                $address = $wallet->getNewDepositReceiveAddress($user->username);
                UserAddressDeposit::insert(array('user_id' => $user->id, 'wallet_id' => $wallet->id, 'addr_deposit'=>$address));
            } catch (Exception $e) {
                $data['error_message']= "Not connected to this wallet"; //'Caught exception: '.$e->getMessage()."\n"; //"Not connected to this wallet"; //
            }
        } else {
            $address = $addr_deposit->addr_deposit;
        }
        $data['address_deposit'] = $address;
        $data['wallet_id'] = $wallet->id;
        $data['wallet'] = $wallet;
        return view('user.profile', $data);
    }
    public function formWithdraw($wallet_id = '')
    {
        $user = Confide::user();
        $user_id = $user->id;
        $data = array();
        if ($wallet_id=='') {
            $wallet = Wallet::first();
        } else {
            $wallet =Wallet::find($wallet_id);
        }
        $balance = new Balance();
        $order = new Order();
        $market = new Market();

        $data['page'] = 'withdraw';
        $data['current_coin'] = $wallet->getType($wallet->id);
        $st = DB::table("whitelist_ip_state")
                ->select("withdraw")
                ->where("user_id", "=", $user->id)
                ->first();
        $data["dd"] = false;
        if (isset($st->withdraw) && $st->withdraw == "on") {
            $cip = $this->get_client_ip();
            $ips = DB::table("whitelist_withdraw_ip")
                ->select("ip")
                ->where("user_id", "=", $user->id)
                ->get();
            if ($ips) {
                $flag = false;
                foreach ($ips as $ip) {
                    if (@preg_match("/$ip->ip/", $cip)) {
                        $flag = true;
                        break;
                    }
                }
                if (! $flag) {
                    $data["dd"] = true;
                }
            }
        }



        $balance_amount = $balance->getBalance($wallet->id);
        $data['balance'] = sprintf('%.8f', $balance_amount);
        $fee_withdraw = new FeeWithdraw();
        $fee=$fee_withdraw->getFeeWithdraw($wallet->id);
        $data['fee_withdraw'] = sprintf('%.8f', $fee);
        $data['wallet_id'] = $wallet->id;
        $data['wallet'] = $wallet;
        return view('user.profile', $data);
    }
    public function doWithdraw_bakup()
    {
        $amount = Request::get('amount');
        $address = Request::get('address');
        $wallet_id =(int)Request::get('wallet_id');
        $password = Request::get('password');
        $wallet = Wallet::find($wallet_id);

        $setting = new Setting();
        if ($setting->getSetting('disable_withdraw', 0)) {
            return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('notice', 'Sorry. we pause function withdrawals'); //"Not connected to this wallet."
        }

        $user = User::find((int)Confide::user()->id);
        $balance = new Balance();
        if (Hash::check($password, Confide::user()->password)) {
            $balance_amount = $balance->getBalance($wallet->id);
            $fee_withdraw = new FeeWithdraw();
            $fee=$fee_withdraw->getFeeWithdraw($wallet->id);
            $net_total = $amount-$fee;
            $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
            $min_amount = $wallet->getTxFee()+$fee;//$wallet->getTxFee();
            if ($amount < $min_amount) {
                return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('error', "Amount withdraw must be equal to or great than ".$min_amount.".");
            } elseif ($balance_amount >= $net_total) {
                try {
                    //$wallet->connectJsonRPCclient($wallet->wallet_username,$wallet->wallet_password,$wallet->wallet_ip,$wallet->port);
                    $txid=$wallet->sendToAddress($address, $net_total);
                    if ($txid) {
                        $balance->takeMoney($amount, $wallet->id, $user->id);
                        Withdraw::insert(array('user_id' => $user->id, 'wallet_id' => $wallet->id, 'to_address'=>$address, 'amount'=>$amount, 'fee_amount'=>$fee,'receive_amount'=>$net_total,'created_at'=>date('Y-m-d H:i:s'),'status'=>1,'transaction_id'=>$txid));
                        return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('notice', "You withdrawed ".sprintf('%.8f', $net_total)." ".$wallet->type." to address: ".$address.".");
                    } else {
                        return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('notice', "Can not ".$wallet->type.".");
                    }
                } catch (Exception $e) {
                    return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('notice', "Not connected to this wallet."); //'Caught exception: '.$e->getMessage()
                }
            } else {
                return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('error', "Your balance is not enough.");
            }
        } else {
            return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('error', "Password invalid.");
        }
    }

    public function doWithdraw()
    {
        Session2FA::check();
        $amount = Request::get('amount');
        $address = Request::get('address');
        $wallet_id = (int)Request::get('wallet_id');
        $password = Request::get('password');
        $wallet = Wallet::find($wallet_id);

        $setting = new Setting();
        if ($setting->getSetting('disable_withdraw', 0)) {
            return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('notice', 'Sorry. we pause function withdrawals'); //"Not connected to this wallet."
        }

        $user = Confide::user();
        $balance = new Balance();
        if (Hash::check($password, Confide::user()->password)) {
            $balance_amount = $balance->getBalance($wallet->id);
            $fee_withdraw = new FeeWithdraw();
            $fee=$fee_withdraw->getFeeWithdraw($wallet->id);
            $net_total = $amount-$fee;
            try {
                $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                $min_amount = $wallet->getTxFee()+$fee;//$wallet->getTxFee();
                if ($amount < $min_amount) {
                    return Redirect::to('user/withdraw/'.$wallet->id)
                                ->with('error', "Amount withdraw must be equal to or great than ".$min_amount.".");
                } elseif (!$wallet->enable_withdraw) {
                    return Redirect::to('user/withdraw/'.$wallet->id)
                                ->with('error', Lang::get('texts.notify_withdraw_disable', array('coin'=>$wallet->name)));
                } elseif ($balance_amount >= $net_total) {
                    $confirmation_code = md5(uniqid(mt_rand(), true));
                    $withdraw=new Withdraw();
                    $withdraw->user_id = $user->id;
                    $withdraw->wallet_id = $wallet->id;
                    $withdraw->to_address = $address;
                    $withdraw->amount = $amount;
                    $withdraw->fee_amount = $fee;
                    $withdraw->receive_amount = $net_total;
                    $withdraw->status = 0;
                    $withdraw->transaction_id = '';
                    $withdraw->confirmation_code=$confirmation_code;
                    $withdraw->save();
                    //Log::info("\n"."Add Withdraw transaction. Send mail confirm: ",array('user_id' => $user->id, 'wallet_id' => $wallet->id, 'to_address'=>$address, 'amount'=>$amount, 'fee_amount'=>$fee,'receive_amount'=>$net_total,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),'status'=>0,'transaction_id'=>'','confirmation_code'=>$confirmation_code));
                    if ($withdraw->id) {
                        //send mail confirm

                        $data_send=array(
                            'wallet' => $wallet,
                            'user' => $user,
                            'amount' => $amount,
                            'withdraw_id'=>$withdraw->id,
                            'confirmation_code'=>$confirmation_code
                        );
                        Mail::send(
                            'emails.confirmwithdraw', $data_send, function ($message) use ($user) {
                        
                                $message->to($user->email)->subject('Confirmation Withdrawal');
                            }
                        );

                        return Redirect::to('user/withdraw/'.$wallet->id)
                                    ->with('notice', "A confirmation e-mail was sent to your e-mail. Please checking e-mail to confirm withdrawal.");
                    } else {
                        return Redirect::to('user/withdraw/'.$wallet->id)
                                    ->with('error', "Can not insert withdraw to database.");
                    }
                } else {
                    return Redirect::to('user/withdraw/'.$wallet->id)
                                ->with('error', "Your balance are not enough.");
                }
            } catch (Exception $e) {
                return Redirect::to('user/withdraw/'.$wallet->id)->with('error', "Not connected to this wallet.");
                //'Caught exception: '.$e->getMessage()
            }
        } else {
            return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('error', "Password invalid.");
        }
    }

    public function confirmWithdraw($withdraw_id = 0, $confirmation_code = '')
    {
        //Log::info("\n"."****************Do confirmWithdraw****************");
        $withdraw_id = (int)$withdraw_id;
        if (($withdraw_id)!=0 && trim($confirmation_code)!='') {
            $withdraw=Withdraw::find($withdraw_id);
            if (isset($withdraw->to_address)) {
                if ($confirmation_code==$withdraw->confirmation_code && $withdraw->status==0) {
                    $wallet=Wallet::find($withdraw->wallet_id);
                    $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                    try {
                        $balance = new Balance();
                        $balance_amount = $balance->getBalance($wallet->id);
                        $admin_balance=$wallet->getBalance();
                        if ($balance_amount>=$withdraw->amount && $admin_balance>=$withdraw->receive_amount) {
                            $txid=$wallet->sendToAddress($withdraw->to_address, $withdraw->receive_amount);
                            if ($txid) {
                                $balance->takeMoney($withdraw->amount, $wallet->id, $withdraw->user_id);
                                Withdraw::where('id', $withdraw->id)->update(array('status' => 1,'transaction_id'=>$txid,'updated_at'=>date('Y-m-d H:i:s')));
                                    return Redirect::to('user/withdraw/'.$wallet->id)
                                    ->with('notice', "You withdrawed ".sprintf('%.8f', $withdraw->receive_amount)." (Fee: ".$withdraw->fee_amount.") ".$wallet->type." to address: ".$withdraw->to_address.".");
                            } else {
                                return Redirect::to('user/withdraw/'.$wallet->id)
                                ->with('error', "Unable to connect to wallet..");
                            }
                            /*
                            if($txid && $balance->takeMoney($withdraw->amount,$wallet->id,$withdraw->user_id)){
                                //Log::info("\n"."Do Confirmation txid: ".$txid);
                                      //Log::info("\n"."Do Confirmation, take money, send money");
                                    return Redirect::to('user/withdraw/'.$wallet->id)
                                    ->with( 'notice', "You withdrawed ".sprintf('%.8f',$withdraw->receive_amount)." (Fee: ".$withdraw->fee_amount.") ".$wallet->type." to address: ".$withdraw->to_address.".");
                            }else{
                                return Redirect::to('user/withdraw/'.$wallet->id)
                                ->with( 'error', "Your balance are not enough.");
                            }
                            */
                        } elseif ($admin_balance<$withdraw->amount) {
                                return Redirect::to('user/withdraw/'.$wallet->id)
                                ->with('error', "Sorry. Now we are not enough money. Please withdraw later.");
                        } else {
                            return Redirect::to('user/withdraw/'.$wallet->id)
                            ->with('error', "Your balance are not enough.");
                        }
                    } catch (Exception $e) {
                        //return Redirect::to('user/withdraw/'.$wallet->id)->with( 'error', 'Caught exception confirmWithdraw: '.$e->getMessage() /*"Not connected to this wallet confirmWithdraw." */);
                        return Redirect::to('user/withdraw/'.$wallet->id)->with('error', 'Caught exception confirmWithdraw: Not connected to this wallet.');
                        //$data['error']= "Not connected to this wallet.";//'Caught exception: '.$e->getMessage()
                    }
                } else {
                    return Redirect::to('user/profile/withdrawals')
                            ->with('error', "The confirmation code not matching.");
                }
            } else {
                return Redirect::to('user/profile/withdrawals')->with('error', "Not found this transaction withdrawals.");
            }
        } else {
            return Redirect::to('user/profile/withdrawals')->with('error', "Not found this transaction withdrawals.");
        }
    }

    public function cancelWithdraw()
    {
        if (Auth::guest()) {
            echo json_encode(array('status'=>'error','message'=> Lang::get('messages.login_to_buy')));
            exit;
        }
        //Log::info('------------------------- Do Cancel Withdraw -----------------------------');
        $user = Confide::user();
        $withdraw_id = (int)$_POST['withdraw_id'];
        $withdraw = Withdraw::find($withdraw_id);
        if ($withdraw->user_id == $user->id) {//this condition use to avoid case a user cancel order of other user
            //Check if withdraw is completed or pending
            if (!$withdraw->status) {        //not completed, withdrawal can be cancelled
                echo json_encode(array('status'=>'success','message'=> "The withdraw " . $withdraw->id . " was cancelled!", 'withdraw_id' => $withdraw->id ));
                //delete order
                $withdraw->delete();
            } else {
                echo json_encode(array('status'=>'error','message'=> "Sorry, you are not allowed cancel this withdraw!"));
            }
            exit;
        } else {
            echo json_encode(array('status'=>'error','message'=> "Sorry, you are not allowed cancel this withdraw!"));
            exit;
        }
    }
    public function referreredTradeKey()
    {
        $user = Confide::user();
        $trade_key = Request::get('trade_key');
        $user_referred = User::where('trade_key', $trade_key)->first();
        if (isset($user_referred->username) && $user_referred->id!=$user->id) {
            User::where('id', $user->id)->update(array('referral' => $user_referred->username));
            return Redirect::to('user/profile/dashboard');
        } elseif (isset($user_referred->username) && $user_referred->id==$user->id) {
            return Redirect::to('user/profile/dashboard')
                            ->with('error', 'Sorry. You can not referrer to yourself!');
        } else {
            return Redirect::to('user/profile/dashboard')
                            ->with('error', 'Sorry. The trade key not exist!');
        }
    }

    public function formTransfer($wallet_id = 0)
    {
        $user = Confide::user();
        $user_id = $user->id;
        $data = array();
        $wallet = (int)$wallet;
        if ($wallet_id==0) {
            $wallet = Wallet::first();
        } else {
            $wallet =Wallet::find($wallet_id);
        }
        $balance = new Balance();

        $data['page'] = 'transfercoin';
        $data['current_coin'] = $wallet->getType($wallet->id);

        $balance_amount = $balance->getBalance($wallet->id);
        $data['balance'] = sprintf('%.8f', $balance_amount);
        $data['wallet_id'] = $wallet->id;

        $setting = new Setting();
        $data['recaptcha_publickey']=$setting->getSetting('recaptcha_publickey', '');
        return view('user.profile', $data);
    }

    private function notificationHandler()
    {
        // select `a`.`id`,`b`.`id` as `order_id` 
        // from `order_notification` as `a` inner join `orders` as `b`
        // on `a`.`order_id` = `b`.`id`
        // where `a`.`status` = 'prepared' and
        // `b`.`user_id` = 214
        // order by 
        // case when
        //   `a`.`updated_at` is null then `a`.`created_at`
        //    else `a`.`updated_at`
        // end;
        DB::table();
    }

    public function doTransfer()
    {
        $trade_key = Request::get('trade_key');
        $amount = Request::get('amount');
        $wallet_id = (int)Request::get('wallet_id');
        $password = Request::get('password');
        $wallet = Wallet::find($wallet_id);
        $balance = new Balance();

        $user=Confide::user();
        if (Hash::check($password, $user->password)) {
            $user_receive = User::where('trade_key', $trade_key)->first();
            $amount_balance=$balance->getBalance($wallet->id);
            if (!isset($user_receive->username)) {
                return Redirect::to('user/transfer-coin/'.$wallet->id)
                                ->with('error', 'Sorry, the trade key does not exist!');
            } elseif ($user_receive->id==$user->id) {
                return Redirect::to('user/transfer-coin/'.$wallet->id)
                                ->with('error', 'Sorry, you cannot refer yourself!');
            } elseif ($amount_balance < $amount) {
                return Redirect::to('user/transfer-coin/'.$wallet->id)
                                ->with('error', 'Amount should be less than or equal to your balance.');
            } else {
                if ($balance->takeMoney($amount, $wallet->id, $user->id)) {
                    $balance->addMoney($amount, $wallet->id, $user_receive->id);
                    $transfer_his = new Transfer();
                    $transfer_his->sender=$user->id;
                    $transfer_his->receiver=$user_receive->id;
                    $transfer_his->wallet_id=$wallet->id;
                    $transfer_his->amount=$amount;
                    $transfer_his->save();
                    return Redirect::to('user/transfer-coin/'.$wallet->id)
                                ->with('success', 'You sent to user "'.$user_receive->username.'" '.$amount.' '.$wallet->getType($wallet->id) .'.');
                }
            }
        } else {
            return Redirect::to('user/transfer-coin/'.$wallet->id)->with('error', "Password invalid.");
        }
    }



    public function completeTwoFactorAuth(\Illuminate\Http\Request $request)
    {
        $user = Confide::user();
        if (isset($_POST['secret']) && isset($_POST['code'])) {
            if (\Google2FA::verifyKey(
                $_POST['secret'],
                $_POST['code'],
                1,
                null, // $timestamp
                "__not_set__"
            )) {
                session(["google2fa" => true]);
                DB::table('users')->where(
                    [
                        ['id', '=', $user->id]
                    ]
                )->update(
                    [
                        'google2fa_secret' => $_POST['secret']
                    ]
                );
                $user = Confide::user();
                $ip=$this->get_client_ip();
                $this->sendMailIPUser($user, $ip);
                User::where('id', $user->id)->update(array('lastest_login' => date("Y-m-d H:i:s"), 'ip_lastlogin'=>$ip));
                return response()->json(
                [
                    "redirect" => \URL::previous()
                ], 200);
            }
            return response()->json(
                [
                    "alert" => trans("user_texts.error_tfa_1")
                ]
            );
        }
        abort(404);
    }


    public function inviteUser()
    {
        $user = Confide::user();
        if ($user && isset($_POST["data"])) {
            $d = json_decode(urldecode($_POST["data"]), true);
            if (isset($d["email"])) {
                header("Content-type:application/json");
                if (! filter_var($d["email"], FILTER_VALIDATE_EMAIL)) {
                    exit(json_encode(
                        [
                            "alert" => trans("user_texts.invalid_email_address")
                        ]
                    ));
                }

                DB::table("invitation")->insert(
                    [
                        "user_id" => $user->id,
                        "receipent" => $d["email"],
                        "status" => "pending",
                        "expired_at" => date("Y-m-d H:i:s", time()+3600*24),
                        "created_at" => date("Y-m-d H:i:s")
                    ]
                );

                exit(json_encode(
                    [
                        "alert" => trans("user_texts.invite_success"),
                        "redirect" => ""
                    ]
                ));
            }
        }
        abort(404);
    }
}
