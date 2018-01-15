<?php

class AuthController extends Controller {
	private $authy;

    public function __construct()
    {       
        //$this->authy = new Authy_Api(Config::get('auth.authy'));
    }

    public function getAuthy(){
        return $this->authy;
    }
    public function ajaxRequestInstallation()
    {
        $user = Auth::user();

        $installation = $this->authy->registerUser($user->email, Input::get('phone'),Input::get('code_area'));       
        if($installation->ok()) { 
            User::where('id', $user->id)->update(array('authy' => $installation->id()));        
            echo json_encode(array('status'=>'success','id'=>$installation->id(),'phone'=>Input::get('phone'), 'code_area'=>Input::get('code_area')));
            exit;            
        }else{
            echo json_encode(array('status'=>'error','errors'=>$installation->errors(),'phone'=>Input::get('phone'), 'code_area'=>Input::get('code_area')));
            exit;
        }
    } 

	/*
	 @ Removes Two Factor Auth. 
	*/
    public function removeTwoFactorAuth()
    {
		
		$user2fa = Authentication::where('user_id','=', Auth::id() )->where('provider','=', 'clef')->first();
		
		

        if($user2fa->delete()) { 
            User::where('id', Auth::id())->update(array('two_factor_auth' => ''));
            echo json_encode(array('status'=>'success','message'=> trans('messages.uninstall_two_auth_success')));
            exit;            
        }else{
            echo json_encode(array('status'=>'error','errors'=>$user2fa->errors()));
            exit;
        }
    } 

    public function ajVerifyToken()
    {
        $user = Auth::user();       
        $verification = $this->authy->verifyToken(Input::get('authy_id'), Input::get('_token'), array('force' => 'true'));
        //echo "<pre>verification: "; print_r($verification); echo "</pre>";
        if($verification->ok()) {
            echo json_encode(array('status'=>'success'));
            exit; 
        }else {            
            echo json_encode((array)$verification->errors()+array('status'=>'error','message'=> trans('messages.unable_verify_token')));
            exit;          
        }
    }    
	



}